<?php
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$uid=$_POST["uid"];
	$picpref=$_POST["picpref"];
	$sql = "UPDATE user ". "SET pic_pref = $picpref ". 
               "WHERE uid = $uid" ;
	echo $sql;
	if(mysqli_query($conn,$sql))
	{
		echo $uid;
	}
	else {
		echo "error";
	}
}
?>