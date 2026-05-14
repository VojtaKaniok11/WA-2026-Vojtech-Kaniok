<?php require_once __DIR__ . '/../header.php'; ?>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="header">
                <div>
                    <h2>🏟️ Přidat nový oblíbený tým</h2>
                    <p class="subtitle">Vyplňte údaje o svém oblíbeném fotbalovém klubu.</p>
                </div>
                <a href="?url=team/index" class="btn btn-secondary">← Zpět na seznam</a>
            </div>

            <form action="?url=team/store" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="team_name">Název klubu <span>*</span></label>
                        <input type="text" id="team_name" name="team_name" placeholder="např. FC Barcelona" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Země</label>
                        <input type="text" id="country" name="country" placeholder="např. Španělsko">
                    </div>
                    <div class="form-group">
                        <label for="league">Liga</label>
                        <input type="text" id="league" name="league" placeholder="např. La Liga">
                    </div>
                    <div class="form-group">
                        <label for="founded_year">Rok založení</label>
                        <input type="number" id="founded_year" name="founded_year" placeholder="např. 1899" min="1800" max="2099">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Poznámka / Proč je to váš oblíbený tým?</label>
                        <textarea id="description" name="description" rows="5" placeholder="Napište, proč tento klub milujete..."></textarea>
                    </div>    
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">⚽ Uložit tým</button>
                    <a href="?url=team/index" class="btn btn-secondary">Storno</a>
                </div>
            </form>
        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>
