<?php
require_once __DIR__ . '/../header.php';

// FALLBACK
if (!isset($books)) {
    require_once __DIR__ . '/../../models/Book.php';
    $tempBook = new Book();
    $books = $tempBook->getAll();
    $baseUrl = "../../../public/?url=";
    $is_public = false; 
} else {
    $baseUrl = "?url=";
    $is_public = true;
}
?>


        
        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Název</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Kategorie</th>
                            <th>Podkat.</th>
                            <th>Rok</th>
                            <th>Cena</th>
                            <th>Odkaz</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($books)): ?>
                            <?php foreach($books as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
                                <td><?= htmlspecialchars($b['author'] ?? '') ?></td>
                                <td><?= htmlspecialchars($b['isbn'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($b['category'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($b['subcategory'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($b['year'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($b['price'] ?? '-') ?></td>
                                <td>
                                    <?php if(!empty($b['link'])): ?>
                                        <a href="<?= htmlspecialchars($b['link']) ?>" target="_blank">Odkaz</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= $baseUrl ?>book/show/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-primary" style="background:#0dcaf0; color:black; border:none;">Detail</a>
                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == ($b['created_by'] ?? null)): ?>
                                        <a href="<?= $baseUrl ?>book/edit/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-warning">Upravit</a>
                                        <a href="<?= $baseUrl ?>book/delete/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Opravdu smazat?');">Smazat</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" style="text-align:center; padding: 3rem; color: var(--text-muted);">
                                    V databázi zatím nejsou žádné knihy. Začněte přidáním první knihy!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
require_once __DIR__ . '/../footer.php';
?>
