<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
}

$m_idx=$_REQUEST["m_idx"];
$m_name=$_REQUEST["m_name"];
$m_pw=$_REQUEST["m_pw"];
$m_hp=$_REQUEST["m_hp"];
$m_mgr=$_REQUEST["m_mgr"];
$m_position=$_REQUEST["m_position"];
$m_pioneer=$_REQUEST["m_pioneer"];
$m_sex=$_REQUEST["m_sex"];
$m_sos_call=$_REQUEST["m_sos_call"];


$req_m_name=$_REQUEST["m_name"];
$req_page=$_REQUEST["page"];
$req_orderBy=$_REQUEST["orderBy"];

$query_string2="";

if ($req_m_name!=""){
	$query_string2="&m_name=$req_m_name";
}
if ($req_orderBy !=""){
	$query_string2.="&orderBy=$req_orderBy";
}
$query_string2.="&page=".$req_page;


for($i=0;$i < count($m_mgr);$i++) { 
	if($i>0){
		$m_mgrs.=",";
	}
	$m_mgrs.= $m_mgr[$i]; 
} 

$sql = "UPDATE member SET ";
//$sql=$sql."m_name='$m_name', ";
$sql=$sql."m_pw='$m_pw', ";
$sql=$sql."m_hp='$m_hp', ";
$sql=$sql."m_mgr='$m_mgrs', ";
$sql=$sql."m_position='$m_position', ";
$sql=$sql."m_pioneer='$m_pioneer', ";
$sql=$sql."m_sex='$m_sex', ";
$sql=$sql."m_sos_call='$m_sos_call', ";
$sql=$sql."m_editdate=now() ";
$sql=$sql."WHERE m_idx='$m_idx'";
//echo $sql;
//exit;
$result=mysql_query($sql);


if ($result=="1"){
?>
	<script type="text/javascript">
	<!--
		alert("저장완료");
		location.href="/admin/minister_list.html?<?=$query_string2 ?>";
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