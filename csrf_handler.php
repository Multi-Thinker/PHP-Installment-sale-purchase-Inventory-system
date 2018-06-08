<?php 
if($CSRFrequired==TRUE){
	if(!isset($_REQUEST['csrf_token'])){
		header("location: index.php?wrong_token");
		die("Wrong token");
	}else{
		if(isset($_SESSION['csrf_token'])){
			if(isset($_REQUEST['csrf_token'])){
				if($_REQUEST['csrf_token']!=$_SESSION['csrf_token']){
					header("location: index.php?wrong_token");
					die("Wrong token");
				}
				$_SESSION['provided_csrf_token'] = $_REQUEST['csrf_token'];
			}
		}

	}
}
$_SESSION['old_csrf_token'] = $_SESSION['csrf_token'];
$_SESSION['csrf_token'] = md5(microtime()."ZBTRADERS");
$csrf_token = $_SESSION['csrf_token'];
?>