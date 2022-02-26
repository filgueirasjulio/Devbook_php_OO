<?php

class LoginRequest {

    private $request;

    public function __construct(array $request)
    {
       $this->request = $request;
    }

    public function validate() {
        
        if (!$this->request['email']) {
            $_SESSION['flash']['message'] = 'Preencha o seu email';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        if (!$this->request['password']) {
            $_SESSION['flash']['message'] = 'Preencha a sua senha';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        return true;
    }
}