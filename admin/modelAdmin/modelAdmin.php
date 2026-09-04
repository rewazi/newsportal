<?php

class modelAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function authenticate($email, $password)
    {
        $user = $this->db->getOnePrepared(
            'SELECT id, username, email, password, status FROM users WHERE email = :email LIMIT 1',
            [':email' => strtolower(trim($email))]
        );

        if (!$user || $user['status'] !== 'admin' || !password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }

    public function getNews()
    {
        return $this->db->getAll(
            'SELECT n.id, n.title, n.category_id, c.name AS category_name
             FROM news n LEFT JOIN category c ON c.id = n.category_id
             ORDER BY n.id DESC'
        );
    }

    public function getNewsById($id)
    {
        return $this->db->getOnePrepared(
            'SELECT id, title, text, picture, category_id FROM news WHERE id = :id',
            [':id' => (int)$id]
        );
    }

    public function getCategories()
    {
        return $this->db->getAll('SELECT id, name FROM category ORDER BY name ASC');
    }

    public function saveNews($data, $picture = null)
    {
        $id = (int)($data['id'] ?? 0);
        $params = [
            ':title' => trim($data['title'] ?? ''),
            ':text' => trim($data['text'] ?? ''),
            ':category_id' => (int)($data['category_id'] ?? 0),
            ':user_id' => (int)($_SESSION['userId'] ?? 0),
        ];

        if ($id > 0) {
            $sql = 'UPDATE news SET title = :title, text = :text, category_id = :category_id';
            if ($picture !== null) {
                $sql .= ', picture = :picture';
                $params[':picture'] = $picture;
            }
            $sql .= ' WHERE id = :id';
            $params[':id'] = $id;
        } else {
            $sql = 'INSERT INTO news (title, text, picture, category_id, user_id)
                    VALUES (:title, :text, :picture, :category_id, :user_id)';
            $params[':picture'] = $picture ?? '';
        }

        if ($params[':title'] === '' || $params[':text'] === '' || $params[':category_id'] <= 0) {
            return false;
        }

        return $this->db->executePrepared($sql, $params);
    }

    public function deleteNews($id)
    {
        return $this->db->executePrepared(
            'DELETE FROM news WHERE id = :id',
            [':id' => (int)$id]
        );
    }

    public function updateAccount($username, $password)
    {
        $params = [':username' => trim($username), ':id' => (int)$_SESSION['userId']];
        $sql = 'UPDATE users SET username = :username';
        if (trim($password) !== '') {
            $sql .= ', password = :password';
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id = :id';
        return $this->db->executePrepared($sql, $params);
    }
}
