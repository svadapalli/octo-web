<?php
session_start ();

include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());
$aid=0;
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	$adesc = $_POST["adesc"];

	//$_adesc = mysql_real_escape_string($_POST["adesc"]);
	
	$qid = $_POST["qid"];
	$uid = $_POST["uname"];
	$sql = "INSERT INTO answers(adesc, qid, uid_ans, answered_date) VALUES ('".$adesc."',".$qid.",".$uid.",'".date("Y-m-d h:i:sa ")."')";
	
	if(mysqli_query($conn,$sql))
	{
		$aid=mysqli_insert_id($conn);
		$_SESSION["recQid"]=$_POST["qid"];
		echo $aid;
	}
	else {
		echo "error";
	}
}
?>