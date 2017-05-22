<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stack Exchange</title>
<link rel="icon" href='./images/icon.png'>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link href="http://fonts.googleapis.com/css?family=Montserrat"
	rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lato"
	rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<script src="js/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet"
	href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
.title {
	color: grey;
	font-weight: bold;
}

.post {
	padding: 10px;
	margin: 2vh;
}

.post-footer {
	background-color: #009688;
	color: #6b6b6b;
}

.error {
	color: #FF0000;
}

.footer-line {
	margin-top: 40px;
	text-align: center;
	background: #404040;
	width: 100%;
	position: relative;
	bottom: 0;
}

.footer-line p {
	line-height: 58px;
	margin-bottom: 0px;
	font-size: 14px;
	color: #FFFFFF;
}

.table {
	border-collapse: collapse;
}

@media screen and (min-width: 768px) {
	#userModal .modal-dialog {
		width: 900px;
	}
}

#userModal .modal-dialog {
	width: 75%;
}
</style>

<?php
session_start ();
include ("connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());


 if($_SESSION["username"] != "")
{
	echo "
			<script type='text/javascript'>
	
	$(document).ready(function() {
	
		$('#loginLink').hide();
		$('#registerLink').hide();
		$('#profileLink').show();
		$('#logoutLink').show();
	});
	 
	
</script>
			";
}

else {
	echo "
			<script type='text/javascript'>
	
	$(document).ready(function() {
	
		$('#loginLink').show();
		$('#registerLink').show();
		$('#profileLink').hide();
		$('#logoutLink').hide();
	});
	
	
</script>
			";
}

?>
<script type="text/javascript">
function regUser()
{
	if ($("#newUserId").val() == "" || $("#newUserPw").val() == "") {
		alert ("Username & Password is mandatory");
	} else {
				
	var postData = "&username="+$("#newUserId").val()+"&password="+$("#newUserPw").val();
    $.ajax({
          type: "post",
          url: "services/RegisterUser.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
              if(responseData.split("-")[0]=="error")
              {
            	  alert(responseData.split("-")[1]);
                }
              else
              {
               alert("Registration successful, please login using the link above")
			   location.reload(); 
              }
          },
          error: function(jqXHR, textStatus, errorThrown) {
              console.log(errorThrown);
          }
      });
	$("#regModal").modal('hide');
	}
}
</script>
</head>
<body>
	<nav class="navbar navbar-inverse"
		style="background-color: #4d636f; color: white;">
		<div class="container-fluid">
			<div class="navbar-header">
				<img class="navbar-brand" src='./images/FoodieLogo.png' alt="foodie"
					style="padding: 5px 10px;">
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="./index.php"
					style="background-color: #3a4b53; margin-right: 2px; margin-left: 2px;">Home
						Page</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li id="registerLink" style='cursor: pointer;'><a data-toggle='modal' data-target='#regModal' >Register</a></li>
				<li id='profileLink' style="color: white;"><a data-toggle='modal'	data-target='#userModal' ><?php echo $_SESSION["username"]; ?> </a>
				</li>
				<li id='logoutLink'><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</nav>

	<div class="row">
		<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<h4 style="color:#0ea9f7"><b>Welcome to Foodie</b></h4><br>
					<h5 style="color:#e25c14"><b>Get Started!</b></h5><br>
						<h6><b>New User registration</b></h6>
							<p> Click on the Registration link on the top right corner and fill up the form to register.</p>
							<img src='./images/Register.png' alt="Register"style="padding: 5px 10px;"><br><br>
						<h6><b>Logging in</b></h6>
							<p>Use the login link on the top right corner and key in your username and password and then hit submit</p>
							<img src='./images/Login.png' alt="Login"style="padding: 5px 10px;"><br><br>
					<br><h5 style="color:#e25c14"><b>Ask Questions, find answers, answer questions</b></h5><br>
						<h6><b>Post a Question</b></h6>
							<p>Once logged-in the post link will be visible. Click on the link and enter all the details. </p>
							<img src='./images/Post.png' alt="Post"style="padding: 5px 10px;"><br><br>
						<h6><b>Search Questions</b></h6>
							<p>Search for questions from other users with keywords. </p>
							<img src='./images/SearchQuestions.png' alt="SearchQuestions"style="padding: 5px 10px;"><br><br>	
						<h6><b>Get Answers</b></h6>
							<p>Veiw answers to your questions</p>
							<img src='./images/GetAnswers.png' alt="GetAnswers"style="padding: 5px 10px;"><br><br>	
						<h6><b>Answering Questions</b></h6>
							<p>Enter the text in the box and click submit.</p>
							<img src='./images/AnsweringQuestions.png' alt="AnsweringQuestions"style="padding: 5px 10px;"><br><br>
					<br><h5 style="color:#e25c14"><b>Veiw your profile, Upload a picture, Veiw other user's profiles</b></h5><br>	
						<h6><b>Click on your username at top right to view your profile and to add a picture</b></h6>	
						<h6><b>Searching other users</b></h6>	
							<img src='./images/ProfileView.png' alt="ProfileView"style="padding: 5px 10px;"><br><br>
	
		
	</div>
</div>

<div class="modal fade" id="regModal" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Create your Foodie account</h4>
					</div>
					<div class="modal-body">
					       	<div class="form-group">
								<label for="email">Email address/User Name:<span style="color: red;">*</span></label> <input type="text"
									class="form-control" name="newUserId" id="newUserId" required>
							</div>
							<div class="form-group">
								<label for="pwd">Password:<span style="color: red;">*</span></label> <input type="password"
									class="form-control" name="newUserPw" id="newUserPw" required>
									<br><p style="font-size: 12px;">Feilds marked <span style="color: red;"> * </span>are mandatory<p>
							</div>
							<button class="btn btn-default" onclick = "regUser()">Register</button>
					 </div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
	
	 <div class="container footer-line">
		<p>Project for CS518 - Developed by Kumar,Surabhi,Satya - 2016</p>
	 </div>

</body>
</html>