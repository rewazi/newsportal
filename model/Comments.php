<?php

require_once __DIR__ . '/../inc/Database.php';

class Comments
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function add($newsId, $text, $userId)
    {
        $newsId = (int)$newsId;
        $userId = (int)$userId;
        $text = trim($text);

        if ($newsId <= 0 || $userId <= 0 || $text === '') {
            return false;
        }

        return $this->db->executePrepared(
            'INSERT INTO comments (user_id, news_id, text) VALUES (:user_id, :news_id, :text)',
            [
                ':user_id' => $userId,
                ':news_id' => $newsId,
                ':text' => $text,
            ]
        );
    }

    public function getByNewsId($newsId)
    {
        $newsId = (int)$newsId;

        return $this->db->getAll(
            "SELECT id, user_id, news_id, text, date
             FROM comments
             WHERE news_id = $newsId
             ORDER BY date DESC, id DESC"
        );
    }

    public function countByNewsId($newsId)
    {
        $newsId = (int)$newsId;

        $result = $this->db->getOne(
            "SELECT COUNT(*) AS comment_count
             FROM comments
             WHERE news_id = $newsId"
        );

        return (int)$result['comment_count'];
    }
}
