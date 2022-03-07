<?php
require_once 'models/Post.php';

class PostDaoMysql implements PostDAO {
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;    
    }

     public function create(Post $post) {
        $sql = $this->pdo->prepare('INSERT INTO posts (
            user_id, type, created_at, body
        ) VALUES (
            :user_id, :type, :created_at, :body
        )');

        $sql->bindValue(':user_id', $post->user_id);
        $sql->bindValue(':type', $post->type);
        $sql->bindValue(':created_at', $post->created_at);
        $sql->bindValue(':body', $post->body);
        $sql->execute();
     }
    
}