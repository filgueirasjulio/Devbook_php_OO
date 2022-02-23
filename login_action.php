<?php

require 'config.php';
require 'models/Auth.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');

if (!$email) {
    $_SESSION['flash'] = 'Preencha o seu email';
    header("Location: " . $base . "/login.php");
    exit;
}

if (!$password) {
    $_SESSION['flash'] = 'Preencha a sua senha';
    header("Location: " . $base . "/login.php");
    exit;
}

$auth = new Auth($pdo, $base);

if (!$auth->validateLogin($email, $password)) {
    $_SESSION['flash'] = 'E-mail e/ou senha errados';
    header("Location: " . $base . "/login.php");
    exit;
}

header("Location: " . $base);
exit;