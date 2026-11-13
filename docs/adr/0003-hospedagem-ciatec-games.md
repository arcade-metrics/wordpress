# 0003 — Hospedagem na EC2 ciatec-games

- **Status:** aceita
- **Data:** 2026-11-13

## Contexto

A EC2 da rede anterior não existe mais. A `ciatec-games` (t3.small, Ubuntu 24.04, Elastic IP `18.119.16.166`) está quase
ociosa, já tem Nginx no host servindo `*.game.ciatec.org` e certbot. Os sites são landing pages de baixo tráfego.

## Decisão

Rodar a rede na `ciatec-games`, em Docker (WordPress + MariaDB), publicada em `127.0.0.1:8082`, atrás do Nginx do host,
que ganha um bloco por domínio. O DNS de `ciatec.org`, `www` e `hint.ciatec.org` já aponta para `18.119.16.166`
(GoDaddy, 2026-11-13).

## Consequências

- Sem custo novo de instância.
- Memória é o limite: o stack tem teto de 640 MB e o host ganha swap (spec 01 e 05).
- Os jogos e a rede compartilham Nginx e máquina: regra rígida de não tocar nos blocos e serviços dos jogos.
- Um problema na máquina derruba jogos e sites juntos; o backup fora da máquina (spec 07) é obrigatório.

## Alternativas descartadas

- **EC2 dedicada:** mais isolamento, mas custo novo para sites pequenos.
- **Caddy em container nas portas 80/443:** conflita com o Nginx do host, que já serve os jogos.
- **RDS:** custo novo e memória não é economizada na máquina de forma relevante (o MariaDB ajustado usa pouco).
