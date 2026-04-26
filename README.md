# Mega Portal de Notícias

Portal agregador de notícias desenvolvido em **PHP Puro** com banco de dados **SQLite**.

## 🚀 Características

- ✅ 100% PHP puro (sem frameworks)
- ✅ Banco de dados SQLite (sem necessidade de MySQL/PostgreSQL)
- ✅ Abastecimento automático via RSS feeds
- ✅ Design responsivo e moderno
- ✅ Filtro por fonte de notícia
- ✅ Paginação de resultados
- ✅ Atualização automática via cron

## 📁 Estrutura de Arquivos

```
/workspace
├── index.php           # Página principal do portal
├── config.php          # Configurações e funções do sistema
├── update_feeds.php    # Script de atualização dos feeds RSS
├── data/
│   └── news.db         # Banco de dados SQLite (criado automaticamente)
└── README.md           # Este arquivo
```

## 🔧 Instalação

### 1. Pré-requisitos

- PHP 7.4 ou superior
- Extensão PDO_SQLite habilitada
- Permissão de escrita na pasta `/workspace/data`

### 2. Configuração Inicial

O sistema já vem pré-configurado com as seguintes fontes de notícias:

- G1
- UOL Notícias
- R7
- Terra
- BBC Brasil
- CNN Brasil
- Estadão
- Folha de S.Paulo
- O Globo
- Reuters

### 3. Primeira Execução

Execute o script de atualização para popular o banco de dados:

```bash
php update_feeds.php
```

### 4. Acessar o Portal

Abra o arquivo `index.php` no seu navegador através de um servidor web:

```bash
# Usando o servidor embutido do PHP
php -S localhost:8000 -t /workspace
```

Acesse: http://localhost:8000

## ⏰ Atualização Automática

Para atualizar as notícias automaticamente a cada 5 minutos, configure um job no cron:

```bash
crontab -e
```

Adicione a linha:

```cron
*/5 * * * * php /workspace/update_feeds.php >> /workspace/data/cron.log 2>&1
```

## 🎨 Personalização

### Adicionar Novas Fontes

Edite o arquivo `config.php` e adicione novas fontes no array `$news_sources`:

```php
$news_sources = [
    'G1' => 'https://g1.globo.com/rss/g1/',
    'Nova Fonte' => 'https://exemplo.com/rss',
    // ...
];
```

### Alterar Cores do Tema

No arquivo `index.php`, modifique as variáveis CSS na seção `:root`:

```css
:root {
    --primary-color: #c4170c;      /* Cor principal */
    --secondary-color: #1a1a2e;    /* Cor secundária */
    --accent-color: #0f3460;       /* Cor de destaque */
}
```

## 📊 Funcionalidades

- **Página Principal**: Grid de notícias com cards responsivos
- **Filtro por Fonte**: Selecione notícias de uma fonte específica
- **Busca**: Pesquise notícias por termo (funcionalidade a implementar)
- **Paginação**: Navegue por páginas de resultados
- **Breaking News**: Banner com a última notícia
- **Sidebar**: Lista de fontes e notícias mais recentes
- **Estatísticas**: Total de notícias e fontes disponíveis

## 🗄️ Banco de Dados

O sistema utiliza SQLite com as seguintes tabelas:

### sources
- id, name, url, active, last_update

### news
- id, title, description, content, link, image_url, source_id, category, published_at, created_at, updated_at

### categories
- id, name

## 🔒 Segurança

- Prepared statements para prevenir SQL injection
- htmlspecialchars para prevenir XSS
- Validação de inputs do usuário

## 📝 Licença

Desenvolvido para fins educacionais e de demonstração.

## 💡 Dicas

1. Mantenha o script de atualização em execução periódica para ter notícias sempre atualizadas
2. Monitore o log do cron para verificar possíveis erros
3. Ajuste o número de notícias por página conforme necessário
4. Considere implementar cache para melhorar performance

## 🛠️ Troubleshooting

### Erro: "Nenhuma notícia encontrada"

Execute manualmente o script de atualização:
```bash
php update_feeds.php
```

### Erro: "Permission denied"

Verifique as permissões da pasta data:
```bash
chmod 755 /workspace/data
chown www-data:www-data /workspace/data
```

### Erro: "PDO extension not found"

Instale a extensão PDO SQLite:
```bash
# Ubuntu/Debian
apt-get install php-sqlite3

# CentOS/RHEL
yum install php-pdo
```

---

**Desenvolvido com ❤️ em PHP Puro + SQLite**
