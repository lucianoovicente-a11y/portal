<?php
$id = $_GET['id'] ?? 0;
$item = $newsModel->getById($id);

if (!$item) {
    die('Notícia não encontrada');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'content' => $_POST['content'],
        'image' => $_POST['image'],
        'source' => $_POST['source'],
        'category' => $_POST['category'],
        'published_at' => $_POST['published_at']
    ];
    
    if ($newsModel->update($id, $data)) {
        $_SESSION['message'] = 'Notícia atualizada com sucesso!';
        header('Location: index.php?action=news');
        exit;
    }
}
?>
<div class="card">
    <h2>Editar Notícia #<?= $id ?></h2>
    <form method="POST">
        <label>Título *</label>
        <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
        
        <label>Categoria *</label>
        <select name="category" required>
            <?php foreach (CATEGORIES as $key => $label): ?>
                <option value="<?= $key ?>" <?= $item['category']==$key?'selected':'' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Descrição</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
        
        <label>Conteúdo</label>
        <textarea name="content" rows="5"><?= htmlspecialchars($item['content']) ?></textarea>
        
        <label>URL da Imagem</label>
        <input type="text" name="image" value="<?= htmlspecialchars($item['image']) ?>">
        
        <label>Fonte</label>
        <input type="text" name="source" value="<?= htmlspecialchars($item['source']) ?>">
        
        <label>Data</label>
        <input type="datetime-local" name="published_at" value="<?= date('Y-m-d\TH:i', strtotime($item['published_at'])) ?>">
        
        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="?action=news" class="btn btn-primary">Cancelar</a>
        <a href="?action=delete_news&id=<?= $id ?>&confirm=1" onclick="return confirm('Excluir?')" class="btn btn-danger">Excluir</a>
    </form>
</div>
