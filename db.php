<?php 
Class DB{
	public function connection(){	
		$host = "nomanwasim.ipagemysql.com";
		$user = "talha";
		$pass = "habib";
		$db   = "newzbadmin";	
		$con = mysqli_connect($host,$user,$pass,$db) or die(mysqli_error($con));
		return $con;
	}
}
?>