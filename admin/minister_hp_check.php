<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
}

$req_m_hp=$_REQUEST["m_hp"];

$sql="SELECT * FROM member WHERE m_hp='$req_m_hp'";
$result=mysql_query($sql);
$num=mysql_num_rows($result);

if($num>0){
	echo "이미 등록된 핸드폰번호가 있습니다. 핸드폰번호를 확인해주세요.";
}else{
	echo "";
}
?>
