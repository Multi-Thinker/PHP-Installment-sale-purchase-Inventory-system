<?php 

$inner = TRUE;
$functionsINNER = TRUE;
$functions['GLOBAL_ONLY'] = 1;
$functions['USER_LEVEL_ONLY'] = 1;
$functions['DB_ONLY'] = 1;
$CSRFrequired = 1;
include "config.php";
$userInfo = userInfo();
if(isset($_POST['changePass'])){
	$Opassword = dbIN($_POST['password']);
	$Npassword = dbIN($_POST['NPassword']);
	$Rpassword = dbIN($_POST['RPassword']);
//	print_r($_REQUEST);
//	echo "Password Match ".$NPassword==$RPassword."<br>"."New Pass {$Npassword}<br> Repeated Pass {$Rpassword}<br> having old as {$Opassword}<br>";
	if($Npassword==$Rpassword && $Npassword!='' && $Rpassword!='' && $Opassword!=''){
		$check = mysqli_query($con,"SELECT id FROM users WHERE name='".$userInfo['name']."' AND password='".md5($Opassword)."'");
//		echo "name {$userinfo['name']} and password {$Opassword} count ".mysqli_num_rows($check);
		if(mysqli_num_rows($check)==1){
			$update = mysqli_query($con,"UPDATE users SET password='".md5($Rpassword)."' WHERE id='".$userInfo['id']."'");
				$_SESSION['log_message'] = "Password Updated.";
				redirect("profile.php?UID=".$userInfo['id']);
		}else{
			$_SESSION['log_message'] = "Old Password Doesn't match";
			redirect("profile.php?UID=".$userInfo['id']);
		}
	}else{
		$_SESSION['log_message'] = "ERROR";
		redirect("profile.php?UID=".$userInfo['id']);
	}
}
redirect("profile.php?UID=".$userInfo['id']);
