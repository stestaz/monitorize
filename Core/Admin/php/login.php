<?php
include("../inc/settings.php");

$user = $_POST["user"];
$pass = $_POST["pass"];
$result = 0;
error_log("ref login ".$_SERVER['HTTP_REFERER']);
if($user == null || 
	$pass == null || 
	$_SERVER['HTTP_REFERER'] !=$referrer){
	$result = 0;
}else{
	$query = "select * from users where username='".$user."' and password ='".$pass."'";
	$mySqlConn = new mysqli($db_server, $db_user, $db_pass, $db_name);
	if($res = $mySqlConn->query($query)){
			if ($res->num_rows!=0){	
			session_start();
			$_SESSION["user"] = $user;
    		$result = 1;
			}
	}
}
echo $result;