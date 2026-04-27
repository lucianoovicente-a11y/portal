<?php
/**
 * Controller principal do portal
 */

class HomeController {
    private NewsModel $newsModel;
    private FeedService $feedService;
    
    public function __construct(NewsModel $newsModel, FeedService $feedService) {
        $this->newsModel = $newsModel;
        $this->feedService = $feedService;
    }
    
    /**
     * Exibir página principal
     */
    public function index(): void {
        // Parâmetros da requisição
        $page = max(1, (int)Helpers::getParam('page', 1));
        $source_filter = Helpers::getParam('source');
        $search = Helpers::getParam('search');
        
        if (!empty($search)) {
            $search = trim($search);
        }
        
        // Obter notícias
        $news = $this->newsModel->getNews($page, NEWS_PER_PAGE, $source_filter, $search);
        $total_news = $this->newsModel->countNews($source_filter, $search);
        $total_pages = ceil($total_news / NEWS_PER_PAGE);
        
        // Obter fontes para o sidebar
        $sources = $this->newsModel->getSources();
        
        // Última notícia para destaque
        $latest_news = $this->newsModel->getLatestNews();
        
        // Renderizar view
        Helpers::render('home/index', [
            'page' => $page,
            'news' => $news,
            'total_news' => $total_news,
            'total_pages' => $total_pages,
            'sources' => $sources,
            'latest_news' => $latest_news,
            'source_filter' => $source_filter,
            'search' => $search
        ]);
    }
}
