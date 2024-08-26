<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_week_idx=$_REQUEST["week_idx"];
$req_week_yoil=$_REQUEST["week_yoil"];
$req_week_ampm=$_REQUEST["week_ampm"];
$req_week_time=$_REQUEST["week_time"];
$req_week_guide1=$_REQUEST["week_guide1"];
$req_week_guide2=$_REQUEST["week_guide2"];
$req_week_service=$_REQUEST["week_service"];
$req_week_location=$_REQUEST["week_location"];




if ($work=="week_del"){

	$sql = "DELETE FROM week_list ";
	$sql.= "WHERE week_idx='$req_week_idx'";
	$result=mysql_query($sql);	

}else if ($work=="week_save"){

	$sql = "UPDATE week_list SET ";
	$sql=$sql."week_ampm='$req_week_ampm', ";
	$sql=$sql."week_time='$req_week_time', ";
	$sql=$sql."week_guide1='$req_week_guide1', ";
	$sql=$sql."week_guide2='$req_week_guide2', ";
	$sql=$sql."week_service='$req_week_service', ";
	$sql=$sql."week_location='$req_week_location' ";
	$sql=$sql."WHERE week_idx='$req_week_idx'";
	$result=mysql_query($sql);	

}else if ($work=="week_add"){

	$sql = "INSERT INTO week_list(";
	$sql=$sql."week_yoil) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$req_week_yoil."') ";
	$result=mysql_query($sql);

}
?>