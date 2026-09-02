<?php

require_once  __DIR__ . '/../inc/Database.php';

class News
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    
    public function getLastNews($limit = 3)
    {
        $limit = (int)$limit;

        $sql = "
            SELECT 
                n.id,
                n.title,
                n.text,
                n.picture,
                n.user_id,
                n.category_id,
                c.name AS category_name
            FROM news n
            LEFT JOIN category c 
                ON n.category_id = c.id
            ORDER BY n.id DESC
            LIMIT $limit
        ";

        return $this->db->getAll($sql);
    }

    
    public function getAllNews()
    {
        $sql = "
            SELECT 
                n.id,
                n.title,
                n.text,
                n.picture,
                n.user_id,
                n.category_id,
                c.name AS category_name
            FROM news n
            LEFT JOIN category c 
                ON n.category_id = c.id
            ORDER BY n.id DESC
        ";

        return $this->db->getAll($sql);
    }

  
    public function getNews($id)
    {
        $id = (int)$id;

        $sql = "
            SELECT 
                n.id,
                n.title,
                n.text,
                n.picture,
                n.user_id,
                n.category_id,
                c.name AS category_name
            FROM news n
            LEFT JOIN category c 
                ON n.category_id = c.id
            WHERE n.id = $id
        ";

        return $this->db->getOne($sql);
    }

    public function getNewsByCategory($categoryId)
    {
        $categoryId = (int)$categoryId;

        $sql = "
            SELECT 
                n.id,
                n.title,
                n.text,
                n.image,
                n.user,
                n.category_id,
                c.name AS category_name
            FROM news n
            LEFT JOIN categories c 
                ON n.category_id = c.id
            WHERE n.category_id = $categoryId
            ORDER BY n.id DESC
        ";

        return $this->db->getAll($sql);
    }
}