<?php
require 'config.php';

unset($_SESSION['token']);
header("Location: ".$base);
exit;