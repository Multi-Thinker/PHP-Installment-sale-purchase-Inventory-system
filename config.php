<?php 
session_start();
error_reporting(E_WARNING||E_ERROR||E_NOTICE);
include "csrf_handler.php";
include "functions.php";
if(!isset($inner) || $inner!==TRUE){
	if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
		redirect("login.php");
	}
}
$con = addDB();
?>