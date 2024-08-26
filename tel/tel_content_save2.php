<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

$req_tel_write=$_REQUEST["tel_write"];
$req_tel_idx=$_REQUEST["tel_idx"];
$req_tel_list_idx=$_REQUEST["tel_list_idx"];

$req_tel_content_idx=$_REQUEST["tel_content_idx"];
$req_tel_content_content2=$_REQUEST["tel_content_content2"];



$sql="UPDATE tel_content_list SET ";
$sql.="tel_content_content='$req_tel_content_content2' ";
$sql.="WHERE tel_content_idx='$req_tel_content_idx'";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		location.href="tel_content.html?tel_write=<?=$req_tel_write ?>&tel_idx=<?=$req_tel_idx ?>&tel_list_idx=<?=$req_tel_list_idx ?>";
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