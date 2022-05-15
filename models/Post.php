<?php

class Post {
    public $id;
    public $user_id;
    public $type;
    public $created_at;
    public $body;
}

interface PostDAO {
    public function  create(Post $post);
    public function delete($id, $userId);
    public function getHomeFeed($userId); 
    public function getUserFeed($userId);
    public function getPhotosFrom($userId);
}