<?php 
/** pre-loaded **/
function printlog($pre=false){
	
	if(isset($_REQUEST)){
		if($pre!=false){
			echo "<pre>";
		}
		print_r($_REQUEST);
		if($pre!=false){
			echo "</pre>";
		}
	}
	
}
function set_message($message,$log=false,$query=""){
	$con = addDB();
	if(mysqli_error($con)==""){
		if($log==false){
			$_SESSION['log_message'] = $message;	
		}else{

			$_SESSION['log_message'] = "message:: ".$message."<br> error::".mysqli_error($con)."<br> info::".mysqli_info($con)."<br> Query::".$query;	

		}
	 }else{
		$_SESSION['log_message'] = mysqli_error($con);
		goBack();
	}
}
function checkRequest($param){
	if(isset($_REQUEST[$param]) && $_REQUEST[$param]!=''){
			return $_REQUEST[$param];
	}else{
		return null;
	}
}
function refreshToken(){
	$_SESSION['csrf_token'] = md5(microtime()."ZBTRADERS");
	return $_SESSION['csrf_token'];
}
function log_message(){
	$message = "";
	if(isset($_SESSION['log_message']) && !empty($_SESSION['log_message'])){
		$message =  $_SESSION['log_message'];
	}
	$_SESSION['log_message'] = "";
	return $message;

}
function addDB(){
	require_once "db.php";
	$DB = new DB();
	$connection = $DB->connection();
	return $connection;
}
function goBack($location=""){
	if($location==""){
		if($_SERVER['HTTP_REFERER']){
			header("location: ".$_SERVER['HTTP_REFERER']);
		}else{
			die("DIRECT LINK NOT ALLOWED");
		}
	}else{
		header("location: ".$location);
	}
}
if(!isset($functionsINNER) || $functionsINNER!==TRUE){
	die("NOTHING HERE TO SEE");	
}

if(isset($functions['TRADITIONAL']) && $functions['TRADITIONAL']==1){
	$con = addDB();
	$connection = $con;
	function redirect($location){
		header("location: $location");
		die();
	}
	function dbIN($str){
		return htmlentities(urlencode($str));
	}
	function dbOUT($str){
		return html_entity_decode(urldecode($str));
	}
	function userInfo(){
		global $con;
		$data = array();
		if(isset($_SESSION['user'])){
			$query = mysqli_query($con,"SELECT name,email,id,updated_at FROM users where name='".$_SESSION['user']."' OR email='".$_SESSION['user']."'");
			$data  = mysqli_fetch_array($query);
		}else{
			$data[0] = '0';
		}
		return $data;
	}
}
if(isset($functions['DB_ONLY']) && $functions['DB_ONLY']==1){
	$con = addDB();
}
if(isset($functions["GLOBAL_ONLY"]) && $functions["GLOBAL_ONLY"]==1){
	function redirect($location){
		header("location: $location");
		die();
	}
	function dbIN($str){
			return htmlentities(urlencode($str));
	}
	function dbOUT($str){
		return html_entity_decode(urldecode($str));
	}
}
if(isset($functions["LOGIN_ONLY"]) && $functions['LOGIN_ONLY']==1){
	$warnShow = " style='display:none;' ";
	if(isset($_REQUEST['wrong_password']) && !empty($_REQUEST['wrong_password'])){
		$warnShow = " style='display:block;' ";
	}
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
		redirect("index.php");
	}
	function recordSession($user){
		$_SESSION['user'] = $user;
		return 1;
	}
}
if(isset($functions["USER_LEVEL_ONLY"]) && $functions['USER_LEVEL_ONLY']==1){
	
	function userInfo(){
		global $con;
		$data = array();
		if(isset($_SESSION['user'])){
			$query = mysqli_query($con,"SELECT name,email,id,updated_at FROM users where name='".$_SESSION['user']."' OR email='".$_SESSION['user']."'") or die(mysqli_error($con));
			
			$data  = mysqli_fetch_array($query);

		}else{

			$data[0] = '0';
		}
		return $data;
	}
}
?>