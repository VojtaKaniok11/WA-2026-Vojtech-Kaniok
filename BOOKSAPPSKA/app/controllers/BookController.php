<?php
require_once __DIR__ . '/../models/Book.php';

class BookController
{

    /**
     * Výchozí metoda, která zobrazí seznam všech knih
     */
    public function index()
    {
        $book = new Book();
        $books = $book->getAll(); // Získáme všechny knihy z DB

        // Načteme pohled pro zobrazení (seznam knih)
        require_once __DIR__ . '/../views/books/index.php';
    }

    /**
     * Metoda pro zobrazení formuláře na přidání knihy
     */
    public function create()
    {
        // !!! ZMĚNA: Autorizace: Pokud uživatel není přihlášen, nemá tu co dělat
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        // Načteme existující pohled s formulářem
        require_once __DIR__ . '/../views/books/book_created.php';
    }

    /**
     * Metoda pro zpracování odeslaného formuláře
     */
    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        // 1. Kontrola, zda byla data odeslána přes POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'author' => trim($_POST['author'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'subcategory' => trim($_POST['subcategory'] ?? ''),
                'year' => trim($_POST['year'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'link' => trim($_POST['link'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];

            // Nahrání obrázků a přidání do dat pod klíčem 'images'
            $uploadedImages = $this->processImageUploads();
            $data['images'] = json_encode($uploadedImages);


            // 3. Základní validace vstupů podle povinných oken z formuláře
            if (!empty($data['title']) && !empty($data['author']) && !empty($data['year'])) {

                // Vytvoření instance modelu Book
                $book = new Book();

                // 4. Zavolání metody modelu s celým polem a předáním ID uživatele
                if ($book->create($data, $_SESSION['user_id'])) {

                    echo "<div style='color: green; margin: 20px 0; font-weight: bold;'>";
                    echo "Kniha byla úspěšně přidána do databáze (včetně všech dodatečných údajů)!";
                    echo "</div>";
                    echo "<a href='?url=book/index'>Zpět na seznam knih</a>";
                } else {
                    echo "Došlo k chybě při zápisu do databáze.";
                }
            } else {
                echo "Chyba: Prosím, vyplňte všechna povinná pole (název, autor a rok vydání).";
                echo "<br><a href='javascript:history.back()'>Zpět na formulář</a>";
            }
        } else {
            // Reakce na nedovolený způsob přístupu bez formuláře
            echo "Neplatný požadavek. Tuto stránku nelze otevřít napřímo, odesílejte prosím formulář.";
        }
    }

    /**
     * Smazání konkrétní knihy
     */
    public function delete($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($id) {
            $bookModel = new Book();
            $book = $bookModel->getById($id);
            
            if (!$book || ($book['created_by'] ?? null) != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění smazat tuto knihu.');
                header('Location: ' . BASE_URL . '/index.php?url=book/index');
                exit;
            }

            if ($bookModel->delete($id)) {
                // Přesměrování probíhá javascriptem (alternativa za hlavičky)
                echo "<script>alert('Záznam byl úspěšně smazán!'); window.location.href='?url=book/index';</script>";
            } else {
                echo "Chyba při mazání z DB.";
            }
        }
    }

    /**
     * Zobrazení detailu jedné konkrétní knihy
     */
    public function show($id = null)
    {
        if ($id) {
            $bookModel = new Book();
            $book = $bookModel->getById($id); // Model už tuto metodu má
            if ($book) {
                // Načteme pohled detailu knihy
                require_once __DIR__ . '/../views/books/book_show.php';
            } else {
                echo "Kniha s tímto ID nebyla nalezena.";
            }
        } else {
            echo "Není zadáno ID knihy.";
        }
    }

    /**
     * Zobrazení formuláře s už vyplněnými daty pro editaci (Úprava knihy)
     */
    public function edit($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($id) {
            $bookModel = new Book();
            $book = $bookModel->getById($id); // Získáme konkrétní knihu
            
            if (!$book || ($book['created_by'] ?? null) != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tuto knihu.');
                header('Location: ' . BASE_URL . '/index.php?url=book/index');
                exit;
            }

            // Načtení stejného views jako je create, ale vytvoříme na míru edit form
            require_once __DIR__ . '/../views/books/edit.php';
        }
    }

    /**
     * Zpracování úpravy po odeslání edit formuláře
     */
    public function update($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $id) {
            $bookModel = new Book();
            $existingBook = $bookModel->getById($id); // sáhneme do databáze

            if (!$existingBook || ($existingBook['created_by'] ?? null) != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tuto knihu.');
                header('Location: ' . BASE_URL . '/index.php?url=book/index');
                exit;
            }

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'author' => trim($_POST['author'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'subcategory' => trim($_POST['subcategory'] ?? ''),
                'year' => trim($_POST['year'] ?? ''),
                'price' => trim($_POST['price'] ?? ''),
                'link' => trim($_POST['link'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];

            // Zpracování nových obrázků
            $uploadedImages = $this->processImageUploads();

            // ÚKOL 1: Ochrana proti přepsání starých fotek (pokud žádné nové nenahrajeme)
            if (empty($uploadedImages)) {
                // Zkontrolujeme, zda má kniha uložené nějaké obrázky (jako JSON)
                if (!empty($existingBook['images']) && $existingBook['images'] !== '[]') {
                    // Převedeme JSON řetězec zpět na pole a vložíme do proměnné
                    $decoded = json_decode($existingBook['images'], true);
                    if (is_array($decoded)) {
                         $uploadedImages = $decoded;
                    }
                }
            }

            $data['images'] = json_encode($uploadedImages);


            if (!empty($data['title']) && !empty($data['author']) && !empty($data['year'])) {
                $bookModel = new Book();

                if ($bookModel->update($id, $data)) {
                    echo "<div style='color: green; margin: 20px 0; font-weight: bold;'>";
                    echo "Úprava proběhla úspěšně! Záznam byl změněn.";
                    echo "</div>";
                    echo "<a href='?url=book/index'>Zpět na seznam knih</a>";
                } else {
                    echo "Nastala chyba při aktualizaci v databázi.";
                }
            } else {
                echo "Chyba: Prosím, vyplňte všechna povinná pole.";
                echo "<br><a href='javascript:history.back()'>Zpět na úpravu</a>";
            }
        } else {
            echo "Neplatný požadavek.";
        }
    }

    // --- Pomocná metoda pro zpracování nahrávání obrázků ---
    protected function processImageUploads() {
        $uploadedFiles = [];
        
        // Cesta ke složce, kam se budou obrázky fyzicky ukládat (relativně od index.php)
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        // Zkontrolujeme, zda vůbec existuje adresář, pokud ne, vytvoříme ho
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Zkontrolujeme, zda byl odeslán alespoň jeden soubor
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                // Pokud při nahrávání tohoto konkrétního souboru nedošlo k chybě
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    // Zjištění koncovky (např. jpg, png)
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    // Můžeme zde přidat i kontrolu povolených formátů (volitelné)
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; // Přeskočíme nepodporovaný soubor
                    }

                    // 1. Vygenerování unikátního jména pomocí aktuálního času a náhodného řetězce
                    // např: book_64a2b1c_8f2a.jpg
                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    // 2. Fyzický přesun souboru z dočasné paměti do naší složky uploads
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        // 3. Uložení POUZE NÁZVU do pole, které pak pošleme databázi
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    // --- Pomocné metody pro notifikace ---
    protected function addSuccessMessage($message)
    {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message)
    {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message)
    {
        $_SESSION['messages']['error'][] = $message;
    }
}
