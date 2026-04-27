<?php
require_once __DIR__ . '/../Models/NewsModel.php';

class FeedService {
    private $newsModel;
    
    public function __construct() {
        $this->newsModel = new NewsModel();
    }
    
    public function fetchAllFeeds() {
        $totalAdded = 0;
        foreach (RSS_FEEDS as $category => $feeds) {
            foreach ($feeds as $feed) {
                $added = $this->fetchFeed($category, $feed);
                $totalAdded += $added;
            }
        }
        return $totalAdded;
    }
    
    public function fetchFeed($category, $feedInfo) {
        $added = 0;
        try {
            $rssContent = file_get_contents($feedInfo['url']);
            if (!$rssContent) return 0;
            $rss = simplexml_load_string($rssContent);
            if (!$rss) return 0;
            $items = [];
            if (isset($rss->channel->item)) {
                $items = $rss->channel->item;
            } elseif (isset($rss->entry)) {
                $items = $rss->entry;
            }
            foreach ($items as $item) {
                $data = $this->parseItem($item, $category, $feedInfo['name']);
                if ($data) {
                    $result = $this->newsModel->add($data);
                    if ($result) $added++;
                }
            }
        } catch (Exception $e) {
            error_log("Erro feed {$feedInfo['url']}: " . $e->getMessage());
        }
        return $added;
    }
    
    private function parseItem($item, $category, $sourceName) {
        $data = ['category' => $category, 'source' => $sourceName, 'is_manual' => 0];
        $data['title'] = $this->getStringValue($item, 'title');
        if (!$data['title']) return null;
        $data['description'] = $this->getStringValue($item, 'description') ?? $this->getStringValue($item, 'summary');
        $data['link'] = $this->getStringValue($item, 'link');
        if (!$data['link'] && isset($item->link->attributes()['href'])) {
            $data['link'] = (string)$item->link->attributes()['href'];
        }
        $data['image'] = $this->extractImage($item);
        $pubDate = $this->getStringValue($item, 'pubDate') ?? $this->getStringValue($item, 'published');
        $data['published_at'] = $pubDate ? date('Y-m-d H:i:s', strtotime($pubDate)) : date('Y-m-d H:i:s');
        return $data;
    }
    
    private function getStringValue($item, $field) {
        if (isset($item->$field)) return (string)$item->$field;
        $namespaces = $item->getNamespaces(true);
        foreach ($namespaces as $prefix => $ns) {
            if (isset($item->children($ns)->$field)) return (string)$item->children($ns)->$field;
        }
        return null;
    }
    
    private function extractImage($item) {
        // Tenta encontrar imagem em vários formatos RSS
        // 1. Media RSS thumbnail
        if (isset($item->children('http://search.yahoo.com/mrss/')->thumbnail)) {
            $media = $item->children('http://search.yahoo.com/mrss/');
            if (isset($media->thumbnail->attributes()['url'])) {
                return (string)$media->thumbnail->attributes()['url'];
            }
        }
        
        // 2. Media RSS content
        if (isset($item->children('http://search.yahoo.com/mrss/')->content)) {
            $media = $item->children('http://search.yahoo.com/mrss/');
            if (isset($media->content->attributes()['url'])) {
                return (string)$media->content->attributes()['url'];
            }
        }
        
        // 3. Enclosure
        if (isset($item->enclosure)) {
            $attrs = $item->enclosure->attributes();
            if (isset($attrs['type']) && strpos((string)$attrs['type'], 'image') !== false && isset($attrs['url'])) {
                return (string)$attrs['url'];
            }
            // Alguns feeds não têm type mas têm url de imagem
            if (isset($attrs['url'])) {
                $url = (string)$attrs['url'];
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i', $url)) {
                    return $url;
                }
            }
        }
        
        // 4. Imagem no conteúdo/descrição
        $content = $this->getStringValue($item, 'content') ?? $this->getStringValue($item, 'description') ?? '';
        if ($content) {
            // Tenta encontrar img tag
            if (preg_match('/<img[^>]+src="([^"]+)"/i', $content, $matches)) {
                return $matches[1];
            }
            // Tenta encontrar figura em data-uri ou outros formatos
            if (preg_match('/<figure[^>]*>.*?<img[^>]+src="([^"]+)"/is', $content, $matches)) {
                return $matches[1];
            }
        }
        
        // 5. Elemento image direto no item (alguns feeds usam)
        if (isset($item->image)) {
            return (string)$item->image;
        }
        
        // 6. Tenta encontrar no children de outros namespaces comuns
        $namespaces = $item->getNamespaces(true);
        foreach ($namespaces as $prefix => $ns) {
            if (strpos($ns, 'media') !== false || strpos($ns, 'mrss') !== false) {
                $children = $item->children($ns);
                if (isset($children->thumbnail) && isset($children->thumbnail->attributes()['url'])) {
                    return (string)$children->thumbnail->attributes()['url'];
                }
                if (isset($children->content) && isset($children->content->attributes()['url'])) {
                    return (string)$children->content->attributes()['url'];
                }
            }
        }
        
        // Retorna string vazia se não encontrar imagem
        return '';
    }
}
