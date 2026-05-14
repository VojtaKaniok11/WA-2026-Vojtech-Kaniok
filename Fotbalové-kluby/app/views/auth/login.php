<?php require_once __DIR__ . '/../header.php'; ?>

<div class="container">
    <div class="card" style="max-width: 500px; margin: 3rem auto;">
        
        <div class="header" style="justify-content: center; text-align: center; margin-bottom: 2rem;">
            <div style="width: 100%;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">⚽</div>
                <h2 style="font-size: 2rem; font-weight: 700;">Přihlášení k účtu</h2>
                <p class="subtitle" style="margin-top: 5px;">Zadejte své přihlašovací údaje pro přístup k vašim týmům.</p>
            </div>
        </div>

        <form action="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/authenticate" method="post">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="email">E-mail <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="vas@email.cz" required>
            </div>

            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label for="password">Heslo <span>*</span></label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">
                    Nemáte účet? <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/register" style="color: var(--accent); font-weight: 600; text-decoration: none;">Registrace</a>
                </p>
                <button type="submit" class="btn btn-primary" style="font-size: 1.05rem; padding: 0.75rem 2rem;">
                    Přihlásit se
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
