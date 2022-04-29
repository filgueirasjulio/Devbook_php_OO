<?php

require_once 'models/UserRelation.php';

class UserRelationDaoMysql implements UserRelationDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

   public function create(UserRelation $UserRelation)
   {
        $sql = $this->pdo->prepare("INSERT INTO user_relations
        (user_from, user_to) VALUES
        (:user_from, :user_to)");

        $sql->bindValue(':user_from', $UserRelation->user_from);
        $sql->bindValue(':user_to', $UserRelation->user_to);
        $sql->execute();
   }

   public function delete(UserRelation $UserRelation)
   {
        $sql = $this->pdo->prepare("DELETE FROM user_relations
        WHERE user_from = :user_from AND user_to = :user_to");

        $sql->bindValue(':user_from', $UserRelation->user_from);
        $sql->bindValue(':user_to', $UserRelation->user_to);
        $sql->execute();
   }

   public function getFollowing($id) {
    $users = [];
    $sql = $this->pdo->prepare("SELECT user_to FROM user_relations
    WHERE user_from = :user_from");

    $sql->bindValue(':user_from', $id);
    $sql->execute();

    if($sql->rowCount() > 0) {
        $data = $sql->fetchAll();
        foreach($data as $item) {
            $users[] = $item['user_to'];
        }
    }

    return $users;
}

   public function getFollowers($id)
   {
    $users = [];
    $sql = $this->pdo->prepare("SELECT user_from FROM user_relations
    WHERE user_to = :user_to");

    $sql->bindValue(':user_to', $id);
    $sql->execute();

    if($sql->rowCount() > 0) {
        $data = $sql->fetchAll();
        foreach($data as $item) {
            $users[] = $item['user_from'];
        }
    }

    return $users;
   }

   public function isFollowing($me, $id) {
    $sql = $this->pdo->prepare("SELECT * FROM user_relations WHERE 
    user_from = :user_from AND user_to = :user_to");

    $sql->bindValue(':user_from', $me);
    $sql->bindValue(':user_to', $id);
    $sql->execute();

    if($sql->rowCount() > 0) {    
      return true;
    } else {
        return false;
    }
   }
}