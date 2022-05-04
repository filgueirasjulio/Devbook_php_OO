<?php

require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';
require_once 'helpers/UploadHelper.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$array = ['error' => ''];

$postDao = new PostDaoMysql($pdo);
$uploadHelper = new UploadHelper();

if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
  
    $newPhoto = $_FILES['photo'];
    $finalImage = $uploadHelper->execute($newPhoto, 800, 800);
   
    if(!$finalImage) {
        $array['error'] = 'Nenhuma imagem enviada ou arquivo não suportado';
    }

    $photoName = md5(time().rand(0,999).'.'.$newPhoto['type']);
    imagejpeg($finalImage, './media/uploads/'.$photoName, 100);
    
    $newPost = new Post();
    $newPost->user_id = $userInfo->id;
    $newPost->type = 'photo';
    $newPost->created_at = date('Y-m-d H:i:s');
    $newPost->body = $photoName;
 
    $postDao->create($newPost);
}

header("Content-Type: application/json");
echo json_encode($array);
exit;

