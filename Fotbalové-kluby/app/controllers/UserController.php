<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Team.php';
require_once __DIR__ . '/../models/Comment.php';

class UserController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro zobrazení seznamu uživatelů se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $userModel = new User();
        $users = $userModel->getAll();
        require_once __DIR__ . '/../views/users/index.php';
    }

    public function show($id = null)
    {
        if (!$id) {
            $this->addErrorMessage('Není zadáno ID uživatele.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->addErrorMessage('Uživatel s tímto ID nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $teamModel = new Team();
        $teams = $teamModel->getAllByUser($id);

        $commentModel = new Comment();
        $comments = $commentModel->getByProfile($id);

        require_once __DIR__ . '/../views/users/show.php';
    }

    public function edit($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu profilu se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
        if ((int)$_SESSION['user_id'] !== (int)$id && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento profil.');
            header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $id);
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->addErrorMessage('Uživatel nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        require_once __DIR__ . '/../views/users/edit.php';
    }

    public function update($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu profilu se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
        if ((int)$_SESSION['user_id'] !== (int)$id && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento profil.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $username  = trim(htmlspecialchars($_POST['username'] ?? ''));
        $email     = trim(htmlspecialchars($_POST['email'] ?? ''));
        $firstName = trim(htmlspecialchars($_POST['first_name'] ?? ''));
        $lastName  = trim(htmlspecialchars($_POST['last_name'] ?? ''));
        $nickname  = trim(htmlspecialchars($_POST['nickname'] ?? ''));
        $newPwd    = $_POST['new_password'] ?? '';
        $confirmPwd = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email)) {
            $this->addErrorMessage('Uživatelské jméno a e-mail jsou povinné.');
            header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
            exit;
        }

        $userModel = new User();
        if (!$userModel->update($id, $username, $email, $firstName ?: null, $lastName ?: null, $nickname ?: null)) {
            $this->addErrorMessage('Tento e-mail je již používán jiným uživatelem.');
            header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
            exit;
        }

        if ((int)$_SESSION['user_id'] === (int)$id) {
            $_SESSION['user_name'] = !empty($nickname) ? $nickname : $username;
        }

        if (!empty($newPwd)) {
            if (strlen($newPwd) < 8) {
                $this->addErrorMessage('Nové heslo musí mít alespoň 8 znaků.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }
            if (!preg_match('/[A-Z]/', $newPwd)) {
                $this->addErrorMessage('Nové heslo musí obsahovat alespoň jedno velké písmeno.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }
            if (!preg_match('/[^a-zA-Z\d]/', $newPwd)) {
                $this->addErrorMessage('Nové heslo musí obsahovat alespoň jeden speciální znak.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }
            if ($newPwd !== $confirmPwd) {
                $this->addErrorMessage('Zadaná hesla se neshodují.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }
            $userModel->updatePassword($id, $newPwd);
        }

        $this->addSuccessMessage('Profil byl úspěšně upraven.');
        header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $id);
        exit;
    }

    public function delete($id = null)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->addErrorMessage('Nemáte oprávnění mazat uživatele.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        if ((int)$id === (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžete smazat vlastní účet.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $userModel = new User();
        if ($userModel->delete((int)$id)) {
            $this->addSuccessMessage('Uživatel byl úspěšně smazán.');
        } else {
            $this->addErrorMessage('Chyba při mazání uživatele.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/index');
        exit;
    }

    public function addComment($profileId = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání komentáře se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$profileId) {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        if ((int)$_SESSION['user_id'] === (int)$profileId) {
            $this->addErrorMessage('Nemůžete hodnotit vlastní profil.');
            header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $profileId);
            exit;
        }

        $commentModel = new Comment();
        $existingComment = $commentModel->getByProfile($profileId);
        $userAlreadyCommented = false;
        foreach ($existingComment as $c) {
            if ((int)$c['author_id'] === (int)$_SESSION['user_id']) {
                $userAlreadyCommented = true;
                break;
            }
        }

        if ($userAlreadyCommented) {
            $this->addErrorMessage('Již jste přidali komentář k tomuto profilu. Můžete si jej pouze upravit.');
            header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $profileId);
            exit;
        }

        $text = trim($_POST['comment_text'] ?? '');
        if (empty($text)) {
            $this->addErrorMessage('Text komentáře nesmí být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $profileId);
            exit;
        }

        if ($commentModel->create($profileId, $_SESSION['user_id'], $text)) {
            $this->addSuccessMessage('Vaše hodnocení bylo úspěšně přidáno!');
        } else {
            $this->addErrorMessage('Došlo k chybě při ukládání komentáře.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $profileId);
        exit;
    }

    public function deleteComment($commentId = null)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->addErrorMessage('Nemáte oprávnění mazat komentáře.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        if (!$commentId) {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $commentModel = new Comment();
        $comment = $commentModel->findById((int)$commentId);

        if (!$comment) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $profileId = $comment['profile_id'];
        if ($commentModel->deleteById((int)$commentId)) {
            $this->addSuccessMessage('Komentář byl odstraněn.');
        } else {
            $this->addErrorMessage('Chyba při mazání komentáře.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $profileId);
        exit;
    }

    public function editComment($commentId = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$commentId) {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $commentModel = new Comment();
        $comment = $commentModel->findById((int)$commentId);

        if (!$comment || (int)$comment['author_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        require_once __DIR__ . '/../views/users/edit_comment.php';
    }

    public function updateComment($commentId = null)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$commentId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $commentModel = new Comment();
        $comment = $commentModel->findById((int)$commentId);

        if (!$comment || (int)$comment['author_id'] !== (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        $text = trim($_POST['comment_text'] ?? '');
        if (empty($text)) {
            $this->addErrorMessage('Text komentáře nesmí být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=user/editComment/' . $commentId);
            exit;
        }

        if ($commentModel->update((int)$commentId, $text)) {
            $this->addSuccessMessage('Komentář byl úspěšně upraven.');
        } else {
            $this->addErrorMessage('Chyba při ukládání komentáře.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $comment['profile_id']);
        exit;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addNoticeMessage($message)  { $_SESSION['messages']['notice'][]  = $message; }
    protected function addErrorMessage($message)   { $_SESSION['messages']['error'][]   = $message; }
}
