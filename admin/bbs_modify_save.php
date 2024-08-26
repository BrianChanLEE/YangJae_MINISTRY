<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$req_page=$_REQUEST["page"];

$bbs_idx=$_REQUEST["bbs_idx"];
$bbs_subject=$_REQUEST["bbs_subject"];
$bbs_content=$_REQUEST["bbs_content"];
$bbs_isNotice=$_REQUEST["bbs_isNotice"];
$bbs_isElder=$_REQUEST["bbs_isElder"];

$bbs_subject=str_replace("'", "''", $bbs_subject);
$bbs_content=str_replace("'", "''", $bbs_content);

if ($bbs_isNotice=="1"){
	$bbs_isNotice="1";
}else{
	$bbs_isNotice="0";
}


$query_string2.="page=".$req_page."&elder=".$bbs_isElder;

$sql = "UPDATE bbs SET ";
$sql=$sql."bbs_subject='$bbs_subject', ";
$sql=$sql."bbs_content='$bbs_content', ";
$sql=$sql."bbs_isNotice='$bbs_isNotice', ";
$sql=$sql."bbs_isElder='$bbs_isElder', ";
$sql=$sql."bbs_editdate=now() ";
$sql=$sql."WHERE bbs_idx='$bbs_idx'";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		alert("저장완료");
		location.href="/admin/bbs_list.html?<?=$query_string2 ?>";
	//-->
	</script>
<?
}else{
?>
	<script type="text/javascript">
	<!--
		alert("저장오류");
		history.back();
	//-->
	</script>
<?
}
?>