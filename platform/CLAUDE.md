# platform/ — agente: platform-engineer

Imagem e configuração da rede. Specs: 02 (plataforma), 03 (plugins), 06 (segredos).

| Arquivo | O quê |
|---|---|
| `Dockerfile` | Imagem imutável (ADR 0004). Build a partir da raiz: `docker build -f platform/Dockerfile .` |
| `plugins.lock` | Plugins e temas de terceiros, versões fixas. Mudança = PR dedicado |
| `sites.conf` | Sites da rede (slug, subdomínio, título) |
| `build/fetch.sh` | Baixa o que está no lock (estágio de build) |
| `bin/network-setup.sh` | Ativa plugins/temas da rede, cria sites, roda seeds. Idempotente, roda no container |
| `config/network.php` | Constantes do multisite e de segurança (`wp-config`) |
| `config/*` | `.htaccess`, PHP, Apache |
| `mu-plugins/` | Padrões da rede e helpers de seed (`cnet_*`) |

Site novo: linha em `sites.conf` + `sites/<slug>/theme` + `sites/<slug>/seed.php` + `COPY` no Dockerfile + mount no `compose.yaml`.
