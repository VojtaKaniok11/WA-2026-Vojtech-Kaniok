<?php require_once __DIR__ . '/../header.php';

$isAdmin      = ($_SESSION['user_role'] ?? '') === 'admin';
$isOwnProfile = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$user['id'];
?>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="header" style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; border: 2px solid var(--primary); box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                👤
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <h2 style="margin: 0; font-size: 2rem;"><?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])) ?></h2>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span style="background: var(--primary); color: white; font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 700; letter-spacing: 0.05em;">ADMIN</span>
                    <?php endif; ?>
                </div>
                <p class="subtitle" style="margin: 0.2rem 0 0.5rem 0;">
                    <span style="color: var(--primary);">@<?= htmlspecialchars($user['username']) ?></span>
                    <?php if (!empty($user['nickname'])): ?>
                        <span style="color: var(--text-muted); font-size: 0.85rem;"> · <?= htmlspecialchars($user['nickname']) ?></span>
                    <?php endif; ?>
                </p>
                <span class="badge badge-league" style="font-size: 0.7rem; opacity: 0.8;">
                    Členem od <?= date('d. m. Y', strtotime($user['created_at'])) ?>
                </span>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-start;">
            <?php if ($isOwnProfile || $isAdmin): ?>
                <a href="?url=user/edit/<?= $user['id'] ?>" class="btn btn-secondary">Upravit profil</a>
            <?php endif; ?>
            <a href="?url=user/index" class="btn btn-secondary">← Zpět na seznam</a>
        </div>
    </div>

    <!-- Oblíbené kluby -->
    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        ⚽ Oblíbené kluby tohoto uživatele
    </h3>

    <?php if (!empty($teams)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
            <?php foreach ($teams as $t): ?>
                <div class="card" style="background: var(--bg-surface); border-color: rgba(255,255,255,0.05); animation: none;">
                    <?php if (!empty($t['image'])): ?>
                        <div style="margin: -1.5rem -1.5rem 1rem -1.5rem; border-radius: 12px 12px 0 0; overflow: hidden; background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center; padding: 1rem; height: 110px;">
                            <img src="<?= $baseUrl ?>/uploads/teams/<?= htmlspecialchars($t['image']) ?>"
                                 alt="Logo <?= htmlspecialchars($t['team_name']) ?>"
                                 style="max-height: 90px; max-width: 100%; object-fit: contain;">
                        </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <h4 style="margin: 0; color: var(--accent); font-size: 1.2rem;">
                            <?= htmlspecialchars($t['team_name']) ?>
                        </h4>
                        <span class="badge badge-country"><?= htmlspecialchars($t['country']) ?></span>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <div style="margin-bottom: 0.3rem;">🏆 <?= htmlspecialchars($t['league']) ?></div>
                        <div>📅 Založeno: <?= htmlspecialchars($t['founded_year'] ?: 'Nezadáno') ?></div>
                    </div>
                    <p style="font-size: 0.85rem; line-height: 1.5; color: var(--text-main); margin: 0; padding-top: 0.75rem; border-top: 1px solid var(--border); font-style: italic; opacity: 0.8;">
                        <?= !empty($t['description']) ? mb_strimwidth(htmlspecialchars($t['description']), 0, 100, '...') : 'Žádná poznámka.' ?>
                    </p>
                    <div style="margin-top: 1.25rem; text-align: right;">
                        <a href="?url=team/show/<?= $t['id'] ?>" class="btn btn-sm btn-secondary" style="font-size: 0.75rem;">
                            Detail týmu
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="padding: 3rem; background: var(--bg-surface); border-radius: 12px; border: 1px dashed var(--border);">
            <span class="emoji">👟</span>
            <h3>Zatím žádné týmy</h3>
            <p>Tento uživatel si ještě nepřidal žádné oblíbené kluby.</p>
        </div>
    <?php endif; ?>

    <!-- Sekce komentářů -->
    <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--border);">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem;">
            💬 Hodnocení a komentáře
        </h3>

        <?php if (isset($_SESSION['user_id']) && !$isOwnProfile): ?>
            <form action="?url=user/addComment/<?= $user['id'] ?>" method="POST"
                  style="margin-bottom: 3rem; background: var(--bg-surface); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border);">
                <div class="form-group">
                    <label for="comment_text">Napište své hodnocení k profilu uživatele <span style="color: var(--primary);"><?= htmlspecialchars($user['first_name'] ?: $user['username']) ?></span></label>
                    <textarea name="comment_text" id="comment_text" rows="3"
                              placeholder="Jak hodnotíte výběr týmů tohoto uživatele?..." required></textarea>
                </div>
                <div style="text-align: right; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">Odeslat hodnocení</button>
                </div>
            </form>
        <?php elseif ($isOwnProfile && isset($_SESSION['user_id'])): ?>
            <div class="msg-notice" style="margin-bottom: 2rem;">
                <p style="margin: 0;">Vlastní profil nelze hodnotit. Ostatní uživatelé zde mohou zanechat komentář.</p>
            </div>
        <?php else: ?>
            <div class="msg-notice" style="margin-bottom: 2rem;">
                <p style="margin: 0;">Pro přidání vlastního hodnocení se prosím <a href="?url=auth/login" style="color: inherit; text-decoration: underline; font-weight: 700; margin-left: 0;">přihlaste</a>.</p>
            </div>
        <?php endif; ?>

        <!-- Výpis komentářů -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $c): ?>
                    <?php
                        $canDelete = $isAdmin;
                        $canEdit   = isset($_SESSION['user_id']) && (int)$c['author_id'] === (int)$_SESSION['user_id'];
                    ?>
                    <div style="background: rgba(255,255,255,0.02); padding: 1.25rem; border-radius: 12px; border-left: 4px solid var(--primary); position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; gap: 1rem; flex-wrap: wrap;">
                            <div>
                                <span style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">
                                    <?= htmlspecialchars(trim($c['first_name'] . ' ' . $c['last_name'])) ?>
                                </span>
                                <span style="font-weight: 400; color: var(--text-muted); font-size: 0.8rem; margin-left: 0.5rem;">
                                    (@<?= htmlspecialchars($c['author_name']) ?>)
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                <span style="font-size: 0.75rem; color: var(--text-muted);">
                                    <?= date('d. m. Y H:i', strtotime($c['created_at'])) ?>
                                    <?php if (!empty($c['updated_at'])): ?>
                                        <em style="opacity: 0.6;"> · upraveno</em>
                                    <?php endif; ?>
                                </span>
                                <?php if ($canEdit): ?>
                                    <a href="?url=user/editComment/<?= $c['id'] ?>"
                                       style="font-size: 0.75rem; color: var(--text-muted); text-decoration: none; padding: 0.2rem 0.5rem; border: 1px solid var(--border); border-radius: 6px; transition: all 0.2s;"
                                       onmouseover="this.style.color='white'; this.style.borderColor='var(--primary)'"
                                       onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--border)'">
                                        Upravit
                                    </a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                    <a href="?url=user/deleteComment/<?= $c['id'] ?>"
                                       style="font-size: 0.75rem; color: #f87171; text-decoration: none; padding: 0.2rem 0.5rem; border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; transition: all 0.2s;"
                                       onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                                       onmouseout="this.style.background='transparent'"
                                       onclick="return confirm('Opravdu smazat tento komentář?')">
                                        Smazat
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="color: var(--text-main); line-height: 1.6; font-size: 0.95rem;">
                            <?= nl2br(htmlspecialchars($c['comment_text'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-style: italic;">
                    Zatím žádná hodnocení. Buďte první!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
