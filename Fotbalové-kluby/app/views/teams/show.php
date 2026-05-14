<?php
// Fallback protection pro přímý vstup
if (!isset($team)) {
    die("Detail nelze otevřít napřímo bez zadaného ID. Vstupte prosím přes odkaz 'Detail' v seznamu týmů.");
}

require_once __DIR__ . '/../header.php';
?>
<style>
    .detail-row { display: flex; padding: 1rem 0; border-bottom: 1px solid var(--border); }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; width: 30%; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.03em; }
    .detail-value { width: 70%; color: var(--text-main); font-size: 1.05rem; }
    .detail-desc { margin-top: 10px; padding: 15px; background: var(--bg-surface); border-radius: 8px; font-style: italic; color: var(--text-muted); border: 1px solid var(--border); }
</style>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            
            <div class="header">
                <div>
                    <h2>⚽ Detail týmu</h2>
                    <p class="subtitle">Kompletní informace o vašem oblíbeném klubu.</p>
                </div>
                <a href="?url=team/index" class="btn btn-secondary">← Zpět na seznam</a>
            </div>

            <div>
                <div class="detail-row">
                    <div class="detail-label">Název klubu</div>
                    <div class="detail-value" style="font-size: 1.4rem; font-weight:700; color: var(--accent);">
                        <?= htmlspecialchars($team['team_name'] ?? '') ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Země</div>
                    <div class="detail-value">
                        <?php if (!empty($team['country'])): ?>
                            <span class="badge badge-country"><?= htmlspecialchars($team['country']) ?></span>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">Nezadáno</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Liga</div>
                    <div class="detail-value">
                        <?php if (!empty($team['league'])): ?>
                            <span class="badge badge-league"><?= htmlspecialchars($team['league']) ?></span>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">Nezadáno</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Rok založení</div>
                    <div class="detail-value"><?= !empty($team['founded_year']) ? htmlspecialchars($team['founded_year']) : 'Nezadáno' ?></div>
                </div>
                <div class="detail-row" style="flex-direction: column;">
                    <div class="detail-label" style="width:100%; margin-bottom:10px;">Poznámka</div>
                    <div class="detail-value detail-desc" style="width:100%;">
                        <?= nl2br(htmlspecialchars($team['description'] ?? 'Žádná poznámka nebyla přidána.')) ?>
                    </div>
                </div>
            </div>

            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == ($team['user_id'] ?? null)): ?>
            <div class="form-actions" style="justify-content: flex-end;">
                <a href="?url=team/edit/<?= htmlspecialchars($team['id']) ?>" class="btn btn-warning">✏️ Upravit tým</a>
            </div>
            <?php endif; ?>

        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>
