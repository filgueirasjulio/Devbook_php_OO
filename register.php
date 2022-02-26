<?php
require 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastro</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <header>
        <div class="container">
            <a href="<?=$base;?>"><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?=$base;?>/register_action.php">
            <?php
                include 'partials/components/message.php';
            ?>
   
            <input placeholder="Digite seu nome completo" class="input" type="text" name="name" value="<?php echo isset($_SESSION['form']) ? $_SESSION['form']['name'] : '' ?>" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email"  value="<?php echo isset($_SESSION['form']) ? $_SESSION['form']['email'] : '' ?>"/>

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input placeholder="Digite sua data de nascimento" class="input" type="text" name="birthdate" id="birthdate"  value="<?php echo isset($_SESSION['form']) ? $_SESSION['form']['birthdate'] : '' ?>"/>

            <input class="button" type="submit" value="Fazer o cadastro" />

            <a href="<?=$base;?>/login.php" id="btn">Já tem conta? Faça o login.</a>
        </form>
    </section>
    <script src="https://unpkg.com/imask"></script>
    <script>
        IMask(
            document.getElementById("birthdate"),
            {mask:'00/00/0000'}
        );
    </script>
    <?php 
        unset($_SESSION['form']); 
        unset($_SESSION['flash']);         
    ?>
</body>
</html>