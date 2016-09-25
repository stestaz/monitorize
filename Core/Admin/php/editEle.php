<?php
include("../inc/settings.php");
$ref = $_SERVER["HTTP_REFERER"];
if( $ref === $referrer."listKeys.php"){
	$id = $_POST["id"];
	$description = $_POST["description"];
	$level = $_POST["level"];
	$enable = $_POST["enable"];
	
	$myConn = new mysqli($db_server,$db_user,$db_pass,$db_name);
	$query = "update apis set description='".$description
	."', enable=".$enable
	.", level=".$level." where id=".$id;
	echo $myConn->query($query);
}else{
	die;
}
