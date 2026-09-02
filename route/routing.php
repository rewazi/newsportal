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

            case 'catnews':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $controller->catNews($id);
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