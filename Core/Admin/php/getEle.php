<?php
include("../inc/settings.php");
$ref = $_SERVER["HTTP_REFERER"];
if( $ref === $referrer."listKeys.php"){
	$id = $_POST["id"];
	
	$myConn = new mysqli($db_server,$db_user,$db_pass,$db_name);
	$query = "select * from apis where id=".$id;
	$result = $myConn->query($query);
	error_log("Query: ".$query);
	error_log($result);
	echo json_encode($result->fetch_assoc());
}else{
	die;
}
