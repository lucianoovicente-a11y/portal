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
        if (isset($item->children('http://search.yahoo.com/mrss/')->thumbnail)) {
            $media = $item->children('http://search.yahoo.com/mrss/');
            if (isset($media->thumbnail->attributes()['url'])) return (string)$media->thumbnail->attributes()['url'];
        }
        if (isset($item->enclosure)) {
            $attrs = $item->enclosure->attributes();
            if (isset($attrs['type']) && strpos((string)$attrs['type'], 'image') !== false && isset($attrs['url'])) {
                return (string)$attrs['url'];
            }
        }
        $content = $this->getStringValue($item, 'content') ?? $this->getStringValue($item, 'description');
        if ($content && preg_match('/<img[^>]+src="([^"]+)"/i', $content, $matches)) return $matches[1];
        return '';
    }
}
