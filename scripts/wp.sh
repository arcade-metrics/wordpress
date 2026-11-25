#!/usr/bin/env bash
# WP-CLI no container local, como www-data.
#   bash scripts/wp.sh site list
#   bash scripts/wp.sh option get blogname --url=hint.ciatec.localhost
set -euo pipefail
export MSYS_NO_PATHCONV=1   # Git Bash não reescreve caminhos /opt/...

tty=(-T); [[ -t 0 && -t 1 ]] && tty=()
exec docker compose exec "${tty[@]}" -u www-data wordpress wp "$@"
