<?php   
session_start(); 
session_destroy();

require_once __DIR__ . '/config.php';

header("location:".BASE_URL."/login.php");
exit();
?>