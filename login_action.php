<?php

require 'config.php';
require 'models/Auth.php';
require 'requests/auth/LoginRequest.php';

$auth = new Auth($pdo, $base);

$data = [
    'email' => $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
    'password' => $password = filter_input(INPUT_POST, 'password'),
];

#validações
$_SESSION['form'] = [
    'email' => $email ?? '',
];

$is_validated = new  LoginRequest($data);
if(!$is_validated->validate()) {
    header("Location: " . $base . "/login.php");
    exit;
}

#login
if (!$auth->validateLogin($email, $password)) {
    $_SESSION['flash']['message'] = 'E-mail e/ou senha errados';
    $_SESSION['flash']['status'] = 'danger';
    header("Location: " . $base . "/login.php");
    exit;
}

header("Location: " . $base);
exit;