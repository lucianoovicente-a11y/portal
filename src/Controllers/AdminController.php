<?php
/**
 * Controller para área administrativa
 */

class AdminController {
    private NewsModel $newsModel;
    private SettingsModel $settingsModel;
    private PollModel $pollModel;
    private FeedService $feedService;
    
    public function __construct(NewsModel $newsModel, SettingsModel $settingsModel, PollModel $pollModel, FeedService $feedService) {
        $this->newsModel = $newsModel;
        $this->settingsModel = $settingsModel;
        $this->pollModel = $pollModel;
        $this->feedService = $feedService;
    }
    
    /**
     * Verificar autenticação
     */
    private function checkAuth(): bool {
        session_start();
        return isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true;
    }
    
    /**
     * Página de login
     */
    public function login(): void {
        if ($this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/dashboard');
            exit;
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Login simples (em produção, usar hash e banco de dados)
            $adminUser = $this->settingsModel->getValue('admin_username', 'admin');
            $adminPass = $this->settingsModel->getValue('admin_password', 'admin123');
            
            if ($username === $adminUser && $password === $adminPass) {
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_user'] = $username;
                header('Location: ' . SITE_URL . '/admin/dashboard');
                exit;
            } else {
                $error = 'Usuário ou senha inválidos';
            }
        }
        
        Helpers::render('admin/login', ['error' => $error]);
    }
    
    /**
     * Logout
     */
    public function logout(): void {
        session_start();
        session_destroy();
        header('Location: ' . SITE_URL . '/admin/login');
        exit;
    }
    
    /**
     * Dashboard administrativo
     */
    public function dashboard(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        // Estatísticas
        $totalNews = $this->newsModel->countNews();
        $newsByCategory = $this->newsModel->countByCategory();
        $sources = $this->newsModel->getSources();
        $activePoll = $this->pollModel->getActive();
        $lastUpdate = $this->settingsModel->getValue('last_update', 'Nunca');
        
        Helpers::render('admin/dashboard', [
            'totalNews' => $totalNews,
            'newsByCategory' => $newsByCategory,
            'sources' => $sources,
            'activePoll' => $activePoll,
            'lastUpdate' => $lastUpdate
        ]);
    }
    
    /**
     * Gerenciar notícias
     */
    public function news(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $page = max(1, (int)Helpers::getParam('page', 1));
        $category = Helpers::getParam('category');
        $search = Helpers::getParam('search');
        
        $news = $this->newsModel->getNews($page, 50, null, $search, $category);
        $totalNews = $this->newsModel->countNews(null, $search, $category);
        $totalPages = ceil($totalNews / 50);
        
        global $CATEGORIES;
        
        Helpers::render('admin/news', [
            'news' => $news,
            'page' => $page,
            'totalPages' => $totalPages,
            'categories' => $CATEGORIES,
            'currentCategory' => $category,
            'search' => $search
        ]);
    }
    
    /**
     * Adicionar notícia manualmente
     */
    public function addNews(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'link' => $_POST['link'] ?? '',
                'source_name' => $_POST['source_name'] ?? 'Manual',
                'category' => $_POST['category'] ?? 'nacional',
                'image_url' => $_POST['image_url'] ?? ''
            ];
            
