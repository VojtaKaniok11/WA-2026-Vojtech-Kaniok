<?php require_once __DIR__ . '/../header.php'; ?>

<div class="card">
    <div class="header">
        <div>
            <h2>Seznam uživatelů</h2>
            <p class="subtitle">Prohlédněte si komunitu a jejich oblíbené fotbalové kluby.</p>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Uživatel</th>
                    <th>Role</th>
                    <th>Členem od</th>
                    <th style="text-align: right;">Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; background: var(--navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; border: 1px solid var(--primary);">
                                        👤
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-main);">
                                            <?= htmlspecialchars(trim($u['first_name'] . ' ' . $u['last_name'])) ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            @<?= htmlspecialchars($u['username']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span style="background: var(--primary); color: white; font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 700; letter-spacing: 0.05em;">ADMIN</span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Uživatel</span>
                                <?php endif; ?>
                            </td>

                            <td style="color: var(--text-muted); font-size: 0.85rem;">
                                <?= date('d.m.Y', strtotime($u['created_at'])) ?>
                            </td>

                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; flex-wrap: wrap;">
                                    <a href="?url=user/show/<?= $u['id'] ?>" class="btn btn-sm btn-primary">
                                        Zobrazit profil
                                    </a>

                                    <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$u['id']): ?>
                                        <a href="?url=user/edit/<?= $u['id'] ?>" class="btn btn-sm btn-secondary">
                                            Upravit
                                        </a>
                                    <?php endif; ?>

                                    <?php if (($_SESSION['user_role'] ?? '') === 'admin' && (int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                                        <a href="?url=user/delete/<?= $u['id'] ?>"
                                           class="btn btn-sm"
                                           style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);"
                                           onclick="return confirm('Opravdu chcete smazat uživatele <?= htmlspecialchars(addslashes($u['username'])) ?>? Tato akce je nevratná.')">
                                            Smazat
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <span class="emoji">🔍</span>
                            <h3>Žádní uživatelé</h3>
                            <p>Zatím se nikdo jiný nezaregistroval.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>
