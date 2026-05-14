<?php require_once __DIR__ . '/../header.php'; ?>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="header">
                <div>
                    <h2>✏️ Upravit tým</h2>
                    <p class="subtitle">Upravujete záznam: <strong style="color: var(--accent);"><?= htmlspecialchars($team['team_name'] ?? '') ?></strong></p>
                </div>
                <a href="?url=team/index" class="btn btn-secondary">← Zpět na seznam</a>
            </div>

            <form action="?url=team/update/<?= htmlspecialchars($team['id']) ?>" method="post">
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
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($team['description'] ?? '') ?></textarea>
                    </div>    
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">💾 Uložit změny</button>
                    <a href="?url=team/index" class="btn btn-secondary">Zpět na seznam</a>
                </div>
            </form>
        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>
