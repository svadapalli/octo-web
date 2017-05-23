<?php
session_start ();
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());

$vid_ques=0;
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$vote=$_POST["vote_ques"];
	$qid=$_POST["qid"];
	$uid=$_POST["vote_ques_uid"];
	$sql = "INSERT INTO votes_ques(vote_ques, qid, vote_ques_uid) VALUES (".$vote.",".$qid.",".$uid.")";
			
	if(mysqli_query($conn,$sql))
	{
		$vid=mysqli_insert_id($conn);
		$_SESSION["recQid"]=$_POST["qid"];
		echo $vid_ques;
	}
	else {
		echo "error";
	}
}
?>
