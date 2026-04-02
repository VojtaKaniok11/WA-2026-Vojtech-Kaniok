<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Úprava knihy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="header">
                <div>
                    <h2>Upravit knihu</h2>
                    <p class="subtitle">Právě upravujete záznam v databázi.</p>
                </div>
            </div>

            <form action="?url=book/update/<?= htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Název knihy <span>*</span></label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Autor <span>*</span></label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="year">Rok vydání <span>*</span></label>
                        <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategorie</label>
                        <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="subcategory">Podkategorie</label>
                        <input type="text" id="subcategory" name="subcategory" value="<?= htmlspecialchars($book['subcategory'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Cena knihy</label>
                        <input type="number" id="price" name="price" step="0.5" value="<?= htmlspecialchars($book['price'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="link">Odkaz</label>
                        <input type="text" id="link" name="link" value="<?= htmlspecialchars($book['link'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Popis knihy</label>
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
                    </div>    
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-warning" style="color: black;">Uložit změny</button>
                    <a href="?url=book/index" class="btn btn-secondary">Zpět na seznam</a>
                </div>
            </form>
        </div>

    </div>
</body>
</html>
