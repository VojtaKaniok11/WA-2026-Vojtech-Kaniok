<?php require_once __DIR__ . '/../header.php'; ?>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="header">
                <div>
                    <h2>✏️ Upravit tým</h2>
                    <p class="subtitle">Upravujete záznam: <strong style="color: var(--accent);"><?= htmlspecialchars($team['team_name'] ?? '') ?></strong></p>
                </div>
                <a href="?url=team/index" class="btn btn-secondary">← Zpět na seznam</a>
            </div>

            <form action="?url=team/update/<?= htmlspecialchars($team['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="team_name">Název klubu <span>*</span></label>
                        <input type="text" id="team_name" name="team_name" value="<?= htmlspecialchars($team['team_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Země</label>
                        <input type="text" id="country" name="country" value="<?= htmlspecialchars($team['country'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="league">Liga</label>
                        <input type="text" id="league" name="league" value="<?= htmlspecialchars($team['league'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="founded_year">Rok založení</label>
                        <input type="number" id="founded_year" name="founded_year" value="<?= htmlspecialchars($team['founded_year'] ?? '') ?>" min="1800" max="2099">
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Poznámka / Proč je to váš oblíbený tým?</label>
                        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($team['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Sekce obrázku -->
                    <div class="form-group full-width">
                        <label>Logo nebo fotka týmu</label>

                        <?php if (!empty($team['image'])): ?>
                            <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1.25rem; background: var(--bg-surface); padding: 1rem; border-radius: 10px; border: 1px solid var(--border);">
                                <img src="<?= $baseUrl ?>/uploads/teams/<?= htmlspecialchars($team['image']) ?>"
                                     alt="Logo týmu"
                                     style="height: 80px; width: 80px; object-fit: contain; border-radius: 8px; background: rgba(255,255,255,0.05);">
                                <div>
                                    <p style="margin: 0; font-weight: 600; color: var(--text-main);">Aktuální obrázek</p>
                                    <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--text-muted);">
                                        Pokud nahrajete nový soubor, tento bude nahrazen.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <input type="file" id="team_image" name="team_image" accept="image/jpeg,image/png,image/gif,image/webp"
                               style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: 8px; padding: 0.6rem; color: var(--text-main); width: 100%; cursor: pointer;">
                        <small style="color: var(--text-muted); font-size: 0.78rem; margin-top: 0.3rem; display: block;">
                            Povolené formáty: JPG, PNG, GIF, WEBP · Max. 5 MB
                            <?= !empty($team['image']) ? '· Ponechte prázdné pro zachování stávajícího obrázku.' : '· Nepovinné.' ?>
                        </small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">💾 Uložit změny</button>
                    <a href="?url=team/index" class="btn btn-secondary">Zpět na seznam</a>
                </div>
            </form>
        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>
