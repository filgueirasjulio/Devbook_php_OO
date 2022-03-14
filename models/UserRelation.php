<?php

class UserRelation {
    public $id;
    public $user_from;
    public $user_to;
}

interface UserRelationDAO {
    public function create(UserRelation $userRelation);
    public function getRelationsFrom($id);
}