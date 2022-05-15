<?php
require_once 'models/PostComment.php';
require_once 'dao/UserDaoMysql.php';

class PostCommentDaoMysql implements PostCommentDAO {
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function  getComments($post_id) {
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM post_comments WHERE post_id = :post_id");
        $sql->bindValue(':post_id', $post_id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $userDao = new UserDaoMysql($this->pdo);

            foreach($data as $item) {
                $commentItem = new PostComment();
                $commentItem->id = $item['id'];
                $commentItem->post_id = $item['post_id'];
                $commentItem->user_id = $item['user_id'];
                $commentItem->body = $item['body'];
                $commentItem->created_at = $item['created_at'];
                $commentItem->user = $userDao->findById($item['user_id']);

                $array[] = $commentItem;
            }
        }

        return $array;
    }

    public function  addComment(PostComment $pc) {
        $sql = $this->pdo->prepare("INSERT INTO post_comments
        (post_id, user_id, body, created_at) VALUES
        (:post_id, :user_id, :body, :created_at)");

        $sql->bindValue(':post_id', $pc->post_id);
        $sql->bindValue(':user_id', $pc->user_id);
        $sql->bindValue(':body', $pc->body);
        $sql->bindValue(':created_at', $pc->created_at);
        $sql->execute();
    }

    public function deleteFromPost($post_id)
    { 
        $sql = $this->pdo->prepare("DELETE FROM post_comments 
        WHERE post_id = :post_id");
        $sql->bindValue(':post_id', $post_id);
        $sql->execute();
    }
}