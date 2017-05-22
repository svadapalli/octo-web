<?php
session_start ();
include("connectDB.php");
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	if(empty($_SESSION["userprofile"]))
	{
		$_SESSION["username"]=$_POST["email"];
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
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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

/* Satya: code for search */
div.searchResults {
    display: none;
    position: absolute;
    top: 40px;
    left: none;
    font-size:12px;
    color: #0096e1;
    font-weight: bold;
    background: white;
    margin-left:63.5%;
    border: 1px solid #ccc;
    border-top-color: #d9d9d9;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    -webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    opacity: 1;
    z-index:1000;
    width: 206px;
}

#searchResults li{
    padding:5px;
    
}
#searchResults li:hover{
    background-color:#dbe8fc;
    
}
</style>
 <script>tinymce.init({ selector:'textarea',plugins : 'image,bbcode,paste',  paste_as_text: true, file_browser_callback: function(field_name, url, type, win) {
	$("#mceUpload").val(field_name);
     if(type=='image') $('#my_form input').click();
 } });</script>
<script type="text/javascript">
var uname="";
 var recQid='<?php echo $_SESSION["recQid"]; ?>';

var topQuesCount=0;
var topQuesPage=1;
var lastQuesPage=1;
var userId=1;

/* Satya: Code for user registration: */
function regUser()
{
	if ($("#newUserId").val() == "" || $("#newUserPw").val() == "" || $("#newUseremail").val() == "") {
		alert ("Username & Password & email is mandatory");
	} else {
				
	var postData = "&username="+$("#newUserId").val()+"&password="+$("#newUserPw").val()+"&email="+$("#newUseremail").val();
    $.ajax({
          type: "post",
          url: "services/RegisterUser.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
              console.log(responseData);
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

function setUploadName(fieldname,fname){
	$("#"+fieldname).val(fname);
}

function createPost()
{
	tinyMCE.triggerSave();
	if($("#postTitle").val()=='' || $("#postTitle").val()==null || tinymce.get('postContent').getBody().innerHTML=="" || tinymce.get('postContent').getBody().innerHTML==null )
	{
		alert("Post Title & content cannot be empty : "+$("#tags").val());
		}
	else
	{
			var postData = "uname="+ uname+"&title="+$("#postTitle").val()+"&content="+tinymce.get('postContent').getBody().innerHTML+"&tags="+$("#tags").val();
		    $.ajax({
		          type: "post",
		          url: "services/CreatePost.php",
		          data: postData,
		          contentType: "application/x-www-form-urlencoded",
		          success: function(responseData, textStatus, jqXHR) {
		              if(responseData=="error")
		              {
		            	 // alert("uname:"+uname+"title"+$("#postTitle").val());
		            	  location.reload(); 
		                 }
		              else
		              {
					   location.reload(); 
		              }
		          },
		          error: function(jqXHR, textStatus, errorThrown) {
		              console.log(errorThrown);
		          }
		      });
			$("#myPostModal").modal('hide');
	}
}
function saveAnswer(type,x,qid)
{
	var desc="";
	if(type==1)
	{
		desc=tinymce.get("comment"+x).getContent()
		}else
		{
			desc=tinymce.get("mycomment"+x).getContent()
		}
	 
	var postData = "uname="+ uname+"&qid="+qid+"&adesc="+desc;
	$.ajax({
          type: "post",
          url: "services/CreateAnswers.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			recQid=qid;
			  location.reload();   
          },
          error: function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR+":"+errorThrown);
          }
      });
	if(type==1)
	{
		$("#comment"+x).val("");
		}else
		{
			$("#mycomment"+x).val("");
		}
}

function showLogout()
{
	$("#postLink").show();
	$("#topbar").show();
	$("#loginLink").hide();
	$("#logoutLink").show();
	$("#myQuesSection").show();
	$("#aboutUs").hide();
	$("#profileLink").show();
	$("#registerLink").hide();
	$('#myQuesSection').css('opacity', '1');
	$('#recommendationPanel').show();
	$('#search-people').show();

}
function showMyQuestions()
{
	if(uname=="")
	{
		$('#myQuesSection').css('opacity', '0');
		$('#myQuesPages').css('opacity', '0');
		$('#recommendationPanel').hide();
	}
	else{
		$('#myQuesSection').css('opacity', '1');
		loadRecommendations(uname,1,recQid);
	}
}
function submitBestAns(aid)
{
	var postData = "aid="+ aid+"&bestans=1";
	$.ajax({
          type: "post",
          url: "services/BestAns.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			  location.reload();   
			  alert("Best answer for your question is marked!!");
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error setting best answer!! Try again");
              console.log(jqXHR+":"+errorThrown);
          }
      });
}
function voteQuestion(voteValue,qid)
{
	// calling Set Vote Question service
	var postData = "vote_ques="+ voteValue+"&qid="+qid+"&vote_ques_uid="+uname;
	$.ajax({
          type: "post",
          url: "services/SetVotesQues.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
              if(responseData!="error")
              {
		            	  if(parseInt(voteValue)==1)
		          		{
		          			$('#qVoteUp'+qid).text(parseInt($('#qVoteUp'+qid).text())+1);
		          			recQid=qid;
		          			loadRecommendations(uname,1,qid);
		          		}
		          		else{
		          			$('#qVoteDown'+qid).text(parseInt($('#qVoteDown'+qid).text())+1);
		          		}
                }
              else
              {
					alert("You have already voted for this question");
                  }
        	
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error setting best answer!! Try again");
              console.log(jqXHR+":"+errorThrown);
          }
      });
}

function voteAnswer(voteValue,aid,qid)
{
	var postData = "vote="+ voteValue+"&aid="+aid+"&uname="+uname+"&qid="+qid;
	$.ajax({
          type: "post",
          url: "services/SetVoteAns.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
              if(responseData!="error")
              {
            	  if(parseInt(voteValue)==1)
            		{
            			$('#qAnsUp'+aid).text(parseInt($('#qAnsUp'+aid).text())+1);
            		}
            		else{
            			$('#qAnsDown'+aid).text(parseInt($('#qAnsDown'+aid).text())+1);
            		}
            	  	recQid=qid;
        			loadRecommendations(uname,1,qid);
                }
              else
              {
					alert("You have already voted for this answer");
                  }
        	
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error setting best answer!! Try again");
              console.log(jqXHR+":"+errorThrown);
          }
      });
	// calling Set Vote Answer service
	
	
}

