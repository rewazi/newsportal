<?php

require_once __DIR__ . '/../inc/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function register($username, $email, $password)
    {
        $username = trim($username);
        $email = strtolower(trim($email));

        if ($username === '') {
            return 'Введите имя пользователя.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Введите корректный email, например user@example.com.';
        }

        $passwordLength = function_exists('mb_strlen') ? mb_strlen($password) : strlen($password);
        if ($passwordLength < 6) {
            return 'Пароль должен содержать минимум 6 символов.';
        }

        $existing = $this->db->getOnePrepared(
            'SELECT id FROM users WHERE email = :email LIMIT 1',
            [':email' => $email]
        );
        if ($existing) {
            return 'Пользователь с таким email уже зарегистрирован.';
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $created = $this->db->executePrepared(
            'INSERT INTO users (username, email, password, status, registration_date, pass)
             VALUES (:username, :email, :password, :status, CURDATE(), :pass)',
            [
                ':username' => $username,
                ':email' => $email,
                ':password' => $passwordHash,
                ':status' => 'user',
                ':pass' => $passwordHash,
            ]
        );

        return $created ? '' : 'Не удалось создать пользователя.';
    }

    public function authenticate($email, $password)
    {
        $user = $this->db->getOnePrepared(
            'SELECT id, username, email, password, status FROM users WHERE email = :email LIMIT 1',
            [':email' => strtolower(trim($email))]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }
}
