<?php
require 'config.php';
require 'models/Auth.php';
require 'requests/auth/RegisterRequest.php';

$auth = new Auth($pdo, $base);

$data = [
    'name' => $name =  filter_input(INPUT_POST, 'name'),
    'email' => $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
    'password' => $password = filter_input(INPUT_POST, 'password'),
    'birthdate' => $birthdate = filter_input(INPUT_POST, 'birthdate'),
];

$_SESSION['form'] = [
    'name' => $name ?? '',
    'email' => $email ?? '',
    'birthdate' => $birthdate ?? ''
];

#validações
$is_validated = new RegisterRequest($data);
if(!$is_validated->validate()) {
    header("Location: " . $base . "/register.php");
    exit;
}

#cadastro do usuário
if($auth->emailExists($email) === false) {

    $auth->registerUser($data);
    header("Location: ".$base);
    exit;

} else {
    $_SESSION['flash'] = 'E-mail já cadastrado';
    header("Location: ".$base."/signup.php");
    exit;
}

header("Location: " . $base);
exit;