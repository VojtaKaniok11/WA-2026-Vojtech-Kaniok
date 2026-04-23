<?php require_once __DIR__ . '/../header.php'; ?>

<div class="container">
    <div class="card" style="max-width: 500px; margin: 3rem auto;">
        
        <!-- Výpis případných chyb -->
        <?php if (!empty($_SESSION['messages']['error'])): ?>
            <div style="background-color: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fca5a5;">
                <?php foreach ($_SESSION['messages']['error'] as $error): ?>
                    <p style="margin: 0; font-weight: 500;">⚠ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['messages']['success'])): ?>
            <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #86efac;">
                <?php foreach ($_SESSION['messages']['success'] as $msg): ?>
                    <p style="margin: 0; font-weight: 500;">✓ <?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['success']); ?>
            </div>
        <?php endif; ?>

        <div class="header" style="justify-content: center; text-align: center; margin-bottom: 2rem;">
            <div style="width: 100%;">
                <h2 style="font-size: 2rem; font-weight: 700;">Přihlášení k účtu</h2>
                <p class="subtitle" style="margin-top: 5px;">Zadejte své přihlašovací údaje.</p>
            </div>
        </div>

        <form action="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/authenticate" method="post">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="email">E-mail <span>*</span></label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label for="password">Heslo <span>*</span></label>
                <input type="password" id="password" name="password" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">
                    Nemáte účet? <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/index.php?url=auth/register" style="color: var(--primary); font-weight: 600; text-decoration: none;">Registrace</a>
                </p>
                <button type="submit" class="btn btn-primary" style="font-size: 1.05rem; padding: 0.75rem 2rem;">
                    Přihlásit se
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