function searchPosts()
{
	var txt = $('#search-criteria').val();
	if(txt=='' || txt ==null)
	{
		$('.top-posts').show();
		}
	else
	{
		$('.top-posts').hide();
	    $('.top-posts').each(function(){
	       if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
	           $(this).show();
	       }
	    });
	}
}

function clearSearchResults()
{
	$('#search-criteria').val("");
	$('.top-posts').show();
}

function sortTopQuestions(x)
{
	var $divs = $("div.top-posts");
		if(x==1)
		{
// 			var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
// 		        return $(a).find("qVoteUp").text() < $(b).find("qVoteUp").text();
// 		    });
// 		    $("#topQuestionsSection").html(alphabeticallyOrderedDivs);
			}
}

function showQuesDesc(x)
{
	$('#myDesc'+x).css('opacity', '1');
}

function loadRecommendations(uid,eventid,qid)
{
	var postData = "uid="+ uid+"&event="+eventid+"&qid="+qid;
		$.ajax({
	          type: "post",
	          url: "services/Recommendations.php",
	          data: postData,
	          contentType: "application/x-www-form-urlencoded",
	          success: function(responseData, textStatus, jqXHR) {
					$("#recommendationSection").empty();
	           		$("#recommendationSection").append(responseData);
	          },
	          error: function(jqXHR, textStatus, errorThrown) {
	              alert("Error setting best answer!! Try again");
	              console.log(jqXHR+":"+errorThrown);
	          }
	      });
}

function showTopQuesPagination(currpage,lastpage,uid)
{
	$("#topQuesPages").empty();
	var end=currpage+2;
	var start=currpage-2;
	if(end>lastpage)
	{
		end=lastpage;
		}

	if(start<1)
	{
		start=1;
		}

	
	for(var i=start;i<=end;i++)
	{
		if(i==currpage)
		{
			$("#topQuesPages").append("<li class='active'><a href='javascript:openTopQuesPage("+i+","+uid+","+lastpage+")'>"+i+"</a></li>");
			}
		else
		{
			$("#topQuesPages").append("<li><a href='javascript:openTopQuesPage("+i+","+uid+","+lastpage+")'>"+i+"</a></li>");
		}
	}
}

function openTopQuesPage(pgno,uid,lastpage)
	{
	topQuesPage=pgno;
	lastQuesPage=lastpage;
	userId=uid;
	var postData ="uid="+ uid+"&pgno="+pgno+"&totalPages="+lastpage+"&tagid="+$("#topQuesTag").val();
	$.ajax({
          type: "post",
          url: "services/GetTopQuestions.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			$("#topQuestionHolder").empty();
			$("#topQuestionHolder").append(responseData);

			//tinymce.init({ selector:'textarea',plugins : 'image' });
			var commentBox=$("#topQuestionHolder").find("textarea");
		tinymce.remove();
		tinymce.init({ selector:'textarea',plugins : 'image' });

// 			for(var i=1;i<=commentBox.length;i++)
// 				{
// 					alert(" i : "+i);
// 				}
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error get page data!! Try again");
          }
      });
	}

function showTopAnsPagination(secid,currpage,lastpage,qid)
{
	$("#topAnsPages"+secid).empty();
	var end=currpage+2;
	var start=currpage-2;
	if(end>lastpage)
	{
		end=lastpage;
		}

	if(start<1)
	{
		start=1;
		}

	
	for(var i=start;i<=end;i++)
	{
		if(i==currpage)
		{
			$("#topAnsPages"+secid).append("<li class='active'><a href='javascript:openTopAnsPage("+secid+","+i+","+qid+","+lastpage+")'>"+i+"</a></li>");
			}
		else
		{
			$("#topAnsPages"+secid).append("<li><a href='javascript:openTopAnsPage("+secid+","+i+","+qid+","+lastpage+")'>"+i+"</a></li>");
		}
	}
}

function openTopAnsPage(secid,pgno,qid,lastpage)
	{
	var postData ="qid="+ qid+"&pgno="+pgno+"&totalPages="+lastpage+"&secid="+secid;
	$.ajax({
          type: "post",
          url: "services/GetTopAnswers.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			$("#ansSection"+secid).empty();
			$("#ansSection"+secid).append(responseData);
			$("#collapse"+secid).collapse({"toggle:":true});
		tinymce.remove();
		tinymce.init({ selector:'textarea',plugins : 'image' });
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error get page data!! Try again");
          }
      });
	}


//pagination for myquestions
function showMyQuesPagination(currpage,lastpage,uid)
{
	$("#myQuesPages").empty();
	var end=currpage+2;
	var start=currpage-2;
	if(end>lastpage)
	{
		end=lastpage;
		}

	if(start<1)
	{
		start=1;
		}
	
	for(var i=start;i<=end;i++)
	{
		if(i==currpage)
		{
			$("#myQuesPages").append("<li class='active'><a href='javascript:openMyQuesPage("+i+","+uid+","+lastpage+")'>"+i+"</a></li>");
			}
		else
		{
			$("#myQuesPages").append("<li><a href='javascript:openMyQuesPage("+i+","+uid+","+lastpage+")'>"+i+"</a></li>");
		}
	}
}

function openMyQuesPage(pgno,uid,lastpage)
{
	var postData ="mypgno="+ pgno;
	$.ajax({
          type: "post",
          url: "services/MyQuestionPagination.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			location.reload();
			tinymce.remove();
			tinymce.init({ selector:'textarea',plugins : 'image' });
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error get page data!! Try again");
          }
      });
}

function showMyAnsPagination(secid,currpage,lastpage,qid)
{
	$("#myAnsPages"+secid).empty();
	var end=currpage+2;
	var start=currpage-2;
	if(end>lastpage)
	{
		end=lastpage;
		}

	if(start<1)
	{
		start=1;
		}

	
	for(var i=start;i<=end;i++)
	{
		if(i==currpage)
		{
			$("#myAnsPages"+secid).append("<li class='active'><a href='javascript:openMyAnsPage("+secid+","+i+","+qid+","+lastpage+")'>"+i+"</a></li>");
			}
		else
		{
			$("#myAnsPages"+secid).append("<li><a href='javascript:openMyAnsPage("+secid+","+i+","+qid+","+lastpage+")'>"+i+"</a></li>");
		}
	}
}

