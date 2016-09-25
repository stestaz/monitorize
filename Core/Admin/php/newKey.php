<?php
include("../inc/settings.php");

$ref = $_SERVER["HTTP_REFERER"];
if( $ref === $referrer."newKey.php"){
	$description = $_POST["desc"];
	$level = $_POST["lvl"];
	
	$myConn = new mysqli($db_server,$db_user,$db_pass,$db_name);
	do{
	$string = '';
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
 	for ($i = 0; $i < 40; $i++) {
      $string .= $characters[rand(0, strlen($characters) - 1)];
 	}
	$query = "insert into apis values(DEFAULT,'".$string."','".$description."',".$level.",1)";
	$toRet = $string;
	}while(!($myConn->query($query)));
	error_log("ritorno ".$toRet);
	echo json_encode($toRet);
}else{
	die;
}
