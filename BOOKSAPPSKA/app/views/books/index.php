<?php
// FALLBACK: Pokud někdo otevře soubor přímo přes app/views/books/index.php (obchází MVC public/index.php)
$is_public = true;
if (!isset($books)) {
    require_once __DIR__ . '/../../models/Book.php';
    $tempBook = new Book();
    $books = $tempBook->getAll();
    $baseUrl = "../../../public/?url=";
    $is_public = false; 
} else {
    $baseUrl = "?url=";
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knihovna</title>
    
    <?php if ($is_public): ?>
        <!-- Premium moderní styl se vykreslí jen když uživatel vstoupí přes MVC public/ bránu -->
        <link rel="stylesheet" href="style.css">
    <?php else: ?>
        <!-- "Surový" původní styl, který se ukáže, když jsi přímo ve složce views/books/ -->
        <style>
            body { font-family: "Times New Roman", serif; padding: 20px; color: black; background: white; }
            h1 { font-size: 2em; margin-bottom: 20px;}
            a { color: purple; text-decoration: underline; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 5px; text-align: left; border: none; }
            th { font-weight: bold; background: transparent; }
        </style>
    <?php endif; ?>
</head>
<body>

    <?php if ($is_public): ?>
    <div class="container">
        <div class="header">
    <?php endif; ?>

            <div>
                <h1>Knihovna</h1>
                <p class="subtitle" style="<?= !$is_public ? 'margin-bottom: 20px;' : '' ?>">Přehled všech uložených knih v databázi.</p>
            </div>
            <a href="<?= $baseUrl ?>book/create" class="<?= $is_public ? 'btn btn-primary' : '' ?>" style="<?= !$is_public ? 'display: block; margin-bottom: 20px;' : '' ?>">Přidat novou knihu</a>

    <?php if ($is_public): ?>
        </div>
        <div class="card">
            <div class="table-container">
    <?php endif; ?>

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
                                    <?php if ($is_public): ?>
                                    <div class="actions">
                                        <a href="<?= $baseUrl ?>book/show/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-primary" style="background:#0dcaf0; color:black; border:none;">Detail</a>
                                        <a href="<?= $baseUrl ?>book/edit/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-warning">Upravit</a>
                                        <a href="<?= $baseUrl ?>book/delete/<?= htmlspecialchars($b['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Opravdu smazat?');">Smazat</a>
                                    </div>
                                    <?php else: ?>
                                        <a href="<?= $baseUrl ?>book/show/<?= htmlspecialchars($b['id']) ?>">Detail</a> | 
                                        <a href="<?= $baseUrl ?>book/edit/<?= htmlspecialchars($b['id']) ?>">Upravit</a> | 
                                        <a href="<?= $baseUrl ?>book/delete/<?= htmlspecialchars($b['id']) ?>" onclick="return confirm('Opravdu smazat?');">Smazat</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" style="<?= !$is_public ? 'text-align:center; padding: 20px;' : 'text-align:center; padding: 3rem; color: var(--text-muted);' ?>">
                                    V databázi zatím nejsou žádné knihy. Začněte přidáním první knihy!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

    <?php if ($is_public): ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
