#!/bin/bash
# generate-pdf.sh — Compila documentação Markdown + SVG para PDF
# Requer: pandoc + wkhtmltopdf OU md-to-pdf
# Uso: bash scripts/generate-pdf.sh

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
DOCS_DIR="$PROJECT_DIR/docs"
OUTPUT="$PROJECT_DIR/docs/compilado.pdf"

echo "=== A gerar PDF compilado da documentação ==="

# Tentar com pandoc + wkhtmltopdf
if command -v pandoc &>/dev/null && command -v wkhtmltopdf &>/dev/null; then
    echo "[ok] Usando pandoc + wkhtmltopdf"

    # Lista de ficheiros markdown
    FILES=$(find "$DOCS_DIR" -name "*.md" | sort)

    pandoc $FILES \
        --pdf-engine=wkhtmltopdf \
        --toc \
        --toc-depth=2 \
        -o "$OUTPUT"

    echo "[ok] PDF gerado: $OUTPUT"

elif command -v md-to-pdf &>/dev/null; then
    echo "[ok] Usando md-to-pdf"
    cd "$DOCS_DIR"
    npx md-to-pdf ./*/*.md --output "$OUTPUT"
    echo "[ok] PDF gerado: $OUTPUT"

else
    echo "[!] Nenhuma ferramenta encontrada para gerar PDF."
    echo "    Instala pandoc + wkhtmltopdf ou md-to-pdf (npm)."
    echo ""
    echo "    sudo apt install pandoc wkhtmltopdf"
    echo "    # ou"
    echo "    npm install -g md-to-pdf"
    exit 1
fi
