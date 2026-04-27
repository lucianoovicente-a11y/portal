<footer class="site-footer">
    <?php if (!empty($config['ads_footer'])): ?>
        <div class="ads-banner ads-footer"><?= $config['ads_footer'] ?></div>
    <?php endif; ?>
    
    <div class="container footer-content">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['site_name'] ?? 'Portal de Notícias') ?>. Todos os direitos reservados.</p>
        <div class="footer-contact">
            <?php if (!empty($config['whatsapp'])): ?>
                <span>WhatsApp: <?= htmlspecialchars($config['whatsapp']) ?></span>
            <?php endif; ?>
            <?php if (!empty($config['email'])): ?>
                <span>Email: <?= htmlspecialchars($config['email']) ?></span>
            <?php endif; ?>
        </div>
    </div>
</footer>

<script>
// Votação em enquete
document.querySelectorAll('.poll-option').forEach(btn => {
    btn.addEventListener('click', function() {
        const pollId = this.closest('.poll').dataset.pollId;
        const option = this.dataset.option;
        fetch('/admin/poll_vote.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({poll_id: pollId, option: option})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    });
});
</script>
