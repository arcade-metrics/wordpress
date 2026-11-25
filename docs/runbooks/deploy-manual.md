# Deploy manual em produção (via SSM, a partir do projeto aws-cli)

**Quando usar:** depois de um merge no `main`, para pôr a versão nova no ar. Também para reverter (basta uma tag anterior).

**Decisão (2026-11-25):** o deploy **não** é disparado pelo GitHub Actions. O workflow `deploy.yml` só publica a imagem em
`ghcr.io/arcade-metrics/wordpress:sha-<commit>`; o job `deploy` fica pulado porque o GitHub não tem credencial na conta AWS
(as `vars.AWS_DEPLOY_ROLE_ARN`/`DEPLOY_INSTANCE_ID` não existem, de propósito). Quem executa é um operador com o perfil
`ciatec-cli`, numa sessão do Claude Code aberta em `D:\ciatec\aws-cli` (guarda AWS + registro em `logs/changes.md`).
Se um dia quiser automático: ADR 0005, passo "OIDC + papel IAM".

## Pré-requisitos

- Imagem publicada: Actions → Deploy → job `build` verde para o commit. A tag é `sha-` + 7 primeiros caracteres do SHA.
- Pacote GHCR **público** (Package settings → Change visibility). A EC2 não tem token para pacote privado.
- Instância `ciatec-games` = `i-0dd545d470012db34` (us-east-2), com `CIATecEC2Role` (SSM) — já está.
- `/opt/wordpress/.env` existe na máquina (gerado em 2026-11-13).

## Passos (todos via `aws ssm send-command --instance-ids i-0dd545d470012db34 --document-name AWS-RunShellScript`)

1. **Retrato dos jogos** (regra 6 do CLAUDE.md): `ls -la /etc/nginx/sites-enabled; docker ps --format '{{.Names}} {{.Image}}'`.
   Guarde a saída para comparar no fim.

2. **Backup do banco e uploads.** No primeiro deploy automático `bundle/` e `.env.deploy` ainda não existem, então
   `backup.sh` não roda; faça direto:
   ```bash
   mkdir -p /opt/wordpress/backups && cd /opt/wordpress/backups && S=$(date -u +%Y%m%dT%H%M%SZ)
   docker exec wordpress-db-1 sh -c 'exec mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" --single-transaction --routines --databases "$MARIADB_DATABASE"' | gzip > db-$S.sql.gz && gzip -t db-$S.sql.gz
   docker run --rm -v wordpress_uploads:/data:ro alpine tar czf - -C /data . > uploads-$S.tgz
   ls -la /opt/wordpress/backups
   ```
   (Se o nome do container for outro, veja `docker ps`.)

3. **Deploy.** O `deploy.sh` vem de dentro da imagem, então o que roda é o que passou na CI:
   ```bash
   set -e; TAG=sha-1134f59; IMAGE=ghcr.io/arcade-metrics/wordpress
   docker pull -q $IMAGE:$TAG
   d=$(mktemp -d); docker run --rm --entrypoint tar $IMAGE:$TAG -C /opt/stack -c server | tar -x -C $d
   bash $d/server/deploy.sh $TAG
   ```
   O script faz: lock → extrai o bundle → escreve `.env.deploy` → `compose up -d` (projeto `wordpress`, mesmos volumes
   `wordpress_db_data`/`wordpress_uploads`: banco e uploads são preservados) → `wp core update-db` → `network-setup` →
   smoke test em `127.0.0.1:8082` → confere que o cabeçalho `X-Release` é a tag pedida → limpa o cache do Nginx
   (`/var/cache/nginx/wordpress`, só da rede). Falhou o smoke test ou a tag → volta para a tag anterior.
   Tempo: ~2–4 min. `executionTimeout` do SSM: 900.

4. **Verificar de fora.** Cada página diz a versão no cabeçalho `X-Release` (imagens a partir do PR #5):
   ```bash
   for u in https://ciatec.org/ https://ciatec.org/en/ https://ciatec.org/es/ https://hint.ciatec.org/ https://hint.ciatec.org/pt/; do
     echo "$u $(curl -s -o /dev/null -w '%{http_code}' "$u") $(curl -sI "$u" | grep -i '^x-release' | tr -d '\r')"
   done
   ```
   Esperado: 200 e `X-Release: <tag>` em todas. Como o `deploy.sh` limpa o cache, não há janela com a versão antiga;
   se aparecer a tag anterior com `X-Cache: HIT`, o passo de limpeza falhou (veja `deploy.log`).
   Imagens até `sha-1134f59` não têm `X-Release`: para elas, compare o HTML e lembre do cache de 5 min.

5. **Jogos intactos:** repita o passo 1 e compare; `*.game.ciatec.org` respondendo.

6. **Registrar** em `D:\ciatec\aws-cli\logs\changes.md` (data, instância, tag, comando, resultado, quem aprovou).

## Reverter

`bash /opt/wordpress/bundle/server/deploy.sh <tag-anterior>` (o `deploy.log` lista as tags). A versão mais antiga
restaurável por aqui é `sha-1134f59` (primeiro deploy por este runbook, 2026-11-25). O go-live de 13/11
(`wordpress-local:sha-9d32e50`, construída no servidor) **não** passa pelo `deploy.sh`: só como emergência, subindo
com o compose de `/opt/wordpress/src` — ver `logs/changes.md` de 2026-11-13. Prefira não voltar a ela: não tem a
correção do 404 em `/en/` nem a landing do HINT.

## Notas

- O clone `/opt/wordpress/src` (go-live de 13/11, deploy key) fica como reserva; não é mais o caminho de deploy.
- Nunca `docker compose down -v`, `docker system prune`, nem mexer em blocos Nginx dos jogos.
