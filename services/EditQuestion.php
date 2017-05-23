<?php
session_start ();
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());
$vid=0;
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
$qid=$_POST["qid"];
$qContent=$_POST["qContent"];
$qTitle=$_POST["qTitle"];
echo $qid.$qContent.$qTitle;
	$sql =" UPDATE `question` SET `qtitle`    = '".$qTitle."',`qcontent`  = '".$qContent."' WHERE `qid` = ".$qid;
	echo $sql;
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