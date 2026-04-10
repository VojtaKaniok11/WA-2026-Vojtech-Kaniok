<?php require_once __DIR__ . '/../header.php'; ?>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="header">
                <div>
                    <h2>Přidat novou knihu</h2>
                    <p class="subtitle">Vyplňte údaje a uložte knihu do databáze.</p>
                </div>
            </div>

            <form action="?url=book/store" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Název knihy <span>*</span></label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Autor <span>*</span></label>
                        <input type="text" id="author" name="author" placeholder="Příjmení Jméno" required>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn">
                    </div>
                    <div class="form-group">
                        <label for="year">Rok vydání <span>*</span></label>
                        <input type="number" id="year" name="year" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategorie</label>
                        <input type="text" id="category" name="category">
                    </div>
                    <div class="form-group">
                        <label for="subcategory">Podkategorie</label>
                        <input type="text" id="subcategory" name="subcategory">
                    </div>
                    <div class="form-group">
                        <label for="price">Cena knihy</label>
                        <input type="number" id="price" name="price" step="0.5">
                    </div>
                    <div class="form-group">
                        <label for="link">Odkaz</label>
                        <input type="text" id="link" name="link">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description">Popis knihy</label>
                        <textarea id="description" name="description" rows="5"></textarea>
                    </div>    
                    
                    <div class="form-group full-width">
                        <label>Obrázky</label>
                        <label class="file-upload">
                            <span class="file-upload-text">Klikni pro výběr souborů</span>
                            <span class="file-upload-subtext">JPG / PNG / WebP – můžete vybrat více souborů najednou</span>
                            <input type="file" id="images" name="images[]" multiple accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Uložit knihu do DB</button>
                    <a href="?url=book/index" class="btn btn-secondary">Storno</a>
                </div>
            </form>
        </div>

<?php require_once __DIR__ . '/../footer.php'; ?>