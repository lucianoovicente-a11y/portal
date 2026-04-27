# Mega Portal de Notícias

Portal de notícias completo com atualização automática via RSS, painel administrativo e múltiplas categorias.

## 📋 Categorias Incluídas

- **Nacional**: G1, UOL, R7, CNN Brasil, Estadão, Folha, O Globo
- **Internacional**: BBC News, Reuters, Deutsche Welle, France 24
- **Esportes**: Globo Esporte, ESPN, Lance!, Gazeta Esportiva
- **Janelas de Transferência**: Mercado da Bola, 90min
- **Tecnologia**: TecMundo, Canaltech, Adrenaline, Olhar Digital
- **IA**: AI News, VentureBeat AI
- **Política**: Poder360, Congresso em Foco, Política Hoje
- **Guerra**: BBC World, Al Jazeera, Reuters World
- **Gospel**: Gospel+, Guiame, CPAD News

## 🚀 Funcionalidades

### Front-end
- ✅ Layout em grade responsivo
- ✅ Filtro por categoria
- ✅ Busca de notícias
- ✅ Atualização automática (configurada para 1 hora)
- ✅ Breaking news banner
- ✅ Espaço para publicidade (header, sidebar, footer)

### Painel Administrativo
- ✅ Login seguro (admin/admin123 por padrão)
- ✅ Dashboard com estatísticas
- ✅ Gerenciar notícias (CRUD completo)
- ✅ Adicionar notícias manualmente
- ✅ Atualizar feeds manualmente
- ✅ Configurações do portal:
  - Nome do site
  - Logotipo
  - WhatsApp
  - E-mail
  - Usuário/senha admin
  - Códigos de publicidade
- ✅ Sistema de enquetes
- ✅ Botão de atualização manual

## 📁 Estrutura de Arquivos

```
/workspace
├── index.php              # Página principal
├── admin.php              # Painel administrativo
├── update_feeds.php       # Script de atualização
├── cron_update.sh         # Script para cron (atualização horária)
├── .htaccess              # Regras de rewrite
├── config/
│   └── database.php       # Configuração do banco e fontes RSS
├── src/
│   ├── Models/
│   │   ├── NewsModel.php
│   │   ├── SettingsModel.php
│   │   └── PollModel.php
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   └── AdminController.php
│   ├── Services/
│   │   └── FeedService.php
│   ├── Views/
│   │   ├── home/
│   │   │   └── index.php
│   │   └── admin/
│   │       ├── login.php
│   │       ├── dashboard.php
│   │       ├── news.php
│   │       ├── news_form.php
│   │       └── settings.php
│   └── Utils/
│       ├── Helpers.php
│       └── Router.php
├── public/
│   ├── css/
│   │   └── style.css
│   └── js/
└── data/
    └── portal.db          # Banco SQLite
```

## 🔧 Instalação

1. Certifique-se de ter PHP 8.0+ instalado
2. Clone o repositório ou copie os arquivos para seu servidor
3. Acesse o site pela primeira vez para inicializar o banco de dados
4. Execute a atualização dos feeds: `php update_feeds.php`

## ⚙️ Configuração de Atualização Automática

### Via Cron (Linux)
Adicione ao crontab (`crontab -e`):
```bash
0 * * * * /workspace/cron_update.sh
```

### Via Painel Administrativo
- Acesse `/admin/login`
- Use as credenciais: `admin` / `admin123`
- Clique em "Atualizar Feeds Agora"

## 🔐 Acesso Administrativo

- URL: `/admin` ou `/admin.php`
- Usuário padrão: `admin`
- Senha padrão: `admin123`

**Importante**: Altere as credenciais no menu Configurações!

## 📊 Banco de Dados

O sistema utiliza SQLite localizado em `data/portal.db`.

Tabelas criadas automaticamente:
- `news` - Notícias
- `settings` - Configurações do portal
- `polls` - Enquetes
- `poll_options` - Opções das enquetes
- `poll_votes` - Votos das enquetes

## 🎨 Personalização

Todas as configurações podem ser alteradas pelo painel administrativo:
- Nome do site
- Logotipo
- Contatos (WhatsApp, E-mail)
- Credenciais de acesso
- Códigos de publicidade (Google AdSense, etc.)

## 📱 Responsividade

O layout é totalmente responsivo e se adapta a:
- Desktops
- Tablets
- Celulares

## 🔄 Atualização de Feeds

A atualização ocorre:
- Automaticamente a cada hora (via cron)
- Manualmente pelo botão no painel admin
- Via script CLI: `php update_feeds.php`

## 📝 Licença

Use livremente para seus projetos!
