<?php 
$inner = TRUE;
$functionsINNER=TRUE;
$functions['DB_ONLY'] =  1;
$functions['LOGIN_ONLY'] = 1;
$functions['GLOBAL_ONLY'] = 1;

include "config.php";
if(isset($_POST['subLogin'])){
	$user = $_POST['user'];
	if($user==''){
		die("EMPTY USER");
	}
	$pass = $_POST['password'];
	if($pass==""){
		die("EMPTY USER");
	}
}
$query = mysqli_query($con,"SELECT id FROM users WHERE (name='".dbIN($user)."'
		OR email='".dbIN($user)."') AND password='".dbIN(md5($pass))."'") or die(mysqli_error($con));
if(mysqli_num_rows($query)==1){
 	recordSession($user);
 	redirect("index.php");
}else{
	redirect("login.php?wrong_password=true");
}
?>