<?php
require 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login</title>
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
        <form method="POST" action="<?=$base;?>/login_action.php">
            <?php
                include 'partials/components/message.php';
            ?>

            <input placeholder="Digite seu e-mail" class="input"  type="email" name="email"  value="<?php echo isset($_SESSION['form']) ? $_SESSION['form']['email'] : '' ?>"/>

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input class="button" type="submit" value="Acessar o sistema" />

            <a href="<?=$base;?>/register.php">Ainda não tem conta? Cadastre-se</a>
        </form>
    </section>
    <?php 
        unset($_SESSION['form']); 
        unset($_SESSION['flash']);         
    ?>
</body>
</html>