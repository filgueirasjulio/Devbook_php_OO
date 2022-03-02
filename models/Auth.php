<?php

require_once 'dao/UserDaoMysql.php';

class Auth {

    private $pdo;
    private $base;
    private $dao;

    public function __construct(PDO $pdo, $base)
    {
       $this->pdo = $pdo;
       $this->base = $base;
       $this->dao = new UserDaoMysql($this->pdo); 
    }

    public function checkToken() {
        if(!empty($_SESSION['token'])) {
            $token = $_SESSION["token"];
            $user = $this->dao->findByToken($token);
    
            if ($user) {
                return $user;
            }
        }

        header("Location:".$this->base."/login.php");
        exit;
    }

    public function validateLogin($email, $password) {
       $user = $this->dao->findByEmail($email);

       if($user) {
           if (password_verify($password, $user->password)) {
               $token = md5(time().rand(0,9999));
               $_SESSION['token'] = $token;
               $user->token = $token;
               $this->dao->update($user);
               return true;
           }
       }
    }

    public function emailExists($email)
    {
        return $this->dao->findByEmail($email) ? true : false;
    }

    public function registerUser($data) {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $token = md5(time().rand(0, 9999));
        $birthdate = explode('/', $data['birthdate']);
        $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

        $newUser = new User();
        $newUser->name = $data['name'];
        $newUser->email = $data['email'];
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token;

        $this->dao->create($newUser);

        $_SESSION['token'] = $token;
    }
}