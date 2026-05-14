<?php
require_once __DIR__ . '/../header.php';

// FALLBACK – pokud $teams není nastaveno z controlleru
if (!isset($teams)) {
    require_once __DIR__ . '/../../models/Team.php';
    $teamModel = new Team();
    if (isset($_SESSION['user_id'])) {
        $teams = $teamModel->getAllByUser($_SESSION['user_id']);
    } else {
        $teams = [];
    }
    $baseUrl = "?url=";
} else {
    $baseUrl = "?url=";
}
?>

<div class="card">
    <div class="header">
        <div>
            <h2>⚽ Moje oblíbené týmy</h2>
            <p class="subtitle">Váš osobní seznam oblíbených fotbalových klubů.</p>
        </div>
        <a href="?url=team/create" class="btn btn-primary">+ Přidat tým</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Název klubu</th>
                    <th>Země</th>
                    <th>Liga</th>
                    <th>Založen</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teams)): ?>
                    <?php $counter = 1; ?>
                    <?php foreach($teams as $t): ?>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 600;"><?= $counter++ ?></td>
                        <td style="font-weight: 600; font-size: 1rem;"><?= htmlspecialchars($t['team_name'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($t['country'])): ?>
                                <span class="badge badge-country"><?= htmlspecialchars($t['country']) ?></span>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($t['league'])): ?>
                                <span class="badge badge-league"><?= htmlspecialchars($t['league']) ?></span>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= !empty($t['founded_year']) ? htmlspecialchars($t['founded_year']) : '—' ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?= $baseUrl ?>team/show/<?= htmlspecialchars($t['id']) ?>" class="btn btn-sm btn-secondary">Detail</a>
                                <a href="<?= $baseUrl ?>team/edit/<?= htmlspecialchars($t['id']) ?>" class="btn btn-sm btn-warning">Upravit</a>
                                <a href="<?= $baseUrl ?>team/delete/<?= htmlspecialchars($t['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Opravdu chcete odebrat tento tým?');">Smazat</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <span class="emoji">⚽</span>
                                <h3>Zatím nemáte žádné oblíbené týmy</h3>
                                <p style="margin-bottom: 1.5rem;">Začněte přidáním prvního klubu do vašeho seznamu!</p>
                                <a href="?url=team/create" class="btn btn-primary">+ Přidat první tým</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../footer.php';
?>
