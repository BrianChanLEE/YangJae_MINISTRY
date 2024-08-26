<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$req_m_idx=$_REQUEST["m_idx"];

$sql = "DELETE FROM member WHERE m_idx='$req_m_idx'";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		alert("삭제완료");
		location.href="/admin/minister_list.html";
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