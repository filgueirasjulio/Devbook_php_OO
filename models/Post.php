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
}