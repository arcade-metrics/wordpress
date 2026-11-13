# 0002 — Multisite em modo subdomínio

- **Status:** aceita
- **Data:** 2026-11-13

## Contexto

Dois sites hoje (`ciatec.org`, `hint.ciatec.org`), possivelmente outros no futuro, todos pequenos e mantidos pela mesma equipe.

## Decisão

WordPress multisite em **modo subdomínio**, com `ciatec.org` como domínio principal da rede.
`hint.ciatec.org` é um subsite nativo. Domínios externos futuros entram trocando o endereço do site (domain mapping nativo).
As constantes do multisite ficam em `platform/config/network.php`, versionado, e não só no `wp-config.php` do volume.

## Consequências

- Um core, um banco, um conjunto de plugins para os dois sites: atualizar um plugin afeta os dois.
- Um usuário super admin administra a rede; cada site pode ter seus próprios usuários.
- É difícil voltar para single-site depois (exige exportar/importar).

## Alternativas descartadas

- **Duas instalações single-site:** isolamento maior, mas duplicaria banco, atualizações e memória numa máquina pequena.
- **Modo subdiretório:** não atende domínios distintos e conflita com o `hint.ciatec.org`.
