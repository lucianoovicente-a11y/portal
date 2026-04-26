<?php
/**
 * Script de atualização dos feeds RSS
 * Executar via cron ou manualmente para atualizar as notícias
 */

require_once 'config.php';

echo "========================================\n";
echo "  Atualização de Feeds RSS\n";
echo "  " . date('d/m/Y H:i:s') . "\n";
echo "========================================\n\n";

$total_new = 0;
$successful_feeds = 0;
$failed_feeds = 0;

foreach ($news_sources as $source) {
    echo "📰 Processando: {$source['name']}...\n";
    
    try {
        // Buscar feed
        $items = fetchFeed($source);
        
        if (empty($items)) {
            echo "   ⚠️  Nenhuma notícia encontrada ou erro ao ler feed\n";
            $failed_feeds++;
            continue;
        }
        
        // Salvar no banco
        $new_count = saveNews($items);
        
        echo "   ✅ Encontradas: " . count($items) . " notícias\n";
        echo "   💾 Novas: $new_count\n";
        
        $total_new += $new_count;
        $successful_feeds++;
        
    } catch (Exception $e) {
        echo "   ❌ Erro: " . $e->getMessage() . "\n";
        $failed_feeds++;
    }
    
    // Pequena pausa para não sobrecarregar os servidores
    usleep(500000); // 0.5 segundos
}

echo "\n========================================\n";
echo "  Resumo da Atualização\n";
echo "========================================\n";
echo "  Fontes processadas com sucesso: $successful_feeds\n";
echo "  Fontes com falha: $failed_feeds\n";
echo "  Total de novas notícias: $total_new\n";
echo "========================================\n";

// Limpar notícias muito antigas (opcional - descomente se quiser)
// $deleted = cleanOldNews(60);
// echo "  Notícias antigas removidas: $deleted\n";

echo "\n✅ Atualização concluída!\n";
