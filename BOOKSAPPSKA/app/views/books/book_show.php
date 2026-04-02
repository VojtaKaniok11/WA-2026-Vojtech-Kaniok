<?php
// Fallback protection pro přímý vstup (pokud bys chtěl zobrazit surově jako minule)
$is_public = true;
if (!isset($book)) {
    // Pro ukázku detailu napřímo mimo MVC musíme mít ID, to většinou nemáme, tak vypíšeme jen chybové hlášení
    die("Detail nelze otevřít napřímo bez zadaného ID. Vstupte prosím přes odkaz 'Detail' v seznamu knih.");
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail knihy</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-row { display: flex; padding: 1rem 0; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; width: 30%; color: var(--text-muted); }
        .detail-value { width: 70%; color: var(--text-main); font-size: 1.05rem; }
        .detail-desc { margin-top: 10px; padding: 15px; background: #f8fafc; border-radius: 8px; font-style: italic; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            
            <div class="header">
                <div>
                    <h2>Představení knihy</h2>
                    <p class="subtitle">Kompletní náhled všech uložených informací o titulu.</p>
                </div>
                <a href="?url=book/index" class="btn btn-secondary">Zpět na seznam</a>
            </div>

            <div>
                <div class="detail-row">
                    <div class="detail-label">Název knihy</div>
                    <div class="detail-value" style="font-size: 1.3rem; font-weight:700;">
                        <?= htmlspecialchars($book['title'] ?? '') ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Autor práce</div>
                    <div class="detail-value"><?= htmlspecialchars($book['author'] ?? '') ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ident. číslo (ISBN)</div>
                    <div class="detail-value"><?= !empty($book['isbn']) ? htmlspecialchars($book['isbn']) : 'Nezadáno' ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Rok první publikace</div>
                    <div class="detail-value"><?= htmlspecialchars($book['year'] ?? '') ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kategorie</div>
                    <div class="detail-value">
                        <?= !empty($book['category']) ? htmlspecialchars($book['category']) : 'Bez kategorie' ?> 
                        <?= !empty($book['subcategory']) ? " / " . htmlspecialchars($book['subcategory']) : '' ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Prodejní cena</div>
                    <div class="detail-value">
                        <?= !empty($book['price']) ? htmlspecialchars($book['price']) . ' Kč' : 'Cena nestanovena' ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Internetový odkaz</div>
                    <div class="detail-value">
                        <?php if(!empty($book['link'])): ?>
                            <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank" style="color: var(--primary);">Přejít na odkaz</a>
                        <?php else: ?>
                            Žádný odkaz
                        <?php endif; ?>
                    </div>
                </div>
                <div class="detail-row" style="flex-direction: column;">
                    <div class="detail-label" style="width:100%; margin-bottom:10px;">Anotace a popis knihy</div>
                    <div class="detail-value detail-desc" style="width:100%;">
                        <?= nl2br(htmlspecialchars($book['description'] ?? 'Popis není k dispozici.')) ?>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="justify-content: flex-end;">
                <a href="?url=book/edit/<?= htmlspecialchars($book['id']) ?>" class="btn btn-warning" style="color:black;">Začít úpravu knihy</a>
            </div>

        </div>
    </div>
</body>
</html>
