<?php 

$inner = TRUE;
$functionsINNER = TRUE;
$functions['GLOBAL_ONLY'] = 1;
$CSRFrequired = 1;
include "config.php";
unset($_SESSION['user']);
redirect("login.php");

?>