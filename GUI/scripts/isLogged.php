<?php

if( !isset($_COOKIE["raspeinUser"]) ){
    echo "";
}else{
	echo $_COOKIE["raspeinUser"];
}