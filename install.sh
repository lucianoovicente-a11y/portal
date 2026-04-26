#!/bin/bash
# Script de instalação e configuração do Mega Portal de Notícias

echo "=========================================="
echo "  Mega Portal de Notícias - Instalação"
echo "=========================================="
echo ""

# Verificar se PHP está instalado
if ! command -v php &> /dev/null; then
    echo "❌ ERRO: PHP não está instalado!"
    echo "Instale o PHP com:"
    echo "  Ubuntu/Debian: sudo apt-get install php php-sqlite3"
    echo "  CentOS/RHEL: sudo yum install php php-pdo"
    exit 1
fi

echo "✅ PHP encontrado: $(php -v | head -n 1)"
echo ""

# Verificar extensão SQLite
if ! php -m | grep -i sqlite > /dev/null; then
    echo "❌ ERRO: Extensão SQLite não encontrada!"
    echo "Instale com:"
    echo "  Ubuntu/Debian: sudo apt-get install php-sqlite3"
    echo "  CentOS/RHEL: sudo yum install php-pdo"
    exit 1
fi

echo "✅ Extensão SQLite encontrada"
echo ""

# Criar diretório de dados
DATA_DIR="$(dirname "$0")/data"
if [ ! -d "$DATA_DIR" ]; then
    mkdir -p "$DATA_DIR"
    echo "✅ Diretório de dados criado: $DATA_DIR"
else
    echo "✅ Diretório de dados já existe: $DATA_DIR"
fi

# Definir permissões
chmod 755 "$DATA_DIR"
echo "✅ Permissões configuradas"
echo ""

# Executar primeira atualização
echo "🔄 Executando primeira atualização dos feeds..."
echo ""
cd "$(dirname "$0")"
php update_feeds.php

echo ""
echo "=========================================="
echo "  ✅ Instalação concluída com sucesso!"
echo "=========================================="
echo ""
echo "Para iniciar o servidor de desenvolvimento:"
echo "  php -S localhost:8000 -t $(dirname "$0")"
echo ""
echo "Acesse no navegador:"
echo "  http://localhost:8000"
echo ""
echo "Para atualizar as notícias manualmente:"
echo "  php $(dirname "$0")/update_feeds.php"
echo ""
echo "Para configurar atualização automática (cron):"
echo "  */5 * * * * php $(dirname "$0")/update_feeds.php"
echo ""
