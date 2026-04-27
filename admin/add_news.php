<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'content' => $_POST['content'],
        'link' => $_POST['link'] ?: '#',
        'image' => $_POST['image'],
        'source' => $_POST['source'] ?: 'Manual',
        'category' => $_POST['category'],
        'published_at' => $_POST['published_at'] ?: date('Y-m-d H:i:s'),
        'is_manual' => 1
    ];
    
    if ($newsModel->add($data)) {
        $_SESSION['message'] = 'Notícia adicionada com sucesso!';
        header('Location: index.php?action=news');
        exit;
    } else {
        $error = 'Erro ao adicionar notícia. Link já existe?';
    }
}
?>
<div class="card">
    <h2>Adicionar Notícia Manual</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Título *</label>
        <input type="text" name="title" required>
        
        <label>Categoria *</label>
        <select name="category" required>
            <?php foreach (CATEGORIES as $key => $label): ?>
                <option value="<?= $key ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Descrição</label>
        <textarea name="description" rows="3"></textarea>
        
        <label>Conteúdo Completo</label>
        <textarea name="content" rows="5"></textarea>
        
        <label>URL da Imagem</label>
        <input type="text" name="image" placeholder="https://...">
        
        <label>Link da Notícia</label>
        <input type="text" name="link" placeholder="https://...">
        
        <label>Fonte</label>
        <input type="text" name="source" placeholder="Ex: Redação">
        
        <label>Data de Publicação</label>
        <input type="datetime-local" name="published_at" value="<?= date('Y-m-d\TH:i') ?>">
        
        <button type="submit" class="btn btn-success">Salvar Notícia</button>
        <a href="?action=news" class="btn btn-primary">Cancelar</a>
    </form>
</div>
