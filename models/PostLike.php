<?php

class PostLike {
    public $id;
    public $user_id;
    public $post_id;
    public $created_at;
}

interface PostLikeDAO {
    public function  getLikeCount($post_id);
    public function  isLiked($post_id, $user_id);
    public function  likeToggle($post_id, $user_id);
}