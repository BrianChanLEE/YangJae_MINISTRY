<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$bbs_idx=$_REQUEST["bbs_idx"];

$sql = "DELETE FROM bbs WHERE bbs_idx='$bbs_idx'";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		alert("삭제완료");
		location.href="/admin/bbs_list.html";
	//-->
	</script>
<?
}else{
?>
	<script type="text/javascript">
	<!--
		alert("삭제오류");
		history.back();
	//-->
	</script>
<?
}
?>