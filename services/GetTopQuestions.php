<?php
session_start ();
include("../connectDB.php");
$uid=$_POST["uid"];
$pgno=$_POST["pgno"];
$totalPages=$_POST["totalPages"];
$tagid=$_POST["tagid"];
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysql_error());

if(!empty($tagid))
{
	$sqlcount="SELECT count(*) as count from question Q,user U,question_tag t WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid." and t.qid_fk=Q.qid and t.tag_id_fk=".$tagid;
}
else {
	$sqlcount="SELECT count(*) as count from question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid;
}
$rs2 = mysqli_query($conn,$sqlcount);
while ( $row = mysqli_fetch_assoc ( $rs2 ) ) {
	$totalPages=ceil($row["count"]/5);
}

echo "<script type='text/javascript'>showTopQuesPagination(".$pgno.",".$totalPages.",".$uid.");</script>";
	if(!empty($tagid))
	{
		$sql1 = "SELECT Q.qid,qtitle,qcontent,freeze,U.upic,U.uid,created_date,U.username,IFNULL((select count(*) from question q where q.uid=U.uid and hide!=1),0) as totalquestions,IFNULL((select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid),0) as score,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid and vote_ques=1) as votesup,(select count(*) from votes_ques where qid=Q.qid and vote_ques=-1) as votesdown,(select sum(vote_ques) from votes_ques where qid=Q.qid) as value FROM question Q,user U,question_tag t WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid." and t.qid_fk=Q.qid and t.tag_id_fk=".$tagid." order by value desc LIMIT ".(($pgno-1)*5).",5";
	}
	else {
	$sql1 = "SELECT Q.qid,qtitle,qcontent,freeze,U.upic,U.uid,created_date,U.username,pic_pref,email,IFNULL((select count(*) from question q where q.uid=U.uid and hide!=1),0) as totalquestions,IFNULL((select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid),0) as score,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid and vote_ques=1) as votesup,(select count(*) from votes_ques where qid=Q.qid and vote_ques=-1) as votesdown,(select sum(vote_ques) from votes_ques where qid=Q.qid) as value FROM question Q,user U WHERE U.uid=Q.uid and Q.hide!=1 and U.uid!=".$uid." order by value desc LIMIT ".(($pgno-1)*5).",5";
	}
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
			<div id='myDesc".($x + 1)."'>".$row["qcontent"]."</div>";
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
  		<div class='col-sm-3'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'' ><!--<p style='word-wrap: break-word;'>Posted by:<br>--><p style='padding: 5px;'>".$row ["username"]." [Score:".$row ["score"]."<span class='adminonly'>,Total Ques:".$row["totalquestions"]."</span>]</p><p style='font-size: 12px;color: #0096e1;font-weight: bold;'>".$row ["created_date"]."</p></div>
  		</div><div id='ansSection".($x + 1)."'>";
			
		$anspages=0;
		$sqlanscount="SELECT count(*) as count from answers A WHERE A.qid=".$row["qid"];
		$rsans = mysqli_query($conn,$sqlanscount);
		while ( $countrow = mysqli_fetch_assoc ( $rsans ) ) {
			$anspages=ceil($countrow["count"]/5);
		}
		
		$postinfo =	$postinfo."<div id='collapse".($x + 1) ."' class='post-footer collapse'><div class='list-group'><div class='list-group-item row' style='margin:0px;'><a href='javascript:voteQuestion(1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-up.png'></a>
			<a href='javascript:voteQuestion(-1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-down.png' ></a><ul id='topAnsPages".($x + 1)."' class='pagination' style='display: inline;'></ul></div>";
		
		$sql2="SELECT A.aid,A.adesc,U.upic,U.username,pic_pref,email,IFNULL((select count(*) from question q where q.uid=U.uid and hide!=1),0) as totalquestions,IFNULL((select sum(vote_ques) from user u,question q,votes_ques v where u.uid=q.uid and q.qid=v.qid and u.uid=U.uid),0) as score,A.best_ans,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$row["qid"]." order by  A.best_ans desc,value desc limit 0,5";
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
			$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".htmlspecialchars_decode($bestrow["adesc"])."</div><div class='col-sm-2'><img src='".$bestrow["upic"]."' width='50px' height='50px'  class='img-circle img-responsive'' ><b>".$bestrow["username"]."[".$bestrow["score"]."<span class='adminonly'>,".$bestrow["totalquestions"]."</span>]</b></div><div class='col-sm-1'><span id='qAnsUp".$bestrow["aid"]."'>".$bestrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$bestrow["aid"].",".$row["qid"].")'></div><div class='col-sm-1' style='cursor:hand;'><span id='qAnsDown".$bestrow["aid"]."'>".$bestrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$bestrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div><div class='col-sm-2'><img  class='img-responsive' width='24px' height='24px' src='./images/bestans.png' ></div></div>";
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
					$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".htmlspecialchars_decode($ansrow["adesc"])."</div><div class='col-sm-2'><img src='".$picurl."' width='50px' height='50px'  class='img-circle img-responsive'' ><b>".$ansrow["username"]."[".$ansrow["score"]."<span class='adminonly'>,".$ansrow["totalquestions"]."</span>]</b></div><div class='col-sm-1'><span id='qAnsUp".$ansrow["aid"]."'>".$ansrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$ansrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div><div class='col-sm-1'><span id='qAnsDown".$bestrow["aid"]."'>".$ansrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$ansrow["aid"].",".$row["qid"].")' style='cursor:hand;'></div></div>";
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
?>