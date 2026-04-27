<div class="sidebar-section">
    <h3>Estatísticas</h3>
    <ul class="stats-list">
        <li>Total: <strong><?= $newsModel->getTotalCount() ?></strong></li>
        <?php foreach (CATEGORIES as $key => $label): ?>
            <li><?= $label ?>: <strong><?= $categoryCounts[$key] ?? 0 ?></strong></li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($activePoll): ?>
<div class="sidebar-section poll-section">
    <h3>Enquete</h3>
    <div class="poll" data-poll-id="<?= $activePoll['id'] ?>">
        <p class="poll-question"><?= htmlspecialchars($activePoll['question']) ?></p>
        <div class="poll-options">
            <?php 
            $options = json_decode($activePoll['options'], true);
            $votes = json_decode($activePoll['votes'], true) ?: [];
            $totalVotes = array_sum($votes);
            foreach ($options as $index => $option): 
                $voteCount = $votes[$index] ?? 0;
                $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 1) : 0;
            ?>
                <button class="poll-option" data-option="<?= $index ?>"><?= htmlspecialchars($option) ?></button>
            <?php endforeach; ?>
        </div>
        <div class="poll-results" style="display:none;">
            <?php foreach ($options as $index => $option): 
                $voteCount = $votes[$index] ?? 0;
                $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 1) : 0;
            ?>
                <div class="poll-result-item">
                    <span><?= htmlspecialchars($option) ?></span>
                    <div class="poll-bar"><div class="poll-fill" style="width: <?= $percentage ?>%"></div></div>
                    <span><?= $voteCount ?> votos (<?= $percentage ?>%)</span>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="poll-total"><?= $totalVotes ?> votos</p>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($config['ads_sidebar'])): ?>
<div class="sidebar-section ads-sidebar"><?= $config['ads_sidebar'] ?></div>
<?php endif; ?>

<div class="sidebar-section">
    <h3>Contato</h3>
    <?php if (!empty($config['whatsapp'])): ?>
        <p>📱 <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $config['whatsapp']) ?>"><?= htmlspecialchars($config['whatsapp']) ?></a></p>
    <?php endif; ?>
    <?php if (!empty($config['email'])): ?>
        <p>✉️ <a href="mailto:<?= htmlspecialchars($config['email']) ?>"><?= htmlspecialchars($config['email']) ?></a></p>
    <?php endif; ?>
</div>
