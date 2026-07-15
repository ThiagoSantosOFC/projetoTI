#!/bin/bash
# setup.sh — Setup inicial do projeto
# Uso: bash scripts/setup.sh

set -e

echo "=== Setup Base de Consulta TI (Recurso) ==="

# Verificar PHP
if ! command -v php &>/dev/null; then
    echo "[!] PHP não encontrado. Instala PHP 8.x primeiro."
    exit 1
fi
echo "[ok] PHP $(php -v | head -1)"

# Verificar Python
if command -v python3 &>/dev/null; then
    echo "[ok] Python 3 encontrado"
fi

# Criar pastas de dados se não existirem
mkdir -p web/data/resources web/data/auth web/data/camera/imagens
echo "[ok] Pastas de dados criadas"

# Permissões
chmod -R 755 web/public
chmod 777 web/data
echo "[ok] Permissões ajustadas"

# Criar utilizador padrão (admin:admin123 com hash)
php -r "
    \$hash = password_hash('admin123', PASSWORD_BCRYPT);
    file_put_contents('web/data/auth/utilizadores.txt', \"admin:\$hash\nutilizador1:\$hash\n\");
    echo \"[ok] Utilizadores criados com hash bcrypt\n\";
"

echo ""
echo "=== Setup completo ==="
echo "Corre 'bash scripts/dev-server.sh' para iniciar."
