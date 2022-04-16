<?php
require 'config.php';
require 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';
require 'requests/SettingsRequest.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$userDao = new UserDaoMysql($pdo);

$data = [
   'name' => $name = filter_input(INPUT_POST, 'name'),
   'email' => $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
   'birthdate' => $birthdate = filter_input(INPUT_POST, 'birthdate'),
   'city' => $city = filter_input(INPUT_POST, 'city'),
   'work' => $work = filter_input(INPUT_POST, 'work'),
   'password' => $password = filter_input(INPUT_POST, 'password'),
   'password_confirmation' => $password_confirmation = filter_input(INPUT_POST, 'password_confirmation')
];

$_SESSION['form'] = [
    'name' => $name ?? '',
    'email' => $email ?? '',
    'birthdate' => $birthdate ?? '',
    'city' => $city ?? '',
    'work' => $work ?? '',
    'password' => $password ?? '',
    'password_confirmation' => $password_confirmation ?? ''
];

$is_validated = new SettingsRequest($data);
if(!$is_validated->validate()) {
    header("Location: " . $base . "/settings.php");
    exit;
}

$userInfo->name = $name;
$userInfo->city = $city;
$userInfo->work = $work;

$birthdate = explode('/', $data['birthdate']);
$birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
$userInfo->birthdate = $birthdate;

if($userInfo->email != $email) {
    if($userDao->findByEmail($email) === false) {
        $userInfo->email = $email;
   
    } else {
        $_SESSION['flash']['message'] = 'E-mail já existe!';
        header("Location: " . $base . "/settings.php");
        exit;
    }
}

if(!empty($password)) {
    if($password === $password_confirmation) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userInfo->password = $hash;
    } else {
        $_SESSION['flash']['message'] = 'As senhas devem ser iguais!';
        header("Location: " . $base . "/settings.php");
        exit;
    }
}

$userDao->update($userInfo);

$_SESSION['flash']['success'] = 'Informações atualizadas com sucesso';
header("Location: " . $base . "/settings.php");
exit;