<?php require_once __DIR__ . '/../header.php'; ?>

<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="header" style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; margin-bottom: 2rem;">
        <div>
            <h2 style="margin: 0;">Upravit profil</h2>
            <p class="subtitle" style="margin: 0.3rem 0 0;">
                @<?= htmlspecialchars($user['username']) ?>
            </p>
        </div>
        <a href="?url=user/show/<?= $user['id'] ?>" class="btn btn-secondary">← Zpět na profil</a>
    </div>

    <form action="?url=user/update/<?= $user['id'] ?>" method="POST">

        <h3 style="margin: 0 0 1.25rem; font-size: 1rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em;">Základní údaje</h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="first_name">Jméno</label>
                <input type="text" id="first_name" name="first_name"
                       value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="last_name">Příjmení</label>
                <input type="text" id="last_name" name="last_name"
                       value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="username">Uživatelské jméno <span style="color: var(--primary);">*</span></label>
                <input type="text" id="username" name="username" required
                       value="<?= htmlspecialchars($user['username']) ?>">
            </div>
            <div class="form-group">
                <label for="nickname">Přezdívka</label>
                <input type="text" id="nickname" name="nickname"
                       value="<?= htmlspecialchars($user['nickname'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="email">E-mail <span style="color: var(--primary);">*</span></label>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div style="margin: 2rem 0 1.25rem; padding-top: 2rem; border-top: 1px solid var(--border);">
            <h3 style="margin: 0 0 0.25rem; font-size: 1rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em;">Změna hesla</h3>
            <p style="margin: 0 0 1.25rem; font-size: 0.8rem; color: var(--text-muted);">Vyplňte pouze pokud chcete změnit heslo.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="new_password">Nové heslo</label>
                <input type="password" id="new_password" name="new_password"
                       placeholder="min. 8 znaků, 1 velké, 1 spec.">
            </div>
            <div class="form-group">
                <label for="confirm_password">Potvrdit nové heslo</label>
                <input type="password" id="confirm_password" name="confirm_password"
                       placeholder="Zopakujte nové heslo">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
            <a href="?url=user/show/<?= $user['id'] ?>" class="btn btn-secondary">Zrušit</a>
            <button type="submit" class="btn btn-primary">Uložit změny</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
