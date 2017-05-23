<?php
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$aid=$_POST["aid"];
	$bestans=$_POST["bestans"];
	$sql = "UPDATE answers ". "SET best_ans = $bestans ". 
               "WHERE aid = $aid" ;
	echo $sql;
	if(mysqli_query($conn,$sql))
	{
		echo $aid;
	}
	else {
		echo "error";
	}
}
?>