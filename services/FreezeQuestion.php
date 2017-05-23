<?php 
session_start ();
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$qid=$_POST["qid"];
	$freeze=$_POST["freeze"];
	$sql = " UPDATE question SET freeze = ".$freeze." WHERE qid =".$qid;
	//echo $sql;
	if(mysqli_query($conn,$sql))
	{
		echo "success";
	}
	else {
		echo "error";
	}
}
?>