            if (empty($data['title']) || empty($data['link'])) {
                $error = 'Título e link são obrigatórios';
            } else {
                $id = $this->newsModel->addManualNews($data);
                if ($id) {
                    $success = 'Notícia adicionada com sucesso!';
                } else {
                    $error = 'Erro ao adicionar notícia';
                }
            }
        }
        
        global $CATEGORIES;
        
        Helpers::render('admin/news_form', [
            'categories' => $CATEGORIES,
            'error' => $error,
            'success' => $success,
            'editMode' => false
        ]);
    }
    
    /**
     * Editar notícia
     */
    public function editNews(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $id = (int)Helpers::getParam('id', 0);
        $error = '';
        $success = '';
        
        $news = $this->newsModel->getNewsById($id);
        
        if (!$news) {
            header('Location: ' . SITE_URL . '/admin/news');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'category' => $_POST['category'] ?? 'nacional',
                'image_url' => $_POST['image_url'] ?? ''
            ];
            
            if (empty($data['title'])) {
                $error = 'Título é obrigatório';
            } else {
                if ($this->newsModel->updateNews($id, $data)) {
                    $success = 'Notícia atualizada com sucesso!';
                    $news = $this->newsModel->getNewsById($id);
                } else {
                    $error = 'Erro ao atualizar notícia';
                }
            }
        }
        
        global $CATEGORIES;
        
        Helpers::render('admin/news_form', [
            'categories' => $CATEGORIES,
            'error' => $error,
            'success' => $success,
            'news' => $news,
            'editMode' => true
        ]);
    }
    
    /**
     * Excluir notícia
     */
    public function deleteNews(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $id = (int)Helpers::getParam('id', 0);
        
        if ($id && $this->newsModel->deleteNews($id)) {
            $_SESSION['success'] = 'Notícia excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir notícia';
        }
        
        header('Location: ' . SITE_URL . '/admin/news');
        exit;
    }
    
    /**
     * Configurações do portal
     */
    public function settings(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $error = '';
        $success = '';
        
        $settings = [
            'site_name' => $this->settingsModel->getValue('site_name', SITE_NAME),
            'site_logo' => $this->settingsModel->getValue('site_logo', ''),
            'whatsapp' => $this->settingsModel->getValue('whatsapp', ''),
            'email' => $this->settingsModel->getValue('email', ''),
            'admin_username' => $this->settingsModel->getValue('admin_username', 'admin'),
            'admin_password' => $this->settingsModel->getValue('admin_password', 'admin123'),
            'ad_code_header' => $this->settingsModel->getValue('ad_code_header', ''),
            'ad_code_sidebar' => $this->settingsModel->getValue('ad_code_sidebar', ''),
            'ad_code_footer' => $this->settingsModel->getValue('ad_code_footer', '')
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $toSave = [
                'site_name' => $_POST['site_name'] ?? SITE_NAME,
                'site_logo' => $_POST['site_logo'] ?? '',
                'whatsapp' => $_POST['whatsapp'] ?? '',
                'email' => $_POST['email'] ?? '',
                'admin_username' => $_POST['admin_username'] ?? 'admin',
                'admin_password' => $_POST['admin_password'] ?? 'admin123',
                'ad_code_header' => $_POST['ad_code_header'] ?? '',
                'ad_code_sidebar' => $_POST['ad_code_sidebar'] ?? '',
                'ad_code_footer' => $_POST['ad_code_footer'] ?? ''
            ];
            
            if ($this->settingsModel->setMultiple($toSave)) {
                $success = 'Configurações salvas com sucesso!';
                $settings = $toSave;
            } else {
                $error = 'Erro ao salvar configurações';
            }
        }
        
        Helpers::render('admin/settings', [
            'settings' => $settings,
            'error' => $error,
            'success' => $success
        ]);
    }
    
    /**
     * Gerenciar enquetes
     */
    public function polls(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $polls = $this->pollModel->getAll();
        
        Helpers::render('admin/polls', [
            'polls' => $polls
        ]);
    }
    
    /**
     * Atualizar feeds manualmente
     */
    public function updateFeeds(): void {
        if (!$this->checkAuth()) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
        
        $result = $this->feedService->updateAllFeeds();
        
        // Salvar timestamp da última atualização
        $this->settingsModel->set('last_update', date('Y-m-d H:i:s'));
        
        // Salvar notícias no banco
        $db = getDbConnection();
        $newsModel = new NewsModel($db);
        
        $totalNew = 0;
        foreach ($GLOBALS['NEWS_SOURCES'] as $source) {
            try {
                $items = $this->feedService->fetchFeed($source);
                if (!empty($items)) {
                    $newCount = $newsModel->saveNews($items);
                    $totalNew += $newCount;
                }
            } catch (Exception $e) {
                // Continuar mesmo com erro
            }
        }
        
        $_SESSION['update_result'] = [
            'successful_feeds' => $result['successful_feeds'],
            'failed_feeds' => $result['failed_feeds'],
            'total_new' => $totalNew
        ];
        
        header('Location: ' . SITE_URL . '/admin/dashboard?updated=1');
        exit;
    }
}
