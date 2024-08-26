<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

$work=$_REQUEST["work"];




if ($work=="info_save"){

	$m_idx=$_SESSION['m_idx'];
	$req_pwd=$_REQUEST["pwd"];

	$sql="UPDATE member SET ";
	$sql.="m_pw='$req_pwd' ";
	$sql.="WHERE m_idx='$m_idx'";
	$result=mysql_query($sql);
	
	if ($result=="1"){
		$_SESSION['m_idx'] = "";
		$_SESSION['m_name'] = "";
		$_SESSION['m_mgr'] = "";
		$_SESSION['m_position'] = "";
		$_SESSION['m_pioneer'] = "";
		$_SESSION['m_sex'] = "";		
		
		$cookie_name = "jwbs";
		setcookie("jwbs", "", time() - 3600, "/");

		session_unset();
		session_destroy();
	}else{
		
		echo "error";
	}
}
?>