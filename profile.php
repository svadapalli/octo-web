<?php
session_start ();
include ("connectDB.php");
$upicture=null;

if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	//echo "In Post";
	// code to upload image to server and update or insert userpicpath
	if (isset ( $_FILES ['image'] )) {
		$errors = array ();
		$file_name = $_FILES ['image'] ['name'];
		$file_size = $_FILES ['image'] ['size'];
		$file_tmp = $_FILES ['image'] ['tmp_name'];
		$file_type = $_FILES ['image'] ['type'];
		$file_ext = strtolower ( end ( explode ( '.', $_FILES ['image'] ['name'] ) ) );
		
		$new_file_name=$_SESSION["uid"].".".$file_ext;
		//echo $new_file_name;
		
		$expensions = array (
				"jpeg",
				"jpg",
				"png" 
		);
		
		if (in_array ( $file_ext, $expensions ) === false) {
			$errors [] = "extension not allowed, please choose a JPEG or PNG file.";
		}
		
		//echo "<script>alert(".$file_size.");</script>";
		if ($file_size > 2097152) {
			$errors [] = 'File size must be excately 2 MB';
		}
		
		if (count ( $errors ) === 0) {
			$status=move_uploaded_file ( $file_tmp, "profiles/" . $new_file_name );
			
			//echo "Status of file:".$status;
			
			$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
			OR die ('Could not connect to MySQL: '.mysql_error());
			
			$sql="update user set upic='profiles/".$new_file_name."' where uid=".$_SESSION["uid"];
			
			if (mysqli_query($conn, $sql)) {
				echo "<script>alert('Profile pic changed successfully!!');</script>";
			} else {
				echo  "<script>alert('Error uploading profile pic!!');</script>";
			}
			
		} else {
			print_r ( $errors );
		}
	}
}
?>
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
	<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
<style>

  .bar{
    fill: white;
  }

  .bar:hover{
    fill: brown;
  }

	.axis {
	  font: 10px sans-serif;
	}

	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}
	
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

.errorClass {
	color: #FF0000;
}

.footer-line {
	margin-top: 40px;
	text-align: center;
	background: #404040;
	width: 100%;
	position: fixed;
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
<script type="text/javascript">

function getChartData(uid)
{
	$.ajax({
        type: "get",
        url: "services/MyDashboard.php?uid="+uid,
        contentType: "application/json",
        success: function(responseData, textStatus, jqXHR) {
            showChart(responseData);
		},
        error: function(jqXHR, textStatus, errorThrown) {
        }
	});
}

function showChart(data)
{
	var newdata = [];
	var results = d3.map( JSON.parse(data) );
    results.forEach( function( key, val ) {
        if(key=="qcount")
        {
			key="Questions";
           }
        else  if(key=="acount")
        {
			key="Answered";
           }
        else if(key=="bcount")
        {
			key="Best Answers";
           }
        var result = {"count":parseInt(val),"label":key};
        newdata.push( result );
    } );

    console.log(newdata);
	
	var margin = {top: 20, right: 20, bottom: 70, left: 40},
    width = 300 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;


// set the ranges
var x = d3.scale.ordinal().rangeRoundBands([0, width], .5);

var y = d3.scale.linear().range([height, 0]);

// define the axis
var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom")


var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .ticks(10);


// add the SVG element
var svg = d3.select("#myStats").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", 
          "translate(" + margin.left + "," + margin.top + ")");
	
  // scale the range of the data
  x.domain(newdata.map(function(d) { return d.label; }));
  y.domain([0, d3.max(newdata, function(d) { return d.count; })]);

  // add axis
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .selectAll("text")
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", "-.55em")
      .attr("transform", "rotate(-90)" );

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 5)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Frequency");


  // Add bar chart
	svg.selectAll("bar")
      .data(newdata)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("style","fill:steelblue")
      .attr("x", function(d) { return x(d.label); })
      .attr("width", x.rangeBand())
      .transition().delay(function (d,i){ return i * 600;})
	   .duration(800)
	  .attr("y", function(d) { return y(d.count); })
      .attr("height", function(d) { return height - y(d.count); }) ;
}


