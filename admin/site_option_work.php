<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];




if ($work=="option_save"){

	$req_option_name=$_REQUEST["option_name"];
	$req_option_yn=$_REQUEST["option_yn"];

	$sql="update site_option set option_yn='$req_option_yn' where option_name='$req_option_name'";
	$result=mysql_query($sql);

}else if ($work=="cong_save"){

	$req_cong_name=$_REQUEST["cong_name"];

	$sql="update congregation set cong_name='$req_cong_name' where cong_idx='1'";
	$result=mysql_query($sql);

}else if ($work=="notice_save"){

	$req_ministry_notice=$_REQUEST["ministry_notice"];

	$sql="update site_option set option_fullname='$req_ministry_notice' where option_idx='5'";
	$result=mysql_query($sql);
}
?>