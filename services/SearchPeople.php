<?php
session_start ();
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());


 if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$keyword= $_POST["keyword"];
	$resUsers="";
	
	$sql = "select * from user where username LIKE '%$keyword%' limit 5";
	$keyword = mysqli_query($conn,$sql);
	while( $row = mysqli_fetch_assoc ( $keyword ) )
		{
			$resUsers.= $row['username']."|".$row['uid']."-";
		}
		echo $resUsers;
	
 }
?>