<?php

class ControllerAdmin
{
    private $model;

    public function __construct()
    {
        $this->model = new modelAdmin();
    }

    private function isAdmin()
    {
        return isset($_SESSION['userId'], $_SESSION['status']) && $_SESSION['status'] === 'admin';
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            header('Location: index.php?route=login');
            exit;
        }
    }

    public function login()
    {
        if ($this->isAdmin()) {
            header('Location: index.php?route=dashboard');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model->authenticate($_POST['email'] ?? '', $_POST['password'] ?? '');
            if ($user) {
                session_regenerate_id(true);
                $_SESSION['userId'] = (int)$user['id'];
                $_SESSION['name'] = $user['username'];
                $_SESSION['status'] = $user['status'];
                header('Location: index.php?route=dashboard');
                exit;
            }
            $error = 'Неверный email или пароль.';
        }

        require __DIR__ . '/../viewAdmin/formLogin.php';
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?route=login');
        exit;
    }

    public function dashboard()
    {
        $this->requireAdmin();
        $news = $this->model->getNews();
        require __DIR__ . '/../viewAdmin/templates/layout.php';
    }

    public function newsForm($id)
    {
        $this->requireAdmin();
        $item = $id > 0 ? $this->model->getNewsById($id) : null;
        $categories = $this->model->getCategories();
        require __DIR__ . '/../viewAdmin/newsForm.php';
    }

    public function saveNews()
    {
        $this->requireAdmin();
        $picture = null;
        if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $picture = file_get_contents($_FILES['picture']['tmp_name']);
        }
        $this->model->saveNews($_POST, $picture);
        header('Location: index.php?route=dashboard');
        exit;
    }

    public function deleteNews($id)
    {
        $this->requireAdmin();
        $this->model->deleteNews($id);
        header('Location: index.php?route=dashboard');
        exit;
    }

    public function account()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateAccount($_POST['username'] ?? '', $_POST['password'] ?? '');
            $_SESSION['name'] = trim($_POST['username'] ?? '');
        }
        require __DIR__ . '/../viewAdmin/account.php';
    }

    public function error404()
    {
        http_response_code(404);
        echo '404 - Страница не найдена';
    }
}
