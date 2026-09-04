<?php

require_once __DIR__ . '/../model/News.php';
require_once __DIR__ . '/../model/Category.php';
require_once __DIR__ . '/../model/Comments.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../view/comments.php';

class Controller
{
    private $news;
    private $category;
    /** @var Comments */
    private $comments;
    /** @var User */
    private $user;

    public function __construct()
    {
        $this->news = new News();
        $this->category = new Category();
        $this->comments = new Comments();
        $this->user = new User();
    }

   
    public function start()
    {
        $news = $this->news->getLastNews(3);

        require_once __DIR__ . '/../view/layout.php';
    }

    
    public function allNews()
    {
        $news = $this->news->getAllNews();

        require_once __DIR__ . '/../view/layout.php';
    }

    
    public function category()
    {
        $categories = $this->category->getAllCategories();

        require_once __DIR__ . '/../view/layout.php';
    }

    public function register()
    {
        $registrationError = '';
        $registrationSuccess = false;
        $registrationUsername = '';
        $registrationEmail = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registrationUsername = $_POST['username'] ?? '';
            $registrationEmail = $_POST['email'] ?? '';
            $registrationError = $this->user->register(
                $registrationUsername,
                $registrationEmail,
                $_POST['password'] ?? ''
            );
            $registrationSuccess = $registrationError === '';
        }

        require_once __DIR__ . '/../view/layout.php';
    }

    public function accountLogin()
    {
        $loginError = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->user->authenticate(
                $_POST['email'] ?? '',
                $_POST['password'] ?? ''
            );

            if ($user) {
                session_regenerate_id(true);
                $_SESSION['accountUserId'] = (int)$user['id'];
                $_SESSION['accountName'] = $user['username'];

                unset($_SESSION['userId'], $_SESSION['name'], $_SESSION['status']);
                if ($user['status'] === 'admin') {
                    $_SESSION['userId'] = (int)$user['id'];
                    $_SESSION['name'] = $user['username'];
                    $_SESSION['status'] = 'admin';
                }

                header('Location: index.php');
                exit;
            }

            $loginError = 'Неверный email или пароль.';
        }

        require_once __DIR__ . '/../view/layout.php';
    }

    public function accountLogout()
    {
        unset($_SESSION['accountUserId'], $_SESSION['accountName']);
        header('Location: index.php');
        exit;
    }

    
    public function catNews($id)
{
    if ($id <= 0) {
        $this->error404();
        return;
    }

    $category = $this->category->getCategory($id);
    $news = $this->news->getNewsByCategory($id);

    if (!$category) {
        $this->error404();
        return;
    }

    require_once __DIR__ . '/../view/layout.php';
}
    
    public function readNews($id)
    {
        if ($id <= 0) {
            $this->error404();
            return;
        }

        $news = $this->news->getNews($id);

        if (!$news) {
            $this->error404();
            return;
        }

        $comments = $this->comments->getByNewsId($id);

        require_once __DIR__ . '/../view/layout.php';
    }

    public function addComment($id)
    {
        if ($id <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['accountUserId'])) {
            $this->error404();
            return;
        }

        $this->comments->add($id, $_POST['comment'] ?? '', $_SESSION['accountUserId']);

        header('Location: index.php?route=readnews&id=' . $id);
        exit;
    }

    
    public function error404()
    {
        require_once __DIR__ . '/../view/layout.php';
    }
}