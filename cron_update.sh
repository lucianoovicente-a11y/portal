#!/bin/bash
# Script para atualização automática via cron (a cada hora)

cd /workspace

# Executar atualização dos feeds
php update_feeds.php >> /var/log/portal_update.log 2>&1

echo "Atualização concluída em $(date)"
