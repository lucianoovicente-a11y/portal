<?php
/**
 * Service para processamento de feeds RSS
 */

class FeedService {
    private array $sources;
    
    public function __construct(array $sources) {
        $this->sources = $sources;
    }
    
    /**
     * Extrair imagem do conteúdo ou descrição
     */
    private function extractImage(string $content): ?string {
        $patterns = [
            '/<img[^>]+src="([^"]+)"/i',
            '/<meta property="og:image" content="([^"]+)"/i',
            '/url\(([^)]+)\)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return trim($matches[1]);
            }
        }
        
        return null;
    }
    
    /**
     * Buscar e processar feed RSS
     */
    public function fetchFeed(array $source): array {
        $rss = @file_get_contents($source['url']);
        
        if (!$rss) {
            return [];
        }
        
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($rss);
        libxml_clear_errors();
        
        if (!$xml) {
            return [];
        }
        
        $items = [];
        
        // Suporte para diferentes formatos RSS
        $channel = isset($xml->channel) ? $xml->channel : $xml;
        $entries = isset($channel->item) ? $channel->item : (isset($channel->entry) ? $channel->entry : []);
        
        foreach ($entries as $item) {
            $title = (string)($item->title ?? '');
            $link = (string)($item->link ?? '');
            
            // Lidar com links complexos
            if (is_object($link) && isset($link['href'])) {
                $link = (string)$link['href'];
            } elseif (is_array($link)) {
                $link = $link[0] ?? '';
            }
            
            $description = (string)($item->description ?? $item->summary ?? '');
            $pubDate = (string)($item->pubDate ?? $item->published ?? date('r'));
            
            // Tentar extrair imagem
            $imageUrl = null;
            
            // Verificar media:content
            if (isset($item->children('media', true)->content)) {
                $media = $item->children('media', true)->content;
                $imageUrl = (string)$media['url'];
            }
            
            // Verificar enclosure
            if (!$imageUrl && isset($item->enclosure)) {
                $imageUrl = (string)$item->enclosure['url'];
            }
            
            // Extrair do conteúdo se não encontrou
            if (!$imageUrl && !empty($description)) {
                $imageUrl = $this->extractImage($description);
            }
            
            // Converter data
            $timestamp = strtotime($pubDate);
            if ($timestamp === false) {
                $timestamp = time();
            }
            
            $items[] = [
                'title' => strip_tags($title),
                'description' => strip_tags($description),
                'link' => $link,
                'pub_date' => date('Y-m-d H:i:s', $timestamp),
                'source_name' => $source['name'],
                'source_url' => $source['url'],
                'category' => $source['category'],
                'image_url' => $imageUrl
            ];
        }
        
        return $items;
    }
    
    /**
     * Atualizar todos os feeds
     */
    public function updateAllFeeds(): array {
        $results = [
            'total_new' => 0,
            'successful_feeds' => 0,
            'failed_feeds' => 0,
            'details' => []
        ];
        
        foreach ($this->sources as $source) {
            $detail = [
                'name' => $source['name'],
                'status' => 'success',
                'items_count' => 0,
                'new_count' => 0,
                'error' => null
            ];
            
            try {
                $items = $this->fetchFeed($source);
                
                if (empty($items)) {
                    $detail['status'] = 'warning';
                    $detail['error'] = 'Nenhuma notícia encontrada ou erro ao ler feed';
                    $results['failed_feeds']++;
                    $results['details'][] = $detail;
                    continue;
                }
                
                $detail['items_count'] = count($items);
                $results['details'][] = $detail;
                $results['successful_feeds']++;
                
                // Pequena pausa para não sobrecarregar os servidores
                usleep(500000); // 0.5 segundos
                
            } catch (Exception $e) {
                $detail['status'] = 'error';
                $detail['error'] = $e->getMessage();
                $results['failed_feeds']++;
                $results['details'][] = $detail;
            }
        }
        
        return $results;
    }
    
    /**
     * Obter todas as fontes
     */
    public function getSources(): array {
        return $this->sources;
    }
}
