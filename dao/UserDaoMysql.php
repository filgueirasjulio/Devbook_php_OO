<?php

require_once 'models/User.php';
require_oNce 'dao/UserRelationDaoMysql.php';

class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    private function generateUser($data, $full = false) {
        $user = new User();

        $user->id = $data['id'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->password = $data['password'] ?? null;
        $user->name = $data['name'] ?? null;
        $user->birthdate = $data['birthdate'] ?? null;
        $user->city = $data['city'] ?? null;
        $user->work = $data['work'] ?? null;
        $user->avatar = $data['avatar'] ?? null;
        $user->cover = $data['cover'] ?? null;
        $user->token = $data['token'] ?? null;

        if($full) {
            //se tem de obter informações completas: seguidos, seguidos pelo usuário e fotos.
            $userDaoMysql = new UserRelationDaoMysql($this->pdo);
            $postDaoMysql = new PostDaoMysql($this->pdo);

            //transformamos um array de números em um de objetos
            $user->followers = $userDaoMysql->getFollowers($user->id);
            foreach($user->followers as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $user->followers[$key] = $newUser;
            }

            $user->following = $userDaoMysql->getFollowing($user->id);
            foreach($user->following as $key => $following_id) {
                $newUser = $this->findById($following_id);
                $user->followings[$key] = $newUser;
            }

            $user->photos = $postDaoMysql->getPhotosFrom($user->id);
        }
 
        return $user;
    }

    public function findByToken($token) {
        if(!empty($token)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindValue(':token', $token);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        }

        return false;
    }

    public function findByEmail($email) {
        if(!empty($email)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        }

        return false;
    }

    public function findById($id, $full = false) {
        if(!empty($id)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data, $full);
                return $user;
            }
        }

        return false;
    }

    public function create(User $user) {
        $sql = $this->pdo->prepare("INSERT INTO users (
            email, password, name, birthdate, token
        ) VALUES (
            :email, :password, :name, :birthdate, :token
        )");

        $sql->bindValue(':email', $user->email);
        $sql->bindValue(':password', $user->password);
        $sql->bindValue(':name', $user->name);
        $sql->bindValue(':birthdate', $user->birthdate);
        $sql->bindValue(':token', $user->token);
        $sql->execute();

        return true;
    } 

    public function update(User $user) {
        $sql = $this->pdo->prepare("UPDATE users SET 
            email = :email,
            password = :password,
            name = :name,
            birthdate = :birthdate,
            city = :city,
            work = :work,
            avatar = :avatar,
            cover = :cover,
            token = :token
            WHERE id = :id");

        $sql->bindValue(':email', $user->email);
        $sql->bindValue(':password', $user->password);
        $sql->bindValue(':name', $user->name);
        $sql->bindValue(':birthdate', $user->birthdate);
        $sql->bindValue(':city', $user->city);
        $sql->bindValue(':work', $user->work);
        $sql->bindValue(':avatar', $user->avatar);
        $sql->bindValue(':cover', $user->cover);
        $sql->bindValue(':token', $user->token);
        $sql->bindValue(':id', $user->id);
        $sql->execute();

        return true;
    }

}