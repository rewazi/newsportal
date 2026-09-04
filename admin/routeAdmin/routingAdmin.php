<?php

class RoutingAdmin
{
    public function run()
    {
        $route = $_GET['route'] ?? 'login';
        $controller = new ControllerAdmin();

        switch ($route) {
            case 'login':
                $controller->login();
                break;
            case 'logout':
                $controller->logout();
                break;
            case 'dashboard':
                $controller->dashboard();
                break;
            case 'news-form':
                $controller->newsForm((int)($_GET['id'] ?? 0));
                break;
            case 'news-save':
                $controller->saveNews();
                break;
            case 'news-delete':
                $controller->deleteNews((int)($_GET['id'] ?? 0));
                break;
            case 'account':
                $controller->account();
                break;
            default:
                $controller->error404();
        }
    }
}
