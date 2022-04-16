<?php

class SettingsRequest {

    private $request;

    public function __construct(array $request)
    {
       $this->request = $request;
    }

    public function validate()
    {   
        if (!$this->request['name']) {
            $_SESSION['flash']['message'] = 'Preencha o seu nome';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        if (!$this->request['email']) {
            $_SESSION['flash']['message'] = 'Preencha o seu email';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        if (!$this->request['birthdate']) {
            $_SESSION['flash']['message'] = 'Preencha sua data de nascimento';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        $birthdate = explode('/', $this->request['birthdate']);
        if (count($birthdate) != 3) {
            $_SESSION['flash']['message'] = 'Data de nascimento inválida';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }
        
        $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
        if (strtotime($birthdate) === false) {
            $_SESSION['flash']['message'] = 'Data de nascimento inválida';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }

        if (!$this->request['city']) {
            $_SESSION['flash']['message'] = 'Preencha a sua cidade';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }

        if (!$this->request['work']) {
            $_SESSION['flash']['message'] = 'Preencha o seu trabalho';
            $_SESSION['flash']['status'] = 'danger';
            return false;
        }


        return true;
    }
}