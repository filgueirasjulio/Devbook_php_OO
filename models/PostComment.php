<?php

class PostComment {
    public $id;
    public $user_id;
    public $post_id;
    public $created_at;
    public $body;
}

interface PostCommentDAO {
    public function  getComments($post_id);
    public function  addComment(PostComment $pc);
    public function  deleteFromPost($post_id);
}