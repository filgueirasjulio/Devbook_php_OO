<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMySql.php';
require_once 'dao/PostCommentDaoMysql.php';

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

     public function delete($id, $user_id)
     {
         $sql = $this->pdo->prepare("DELETE FROM posts 
         WHERE id = :id AND user_id = :user_id");

         $sql->bindValue(':id', $id);
         $sql->bindValue(':user_id', $user_id);
         $sql->execute();
     }
    
     public function getHomeFeed($userId)
     {
        $feedList = [];

        $userRelation = new UserRelationDaoMysql($this->pdo);
        $userList = $userRelation->getFollowing($userId);
        $userList[] = $userId;
     
        $sql = $this->pdo->query("SELECT * FROM posts 
        WHERE user_id IN (".implode(',', $userList).")
        ORDER BY created_at DESC");

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $feedList = $this->postListToObject($data, $userId);
        }

        return $feedList;
     }

     public function getUserFeed($userId)
     {
        $feedList = [];
     
        $sql = $this->pdo->prepare("SELECT * FROM posts 
        WHERE user_id = :user_id
        ORDER BY created_at DESC");
        $sql->bindValue(':user_id', $userId);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $feedList = $this->postListToObject($data, $userId);
        }

        return $feedList;
     }

     public function getPhotosFrom($userId)
     {
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts
        WHERE user_id = :user_id AND type = 'photo'
        ORDER BY created_at DESC");

        $sql->bindValue(':user_id', $userId);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $array = $this->postListToObject($data, $userId);
        }

        return $array;
     }

     private function postListToObject($post_list, $user_id) {
        $posts = [];
        $userDao = new UserDaoMysql($this->pdo);
        $postLikeDao = new PostLikeDaoMysql($this->pdo);
        $postCommentDao = new PostCommentDaoMysql($this->pdo);
    
        foreach($post_list as $post_item) {
            $newPost = new Post();
            $newPost->id = $post_item['id'];
            $newPost->user_id = $post_item['user_id'];
            $newPost->type = $post_item['type'];
            $newPost->created_at = $post_item['created_at'];
            $newPost->body = $post_item['body'];
            $newPost->mine = false;

            if($post_item['user_id'] == $user_id) {
                $newPost->mine = true;
            }
  
            //informações sobre o usuário que realizou o post
            $newPost->user = $userDao->findById($post_item['user_id']);

            //informações sobre like
            
            $newPost->likeCount = $postLikeDao->getLikeCount($newPost->id);
            $newPost->liked = $postLikeDao->isLiked($newPost->id, $user_id);

            //informações sobre comments
            //TO DO
            $newPost->comments = $postCommentDao->getComments($newPost->id);

            $posts[] = $newPost;
        }

        return $posts;
     }
}