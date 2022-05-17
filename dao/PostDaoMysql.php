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
        $postLikeDao = new PostLikeDaoMysql($this->pdo);
        $postCommentDao = new PostCommentDaoMysql($this->pdo);

        $sql = $this->pdo->prepare("SELECT * FROM posts 
        WHERE id = :id AND user_id = :user_id");
        $sql->bindValue(':id', $id);
        $sql->bindValue(':user_id', $user_id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $post = $sql->fetch(PDO::FETCH_ASSOC);

            $postLikeDao->deleteFromPost($id);
            $postCommentDao->deleteFromPost($id);

            if($post['type'] == 'photo') {
                $img = 'media/uploads/'.$post['body'];
                if(file_exists($img)) {
                    unlink($img);
                }
            }

            $sql = $this->pdo->prepare("DELETE FROM posts 
            WHERE id = :id AND user_id = :user_id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':user_id', $user_id);
            $sql->execute();
        }
     }
    
     public function getHomeFeed($userId)
     {
        $array = [];
        $perPage = 5;
        $page = intval(filter_input(INPUT_GET, 'p'));
      
        if($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        $userRelation = new UserRelationDaoMysql($this->pdo);
        $userList = $userRelation->getFollowing($userId);
        $userList[] = $userId;
     
        $sql = $this->pdo->query("SELECT * FROM posts 
        WHERE user_id IN (".implode(',', $userList).")
        ORDER BY created_at DESC, id DESC LIMIT $offset, $perPage");

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $array['feed'] = $this->postListToObject($data, $userId);
        }

        $sql = $this->pdo->query("SELECT COUNT(*) as c FROM posts
        WHERE user_id IN (".implode(',', $userList).")");

        $totalData = $sql->fetch();
        $total = $totalData['c'];

        $array['pages'] = ceil($total / $perPage);
        $array['currentPage'] = $page;

        return $array;
     }

     public function getUserFeed($userId)
     {
        $array = ['feed' => array()];
        $perPage = 5;
        $page = intval(filter_input(INPUT_GET, 'p'));

        if($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;
     
        $sql = $this->pdo->prepare("SELECT * FROM posts 
        WHERE user_id = :user_id
        ORDER BY created_at DESC LIMIT $offset, $perPage");
        $sql->bindValue(':user_id', $userId);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $array['feed'] = $this->postListToObject($data, $userId);
        }

        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM posts 
        WHERE user_id = :user_id");
        $sql->bindValue(':user_id', $userId);
        $sql->execute();

        $totalData = $sql->fetch();
        $total = $totalData['c'];

        $array['pages'] = ceil($total / $perPage);
        $array['currentPage'] = $page;

        return $array;
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