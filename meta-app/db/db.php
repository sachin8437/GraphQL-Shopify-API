<?php

$servername = "localhost";
$username = "u813765851_diy_assist_app";
$password = "pZY7VpLpqB#9";
$db = "u813765851_diy_assist_app";
$conn = mysqli_connect($servername, $username, $password, $db);
if($conn){
	//echo "connected";
}else{
	echo "db not connected";
}