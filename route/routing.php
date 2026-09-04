<?php

require_once __DIR__. '/../controller/Controller.php';

class Routing
{
    public function run()
    {
        $route = isset($_GET['route']) ? $_GET['route'] : 'start';

        $controller = new Controller();

        switch ($route) {

            case 'start':
                $controller->start();
                break;

            case 'allnews':
                $controller->allNews();
                break;

            case 'category':
                $controller->category();
                break;

            case 'register':
                $controller->register();
                break;

            case 'account-login':
                $controller->accountLogin();
                break;

            case 'account-logout':
                $controller->accountLogout();
                break;

            case 'catnews':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $controller->catNews($id);
                break;

            case 'addcomment':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $controller->addComment($id);
                break;

            case 'readnews':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $controller->readNews($id);
                break;

            default:
                $controller->error404();
                break;
        }
    }
}