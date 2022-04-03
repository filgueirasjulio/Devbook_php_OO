<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';

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

     private function postListToObject($post_list, $user_id) {
        $posts = [];
        $userDao = new UserDaoMysql($this->pdo);
    
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
            //TO DO
            $newPost->likeCount = 0;
            $newPost->liked = false;

            //informações sobre comments
            //TO DO
            $newPost->comments = [];

            $posts[] = $newPost;
        }

        return $posts;
     }
}