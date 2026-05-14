<?php require_once __DIR__ . '/../header.php'; ?>

<div class="card" style="max-width: 680px; margin: 0 auto;">
    <div class="header" style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; margin-bottom: 2rem;">
        <div>
            <h2 style="margin: 0;">Upravit komentář</h2>
        </div>
        <a href="?url=user/show/<?= $comment['profile_id'] ?>" class="btn btn-secondary">← Zpět na profil</a>
    </div>

    <form action="?url=user/updateComment/<?= $comment['id'] ?>" method="POST">
        <div class="form-group">
            <label for="comment_text">Text komentáře</label>
            <textarea name="comment_text" id="comment_text" rows="5" required
                      style="resize: vertical;"><?= htmlspecialchars($comment['comment_text']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <a href="?url=user/show/<?= $comment['profile_id'] ?>" class="btn btn-secondary">Zrušit</a>
            <button type="submit" class="btn btn-primary">Uložit komentář</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
