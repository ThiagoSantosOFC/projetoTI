#!/bin/bash
# dev-server.sh — Servidor PHP local para desenvolvimento
# Uso: bash scripts/dev-server.sh

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
WEB_DIR="$PROJECT_DIR/web/public"
HOST="0.0.0.0"
PORT="8000"

echo "=========================================="
echo "  Servidor PHP Local - TI Recurso"
echo "  URL: http://localhost:$PORT"
echo "  Ctrl+C para parar"
echo "=========================================="
echo ""

php -S "$HOST:$PORT" -t "$WEB_DIR"
