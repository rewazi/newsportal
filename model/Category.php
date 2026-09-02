<?php

require_once  __DIR__ . '/../inc/Database.php';

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }


    public function getAllCategories()
    {
        $sql = "
            SELECT *
            FROM category
            ORDER BY name ASC
        ";

        return $this->db->getAll($sql);
    }


    public function getCategory($id)
    {
        $id = (int)$id;

        $sql = "
            SELECT *
            FROM category
            WHERE id = $id
        ";

        return $this->db->getOne($sql);
    }
}