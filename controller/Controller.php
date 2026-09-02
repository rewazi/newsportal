<?php

require_once __DIR__ . '/../model/News.php';
require_once __DIR__ . '/../model/Category.php';

class Controller
{
    private $news;
    private $category;

    public function __construct()
    {
        $this->news = new News();
        $this->category = new Category();
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

        require_once __DIR__ . '/../view/layout.php';
    }

    
    public function error404()
    {
        require_once __DIR__ . '/../view/layout.php';
    }
}