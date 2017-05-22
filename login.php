<?php 
session_start();

include("connectDB.php");


define('clientID', '37f1064c2b0a36df69dd');
define('clientSecret', '19068fcaf8337b1e16b61f2674362fcdfe0714de');
define('appName', 'FoodieLocal');

//Foodie Dev
//clientid 3cb6030696f66392a6c1
//secret e3688a8fe38f8880fed1a0c9a1784956ba089a10

echo $_GET["code"];
		
$url = 'https://github.com/login/oauth/access_token';

$fields = array(
		'client_id' => urlencode("3cb6030696f66392a6c1"),
		'client_secret' => urlencode("e3688a8fe38f8880fed1a0c9a1784956ba089a10"),
		'code' => urlencode($_GET['code'])
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post

$result = json_decode(curl_exec($ch),TRUE);
echo $result["access_token"];
//close connection
curl_close($ch);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"https://api.github.com/user?access_token=".$result["access_token"]);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_USERAGENT,'http://developer.github.com/v3/#user-agent-required');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

$output=json_decode(curl_exec($ch),TRUE);
curl_close($ch);

$_SESSION["username"]=$output["login"];
$_SESSION["userprofile"]=$output;

//add the user to local db
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());

$count=0;
$sql = "SELECT * from user where username='".$output["login"]."'";
$result = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_array($result)) {
	$count=$count+1;
	$_SESSION["role"]=$row["role"];
	$_SESSION["uid"]=$row["uid"];
}

echo "\n count:".$output["avatar_url"];
if($count==0)
{
	$sql = "INSERT INTO user(username, password, created_on,role) VALUES ('".$output["login"]."','','".date("Y-m-d h:i:sa")."',0)";
	if(mysqli_query($conn,$sql))
	{
		$uid=mysqli_insert_id($conn);
		$_SESSION["uid"]=$uid;
	}
	else {
		$_SESSION["uid"]=0;
	}
}
else
{
	$sql = "update user set upic='".$output["avatar_url"]."' where uid=".$_SESSION["uid"];
	if(mysqli_query($conn,$sql))
	{
		echo $sql;
	}
	else
	{
		echo $sql;
	}
}

?>
<form id="myForm" action="./index.php" method="post">
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>