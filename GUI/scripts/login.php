<?php

include("../inc/include.php");

$myConn = new mysqli($dbServer,$dbUser,$dbPass,$dbName);

$user=$_POST["username"];
$pass=base64_decode($_POST["password"]);
$result=0;
if($user != null && $pass !=null){
	$query = "select id from users where username='".$user."' and password='".$pass."' and enable=1";
	$res = $myConn->query($query);
	if(mysqli_num_rows($res)!=0){
		$result=1;
		setcookie("raspeinUser",$user,time()+(86400*30),"/");
	}
}
echo $result;