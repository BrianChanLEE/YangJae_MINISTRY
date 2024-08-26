<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_month_idx=$_REQUEST["month_idx"];
$req_month_date=$_REQUEST["month_date"];
$req_month_ampm=$_REQUEST["month_ampm"];
$req_month_time=$_REQUEST["month_time"];
$req_month_guide1=$_REQUEST["month_guide1"];
$req_month_guide2=$_REQUEST["month_guide2"];
$req_month_service=$_REQUEST["month_service"];
$req_month_location=$_REQUEST["month_location"];
$req_month_content=$_REQUEST["month_content"];




if ($work=="month_del"){

	$sql = "DELETE FROM month_list ";
	$sql.= "WHERE month_idx='$req_month_idx'";
	$result=mysql_query($sql);	

}else if ($work=="month_save"){

	$sql = "UPDATE month_list SET ";
	$sql=$sql."month_ampm='$req_month_ampm', ";
	$sql=$sql."month_time='$req_month_time', ";
	$sql=$sql."month_guide1='$req_month_guide1', ";
	$sql=$sql."month_guide2='$req_month_guide2', ";
	$sql=$sql."month_service='$req_month_service', ";
	$sql=$sql."month_location='$req_month_location', ";
	$sql=$sql."month_content='$req_month_content' ";
	$sql=$sql."WHERE month_idx='$req_month_idx'";
	$result=mysql_query($sql);	

}else if ($work=="month_add"){

	$sql = "INSERT INTO month_list(";
	$sql=$sql."month_date) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$req_month_date."') ";
	$result=mysql_query($sql);

}
?>