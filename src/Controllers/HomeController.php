<?php
/**
 * Controller principal do portal
 */

class HomeController {
    private NewsModel $newsModel;
    private FeedService $feedService;
    private ?SettingsModel $settingsModel = null;
    
    public function __construct(NewsModel $newsModel, FeedService $feedService, ?SettingsModel $settingsModel = null) {
        $this->newsModel = $newsModel;
        $this->feedService = $feedService;
        $this->settingsModel = $settingsModel;
    }
    
    /**
     * Exibir página principal
     */
    public function index(): void {
        // Parâmetros da requisição
        $page = max(1, (int)Helpers::getParam('page', 1));
        $category_filter = Helpers::getParam('category');
        $search = Helpers::getParam('search');
        
        if (!empty($search)) {
            $search = trim($search);
        }
        
        // Obter notícias
        $news = $this->newsModel->getNews($page, NEWS_PER_PAGE, null, $search, $category_filter);
        $total_news = $this->newsModel->countNews(null, $search, $category_filter);
        $total_pages = ceil($total_news / NEWS_PER_PAGE);
        
        // Última notícia para destaque
        $latest_news = $this->newsModel->getLatestNews();
        
        global $CATEGORIES;
        
        // Renderizar view
        Helpers::render('home/index', [
            'page' => $page,
            'news' => $news,
            'total_news' => $total_news,
            'total_pages' => $total_pages,
            'latest_news' => $latest_news,
            'category_filter' => $category_filter,
            'search' => $search,
            'CATEGORIES' => $CATEGORIES,
            'settingsModel' => $this->settingsModel
        ]);
    }
}
