<?php
include("../connectDB.php");
$conn = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Could not connect to MySQL: ' . mysql_error () );
$sql = "select (select count(*) from question where uid=U.uid) as qcount,(select count(*) from answers where uid_ans=U.uid) as acount,(select count(*) from answers where uid_ans=U.uid and best_ans=1) as bestcount from user U where U.uid=".$_GET["uid"];
$rs1 = mysqli_query($conn,$sql);
$data=array();
while ( $row = mysqli_fetch_assoc ( $rs1 ) ) {
	$data = array(
      'qcount' => $row['qcount'],
      'acount' => $row['acount'],
      'bcount' => $row['bestcount']
   );
}
$json = json_encode($data);
mysqli_close($conn);
echo $json;
?>