function openMyAnsPage(secid,pgno,qid,lastpage)
	{
	var postData ="qid="+ qid+"&pgno="+pgno+"&totalPages="+lastpage+"&secid="+secid;
	$.ajax({
          type: "post",
          url: "services/GetMyAnswers.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			$("#myansSection"+secid).empty();
			$("#myansSection"+secid).append(responseData);
			$("#mycollapse"+secid).collapse({"toggle:":true});
		tinymce.remove();
		tinymce.init({ selector:'textarea',plugins : 'image' });
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error get page data!! Try again");
          }
      });
	}

function clearTagQuestions()
{
	$("#clearTag").html("");
	$("#topQuesTag").val("");
	location.reload();
}

function showTagQuestions(uid,tagid,tagname)
{
	$("#clearTag").html(tagname+"<span onclick=\"clearTagQuestions()\"  class=\"badge\">x</span>");
	$("#topQuesTag").val(tagid);
	openTopQuesPage(1,uid,lastQuesPage);
}

function deleteQuestion(qid)
{
	var postData ="qid="+qid;
	$.ajax({
          type: "post",
          url: "services/DeleteQuestion.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
        	  openTopQuesPage(topQuesPage,userId,lastQuesPage);
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error in freezing!! Try again");
          }
      });
}

function deleteUrQuestion(qid)
{
	var postData ="qid="+qid;
	$.ajax({
          type: "post",
          url: "services/DeleteQuestion.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
        	  location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error in freezing!! Try again");
          }
      });
}

function editQuestion(qid,x)
{
$("#postEditTitle").val($("#myTitle"+x).text());
$("#updateQid").val(qid);
tinymce.get('postEditContent').getBody().innerHTML=$("#myDesc"+x).html();
//tinyMCE.DOM.setHTML("postEditContent",$("#myDesc"+x).text());
$("#myEditModal").modal("show");
$("#xValue").val(x);
}

function editYourQuestion(qid,x)
{
	$("#postEditTitle").val($("#urTitle"+x).text());
	$("#updateQid").val(qid);
	tinymce.get('postEditContent').getBody().innerHTML=$("#urDesc"+x).html();
	//tinyMCE.DOM.setHTML("postEditContent",$("#myDesc"+x).text());
	$("#myEditModal").modal("show");
	$("#xValue").val(x);
}

function updatePost()
{
var postData ="qid="+ $("#updateQid").val()+"&qContent="+tinymce.get('postEditContent').getBody().innerHTML+"&qTitle="+$("#postEditTitle").val();
	$.ajax({
          type: "post",
          url: "services/EditQuestion.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			$("#myEditModal").modal("hide");	
// 			$("#myTitle"+$("#xValue").val()).text($("#postEditTitle").val());
// 			$("#myDesc"+$("#xValue").val()).text(tinymce.get('postEditContent').getBody().innerHTML);
			$("#postEditTitle").val("");
			tinymce.get('postEditContent').getBody().innerHTML="";
			location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error get page data!! Try again");
          }
      });

}

function freezeQuestion(qid,freeze)
{
var postData ="qid="+ qid+"&freeze="+freeze;
	$.ajax({
          type: "post",
          url: "services/FreezeQuestion.php",
          data: postData,
          contentType: "application/x-www-form-urlencoded",
          success: function(responseData, textStatus, jqXHR) {
				location.reload();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error in freezing!! Try again");
          }
      });
}

function verifyGCaptcha()
{
	var resp=$("#gcOutput").text();
	//console.log(resp);
	 if(resp.indexOf("success\": true")<0)
	 {
		alert("Captcha Not verified");
		window.location="./logout.php";
		 }
	}

function addTag()
{
	if($( "#tags" ).val()=="")
	{
		$( "#tags" ).val($( "#myTags" ).val());
	}
	else
	{
		$( "#tags" ).val($( "#tags" ).val()+","+$( "#myTags" ).val());
		}
	$( "#myTags" ).val("");
}

$(function() {
var availableTags = ["Indian","Chinese","French","Greek","Italian","Thai","Mediterrian","American","Continental","Cuban","Mexican","Malaysian","Singapore","Spanish"];

$( "#myTags" ).autocomplete({
    source: availableTags
  });
$( "#ui-id-1").attr("style","z-index:1050");

/* satya code for user search */
$("body").click(function(){
	$("#searchResults").hide();
	$("#search-people").val("");
});


});

