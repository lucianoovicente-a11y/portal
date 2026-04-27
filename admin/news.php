<div class="card">
    <h2>Gerenciar Notícias</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Título</th><th>Categoria</th><th>Fonte</th><th>Data</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php 
            $allNews = $newsModel->getAll(null, 100);
            foreach ($allNews as $item): 
            ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars(substr($item['title'], 0, 40)) ?>...</td>
                    <td><?= CATEGORIES[$item['category']] ?? $item['category'] ?></td>
                    <td><?= htmlspecialchars($item['source']) ?></td>
                    <td><?= date('d/m/Y', strtotime($item['published_at'])) ?></td>
                    <td>
                        <a href="?action=edit_news&id=<?= $item['id'] ?>" class="btn btn-primary" style="padding:5px 10px;font-size:12px;">Editar</a>
                        <a href="?action=delete_news&id=<?= $item['id'] ?>&confirm=1" onclick="return confirm('Excluir esta notícia?')" class="btn btn-danger" style="padding:5px 10px;font-size:12px;">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
