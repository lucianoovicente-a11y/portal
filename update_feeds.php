<?php
/**
 * Script de atualização automática de feeds
 * Execute via cron: */5 * * * * php /path/to/update_feeds.php
 */

require_once __DIR__ . '/config.php';

echo "=== Mega Portal de Notícias ===\n";
echo "Iniciando atualização dos feeds...\n\n";

$db = getDB();
initDatabase($db);

$total = updateAllFeeds($db, $news_sources);

echo "\n=== Atualização concluída ===\n";
echo "Total de notícias processadas: $total\n";
echo "Data: " . date('d/m/Y H:i:s') . "\n";
