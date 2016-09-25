<?php
include("../inc/include.php");
$return = array("key"=>$apiKey,"id"=>$instId,"server"=>$apiServer);
echo json_encode($return);