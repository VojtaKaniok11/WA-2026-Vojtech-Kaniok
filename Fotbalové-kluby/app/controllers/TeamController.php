<?php
require_once __DIR__ . '/../models/Team.php';

class TeamController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addNoticeMessage('Pro zobrazení vašich oblíbených týmů se prosím přihlaste.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $teamModel = new Team();
        $teams = $teamModel->getAllByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/teams/index.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
        require_once __DIR__ . '/../views/teams/create.php';
    }

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }

        $data = [
            'team_name'    => trim($_POST['team_name']    ?? ''),
            'country'      => trim($_POST['country']      ?? ''),
            'league'       => trim($_POST['league']       ?? ''),
            'founded_year' => trim($_POST['founded_year'] ?? ''),
            'description'  => trim($_POST['description']  ?? ''),
            'image'        => null,
        ];

        if (empty($data['team_name'])) {
            $this->addErrorMessage('Prosím, vyplňte název týmu.');
            header('Location: ' . BASE_URL . '/index.php?url=team/create');
            exit;
        }

        $uploadedImage = $this->handleImageUpload();
        if ($uploadedImage === false) {
            header('Location: ' . BASE_URL . '/index.php?url=team/create');
            exit;
        }
        $data['image'] = $uploadedImage;

        $teamModel = new Team();
        if ($teamModel->create($data, $_SESSION['user_id'])) {
            $this->addSuccessMessage('Tým byl úspěšně přidán do vašeho seznamu!');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
        } else {
            $this->addErrorMessage('Došlo k chybě při zápisu do databáze.');
            header('Location: ' . BASE_URL . '/index.php?url=team/create');
        }
        exit;
    }

    public function delete($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($id) {
            $teamModel = new Team();
            $team = $teamModel->getById($id);

            if (!$team || $team['user_id'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění smazat tento tým.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }

            // Smazání obrázku z disku
            if (!empty($team['image'])) {
                $imagePath = __DIR__ . '/../../public/uploads/teams/' . $team['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            if ($teamModel->delete($id)) {
                $this->addSuccessMessage('Tým byl úspěšně odebrán z vašeho seznamu.');
            } else {
                $this->addErrorMessage('Chyba při mazání týmu z databáze.');
            }
        }
        header('Location: ' . BASE_URL . '/index.php?url=team/index');
        exit;
    }

    public function show($id = null)
    {
        if ($id) {
            $teamModel = new Team();
            $team = $teamModel->getById($id);
            if ($team) {
                require_once __DIR__ . '/../views/teams/show.php';
            } else {
                $this->addErrorMessage('Tým s tímto ID nebyl nalezen.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }
        } else {
            $this->addErrorMessage('Není zadáno ID týmu.');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }
    }

    public function edit($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($id) {
            $teamModel = new Team();
            $team = $teamModel->getById($id);

            if (!$team || $team['user_id'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tento tým.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }
            require_once __DIR__ . '/../views/teams/edit.php';
        }
    }

    public function update($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }

        $teamModel    = new Team();
        $existingTeam = $teamModel->getById($id);

        if (!$existingTeam || $existingTeam['user_id'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento tým.');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }

        $data = [
            'team_name'    => trim($_POST['team_name']    ?? ''),
            'country'      => trim($_POST['country']      ?? ''),
            'league'       => trim($_POST['league']       ?? ''),
            'founded_year' => trim($_POST['founded_year'] ?? ''),
            'description'  => trim($_POST['description']  ?? ''),
        ];

        if (empty($data['team_name'])) {
            $this->addErrorMessage('Prosím, vyplňte název týmu.');
            header('Location: ' . BASE_URL . '/index.php?url=team/edit/' . $id);
            exit;
        }

        // Zpracování nového obrázku
        $uploadedImage = $this->handleImageUpload();
        if ($uploadedImage === false) {
            header('Location: ' . BASE_URL . '/index.php?url=team/edit/' . $id);
            exit;
        }

        // Pokud byl nahrán nový obrázek, smaž starý
        if ($uploadedImage !== null && !empty($existingTeam['image'])) {
            $oldPath = __DIR__ . '/../../public/uploads/teams/' . $existingTeam['image'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        if ($teamModel->update($id, $data, $_SESSION['user_id'], $uploadedImage)) {
            $this->addSuccessMessage('Tým byl úspěšně upraven!');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
        } else {
            $this->addErrorMessage('Nastala chyba při aktualizaci v databázi.');
            header('Location: ' . BASE_URL . '/index.php?url=team/edit/' . $id);
        }
        exit;
    }

    /**
     * Zpracuje nahraný obrázek.
     * Vrátí: název souboru (string) při úspěchu, null pokud nebyl soubor vybrán, false při chybě.
     */
    private function handleImageUpload()
    {
        if (empty($_FILES['team_image']['name'])) {
            return null;
        }

        $file          = $_FILES['team_image'];
        $allowedMimes  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize       = 5 * 1024 * 1024; // 5 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->addErrorMessage('Chyba při nahrávání souboru.');
            return false;
        }

        if ($file['size'] > $maxSize) {
            $this->addErrorMessage('Obrázek je příliš velký. Maximální velikost je 5 MB.');
            return false;
        }

        // Ověření skutečného MIME typu (bezpečnější než $_FILES['type'])
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            $this->addErrorMessage('Povolené formáty jsou: JPG, PNG, GIF, WEBP.');
            return false;
        }

        $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename  = 'team_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $uploadDir = __DIR__ . '/../../public/uploads/teams/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            $this->addErrorMessage('Nepodařilo se uložit obrázek na disk.');
            return false;
        }

        return $filename;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addNoticeMessage($message)  { $_SESSION['messages']['notice'][]  = $message; }
    protected function addErrorMessage($message)   { $_SESSION['messages']['error'][]   = $message; }
}
