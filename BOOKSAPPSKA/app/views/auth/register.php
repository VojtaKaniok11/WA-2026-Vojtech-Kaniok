<?php require_once __DIR__ . '/../header.php'; ?>

<main class="container">
    <div class="card" style="max-width: 600px; margin: 3rem auto;">
        
        <!-- Výpis chybových hlášek pro registraci -->
        <?php if (!empty($_SESSION['messages']['error'])): ?>
            <div style="background-color: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fca5a5;">
                <?php foreach ($_SESSION['messages']['error'] as $error): ?>
                    <p style="margin: 0; font-weight: 500;">⚠ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['error']); ?>
            </div>
        <?php endif; ?>

        <div class="header" style="justify-content: center; text-align: center; margin-bottom: 2rem;">
            <div style="width: 100%;">
                <h2 style="font-size: 2rem; font-weight: 700;">Nová registrace</h2>
                <p class="subtitle" style="margin-top: 5px;">Vytvořte si účet pro správu vašeho knižního katalogu.</p>
            </div>
        </div>

        <form action="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/storeUser" method="post">
            
            <h3 style="color: var(--primary); font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                Přihlašovací údaje
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="username">Uživatelské jméno <span>*</span></label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group" style="position: relative;">
                    <label for="password">Heslo <span>*</span></label>
                    <input type="password" id="password" name="password" 
                           pattern="^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$"
                           title="Heslo musí mít alespoň 8 znaků, jedno velké písmeno a jeden speciální znak."
                           required>
                    <small style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                        Min. 8 znaků, velké písmeno a speciální znak.
                    </small>
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="password_confirm">Potvrzení hesla <span>*</span></label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
            </div>

            <h3 style="color: var(--primary); font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; margin-bottom: 1.5rem; margin-top: 1rem;">
                Osobní údaje (Volitelné)
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">Křestní jméno</label>
                    <input type="text" id="first_name" name="first_name">
                </div>

                <div class="form-group">
                    <label for="last_name">Příjmení</label>
                    <input type="text" id="last_name" name="last_name">
                </div>
            </div>


            <div class="form-actions" style="justify-content: space-between; align-items: center; border-top: none; padding-top: 1rem; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 1rem;">
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">
                    Už máte účet? <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/login" style="color: var(--primary); font-weight: 600; text-decoration: none;">Přihlaste se zde</a>
                </p>
                <button type="submit" class="btn btn-primary" style="font-size: 1.05rem; padding: 0.75rem 2rem;">
                    Vytvořit účet
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>