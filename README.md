# Mega Portal de Notícias

Portal completo de notícias com múltiplas categorias, painel administrativo e atualização automática.

## 📋 Categorias

- **Nacional**: G1, UOL, R7, CNN Brasil, Estadão, Folha, O Globo
- **Internacional**: BBC News, Reuters, Deutsche Welle, France 24
- **Esportes** (foco em futebol): Globo Esporte, ESPN, Lance!, Gazeta Esportiva, Mercado da Bola
- **Janelas de Transferência**: 90min, Goal
- **Tecnologia**: TecMundo, Canaltech, Adrenaline, Olhar Digital
- **IA**: VentureBeat AI, AI News
- **Política**: Poder360, Congresso em Foco, Política Hoje
- **Guerra** (Brasil e Mundo): BBC World, Al Jazeera, Reuters World
- **Gospel**: Gospel+, Guiame, CPAD News

## ✨ Funcionalidades

### Front-end
- Layout em grade responsivo
- Filtro por categorias
- Busca de notícias
- Sistema de enquetes
- Estatísticas em tempo real
- Espaços para publicidade

### Painel Administrativo (`/admin`)
- Login seguro
- Dashboard com estatísticas
- CRUD completo de notícias
- Adicionar notícias manualmente
- Botão "Atualizar Feeds Agora"
- Configurações editáveis:
  - Nome do site e logotipo
  - WhatsApp e E-mail
  - Credenciais de acesso
  - Códigos de publicidade (header, sidebar, footer)
- Sistema de enquetes
- Exclusão e edição de notícias

## 🔐 Acesso Admin

- URL: `/admin` ou `/admin/login`
- Usuário padrão: `admin`
- Senha padrão: `admin123`

## 🚀 Instalação

1. Certifique-se de ter PHP 7.4+ com SQLite habilitado
2. Acesse o site pela primeira vez para criar o banco
3. Execute a atualização inicial:
   ```bash
   php update_feeds.php
   ```

## ⏰ Atualização Automática

### Via Cron (recomendado)
Adicione ao crontab (crontab -e):
```
0 * * * * /workspace/cron_update.sh
```

### Manual
```bash
php update_feeds.php
```

## 📁 Estrutura

```
/workspace
├── config/
│   └── database.php       # Configurações e fontes RSS
├── src/
│   ├── Models/
│   │   ├── Database.php
│   │   ├── NewsModel.php
│   │   ├── SettingsModel.php
│   │   └── PollModel.php
│   ├── Services/
│   │   └── FeedService.php
│   └── Views/
│       └── partials/
├── admin/
│   ├── index.php          # Painel principal
│   ├── login.php
│   ├── dashboard.php
│   ├── news.php
│   ├── add_news.php
│   ├── edit_news.php
│   ├── settings.php
│   ├── polls.php
│   └── poll_vote.php
├── public/
│   ├── css/style.css
│   └── uploads/
├── data/
│   └── portal.db          # Banco SQLite
├── index.php              # Página principal
├── update_feeds.php       # Script de atualização
└── cron_update.sh         # Script cron
```

## 🛠️ Tecnologias

- PHP 7.4+
- SQLite
- HTML5/CSS3
- JavaScript (vanilla)

## 📝 Licença

Uso livre.
