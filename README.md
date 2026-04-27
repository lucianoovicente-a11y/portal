# 📰 Mega Portal de Notícias

Portal de notícias automático em **PHP Puro** com arquitetura MVC e banco de dados **SQLite**.

## ✨ Funcionalidades

- ✅ **Arquitetura MVC** - Código organizado em Model, View e Controller
- ✅ **100% PHP Puro** - Sem frameworks ou dependências externas
- ✅ **SQLite** - Banco de dados embutido, sem necessidade de MySQL/PostgreSQL
- ✅ **10 Fontes de Notícias** pré-configuradas (G1, UOL, R7, Terra, BBC, CNN, Estadão, Folha, O Globo, Reuters)
- ✅ **Design Responsivo** - Funciona em desktop e mobile
- ✅ **Filtro por Fonte** - Selecione notícias de fontes específicas
- ✅ **Busca** - Pesquise notícias por palavras-chave
- ✅ **Paginação** - Navegação por páginas de resultados
- ✅ **Breaking News Banner** - Destaque para última notícia
- ✅ **Sidebar** - Lista de fontes e estatísticas
- ✅ **Atualização Automática** - Via script cron (a cada 5 minutos)
- ✅ **Segurança** - Prepared statements contra SQL injection, XSS protection

## 📁 Estrutura de Arquivos

```
/workspace/
├── index.php              # Ponto de entrada da aplicação
├── update_feeds.php       # Script de atualização dos feeds
├── install.sh             # Script de instalação automatizada
├── README.md              # Esta documentação
├── config/
│   └── database.php       # Configurações do banco de dados
├── src/
│   ├── Controllers/
│   │   └── HomeController.php    # Controller principal
│   ├── Models/
│   │   └── NewsModel.php         # Model de notícias
│   ├── Services/
│   │   └── FeedService.php       # Service de processamento RSS
│   ├── Views/
│   │   └── home/
│   │       └── index.php         # View da página inicial
│   └── Utils/
│       └── Helpers.php           # Funções utilitárias
├── public/
│   ├── css/
│   │   └── style.css             # Estilos CSS
│   └── js/                       # JavaScript (futuro)
└── data/
    └── portal.db                 # Banco de dados SQLite
```

## 🚀 Instalação

### Pré-requisitos

- PHP 7.4 ou superior
- Extensão SQLite3 habilitada no PHP

### Passo a Passo

1. **Clone o repositório** (se ainda não estiver no local):
```bash
cd /workspace
```

2. **Execute o script de instalação**:
```bash
./install.sh
```

Ou manualmente:

```bash
# Verificar PHP e SQLite
php -v
php -m | grep sqlite

# Criar diretório de dados
mkdir -p data
chmod 755 data

# Executar primeira atualização
php update_feeds.php
```

## 🎯 Uso

### Iniciar Servidor de Desenvolvimento

```bash
php -S localhost:8000 -t /workspace
```

### Acessar no Navegador

```
http://localhost:8000
```

### Atualizar Notícias Manualmente

```bash
php update_feeds.php
```

### Configurar Atualização Automática (Cron)

Edite o crontab:
```bash
crontab -e
```

Adicione a linha:
```cron
*/5 * * * * php /workspace/update_feeds.php
```

Isso atualizará as notícias a cada 5 minutos automaticamente.

## 🔧 Configuração

### Adicionar Novas Fontes

Edite o arquivo `config/database.php` e adicione novas fontes ao array `$NEWS_SOURCES`:

```php
$NEWS_SOURCES = [
    // ... fontes existentes ...
    [
        'name' => 'Nova Fonte',
        'url' => 'https://exemplo.com/rss',
        'category' => 'Geral'
    ],
];
```

### Personalizar Cores

Edite as variáveis CSS em `public/css/style.css`:

```css
:root {
    --primary-color: #c0392b;      /* Cor principal */
    --primary-dark: #a93226;       /* Cor principal escura */
    --secondary-color: #2c3e50;    /* Cor secundária */
    --accent-color: #3498db;       /* Cor de destaque */
}
```

### Ajustar Quantidade de Notícias por Página

Em `config/database.php`, altere:

```php
define('NEWS_PER_PAGE', 20); // Mude para o valor desejado
```

## 🛡️ Segurança

- **SQL Injection**: Previno com prepared statements PDO
- **XSS**: Proteção com `htmlspecialchars()` em todas as saídas
- **Links Externos**: Todos os links abrem em nova aba com `rel="noopener noreferrer"`

## 📊 Banco de Dados

O SQLite é criado automaticamente na primeira execução. Estrutura:

```sql
CREATE TABLE news (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    link TEXT UNIQUE NOT NULL,
    pub_date DATETIME,
    source_name TEXT,
    source_url TEXT,
    category TEXT,
    image_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## 🔍 Funcionalidades da Interface

- **Header**: Logo, barra de busca responsiva
- **Breaking News**: Banner com a última notícia
- **Grid de Notícias**: Cards com imagem, título, descrição e fonte
- **Sidebar**:
  - Filtro por fonte com contador
  - Estatísticas em tempo real
  - Informações do sistema
- **Paginação**: Navegação inteligente entre páginas
- **Footer**: Informações de copyright e atualização

## 🐛 Solução de Problemas

### PHP não encontrado
```bash
# Ubuntu/Debian
sudo apt-get install php php-sqlite3

# CentOS/RHEL
sudo yum install php php-pdo
```

### Extensão SQLite não habilitada
```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# Verificar se está habilitada
php -m | grep sqlite
```

### Permissões do diretório data
```bash
chmod 755 /workspace/data
chown www-data:www-data /workspace/data  # Se usar Apache/Nginx
```

### Feeds não carregam
- Verifique sua conexão com a internet
- Alguns feeds podem estar temporariamente indisponíveis
- Execute `php update_feeds.php` para ver erros específicos

## 📝 Fontes Incluídas

| Fonte | Categoria |
|-------|-----------|
| G1 - Últimas Notícias | Geral |
| UOL Notícias | Geral |
| R7 Notícias | Geral |
| Terra Notícias | Geral |
| BBC News Brasil | Internacional |
| CNN Brasil | Geral |
| Estadão | Geral |
| Folha de S.Paulo | Geral |
| O Globo | Geral |
| Reuters Brasil | Internacional |

## 🏗️ Arquitetura

Este projeto segue o padrão **MVC (Model-View-Controller)**:

- **Models** (`src/Models/`): Gerenciam dados e regras de negócio
- **Views** (`src/Views/`): Templates HTML para apresentação
- **Controllers** (`src/Controllers/`): Processam requisições e coordenam models/views
- **Services** (`src/Services/`): Lógica de negócios específica (ex: processamento RSS)
- **Utils** (`src/Utils/`): Funções utilitárias reutilizáveis

## 📄 Licença

Este projeto é open source e pode ser usado livremente.

## 🤝 Contribuição

Sinta-se à vontade para:
- Adicionar novas fontes de notícias
- Melhorar o design
- Corrigir bugs
- Sugerir novas funcionalidades

---

**Desenvolvido com ❤️ em PHP Puro + SQLite + MVC**
