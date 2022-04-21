<?php
require_once 'models/PostLike.php';

class PostLikeDaoMysql implements PostLikeDAO {
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function  getLikeCount($post_id) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM post_likes
        WHERE post_id = :post_id");

        $sql->bindValue(':post_id', $post_id);
        $sql->execute();

        $data = $sql->fetch();
        return $data['c'];
    }

    public function  isLiked($post_id, $user_id) {
        $sql = $this->pdo->prepare("SELECT * FROM post_likes 
        WHERE post_id = :post_id AND user_id = :user_id");

        $sql->bindValue(':post_id', $post_id);
        $sql->bindValue(':user_id', $user_id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function  likeToggle($post_id, $user_id) {

    }

}