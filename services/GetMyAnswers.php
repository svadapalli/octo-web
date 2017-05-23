<?php
include("../connectDB.php");
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());

$questionId=$_POST["qid"];
$pgno=$_POST["pgno"];
$secid=$_POST["secid"];

$qfreeze=0;
$sqlfreeze="SELECT freeze from question WHERE qid=".$questionId;
$rsfreeze = mysqli_query($conn,$sqlfreeze);
while ( $frow = mysqli_fetch_assoc ( $rsfreeze ) ) {
	$qfreeze=$frow["freeze"];
}

$anspages=0;
$sqlanscount="SELECT count(*) as count from answers A WHERE A.qid=".$questionId;
$rsans = mysqli_query($conn,$sqlanscount);
while ( $countrow = mysqli_fetch_assoc ( $rsans ) ) {
	$anspages=ceil($countrow["count"]/5);
}

$bestMark=0;
$sql="SELECT count(*) as count from answers A WHERE A.qid=".$questionId." and best_ans=1";
$rs = mysqli_query($conn,$sql);
while ( $countrow = mysqli_fetch_assoc ( $rs ) ) {
	$bestpages=$countrow["count"];
	if($bestpages>=1)
	{
		$bestMark=1;
	}
}

$postinfo = $postinfo ."<div id='mycollapse" . ($x + 1) . "' class='post-footer collapse'><div class='list-group'><div class='list-group-item row' style='margin:0px;'><ul id='myAnsPages".($x + 1)."' class='pagination' style='display: inline;'></ul></div>";

$sql2="SELECT A.aid,A.adesc,U.upic,U.username,A.best_ans,pic_pref,email,(select count(*) from question q where q.uid=U.uid and hide!=1) as totalquestions,(select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid) as score,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$questionId." order by A.best_ans desc,value desc limit ".(($pgno-1)*5).",5";
$rs2 = mysqli_query($conn,$sql2);
$bestansid=0;
$bestrow="";
while($arow= mysqli_fetch_assoc ( $rs2 ))
{
	$picurl="profiles/profile.png";
	if(!empty($ansrow["upic"]))
	{
		$picurl=$ansrow["upic"];
		if($arow["best_ans"]==1)
		{
			$bestansid=$arow["aid"];
			$bestrow=$arow;
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
if($qfreeze==0)
{
	$postinfo = $postinfo . "<div class='list-group-item'><label for='Answer'>Comment:</label><textarea class='form-control' rows='5' id='mycomment".($x + 1)."' onclick='event.stopPropagation()'></textarea><input type='button' value='Submit' onclick='saveAnswer(2,".($x+1).",".$questionId.")'></div>";
}
$postinfo = $postinfo . "</div>";
echo $postinfo;
echo "<script type='text/javascript'>showMyAnsPagination(".$secid.",".$pgno.",".$anspages.",".$questionId.");</script>";
?>