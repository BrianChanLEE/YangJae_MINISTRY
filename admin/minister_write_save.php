<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$m_name=$_REQUEST["m_name"];
$m_pw=$_REQUEST["m_pw"];
$m_hp=$_REQUEST["m_hp"];
$m_mgr=$_REQUEST["m_mgr"];
$m_position=$_REQUEST["m_position"];
$m_pioneer=$_REQUEST["m_pioneer"];
$m_sex=$_REQUEST["m_sex"];
$m_sos_call=$_REQUEST["m_sos_call"];


for($i=0;$i < count($m_mgr);$i++) { 
	if($i>0){
		$m_mgrs.=",";
	}
	$m_mgrs.= $m_mgr[$i]; 
} 

$sql = "INSERT INTO member(";
$sql=$sql."m_name, ";
$sql=$sql."m_pw, ";
$sql=$sql."m_hp, ";
$sql=$sql."m_mgr, ";
$sql=$sql."m_position, ";
$sql=$sql."m_pioneer, ";
$sql=$sql."m_sex, ";
$sql=$sql."m_sos_call, ";
$sql=$sql."m_regdate, ";
$sql=$sql."m_editdate) ";
$sql=$sql."VALUES(";
$sql=$sql."'$m_name', ";
$sql=$sql."'$m_pw', ";
$sql=$sql."'$m_hp', ";
$sql=$sql."'$m_mgrs', ";
$sql=$sql."'$m_position', ";
$sql=$sql."'$m_pioneer', ";
$sql=$sql."'$m_sex', ";
$sql=$sql."'$m_sos_call', ";
$sql=$sql."now(), ";
$sql=$sql."now())";
$result=mysql_query($sql);

if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		alert("저장완료");
		location.href="/admin/minister_list.html";
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