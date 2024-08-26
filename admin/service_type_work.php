<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_service_idx=$_REQUEST["service_idx"];
$req_service_type=$_REQUEST["service_type"];
$req_service_rank=$_REQUEST["service_rank"];




if ($work=="service_del"){

	$sql="delete from db_service_type where service_idx='$req_service_idx'";
	$result=mysql_query($sql);

}else if ($work=="service_save"){

	$sql = "UPDATE db_service_type SET ";
	$sql=$sql."service_type='$req_service_type' ";
	$sql=$sql."WHERE service_idx='$req_service_idx'";
	$result=mysql_query($sql);	

}else if ($work=="service_add"){

	$sql="SELECT MAX(service_idx) FROM db_service_type";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_service_idx=$max_fetch[0]+1;

	$sql = "INSERT INTO db_service_type(";
	$sql=$sql."service_idx, ";
	$sql=$sql."service_type, ";
	$sql=$sql."service_rank) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$max_service_idx."', ";
	$sql=$sql."'이름없음', ";
	$sql=$sql."'".$max_service_idx."') ";
	$result=mysql_query($sql);

}else if ($work=="service_sort"){

	$req_idx = $_REQUEST["idx"];
	$req_rank = $_REQUEST["rank"];

	$arr_idx = explode(",",$req_idx);
	$arr_rank = explode(",",$req_rank);

	$sql = " update db_service_type ";
	$sql .= "    set service_rank = ";
	$sql .= "        case ";
	for ($num = 0; $num < count($arr_idx); $num++)
	{
		$sql .= "             when service_idx = " . $arr_idx[$num] . " then " . $arr_rank[$num];
	}
	$sql .= "        end";
	$sql .= " where service_idx in (" . $req_idx . ") ";
	$result=mysql_query($sql);

}
?>