$(document).ready(function(){
	console.log("inside ready func");	
	$('input[name="picpref"]').click(function() {
		uid = $("#profileLink").attr("userid");
		picpref = $('input[name="picpref"]:checked').val();
		var postData = "uid="+ uid+"&picpref="+picpref;
		$.ajax({
	        type: "post",
	        url: "services/PicPref.php",
	        data: postData,
	        contentType: "application/x-www-form-urlencoded",
	        success: function(responseData, textStatus, jqXHR) {
				  location.reload();   
			},
	        error: function(jqXHR, textStatus, errorThrown) {
	            alert("Error setting selecting image!! Try again");
	            console.log(jqXHR+":"+errorThrown);
	        }
		});
	});
});
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
				<li class="active" id='postLink'
					style="display: none; cursor: hand;"><a
					style="background-color: #3a4b53; margin-right: 2px; margin-left: 2px;"
					data-toggle='modal' data-target='#myPostModal'>Post</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li id='profileLink' userId=<?php echo $_SESSION["uid"]; ?> style="cursor: hand; color: white;">
					<!-- Surbhi: Profile page --> <a data-toggle='modal'
					data-target='#userModal' style='cursor: hand;'> Welcome,<?php echo $_SESSION["username"]; ?> </a>
				</li>
				<li id='logoutLink'><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</nav>

	<div class="row" style="margin: 0px;">
		<div class="col-sm-3">
			<form action="#">
      	<?php
						$conn = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Could not connect to MySQL: ' . mysql_error () );
						$sql = "SELECT uid, firstname, lastname, address, email, contact, username, password, upic, pic_pref, created_on,IFNULL((select count(*) from question q where q.uid=". $_GET["uid"] ." and hide!=1),0) as totalquestions,IFNULL((select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=". $_GET["uid"] ."),0) as score FROM user where uid='" . $_GET["uid"] . "'";
						if (! $conn) {
							echo "error";
						}
						$userpicpath ="profiles/profile.png";
								
						$rs1 = mysqli_query ( $conn, $sql );
						$x = 0;
												
						while ( $row = mysqli_fetch_assoc ( $rs1 ) ) {
							$email = $row["email"];
							$d = 'wavatar';
							$s = 80;
							$r = 'g';
										
							$grav_url = "https://www.gravatar.com/avatar/";
							$grav_url .= md5( strtolower( trim( $row["email"] ) ) );
							$grav_url .= "?s=$s&d=$d&r=$r";
							
							$pic_pref =0;
							$pic_pref = $row ["pic_pref"];
							if($pic_pref == 0){
								if($row["upic"]!=NULL){
									$userpicpath=$row["upic"];
								}
								else{
									$userpicpath ="profiles/profile.png";
								}
							} else {
								$userpicpath = $grav_url;
							}							
							echo "<b>First Name:</b>" . $row ["firstname"] . "<br>";
							echo "<b>Last Name:</b>" . $row ["lastname"] . "<br>";
							echo "<b>Address:</b>" . $row ["address"] . "<br>";
							echo "<b>Contact:</b>" . $row ["contact"] . "<br>";

echo "<b>Email:</b>" . $row ["email"] . "<br>";
						if(!empty($_SESSION["uid"]))
									{
										if(!empty($_SESSION["role"]))
										{
											if($_SESSION["role"]==1)
											{
													echo "<b>Score : </b>" . $row ["score"] . "<br>";
													echo "<b>Total Questions : </b>" . $row ["totalquestions"] . "<br>";
											}
										}
									}
						}
			?>
      </form>
		</div>
		<div class="col-sm-3">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?uid=".$_SESSION["uid"];?>" method="post"
				enctype="multipart/form-data">
				<div class="form-group">
					<img width='150' height='160'
						src="<?php echo $userpicpath;?>"  alt="profile" />
				</div>
				<div class="form-group">
					<input class="input-group" type="file" name="image"
						id="image" accept="image/jpeg,image/png" />
					<button id="updateBtn" type="submit" class="btn btn-default">Update</button>
				</div>

			</form>
			<?php
			  if($pic_pref == 0){
			  	echo '<label class="radio-inline"><input type="radio" name="picpref" value="1" >Gravatar Image</label>';
			  	echo '<label class="radio-inline"><input type="radio" name="picpref" value="0" checked >Custom Avatar</label>';
			  }else{
			  	echo '<label class="radio-inline"><input type="radio" name="picpref" value="1" checked>Gravatar Image</label>';
			  	echo '<label class="radio-inline"><input type="radio" name="picpref" value="0"  >Custom Avatar</label>';
			  	echo "<script type='text/javascript'> $(document).ready(function(){ $('#image').hide();$('#updateBtn').hide();});</script>";
			  	//echo "<script type='text/javascript'>$('#updateBtn').hide();</script>";
			  }
				
			?>
		</div>
	</div>
	
	<div class="row" style="margin:0px;">
	<div class="col-sm-6">
	<?php
		if($_SESSION["uid"]==$_GET["uid"])
		{
			echo "<script>$('#updateBtn').show();$('#image').show();</script>";	
		}
		else 
		{
			echo "<script>$('#updateBtn').hide();$('#image').hide();</script>";	
		}
		
					function showMyPosts()
					{
						$uid=$_GET["uid"];
						if ($uid != 0) {
							$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
							OR die ('Could not connect to MySQL: '.mysql_error());
							
							$sql = "SELECT Q.qid,qtitle,qcontent,U.upic,U.uid,created_date,U.username,U.email,U.pic_pref,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid) as votes,IFNULL((select sum(vote_ques) from votes_ques where qid=Q.qid),0) as value FROM question Q,user U WHERE U.uid=Q.uid and U.uid=".$uid." order by value desc";
							$rs = mysqli_query ($conn,$sql );
							$x = 0;
							while ( $row = mysqli_fetch_assoc ( $rs ) ) {
								
								$email = $row["email"];
								$d = 'mm';
								$s = 80;
								$r = 'g';
											
								$grav_url = "https://www.gravatar.com/avatar/";
								$grav_url .= md5( strtolower( trim( $row["email"] ) ) );
								$grav_url .= "?s=$s&d=$d&r=$r";
								
								$pic_pref =0;
								$pic_pref = $row ["pic_pref"];
								$picurl="profiles/profile.png";
								if(($pic_pref == 0)&&($row["upic"]!=NULL)){
									$picurl=$row["upic"];
								} else {
									$picurl = $grav_url;
								}
								
								
								$postinfo = "<div class='w3-card-2 w3-hover-shadow' style='border-left: 4px solid #009688;'><div class='row post'>
								<div class='col-sm-7'>
									<p class='title' style='cursor: hand;' data-toggle='collapse' data-target='#mycollapse" . ($x + 1) . "'>" . $row ["qtitle"] . "</p>
									<p id='qdescription".($x + 1)."'>".$row["qcontent"]."</p>";
						
							$sqltags="select * from tags where tag_id in (SELECT tag_id_fk FROM `question_tag` WHERE qid_fk=".$row["qid"].")";
							$rstags = mysqli_query($conn,$sqltags);
							while ( $rsrow = mysqli_fetch_assoc ( $rstags ) ) {
									$postinfo = $postinfo ."<a href='javascript:showTagQuestions(".$uid.",".$rsrow["tag_id"].",\"".$rsrow["tags"]."\")' style='background-color: #5bc0de;color:#ffffff;padding: 5px;margin-right:5px;'>".$rsrow["tags"]."</a>";
								}
						
								$postinfo = $postinfo ."</div>
								<div class='col-sm-2'>
								Value <a href='#'><span class='badge'>".$row["value"]."</span></a>
								Answers <a href='#'><span class='badge'>" .$row["answers"]."</span></a></div>
						  		<div class='col-sm-3'><img src='".$picurl."' width='50' height='50'  class='img-circle img-responsive' alt='profile'><br>".$row ["username"]."</div>
						  		</div>
								<div id='mycollapse" . ($x + 1) . "' class='post-footer collapse'><div class='list-group'>";
						
								$sql2="SELECT A.aid,A.adesc,U.upic,U.username,A.best_ans,pic_pref,email,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$row["qid"]." order by value desc";
								$rs2 = mysqli_query($conn,$sql2);
								$bestansid=0;
								$bestrow="";
								while($arow= mysqli_fetch_assoc ( $rs2 ))
								{
									$picurl="profiles/profile.png";
									if(!empty($ansrow["upic"]))
									{
										$picurl=$ansrow["upic"];
									}
									
									if($arow["best_ans"]==1)
									{
										$bestansid=$arow["aid"];
										$bestrow=$arow;
										if($arow["pic_pref"]==1)
										{
											$d = 'wavatar';
											$s = 80;
											$r = 'g';
										
											$picurl = "https://www.gravatar.com/avatar/";
											$picurl .= md5( strtolower( trim( $arow["email"] ) ) );
											$picurl .= "?s=$s&d=$d&r=$r";
										}
										$bestrow["upic"]=$picurl;
									}
								}
								mysqli_data_seek($rs2,0);
								$y = 0;
								if($bestansid>0)
								{
									$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$bestrow["adesc"]."</div><div class='col-sm-2'><img src='".$bestrow["upic"]."' width='50' height='50'  class='img-circle img-responsive' alt='profile' ><b>".$bestrow["username"]."</b></div><a href='#' class='col-sm-1'>".$bestrow["upvotes"]."<img width='24' height='24' src='./images/thumb-up-outline.png' alt='up'></a><a href='#' class='col-sm-1'>".$bestrow["downvotes"]."<img width='24' height='24' src='./images/thumb-down-outline.png' alt='down'></a><div class='col-sm-2'><img  class='img-responsive' width='24' height='24' src='./images/bestans.png' alt='best'></div></div>";
									$y=$y+1;
								}
								while ( $ansrow = mysqli_fetch_assoc ( $rs2 ) ) {
									if($bestansid!=$ansrow["aid"])
									{
										$picurl="profiles/profile.png";
										if(!empty($ansrow["upic"]))
										{
											$picurl=$ansrow["upic"];
										}
										if($ansrow["pic_pref"]==1)
										{
											$d = 'wavatar';
											$s = 80;
											$r = 'g';
										
											$picurl = "https://www.gravatar.com/avatar/";
											$picurl .= md5( strtolower( trim( $ansrow["email"] ) ) );
											$picurl .= "?s=$s&d=$d&r=$r";
										}
										
										if($bestansid>0)
										{
											$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50' height='50'  class='img-circle img-responsive' alt='profile' > <b>".$ansrow["username"]."</b></div><a href='#' class='col-sm-1'>".$ansrow["upvotes"]."<img width='24' height='24' src='./images/thumb-up-outline.png' alt='up'></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24' height='24' src='./images/thumb-down-outline.png' alt='down'></a></div>";
										}
										else
										{
											$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50' height='50'  class='img-circle img-responsive' alt='profile'><b>".$ansrow["username"]."</b></div><a href='#' class='col-sm-1'>".$ansrow["upvotes"]."<img width='24' height='24' src='./images/thumb-up-outline.png' alt='up'></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24' height='24' src='./images/thumb-down-outline.png' alt='down'></a><div class='col-sm-2'><button onclick='submitBestAns(".$ansrow["aid"].")'>Mark</button></div></div>";
										}
									}
								}
								//$postinfo = $postinfo . "<div class='list-group-item'><label for='Answer'>Comment:</label><textarea class='form-control' rows='5' id='mycomment".($x + 1)."' onclick='event.stopPropagation()'></textarea><input type='button' value='Submit' onclick='saveAnswer(2,".($x+1).",".$row["qid"].")'></div>";
								$postinfo = $postinfo . "</div></div></div>";
								echo $postinfo;
								$y = $y + 1;
								$x = $x + 1;
							}
						}
					}
						showMyPosts();
						echo "<script>getChartData(".$_SESSION["uid"].")</script>";
					?>
	</div>
		<div class="col-sm-6">
		    <div class="panel panel-info">
			      <div class="panel-heading">User Statistics</div>
			      <div class="panel-body">	
			      	<div id="myStats">
					</div>
				</div>
   			 </div>
			
		</div>
	</div>
	
	
	<div class="container footer-line">
		<p>Project for CS518 - Developed by Kumar,Surabhi,Satya - 2016</p>
	</div>

</body>
</html>
