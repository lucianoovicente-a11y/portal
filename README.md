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
- ✅ Layout em grade responsivo com cards modernos
- ✅ Todas as notícias com imagens (ou ícone placeholder)
- ✅ Filtro por categorias
- ✅ Busca de notícias
- ✅ Sistema de enquetes
- ✅ Estatísticas em tempo real
- ✅ Espaços para publicidade (header, sidebar, footer)
- ✅ Cabeçalho com logo, busca e botão admin
- ✅ Rodapé com informações de contato

### Painel Administrativo (`/admin`)
- ✅ Login seguro (usuário: `admin`, senha: `132004`)
- ✅ Dashboard com estatísticas por categoria
- ✅ CRUD completo de notícias (criar, editar, excluir)
- ✅ Adicionar notícias manualmente com imagem
- ✅ Botão "Atualizar Feeds Agora" (atualização manual)
- ✅ Configurações editáveis:
  - Nome do site e logotipo
  - WhatsApp e E-mail
  - Credenciais de acesso admin
  - Códigos de publicidade (header, sidebar, footer)
- ✅ Sistema de enquetes
- ✅ Exclusão e edição de notícias

## 🔐 Acesso Admin

- URL: `/admin` ou `/admin/login`
- Usuário padrão: `admin`
- Senha padrão: `132004`

## 🚀 Instalação

1. Certifique-se de ter PHP 7.4+ com SQLite habilitado
2. Acesse o site pela primeira vez para criar o banco automaticamente
3. Execute a atualização inicial:
   ```bash
   php update_feeds.php
   ```

## ⏰ Atualização Automática

### Via Cron (recomendado)
Adicione ao crontab (`crontab -e`):
```
0 * * * * /workspace/cron_update.sh
```
Isso atualiza as notícias a cada hora automaticamente.

### Manual
Pelo painel admin: clique em "🔄 Atualizar Feeds Agora"

Ou via terminal:
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
│   │   └── FeedService.php    # Extração avançada de imagens
│   └── Views/
│       └── partials/
│           ├── header.php     # Cabeçalho com logo e busca
│           ├── sidebar.php    # Estatísticas e enquetes
│           └── footer.php     # Rodapé com contatos
├── admin/
│   ├── index.php          # Painel principal
│   ├── login.php          # Login (senha: 132004)
│   ├── dashboard.php      # Dashboard c/ botão atualizar
│   ├── news.php           # Lista de notícias
│   ├── add_news.php       # Adicionar notícia manual
│   ├── edit_news.php      # Editar notícia
│   ├── settings.php       # Configurações do site
│   ├── polls.php          # Gerenciar enquetes
│   └── poll_vote.php      # API de votação
├── public/
│   ├── css/style.css      # Estilos completos
│   └── uploads/           # Uploads futuros
├── data/
│   └── portal.db          # Banco SQLite (auto-criado)
├── index.php              # Página principal em grade
├── update_feeds.php       # Script de atualização CLI
└── cron_update.sh         # Script cron horário
```

## 🎯 Destaques

- **Imagens em todas as notícias**: Sistema inteligente extrai imagens de múltiplos formatos RSS
- **Grade responsiva**: Cards se adaptam a qualquer tamanho de tela
- **Placeholder elegante**: Ícone 📰 aparece quando não há imagem
- **Lazy loading**: Imagens carregam sob demanda para melhor performance
- **Segurança**: Links externos com `rel="noopener"`

## 🛠️ Tecnologias

- PHP 7.4+
- SQLite
- HTML5/CSS3 (Grid, Flexbox)
- JavaScript (vanilla)

## 📝 Licença

Uso livre.
