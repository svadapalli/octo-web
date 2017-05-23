<?php
include("../connectDB.php");
$conn = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME ) or die ( 'Could not connect to MySQL: ' . mysql_error () );

$uid = $_REQUEST ["uid"];
$eventtype = $_REQUEST ["event"];
if ($eventtype == 1) {
	$qid = $_REQUEST ["qid"];
	// answering a question
	$sql = "SELECT Q.qid,qtitle,qcontent,U.uid,created_date,U.username,(select count(*) from answers where qid=Q.qid) as answers,(select count(*) from votes_ques where qid=Q.qid and vote_ques=1) as votesup,(select count(*) from votes_ques where qid=Q.qid and vote_ques=-1) as votesdown,(select sum(vote_ques) from votes_ques where qid=Q.qid) as value FROM question Q,user U WHERE U.uid=Q.uid and U.uid!=" . $uid . " and Q.qid in (select distinct(qid_fk) from question_tag where tag_id_fk in (select tag_id_fk from question_tag where qid_fk=".$qid.") and qid_fk!=".$qid.")  and Q.qid not in(select qid from answers where uid_ans=" . $uid . ")";
	$rs1 = mysqli_query($conn,$sql);
	$x = 0;
	while ( $row = mysqli_fetch_assoc ( $rs1 ) ) {
		$postinfo = "<div class='w3-card-2 w3-hover-shadow' style='border-left: 4px solid #009688;' >
		<div class='row post'>
		<div class='col-sm-7'>
			<p class='title' style='cursor: hand;' data-toggle='collapse' data-target='#reccollapse" . ($x + 1) . "' >" . $row ["qtitle"] . "</p>
			<p id='myDesc".($x + 1)."'>".$row["qcontent"]."</p>";
				
				$sqltags="select * from tags where tag_id in (SELECT tag_id_fk FROM `question_tag` WHERE qid_fk=".$row["qid"].")";
				$rstags = mysqli_query($conn,$sqltags);
					while ( $rsrow = mysqli_fetch_assoc ( $rstags ) ) {
						$postinfo = $postinfo ."<a href='javascript:showTagQuestions(".$uid.",".$rsrow["tag_id"].",\"".$rsrow["tags"]."\")' style='background-color: #5bc0de;color:#ffffff;padding: 5px;margin-right:5px;'>".$rsrow["tags"]."</a>";
					}
					
		$postinfo =	$postinfo."</div>
		<div class='col-sm-2'>
		Up: <span id='qVoteUp".$row["qid"]."' class='badge'>".$row["votesup"]."</span>
		Down: <span id='qVoteDown".$row["qid"]."' class='badge'>".$row["votesdown"]."</span>
		Answers <a href='#'><span id='qanswers".$row["qid"]."' class='badge'>" .$row["answers"]."</span></a>
	
		</div>
  		<div class='col-sm-3'><p style='word-wrap: break-word;'>Posted by:<br>".$row ["username"]."</p><p style='font-size: 12px;color: #0096e1;font-weight: bold;'>".$row ["created_date"]."</p></div>
  		</div>
		<div id='reccollapse".($x + 1) ."' class='post-footer collapse'><div class='list-group'><div class='list-group-item row' style='margin:0px;'><a href='javascript:voteQuestion(1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-up.png'></a>
			<a href='javascript:voteQuestion(-1,".$row["qid"].")'><img width='24px' height='24px' src='./images/ques-down.png' ></a></div>";
		$sql2="SELECT A.aid,A.adesc,U.username,A.best_ans,(select count(*) from votes_ans where aid=A.aid and vote_ans=1) as upvotes,(select count(*) from votes_ans where aid=A.aid and vote_ans=-1) as downvotes,IFNULL((select sum(vote_ans) from votes_ans where aid=A.aid),0) as value FROM answers A,user U WHERE U.uid=A.uid_ans and A.qid=".$row["qid"]." order by value desc";
		$rs2 = mysqli_query($conn,$sql2);
		$bestansid=0;
		$bestrow="";
		while($arow= mysqli_fetch_assoc ( $rs2 ))
		{
			if($arow["best_ans"]==1)
			{
				$bestansid=$arow["aid"];
				$bestrow=$arow;
			}
		}
		mysqli_data_seek($rs2,0);
		$y = 0;
		if($bestansid>0)
		{
			$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$bestrow["adesc"]."</div><div class='col-sm-2'>Answered by: <b>".$bestrow["username"]."</b></div><div class='col-sm-1'><span id='qAnsUp".$bestrow["aid"]."'>".$bestrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$bestrow["aid"].")'></div><div class='col-sm-1' style='cursor:hand;'><span id='qAnsDown".$bestrow["aid"]."'>".$bestrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$bestrow["aid"].")' style='cursor:hand;'></div><div class='col-sm-2'><img  class='img-responsive' width='24px' height='24px' src='./images/bestans.png' ></div></div>";
			$y=$y+1;
		}
		while ( $ansrow = mysqli_fetch_assoc ( $rs2 ) ) {
			if($bestansid!=$ansrow["aid"])
			{
				$postinfo = $postinfo . "<div class='list-group-item row' style='margin:0px;'><div class='col-sm-6'>".$ansrow["adesc"]."</div><div class='col-sm-2'>Answered by: <b>".$ansrow["username"]."</b></div><div class='col-sm-1'><span id='qAnsUp".$ansrow["aid"]."'>".$ansrow["upvotes"]."</span><img width='24px' height='24px' src='./images/thumb-up-outline.png' onclick='voteAnswer(1,".$ansrow["aid"].")' style='cursor:hand;'></div><div class='col-sm-1'><span id='qAnsDown".$bestrow["aid"]."'>".$ansrow["downvotes"]."</span><img width='24px' height='24px' src='./images/thumb-down-outline.png' onclick='voteAnswer(-1,".$ansrow["aid"].")' style='cursor:hand;'></div></div>";
			}
		}
		$postinfo = $postinfo . "<div class='list-group-item'><label for='Answer'>Comment:</label><textarea class='form-control' rows='5' id='comment".($x + 1)."' onclick='event.stopPropagation()'></textarea><input type='button' value='Submit' onclick='saveAnswer(1,".($x+1).",".$row["qid"].")'></div>";
		$postinfo = $postinfo . "</div></div></div>";
		echo $postinfo;
		$y = $y + 1;
		$x = $x + 1;
	}
	mysqli_close($conn);
}
?>