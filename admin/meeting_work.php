<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_meeting_idx=$_REQUEST["meeting_idx"];
$req_meeting_name=$_REQUEST["meeting_name"];
$req_meeting_addr=$_REQUEST["meeting_addr"];




if ($work=="meeting_del"){

	$sql = "DELETE FROM meeting_location ";
	$sql.= "WHERE meeting_idx='$req_meeting_idx'";
	$result=mysql_query($sql);	

}else if ($work=="meeting_save"){

	$sql = "UPDATE meeting_location SET ";
	$sql=$sql."meeting_name='$req_meeting_name', ";
	$sql=$sql."meeting_addr='$req_meeting_addr' ";
	$sql=$sql."WHERE meeting_idx='$req_meeting_idx'";
	$result=mysql_query($sql);	

}else if ($work=="meeting_add"){

	$sql = "INSERT INTO meeting_location(";
	$sql=$sql."meeting_name, ";
	$sql=$sql."meeting_addr) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'', ";
	$sql=$sql."'') ";
	$result=mysql_query($sql);

}
?>