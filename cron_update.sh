#!/bin/bash
# Script para atualização automática de hora em hora
cd /workspace
php update_feeds.php >> /workspace/data/cron.log 2>&1
