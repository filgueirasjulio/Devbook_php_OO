<?php

require_once 'models/User.php';

class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    private function generateUser() {
        $user = new User();
        $user->id = $array['id'] ?? null;
        $user->email = $array['email'] ?? null;
        $user->name = $array['name'] ?? null;
        $user->birthdate = $array['birthdate'] ?? null;
        $user->city = $array['city'] ?? null;
        $user->work = $array['work'] ?? null;
        $user->avatar = $array['avatar'] ?? null;
        $user->cover = $array['cover'] ?? null;
        $user->token = $array['token'] ?? null;

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
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
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

    public function update(User $user)
    {
        $sql = $this->pdo->prepare("UPDATE users SET
            email = :email,
            password = :password,
            name = :name,
            birthdate = :birthdate,
            city = ->toBeDirectory(),
            work = :work,
            avatar = :avatar,
            cover = :cover,
            token = :token,
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