<div class="stats-grid">
    <div class="stat-card">
        <h3><?= $newsModel->getTotalCount() ?></h3>
        <p>Total de Notícias</p>
    </div>
    <?php foreach (CATEGORIES as $key => $label): 
        $count = $categoryCounts[$key] ?? 0;
    ?>
        <div class="stat-card" style="background: linear-gradient(135deg, <?= ['#667eea','#f093fb','#4facfe','#43e97b','#fa709a','#fee140','#30cfd0'][array_search($key, array_keys(CATEGORIES)) % 7] ?>, #333);">
            <h3><?= $count ?></h3>
            <p><?= $label ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h2>Ações Rápidas</h2>
    <div style="display:flex;gap:15px;margin-top:20px;flex-wrap:wrap;">
        <form method="POST" action="update_feeds.php" style="display:inline;">
            <button type="submit" name="manual_update" class="btn btn-warning">🔄 Atualizar Feeds Agora</button>
        </form>
        <a href="?action=add_news" class="btn btn-success">➕ Adicionar Notícia Manual</a>
        <a href="?action=polls" class="btn btn-primary">📊 Gerenciar Enquetes</a>
    </div>
</div>

<div class="card">
    <h2>Últimas Notícias</h2>
    <table>
        <thead>
            <tr><th>Título</th><th>Categoria</th><th>Data</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php 
            $latest = $newsModel->getLatest(10);
            foreach ($latest as $item): 
            ?>
                <tr>
                    <td><?= htmlspecialchars(substr($item['title'], 0, 50)) ?>...</td>
                    <td><?= CATEGORIES[$item['category']] ?? $item['category'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($item['published_at'])) ?></td>
                    <td>
                        <a href="?action=edit_news&id=<?= $item['id'] ?>" class="btn btn-primary" style="padding:5px 10px;font-size:12px;">Editar</a>
                        <a href="?action=delete_news&id=<?= $item['id'] ?>" onclick="return confirm('Tem certeza?')" class="btn btn-danger" style="padding:5px 10px;font-size:12px;">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
