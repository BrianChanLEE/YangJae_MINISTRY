<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

$req_tel_idx=$_REQUEST["tel_idx"];
$req_tel_list_idx=$_REQUEST["tel_list_idx"];
$req_tel_content_content=$_REQUEST["tel_content_content"];
$req_tel_content_content=str_replace("'", "''", $req_tel_content_content);
$req_tel_content_content=nl2br($req_tel_content_content);
$req_tel_list_refusal=$_REQUEST["tel_list_refusal"];
$req_tel_list_ban=$_REQUEST["tel_list_ban"];
$req_tel_list_sunday=$_REQUEST["tel_list_sunday"];
$m_idx=$_SESSION['m_idx'];


$sql="UPDATE tel_list SET ";
$sql.="tel_list_refusal='$req_tel_list_refusal', ";
$sql.="tel_list_ban='$req_tel_list_ban', ";
$sql.="tel_list_sunday='$req_tel_list_sunday' ";
//$sql.="tel_list_write='1' ";
$sql.="WHERE tel_list_idx='$req_tel_list_idx'";
$result=mysql_query($sql);




$sql = "INSERT INTO tel_content_list(";
$sql=$sql."tel_list_idx, ";
$sql=$sql."tel_content_content, ";
$sql=$sql."tel_content_member_idx, ";
$sql=$sql."tel_content_date) ";
$sql=$sql."VALUES(";
$sql=$sql."'$req_tel_list_idx', ";
$sql=$sql."'$req_tel_content_content', ";
$sql=$sql."'$m_idx', ";
$sql=$sql."now())";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		location.href="tel_list.html?tel_idx=<?=$req_tel_idx ?>";
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