<?php
if (isset($_GET['delete'])) {
    $pollModel->delete($_GET['delete']);
    $_SESSION['message'] = 'Enquete excluída!';
    header('Location: index.php?action=polls');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $options = array_filter(explode("\n", $_POST['options']));
    
    if ($question && count($options) >= 2) {
        $pollModel->deactivateAll();
        $pollModel->add($question, $options);
        $_SESSION['message'] = 'Enquete criada com sucesso!';
        header('Location: index.php?action=polls');
        exit;
    }
}

$polls = $pollModel->getAll();
?>
<div class="card">
    <h2>Gerenciar Enquetes</h2>
    
    <h3>Nova Enquete</h3>
    <form method="POST">
        <label>Pergunta</label>
        <input type="text" name="question" required placeholder="Ex: Qual seu time favorito?">
        
        <label>Opções (uma por linha)</label>
        <textarea name="options" rows="5" required placeholder="Time A&#10;Time B&#10;Time C"></textarea>
        
        <button type="submit" class="btn btn-success">Criar Enquete</button>
    </form>
    
    <h3 style="margin-top:30px;">Enquetes Existentes</h3>
    <?php foreach ($polls as $poll): ?>
        <div style="border:1px solid #ddd;padding:15px;margin:10px 0;border-radius:5px;">
            <strong><?= htmlspecialchars($poll['question']) ?></strong>
            <?php if ($poll['is_active']): ?>
                <span style="color:green;font-weight:bold;"> [Ativa]</span>
            <?php endif; ?>
            <br><small>Votos: <?= array_sum(json_decode($poll['votes'], true) ?: []) ?></small>
            <a href="?action=polls&delete=<?= $poll['id'] ?>" onclick="return confirm('Excluir?')" style="color:red;margin-left:15px;">Excluir</a>
        </div>
    <?php endforeach; ?>
</div>
