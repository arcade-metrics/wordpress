# Imagem da rede WordPress (ADR 0004). Build a partir da raiz do repositório:
#   docker build -f platform/Dockerfile -t wordpress-local .
#
# O código fica em /var/www/wordpress (fora do VOLUME /var/www/html da imagem oficial), para que cada imagem nova
# traga o código novo. Só os uploads são volume.

# Base: PHP 8.3 + Apache + entrypoint oficial, fixada por digest. O core vem à parte (WP_CORE_VERSION),
# porque as tags oficiais param na 7.0.4 e a rede antiga já estava na 7.0.8.
ARG WORDPRESS_IMAGE=wordpress:7.0-php8.3-apache@sha256:b427cec767f5de2aa649390cb8805aa1fe320e1e0d57fc1f467754edb6cc0a49

# ---------------------------------------------------------------- plugins, tema pai e WP-CLI
FROM debian:bookworm-slim AS fetch
RUN apt-get update \
 && apt-get install -y --no-install-recommends ca-certificates curl unzip \
 && rm -rf /var/lib/apt/lists/*
ARG WP_CLI_VERSION=2.12.0
ARG WP_CORE_VERSION=7.0.8
RUN mkdir -p /out \
 && curl -fsSL -o /tmp/wp.tgz "https://wordpress.org/wordpress-${WP_CORE_VERSION}.tar.gz" \
 && echo "$(curl -fsSL "https://wordpress.org/wordpress-${WP_CORE_VERSION}.tar.gz.sha1")  /tmp/wp.tgz" | sha1sum -c - \
 && tar -xzf /tmp/wp.tgz -C /out && rm /tmp/wp.tgz
COPY platform/plugins.lock platform/build/fetch.sh /build/
RUN bash /build/fetch.sh /build/plugins.lock /out \
 && curl -fsSL -o /out/wp "https://github.com/wp-cli/wp-cli/releases/download/v${WP_CLI_VERSION}/wp-cli-${WP_CLI_VERSION}.phar" \
 && chmod +x /out/wp

# ---------------------------------------------------------------- imagem final
FROM ${WORDPRESS_IMAGE}

ENV WP_ROOT=/var/www/wordpress \
    WORDPRESS_CONFIG_EXTRA="require '/etc/wordpress/network.php';"

# Core (versão fixa, conferida por SHA-1) sem os extras que não usamos (temas twenty*, akismet, hello dolly).
# /usr/src/wordpress continua na imagem só pelo wp-config-docker.php, usado pelo entrypoint oficial.
COPY --from=fetch /out/wordpress ${WP_ROOT}
RUN rm -rf "${WP_ROOT}"/wp-content/themes/twenty* \
           "${WP_ROOT}"/wp-content/plugins/akismet \
           "${WP_ROOT}"/wp-content/plugins/hello.php \
 && mkdir -p "${WP_ROOT}/wp-content/uploads" "${WP_ROOT}/wp-content/mu-plugins"

COPY --from=fetch /out/wp /usr/local/bin/wp
COPY --from=fetch /out/plugins/ ${WP_ROOT}/wp-content/plugins/
COPY --from=fetch /out/themes/  ${WP_ROOT}/wp-content/themes/

# Pacotes de idioma oficiais (core, plugins, tema pai), nas versões fixadas.
ARG LOCALES="pt_BR es_ES en_GB"
COPY platform/plugins.lock platform/build/fetch-translations.php /tmp/build/
RUN php /tmp/build/fetch-translations.php /tmp/build/plugins.lock "${WP_ROOT}" ${LOCALES} \
 && rm -rf /tmp/build

# Configuração da rede, PHP e Apache
COPY platform/config/network.php     /etc/wordpress/network.php
COPY platform/config/htaccess        ${WP_ROOT}/.htaccess
COPY platform/config/php.ini         /usr/local/etc/php/conf.d/zz-wordpress.ini
COPY platform/config/apache-site.conf /etc/apache2/sites-available/000-default.conf
COPY platform/config/apache-tuning.conf /etc/apache2/conf-available/zz-tuning.conf
RUN a2enmod remoteip headers >/dev/null && a2enconf zz-tuning >/dev/null

# Código próprio: mu-plugins, temas e seeds dos sites
COPY platform/mu-plugins/       ${WP_ROOT}/wp-content/mu-plugins/
COPY sites/ciatec/theme/        ${WP_ROOT}/wp-content/themes/ciatec/
COPY sites/hint/theme/          ${WP_ROOT}/wp-content/themes/hint/
COPY platform/sites.conf platform/plugins.lock /opt/platform/
COPY sites/ciatec/seed.php      /opt/sites/ciatec/seed.php
COPY sites/hint/seed.php        /opt/sites/hint/seed.php
COPY platform/bin/network-setup.sh /usr/local/bin/network-setup

# Pacote de deploy: o servidor extrai isto da própria imagem (ADR 0005)
COPY deploy/compose.yml          /opt/stack/compose.yml
COPY server/                     /opt/stack/server/
COPY scripts/smoke-test.sh       /opt/stack/scripts/smoke-test.sh

# Versão desta imagem (sha-<commit>), passada pelo deploy.yml; devolvida no cabeçalho X-Release (mu-plugin release.php).
# Fica no fim para não invalidar o cache das camadas acima a cada commit.
ARG RELEASE=dev
ENV WP_RELEASE=${RELEASE}

RUN chmod +x /usr/local/bin/network-setup \
 && chown -R www-data:www-data "${WP_ROOT}"

WORKDIR /var/www/wordpress

HEALTHCHECK --interval=15s --timeout=5s --start-period=20s --retries=5 \
  CMD php -r '$s=@fsockopen("127.0.0.1",80);exit($s?0:1);'
