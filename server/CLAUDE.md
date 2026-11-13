# server/ — agente: server-ops

Tudo o que roda **no servidor de produção** (EC2 `ciatec-games`). Specs: 04 (Nginx/TLS), 05 (provisionamento),
07 (backup), 08 (deploy). Agentes escrevem; **um humano executa**.

## Layout no servidor

```
/opt/wordpress/
  .env            segredos (600, root) — gerado por env-from-ssm.sh
  .env.deploy     IMAGE_TAG em produção — escrito por deploy.sh
  bundle/         compose.yml + server/ + scripts/ extraídos da imagem em cada deploy
  backups/        backups locais (7 dias); cópia no S3
  deploy.log      histórico de deploys
```

## Arquivos

| Arquivo | Quando | Quem chama |
|---|---|---|
| `provision.sh` | Uma vez (e de novo após mudanças): swap, Docker, pastas, cron de backup | humano, via SSM |
| `env-from-ssm.sh` | Ao criar/trocar segredos | humano / `provision.sh` |
| `install-nginx.sh` | Ao mudar `nginx/` (depois dos certificados) | humano |
| `deploy.sh <tag>` | A cada deploy | GitHub Actions via SSM |
| `backup/backup.sh` | Diário (cron) | cron |
| `backup/restore.sh` | Restauração | humano |

## Regras do servidor compartilhado

- Os jogos (`*.game.ciatec.org`), os apps Node (8790/8791), o runner do GitHub e o `default_server` do Nginx
  **não são tocados**. Só arquivos com nome `ciatec.org*`, `hint.ciatec.org*` e `wordpress*` são nossos.
- Nginx: `nginx -t` antes de `systemctl reload nginx`. Nunca `restart`.
- Nada de `docker system prune`, `docker volume rm`, `compose down -v`.
