<?php
session_start ();
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());

$vid=0;
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$vote=$_POST["vote"];
	$aid=$_POST["aid"];
	$uid=$_POST["uname"];
	$sql = "INSERT INTO votes_ans(vote_ans, aid, vote_ans_uid) VALUES (".$vote.",".$aid.",".$uid.")";
	if(mysqli_query($conn,$sql))
	{
		$vid=mysqli_insert_id($conn);
		echo $vid;
	}
	else {
		echo "error";
	}
}
?>