/* satya code for user search */
function peopleSearch()
{
	var MIN_LENGTH = 1;
	var keyword = $("#search-people").val();
			var data={};
			data["keyword"] = keyword;
			if (keyword.length >= MIN_LENGTH){
				$.ajax({
			          type: "post",
			          url: "services/SearchPeople.php",
			          data: data,
			          contentType: "application/x-www-form-urlencoded",
			          success: function(responseData, textStatus, jqXHR) {
				        
						    
						var persons = responseData.split("-");
						  var searchDiv = $("#searchResults");
						  var usersStr="";
						  for(var i=0; i<persons.length-1; i++){
							  usersStr+="<li><a href='./profile.php?uid="+persons[i].split("|")[1]+"'>"+persons[i].split("|")[0]+"</a></li>";  
			          		}
			          	
			          		searchDiv.html(usersStr);
			          		$('#searchResults').show();
			          },
			          error: function(jqXHR, textStatus, errorThrown) {
			              alert("No match found!");
			              console.log(jqXHR+":"+errorThrown);
			         	 }
			      });
			}
			else{
				$('#searchResults').hide();
			} 
} 
</script>
</head>
<body onload="showMyQuestions();">
	<nav class="navbar navbar-inverse"
		style="background-color: #4d636f; color: white;">
		<div class="container-fluid">
			<div class="navbar-header">
				<img class="navbar-brand" src='./images/FoodieLogo.png' alt="foodie"
					style="padding: 5px 10px;">
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="#" style="background-color: #3a4b53;margin-right: 2px;margin-left: 2px;">Top
						Questions</a></li>
				<li class="active" id='postLink' style="display: none; cursor: pointer;"><a style="background-color: #3a4b53;margin-right: 2px;margin-left: 2px;"
					data-toggle='modal' data-target='#myPostModal'>Post</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<!-- satya code for user search -->
				<li style="margin-top: 7px;"><input class="form-control" style="display: none; background-color:#dde2d9 ; color: black; position:relative" type="text" placeholder = "Search People" id="search-people" onkeyup="peopleSearch()" /></li>
				<li id="registerLink"><a data-toggle='modal' data-target='#regModal' style='cursor: pointer;'>Register</a></li>
				<li id='profileLink'
					style="display: none; cursor: pointer; color: white;"><a id="profileHref" href=""> Welcome,<?php echo $_SESSION["username"]; ?> </a></li>
				<li id='helpLink'><a href="Help.php">Help</a></li>
				<li id='loginLink'><a data-toggle='modal' data-target='#myModal'
					style='cursor: pointer;'>Login</a></li>
				<li id='logoutLink' style="display: none;"><a href="logout.php">Logout</a></li>
			</ul>
			<div id="searchResults" class="searchResults"></div>
		</div>
	</nav>

	<div id="aboutUs" style="min-height: 80vh; padding: 10px;">
		<img src='./images/aboutus.png' class="img-responsive" alt="about us">

		<div class="jumbotron" style="background-color: #dcdcdc;">
			"One stop to get all your food related questions answered by experts.
			Post your questions, get answers to your questions, choose the best
			answer, vote answers up/down, see related questions from other
			members, share your thoughts by answering the questions." <br> Our
			mission is to teach and inspire food lovers across the globe by
			sharing talent and knowledge.
		</div>
		
		<b>Top 5 Questions</b>
		<?php 
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
		$sql1 = "SELECT Q.qid,qtitle,qcontent,U.upic,U.uid,created_date,U.username,pic_pref,email,(select count(*) from question q where q.uid=U.uid and hide!=1) as totalquestions,(select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid) as score,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid and vote_ques=1) as votesup,(select count(*) from votes_ques where qid=Q.qid and vote_ques=-1) as votesdown,(select sum(vote_ques) from votes_ques where qid=Q.qid) as value FROM question Q,user U WHERE U.uid=Q.uid order by value desc limit 5";
		if(!$conn)
		{
			echo "error";
		}
		$rs1 = mysqli_query($conn,$sql1);
		$x = 0;
		while ( $row = mysqli_fetch_assoc ( $rs1 ) ) {
			$picurl="profiles/profile.png";
			if(!empty($row["upic"]))
			{
				$picurl=$row["upic"];
			}
			if($row["pic_pref"]==1)
			{
				$d = 'wavatar';
				$s = 80;
				$r = 'g';
			
				$picurl = "https://www.gravatar.com/avatar/";
				$picurl .= md5( strtolower( trim( $row["email"] ) ) );
				$picurl .= "?s=$s&d=$d&r=$r";
			}
			$postinfo = "<div class='w3-card-2 w3-hover-shadow' style='border-left: 4px solid #009688;' >
		<div class='row post'>
		<div class='col-sm-7'>
			<p class='title' style='cursor: hand;' data-toggle='modal' data-target='#myModal' >" . $row ["qtitle"] . "</p>
			<div>".$row["qcontent"]."</div>
		</div>
		<div class='col-sm-2'>
		Up: <span  class='badge'>".$row["votesup"]."</span>
		Down: <span class='badge'>".$row["votesdown"]."</span>
		Answers <a href='#'><span  class='badge'>" .$row["answers"]."</span></a>
		</div>
  		<div class='col-sm-3'><img src='".$picurl."' width='50' height='50'  class='img-circle img-responsive' alt='profilepic'><br>".$row ["username"]."[".$row["score"]."]</div>
  		</div>";
			$postinfo = $postinfo . "</div>";
			echo $postinfo;
			$x = $x + 1;
		}
		?>
	</div>
	<div class="row"
		style="margin-left: 0px; margin-right: 0px; min-height: 80vh;">
		<div class="col-sm-6 w3-card-2" id="topQuestionsSection">
		<div class="row" style="padding: 5px; margin:0px;background-color: #1b427;display:none;" id="topbar">
				<button id="clearTag" type="button" class="btn btn-info"><span onclick="clearTagQuestions()" class="badge">x</span></button>
				<input type="text" id="search-criteria" onkeydown="" />
				<button id="search" type="button" class="btn btn-default btn-sm" onclick="searchPosts()">
         		<img src="images/magnify.png" alt="magnify">
        		</button>
        		<button id="clearSearch" type="button" class="btn btn-default btn-sm" onclick="clearSearchResults()">
         		<img src="images/close-circle.png" alt="close">
        		</button>
        		<ul id="topQuesPages" class="pagination" style="display: inline;">
					  <li class="active"><a href="#" >1</a></li>
					  <li ><a href="#">2</a></li>
					  <li><a href="#">3</a></li>
					  <li><a href="#">4</a></li>
					  <li><a href="#">5</a></li>
				</ul>
				<input type="hidden" id="topQuesPageNo" value="" />
				<input type="hidden" id="topQuesTag" value="" />
        	<!-- <button id="sortTimeAesc" type="button" class="btn btn-default btn-sm" onclick="sortTopQuestions(1)">
         		<img src="images/sort-ascending.png" alt="ascending">
        		</button>
        		<button id="sortTimeDesc" type="button" class="btn btn-default btn-sm" onclick="sortTopQuestions(2)">
         		<img src="images/sort-descending.png" alt="descending">
        		</button>
        		<label class="radio-inline"><input type="radio" name="optradio" checked="checked">Date</label>
				<label class="radio-inline"><input type="radio" name="optradio">Score</label> -->	
		 </div>
		 <div id="topQuestionHolder">
<?php
echo $_POST["topQuesTag"];

function bbcode($bbcontent)
{
	if(strpos($bbcontent,'[b]') && strpos($bbcontent,'[/b]'))
	{
		$bbcontent=str_replace("[b]","<b>",$bbcontent);
		$bbcontent=str_replace("[/b]","</b>",$bbcontent);
	}
	if(strpos($bbcontent,'[i]') && strpos($bbcontent,'[/i]'))
	{
		$bbcontent=str_replace("[i]","<em>",$bbcontent);
		$bbcontent=str_replace("[/i]","</em>",$bbcontent);
	}
	return $bbcontent;
}

