<?php
header("location: Report.php");
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();

?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Page Title</h1>  
<?php 
if(isset($_REQUEST['wrong_token'])){
	echo "provided token was :".$_SESSION['provided_csrf_token']." where the token should be: ".$_SESSION['old_csrf_token']." current token is: ".$_SESSION['csrf_token'];
}
?>


<?php include "tFooter.php"; ?>