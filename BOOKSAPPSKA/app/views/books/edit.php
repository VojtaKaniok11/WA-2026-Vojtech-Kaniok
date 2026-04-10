<?php require_once __DIR__ . '/../header.php'; ?>

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
                    <div class="form-group full-width">
                        <label>Obrázky</label>
                        <?php
                            if (!empty($book['images']) && $book['images'] !== '[]') {
                                $existingImages = json_decode($book['images'], true);
                                if (is_array($existingImages) && count($existingImages) > 0) {
                                    echo '<div style="margin-bottom:10px; padding:10px; background:#f1f5f9; border-radius:6px;">';
                                    echo '<strong>Aktuálně uložené obrázky:</strong><ul style="margin-left: 20px; list-style-type: disc;">';
                                    foreach ($existingImages as $img) {
                                        echo '<li>' . htmlspecialchars($img) . '</li>';
                                    }
                                    echo '</ul>';
                                    echo '<small style="color:var(--warning); font-weight:bold;">Upozornění: Pokud nyní nahrajete nové soubory, tyto staré budou přepsány.</small>';
                                    echo '</div>';
                                }
                            }
                        ?>
                        <label class="file-upload">
                            <span class="file-upload-text">Klikni pro výběr souborů pro přepsání starých</span>
                            <span class="file-upload-subtext">JPG / PNG / WebP – můžete vybrat více souborů najednou</span>
                            <input type="file" id="images" name="images[]" multiple accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-warning" style="color: black;">Uložit změny</button>
                    <a href="?url=book/index" class="btn btn-secondary">Zpět na seznam</a>
                </div>
            </form>
        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>