function apiRequest($url, $post=FALSE, $headers=array()) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if($post)
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	$headers[] = 'Accept: application/json';
	if(session('access_token'))
		$headers[] = 'Authorization: Bearer ' . session('access_token');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	return json_decode($response);
}
function get($key, $default=NULL) {
	return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
function session($key, $default=NULL) {
	return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

function showTopPosts($uid) {
	$pgno=$_SESSION["pgno"];
	$totalPages=0;
	if($pgno==null)
	{
		$pgno=1;
	}
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	$sqlcount="SELECT count(*) as count from question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid;
	$rs2 = mysqli_query($conn,$sqlcount);
	while ( $row = mysqli_fetch_assoc ( $rs2 ) ) {
		$totalPages=ceil($row["count"]/5);
	}
	
	echo "<script type='text/javascript'>showTopQuesPagination(".$pgno.",".$totalPages.",".$uid.");</script>";
	$sql1 = "SELECT Q.qid,qtitle,qcontent,freeze,U.upic,U.uid,created_date,U.username,pic_pref,email,(select count(*) from question q where q.uid=U.uid and hide!=1) as totalquestions,(select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid) as score,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid and vote_ques=1) as votesup,(select count(*) from votes_ques where qid=Q.qid and vote_ques=-1) as votesdown,(select sum(vote_ques) from votes_ques where qid=Q.qid) as value FROM question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid." order by value desc LIMIT ".(($pgno-1)*5).",5";
	if(!$conn)
	{
		echo "error";
	}
	$rs1 = mysqli_query($conn,$sql1);
	$x = 0;
	while ( $row = mysqli_fetch_assoc ( $rs1 ) ) {
		$picurl="profiles/profile.png";
		if(!empty($row["upic"]))
		{
			$picurl=$row["upic"];
		}
		if($row["pic_pref"]==1)
		{
			$d = 'wavatar';
			$s = 80;
			$r = 'g';
				
			$picurl = "https://www.gravatar.com/avatar/";
			$picurl .= md5( strtolower( trim( $row["email"] ) ) );
			$picurl .= "?s=$s&d=$d&r=$r";
		}
		$postinfo = "<div class='w3-card-2 w3-hover-shadow' style='border-left: 4px solid #009688;' >
		<div class='row post top-posts'>
		<div class='col-sm-7'>
			<p id='myTitle".($x + 1)."' class='title' style='cursor: hand;' data-toggle='collapse' data-target=#collapse" . ($x + 1) . ">" . $row ["qtitle"] . "</p>
				<div id='myDesc".($x + 1)."'>".bbcode($row["qcontent"])."</div>";
					
				$sqltags="select * from tags where tag_id in (SELECT tag_id_fk FROM `question_tag` WHERE qid_fk=".$row["qid"].")";
				$rstags = mysqli_query($conn,$sqltags);
					while ( $rsrow = mysqli_fetch_assoc ( $rstags ) ) {
						$postinfo = $postinfo ."<a href='javascript:showTagQuestions(".$uid.",".$rsrow["tag_id"].",\"".$rsrow["tags"]."\")' style='background-color: #5bc0de;color:#ffffff;padding: 5px;margin-right:5px;'>".$rsrow["tags"]."</a>";
					}
								
		if(!empty($_SESSION["role"]))
		{
			if($_SESSION["role"]==1)
			{
				$freezeValue=0;
				if($row["freeze"]==0)
				{
					$freezeValue=1;
				}
			$postinfo	=	$postinfo."<button id='deleteBtn' type='button' class='btn btn-default btn-sm' onclick='deleteQuestion(".$row["qid"].")'><img src='images/close-circle.png'></button><button id='editBtn' type='button' class='btn btn-default btn-sm' onclick='editQuestion(".$row["qid"].",".($x + 1).")'><img src='images/edit-button.png'></button><button id='freezeBtn' type='button' class='btn btn-default btn-sm' onclick='freezeQuestion(".$row["qid"].",".$freezeValue.")'><img src='images/freeze-image.png'></button>";
			}
		}
		$postinfo =	$postinfo."</div>
		<div class='col-sm-2'>
		Up: <span id='qVoteUp".$row["qid"]."' class='badge'>".$row["votesup"]."</span>
		Down: <span id='qVoteDown".$row["qid"]."' class='badge'>".$row["votesdown"]."</span>
		Answers <a href='#'><span id='qanswers".$row["qid"]."' class='badge'>" .$row["answers"]."</span></a>
		</div>
  		<div class='col-sm-3'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'' ><!--<p style='word-wrap: break-word;'>Posted by:<br>--><p style='padding: 5px;'>".$row ["username"]." [Score:".$row ["score"]."<span class='adminonly'>,Total Ques:".$row["totalquestions"]."</span>] </p><p style='font-size: 12px;color: #0096e1;font-weight: bold;'>".$row ["created_date"]."</p></div>
  		</div><div id='ansSection".($x + 1)."'>";
			
		$anspages=0;
		$sqlanscount="SELECT count(*) as count from answers A WHERE A.qid=".$row["qid"];
		$rsans = mysqli_query($conn,$sqlanscount);
		while ( $countrow = mysqli_fetch_assoc ( $rsans ) ) {
			$anspages=ceil($countrow["count"]/5);
		}
		
		$postinfo =	$postinfo."<div id='collapse".($x + 1) ."' class='post-footer collapse'><div class='list-group'><div class='list-group-item row' style='margin:0px;'><a href='javascript:voteQuestion(1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-up.png'></a>
			<a href='javascript:voteQuestion(-1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-down.png' ></a><ul id='topAnsPages".($x + 1)."' class='pagination' style='display: inline;'></ul></div>";
		
		$sql2="SELECT A.aid,A.adesc,U.upic,U.username,pic_pref,email,IFNULL((select count(*) from question q where q.uid=U.uid and hide!=1),0) as totalquestions,IFNULL((select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid),0) as score,A.best_ans,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$row["qid"]." order by A.best_ans desc,value desc limit 0,5";
		$rs2 = mysqli_query($conn,$sql2);
		$bestansid=0;
		$bestrow="";
		while($arow= mysqli_fetch_assoc ( $rs2 ))
		{
			if($arow["best_ans"]==1)
			{
				$bestansid=$arow["aid"];
				$bestrow=$arow;
				
				$picurl="profiles/profile.png";
				if(!empty($arow["upic"]))
				{
					$picurl=$arow["upic"];
				}
				if($arow["pic_pref"]==1)
				{
					$d = 'wavatar';
					$s = 80;
					$r = 'g';
						
					$picurl = "https://www.gravatar.com/avatar/";
					$picurl .= md5( strtolower( trim($arow["email"] ) ) );
					$picurl .= "?s=$s&d=$d&r=$r";
				}
				$bestrow["upic"]=$picurl;
			}
		}
		mysqli_data_seek($rs2,0);
		$y = 0;
		if($bestansid>0)
		{
			$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".html_entity_decode($bestrow["adesc"])."</div><div class='col-sm-2'><img src='".$bestrow["upic"]."' width='50px' height='50px'  class='img-circle img-responsive'' ><b>".$bestrow["username"]."[".$bestrow["score"]."<span class='adminonly'>,".$bestrow["totalquestions"]."</span>]</b></div><div class='col-sm-1'><span id='qAnsUp".$bestrow["aid"]."'>".$bestrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$bestrow["aid"].",".$row["qid"].")'></div><div class='col-sm-1' style='cursor:hand;'><span id='qAnsDown".$bestrow["aid"]."'>".$bestrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$bestrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div><div class='col-sm-2'><img  class='img-responsive' width='24px' height='24px' src='./images/bestans.png' ></div></div>";
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
						$picurl .= md5( strtolower( trim($ansrow["email"] ) ) );
						$picurl .= "?s=$s&d=$d&r=$r";
					}
					$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".html_entity_decode($ansrow["adesc"])."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'' ><b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><div class='col-sm-1'><span id='qAnsUp".$ansrow["aid"]."'>".$ansrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$ansrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div><div class='col-sm-1'><span id='qAnsDown".$bestrow["aid"]."'>".$ansrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$ansrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div></div>";
				}
			}
		
		if($row["freeze"]==0)
		{
		$postinfo = $postinfo . "<div class='list-group-item'><label for='Answer'>Comment:</label><textarea class='form-control' rows='5' id='comment".($x + 1)."' onclick='event.stopPropagation()'></textarea><input type='button' value='Submit' onclick='saveAnswer(1,".($x+1).",".$row["qid"].")'></div>";
		}

		$postinfo = $postinfo . "</div></div></div></div>";
		echo $postinfo;
		echo "<script type='text/javascript'>showTopAnsPagination(".($x + 1).",1,".$anspages.",".$row["qid"].");</script>";
		$y = $y + 1;
		$x = $x + 1;
	}
	mysqli_close($conn);
}
// user login code
if ($_SERVER ['REQUEST_METHOD'] == "POST") {

	if(empty($_SESSION["userprofile"]) && $_SESSION["loginstatus"]!="success")
	{
		echo "<span id='gcOutput' style='display:none'>";
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$fields = array(
			'secret' => urlencode("6LfB0g0UAAAAAKU5Anvre3uYnFth40Yn8QRVCW57"),
			'response' => urlencode($_POST['g-recaptcha-response']),
			'remoteip' => urlencode($_SERVER['REMOTE_ADDR'])
		);
		
		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		
		//execute post
		
		$result = curl_exec($ch);
		echo "</span>";
		
		echo "<script>verifyGCaptcha();</script>";
		//close connection
		curl_close($ch);
	}


	if(empty($_SESSION["userprofile"]))
	{	
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
		$uname = "";
		$pwd = "";
		if (empty ( $_POST ["email"] )) {
			$nameErr = "username is required";
		} else {
			$uname = escapeStr ( $conn,$_POST ["email"] );
		}
		$pwd = escapeStr ($conn,$_POST ["pwd"] );
		$sql = "SELECT * FROM user WHERE username='" . $_POST ["email"] . "' and password='" . $pwd . "'";
		$uid = 0;
		$rs = mysqli_query ( $conn,$sql );
		while ( $row = mysqli_fetch_assoc ( $rs ) ) {
			$uid = $row ["uid"];
			$_SESSION["username"]=$row["username"];
			$_SESSION["uid"]=$row["uid"];
			$_SESSION["role"]=$row["role"];
			$_SESSION["loginstatus"]="success";
			echo "<script type='text/javascript'>uname=" . $uid . ";showLogout();</script>";
			showTopPosts($uid);
			echo "<script type='text/javascript'>document.getElementById('profileHref').href='./profile.php?uid=". $uid . "';</script>";
		}
	
		mysqli_close($conn);
		
		if ($uid == 0) {
			//try github authentication
			echo "<script type='text/javascript'>alert('Username or password doesnt match');</script>";
		}
	}
}

