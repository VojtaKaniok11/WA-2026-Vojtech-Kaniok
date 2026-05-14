<?php
require_once __DIR__ . '/../models/Team.php';

class TeamController
{

    /**
     * Výchozí metoda – zobrazí seznam oblíbených týmů přihlášeného uživatele
     */
    public function index()
    {
        // Pokud uživatel není přihlášen, přesměrujeme na přihlášení
        if (!isset($_SESSION['user_id'])) {
            $this->addNoticeMessage('Pro zobrazení vašich oblíbených týmů se prosím přihlaste.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $teamModel = new Team();
        $teams = $teamModel->getAllByUser($_SESSION['user_id']); // Filtrujeme pouze týmy přihlášeného uživatele

        // Načteme pohled pro zobrazení (seznam týmů)
        require_once __DIR__ . '/../views/teams/index.php';
    }

    /**
     * Zobrazení formuláře na přidání nového oblíbeného týmu
     */
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once __DIR__ . '/../views/teams/create.php';
    }

    /**
     * Zpracování odeslaného formuláře pro přidání týmu
     */
    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $data = [
                'team_name'    => trim($_POST['team_name'] ?? ''),
                'country'      => trim($_POST['country'] ?? ''),
                'league'       => trim($_POST['league'] ?? ''),
                'founded_year' => trim($_POST['founded_year'] ?? ''),
                'description'  => trim($_POST['description'] ?? '')
            ];

            // Validace – název týmu je povinný
            if (!empty($data['team_name'])) {

                $teamModel = new Team();

                if ($teamModel->create($data, $_SESSION['user_id'])) {
                    $this->addSuccessMessage('Tým byl úspěšně přidán do vašeho seznamu!');
                    header('Location: ' . BASE_URL . '/index.php?url=team/index');
                    exit;
                } else {
                    $this->addErrorMessage('Došlo k chybě při zápisu do databáze.');
                    header('Location: ' . BASE_URL . '/index.php?url=team/create');
                    exit;
                }
            } else {
                $this->addErrorMessage('Prosím, vyplňte název týmu.');
                header('Location: ' . BASE_URL . '/index.php?url=team/create');
                exit;
            }
        } else {
            $this->addErrorMessage('Neplatný požadavek.');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }
    }

    /**
     * Smazání konkrétního týmu
     */
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
            
            // Ověření vlastnictví – uživatel může smazat jen své týmy
            if (!$team || $team['user_id'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění smazat tento tým.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }

            if ($teamModel->delete($id)) {
                $this->addSuccessMessage('Tým byl úspěšně odebrán z vašeho seznamu.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            } else {
                $this->addErrorMessage('Chyba při mazání týmu z databáze.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }
        }
    }

    /**
     * Zobrazení detailu jednoho konkrétního týmu
     */
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

    /**
     * Zobrazení formuláře s vyplněnými daty pro editaci týmu
     */
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

    /**
     * Zpracování úpravy po odeslání edit formuláře
     */
    public function update($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu týmu se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $id) {
            $teamModel = new Team();
            $existingTeam = $teamModel->getById($id);

            if (!$existingTeam || $existingTeam['user_id'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tento tým.');
                header('Location: ' . BASE_URL . '/index.php?url=team/index');
                exit;
            }

            $data = [
                'team_name'    => trim($_POST['team_name'] ?? ''),
                'country'      => trim($_POST['country'] ?? ''),
                'league'       => trim($_POST['league'] ?? ''),
                'founded_year' => trim($_POST['founded_year'] ?? ''),
                'description'  => trim($_POST['description'] ?? '')
            ];

            if (!empty($data['team_name'])) {

                if ($teamModel->update($id, $data)) {
                    $this->addSuccessMessage('Tým byl úspěšně upraven!');
                    header('Location: ' . BASE_URL . '/index.php?url=team/index');
                    exit;
                } else {
                    $this->addErrorMessage('Nastala chyba při aktualizaci v databázi.');
                    header('Location: ' . BASE_URL . '/index.php?url=team/edit/' . $id);
                    exit;
                }
            } else {
                $this->addErrorMessage('Prosím, vyplňte název týmu.');
                header('Location: ' . BASE_URL . '/index.php?url=team/edit/' . $id);
                exit;
            }
        } else {
            $this->addErrorMessage('Neplatný požadavek.');
            header('Location: ' . BASE_URL . '/index.php?url=team/index');
            exit;
        }
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
