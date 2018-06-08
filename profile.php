<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
if(!isset($_GET['UID']) && $_GET['UID']!=''){
	die("You are not allowed to visit this page");
}
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>
<br>
<h2>You are editing: <?=$userInfo['name']?></h2>
<br>
<h3><?=log_message();?></h3>
<form method="post" action="changePassword.php">
	<p>
		<input id="OPassword" class="form-control" name="password" type="password" Placeholder="Old Password" />
	</p>
	<p>
		<input id="NPassword" class="form-control" name="NPassword" type="password" Placeholder="New Password" />
	</p>
	<p>
		<input id="RPassword" class="form-control" name="RPassword" type="password" placeholder="Repeat New Password" />
	</p>	
	<p>
		<input type="hidden" class="form-control" name="csrf_token" value="<?=$csrf_token?>" />
		<input type="submit" class="btn btn-primary" name="changePass" value="Save" />
	</p>
</form> 


<?php include "tFooter.php"; ?>