if(!empty($_SESSION["username"]) && $_SERVER ['REQUEST_METHOD'] != "POST")
{
	$uid =$_SESSION["uid"];
	echo "<script type='text/javascript'>uname=" . $_SESSION["uid"] . ";showLogout();</script>";
	showTopPosts($_SESSION["uid"]);
	echo "<script type='text/javascript'>document.getElementById('profileHref').href='./profile.php?uid=". $uid . "';</script>";
}
else if(!empty($_SESSION["userprofile"])  && $_SERVER ['REQUEST_METHOD'] == "POST")
{
	$uid =$_SESSION["uid"];
	echo "<script type='text/javascript'>uname=" . $_SESSION["uid"] . ";showLogout();</script>";
	showTopPosts($_SESSION["uid"]);
	echo "<script type='text/javascript'>document.getElementById('profileHref').href='./profile.php?uid=". $uid . "';</script>";
}

?>
</div>
</div>
		<div class="col-sm-6">
		 <div class="row" style="padding-bottom: 10px;">
		 <ul id="myQuesPages" class="pagination" style="display: inline;">
					  <li class="active"><a href="#" >1</a></li>
					  <li ><a href="#">2</a></li>
					  <li><a href="#">3</a></li>
					  <li><a href="#">4</a></li>
					  <li><a href="#">5</a></li>
				</ul>
		 </div>
			<div class="row">
				<div id="myQuesSection" style="opacity: 0;" class="panel panel-info">
					<div class="panel-heading">My Questions</div>
					<div id="myQuesHolder" class="panel-body"
						style="height: 60vh; overflow: scroll;">
					<?php
					function showMyPosts()
					{
						$pgno=$_SESSION["mypgno"];
						if($pgno==null)
						{
							$pgno=1;
						}
						$uid=$_SESSION["uid"];
						if ($uid != 0) {
							$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
							OR die ('Could not connect to MySQL: '.mysql_error());
							
							$sqlcount="SELECT count(*) as count from question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid=".$uid;
							$rs2 = mysqli_query($conn,$sqlcount);
							while ( $row = mysqli_fetch_assoc ( $rs2 ) ) {
								$totalPages=ceil($row["count"]/5);
							}
							
							echo "<script type='text/javascript'>showMyQuesPagination(".$pgno.",".$totalPages.",".$uid.");</script>";
							
							$sql = "SELECT Q.qid,qtitle,qcontent,freeze,U.upic,U.uid,created_date,U.username,pic_pref,email,(select count(*) from question q where q.uid=U.uid and hide!=1) as totalquestions,(select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid) as score,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid) as votes,IFNULL((select sum(vote_ques) from votes_ques where qid=Q.qid),0) as value FROM question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid=".$uid." order by value desc LIMIT ".(($pgno-1)*5).",5";
							$rs = mysqli_query ($conn,$sql );					
							$x = 0;
							while ( $row = mysqli_fetch_assoc ( $rs ) ) {
								$picurl="profiles/profile.png";
								if(!empty($row["upic"]))
								{
									$picurl=$row["upic"];
								}
								if($row["pic_pref"]==1)
								{
									$d = 'wavatar';
									$s = 80;
									$r = 'g';
									
									$picurl = "https://www.gravatar.com/avatar/";
									$picurl .= md5( strtolower( trim( $row["email"] ) ) );
									$picurl .= "?s=$s&d=$d&r=$r";
								}
								$postinfo = "<div class='w3-card-2 w3-hover-shadow' style='border-left: 4px solid #009688;'><div class='row post'>
								<div class='col-sm-7'>
									<p id='urTitle".($x + 1)."' class='title' style='cursor: hand;' data-toggle='collapse' data-target='#mycollapse" . ($x + 1) . "'>" . $row ["qtitle"] . "</p>
									<div id='urDesc".($x + 1)."'>".bbcode($row["qcontent"])."</div>";
						
							$sqltags="select * from tags where tag_id in (SELECT tag_id_fk FROM `question_tag` WHERE qid_fk=".$row["qid"].")";
							$rstags = mysqli_query($conn,$sqltags);
							while ( $rsrow = mysqli_fetch_assoc ( $rstags ) ) {
									$postinfo = $postinfo ."<a href='javascript:showTagQuestions(".$uid.",".$rsrow["tag_id"].",\"".$rsrow["tags"]."\")' style='background-color: #5bc0de;color:#ffffff;padding: 5px;margin-right:5px;'>".$rsrow["tags"]."</a>";
								}
								
								if(!empty($_SESSION["role"]))
								{
									if($_SESSION["role"]==1)
									{
										$freezeValue=0;
										if($row["freeze"]==0)
										{
											$freezeValue=1;
										}
										$postinfo	=	$postinfo."<button id='deleteBtn' type='button' class='btn btn-default btn-sm' onclick='deleteUrQuestion(".$row["qid"].")'><img src='images/close-circle.png'></button><button id='editBtn' type='button' class='btn btn-default btn-sm' onclick='editYourQuestion(".$row["qid"].",".($x + 1).")'><img src='images/edit-button.png'></button><button id='freezeBtn' type='button' class='btn btn-default btn-sm' onclick='freezeQuestion(".$row["qid"].",".$freezeValue.")'><img src='images/freeze-image.png'></button>";
									}
								}
								
								$anspages=0;
								$sqlanscount="SELECT count(*) as count from answers A WHERE A.qid=".$row["qid"];
								$rsans = mysqli_query($conn,$sqlanscount);
								while ( $countrow = mysqli_fetch_assoc ( $rsans ) ) {
									$anspages=ceil($countrow["count"]/5);
								}
								
								$bestMark=0;
								$sql="SELECT count(*) as count from answers A WHERE A.qid=".$questionId." and best_ans=1";
								$bestrs = mysqli_query($conn,$sql);
								while ( $countrow = mysqli_fetch_assoc ( $bestrs ) ) {
									$anspages=$countrow["count"];
									if($anspages>=1)
									{
										$bestMark=1;
									}
								}
						
								$postinfo = $postinfo ."</div>
								<div class='col-sm-2'>
								Votes <a href='#'><span class='badge'>".$row["value"]."</span></a>
								Answers <a href='#'><span class='badge'>" .$row["answers"]."</span></a></div>
						  		<div class='col-sm-3'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'' ><br>".$row ["username"]."[".$row["score"]."<span class='adminonly'>,".$row["totalquestions"]."</span>]</div>
						  		</div><div id='myansSection".($x + 1)."'>
								<div id='mycollapse" . ($x + 1) . "' class='post-footer collapse'><div class='list-group'><div class='list-group-item row' style='margin:0px;'><ul id='myAnsPages".($x + 1)."' class='pagination' style='display: inline;'></ul></div>";
						
								$sql2="SELECT A.aid,A.adesc,U.upic,U.username,A.best_ans,pic_pref,email,(select count(*) from question q where q.uid=U.uid and hide!=1) as totalquestions,(select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid) as score,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$row["qid"]." order by A.best_ans desc,value desc limit 0,5";
								$rs2 = mysqli_query($conn,$sql2);
								$bestansid=0;
								$bestrow="";
								while($arow= mysqli_fetch_assoc ( $rs2 ))
								{
									if($arow["best_ans"]==1)
									{
										$bestansid=$arow["aid"];
										$bestrow=$arow;
										$picurl="profiles/profile.png";
										if(!empty($ansrow["upic"]))
										{
											$picurl=$ansrow["upic"];
										}
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
									$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$bestrow["adesc"]."</div><div class='col-sm-2'><img src='".$bestrow["upic"]."' width='50px' height='50px'  class='img-circle img-responsive' ><b>".$bestrow["username"]."[".$bestrow["score"]."<span class='adminonly'>,".$bestrow["totalquestions"]."</span>]</b></div><a href='#'class='col-sm-1'>".$bestrow["upvotes"]."<img width='24px' height='24px' src='./images/thumb-up-outline.png' ></a><a href='#' class='col-sm-1'>".$bestrow["downvotes"]."<img width='24px' height='24px' src='./images/thumb-down-outline.png' ></a><div class='col-sm-2'><img  class='img-responsive' width='24px' height='24px' src='./images/bestans.png' ></div></div>";
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
											$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'> <b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><a href='#' class='col-sm-1'>".$ansrow["upvotes"]."<img width='24px' height='24px' src='./images/thumb-up-outline.png' ></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24px' height='24px' src='./images/thumb-down-outline.png' ></a></div>";
										}
										else
										{
											if($row["freeze"]==0)
											{
												if($bestMark==0)
												{
											$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive' ><b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><a href='#'class='col-sm-1'>".$ansrow["upvotes"]."<img width='24px' height='24px' src='./images/thumb-up-outline.png' ></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24px' height='24px' src='./images/thumb-down-outline.png' ></a><div class='col-sm-2'><button onclick='submitBestAns(".$ansrow["aid"].")'>Mark</button></div></div>";
												}
												else {
													$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive' ><b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><a href='#'class='col-sm-1'>".$ansrow["upvotes"]."<img width='24px' height='24px' src='./images/thumb-up-outline.png' ></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24px' height='24px' src='./images/thumb-down-outline.png' ></a><div class='col-sm-2'></div></div>";
												}
												}
											else {
											$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive' ><b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><a href='#'class='col-sm-1'>".$ansrow["upvotes"]."<img width='24px' height='24px' src='./images/thumb-up-outline.png' ></a><a href='#' class='col-sm-1'>".$ansrow["downvotes"]."<img width='24px' height='24px' src='./images/thumb-down-outline.png' ></a><div class='col-sm-2'></div></div>";
											}
										}
									}
								}
								if($row["freeze"]==0)
								{
								$postinfo = $postinfo . "<div class='list-group-item'><label for='Answer'>Comment:</label><textarea class='form-control' rows='5' id='mycomment".($x + 1)."' onclick='event.stopPropagation()'></textarea><input type='button' value='Submit' onclick='saveAnswer(2,".($x+1).",".$row["qid"].")'></div>";
								}
								$postinfo = $postinfo . "</div></div></div></div>";
								echo $postinfo;
								echo "<script type='text/javascript'>showMyAnsPagination(".($x + 1).",1,".$anspages.",".$row["qid"].");</script>";
								$y = $y + 1;
								$x = $x + 1;
							}
						}
					}
					
					if ($_SERVER ['REQUEST_METHOD'] == "POST") {
						showMyPosts();
					}
					if(!empty($_SESSION["username"]) && $_SERVER ['REQUEST_METHOD'] != "POST")
					{
						showMyPosts();
					}
					?>
					</div>

				</div>
			</div>
			<div class="row">
				<div id="recommendationPanel" class="panel panel-info" style="display: block;">
					<div class="panel-heading">Recommendations</div>
					<div class="panel-body" id="recommendationSection" style="height: auto;">
						<div></div>
					</div>
				</div>
			</div>

		</div>
		
		<!-- Satya: Modal for user registration: -->	
	
		<div class="modal fade" id="regModal" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Create your Foodie account</h4>
					</div>
					<div class="modal-body">
					       	<div class="form-group">
								<label for="email">User Name:<span style="color: red;">*</span></label> <input type="text"
									class="form-control" name="newUserId" id="newUserId" required>
							</div>
							<div class="form-group">
								<label for="email">Email address<span style="color: red;">*</span></label> <input type="text"
									class="form-control" name="newUseremail" id="newUseremail" required>
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

		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Login Details</h4>
					</div>
					<div class="modal-body">
						<form
							action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
							method="post">
							<div class="form-group">
								<label for="email">Email address:</label> <input type="text"
									class="form-control" name="email" id="email"><span
									class="error">* <?php echo $emailError;?></span>
							</div>
							<div class="form-group">
								<label for="pwd">Password:</label> <input type="password"
									class="form-control" name="pwd" id="pwd"><span class="error">* <?php echo $pwdError;?></span>
							</div>
							<div class="checkbox">
								<label><input type="checkbox"> Remember me</label>
							</div>
							<div class="g-recaptcha" data-sitekey="6LfB0g0UAAAAAF_C8Kipa4HHwJy5UxrHi80ObYkW"></div>
							<button type="submit" class="btn btn-default">Submit</button>
							<!--<a href="https://github.com/login/oauth/authorize?client_id=37f1064c2b0a36df69dd&redirect_uri=http://qav2.cs.odu.edu/kumar/KumarCS518/login.php&scope=user:email/" class="btn btn-primary">GITHUB Login</a> -->
							 <a href="https://github.com/login/oauth/authorize?client_id=3cb6030696f66392a6c1&redirect_uri=http://kkallepalli.cs518.cs.odu.edu/login.php&scope=user:email/" class="btn btn-primary">GITHUB Login</a>
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>

	</div>

	<div class="modal fade" id="myPostModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Post Details</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="postTitle">Title:</label> <input type="text"
							class="form-control" name="postTitle" id="postTitle">
					</div>
					<div class="form-group">
						<label for="postContent">Content:</label>
						<textarea class="form-control" rows="5" id="postContent"
							name="postContent"></textarea>
					</div>
					<div class="form-group">
						<label for="postContent">Tags:</label>
						<input id="tags" disabled="disabled">
						<input id="myTags"><button type="button" onclick="addTag()">Add</button>
					</div>
					<button class="btn btn-default" onclick="createPost()">Submit</button>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="myEditModal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Post Details</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="postEditTitle">Title:</label> <input type="text"
							class="form-control" name="postEditTitle" id="postEditTitle">
					</div>
					<div class="form-group">
						<label for="postEditContent">Content:</label>
						<textarea class="form-control" rows="5" id="postEditContent"
							name="postEditContent"></textarea>
					</div>
					<div class="form-group">
						<label for="postEditContent">Tags:</label>
						<input id="tags1">
					</div>
					<input type="hidden" id="updateQid" value="" />
					<input type="hidden" id="xValue" value=""/>
					<button class="btn btn-default" onclick="updatePost()">Submit</button>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	
	<iframe id="form_target" name="form_target" style="display:none"></iframe>

	<form id="my_form" action="./upload.php" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
	<input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
	<input type="hidden" id="mceUpload" name="mceUpload" value="" />
	</form>


	<div class="container footer-line">
		<p>Project for CS518 - Developed by Kumar,Surabhi,Satya - 2016</p>
	</div>
	<?php 
	if(!empty($_SESSION["uid"]))
	{
		if(!empty($_SESSION["role"]))
		{
			if($_SESSION["role"]==1)
			{
				echo "<script>$('.adminonly').show();</script>";
			}
			else {
				echo "<script>$('.adminonly').hide();</script>";
			}
		}
		else {
			echo "<script>$('.adminonly').hide();</script>";
		}
	}
	
	?>
</body>
</html>
