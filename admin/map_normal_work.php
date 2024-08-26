<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_map_idx=$_REQUEST["map_idx"];
$req_map_service=$_REQUEST["map_service"];
$req_map_no=$_REQUEST["map_no"];




if ($work=="map_del"){

	$sql="select map_sub_idx from map_normal_sub where map_idx='$req_map_idx' ";
	$result=mysql_query($sql);
	$rs=mysql_fetch_array($result);
	$map_sub_idx = $rs[map_sub_idx];


	//구역대분류삭제
	try {
		$sql="delete from map_normal where map_idx='$req_map_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 1: ".  $e->getMessage();
		exit;
	}

	//구역카드전체삭제
	try {
		$sql="delete from map_normal_sub where map_idx='$req_map_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 2: ".  $e->getMessage();
		exit;
	}


	//구역목록전체삭제
	try {
		$sql = "DELETE FROM map_normal_list WHERE map_sub_idx='$map_sub_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 3: ".  $e->getMessage();
		exit;
	}

}else if ($work=="map_save"){

	$sql = "UPDATE map_normal SET ";
	$sql=$sql."map_no='$req_map_no' ";
	$sql=$sql."WHERE map_idx='$req_map_idx'";
	$result=mysql_query($sql);	

}else if ($work=="map_add"){

	$sql="SELECT MAX(map_idx) FROM map_normal";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_idx=$max_fetch[0]+1;

	$sql="SELECT MAX(map_service) FROM map_normal WHERE map_service='$req_map_service'";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_no=$max_fetch[0]+1;

	$sql = "INSERT INTO map_normal(";
	$sql=$sql."map_idx, ";
	$sql=$sql."map_service, ";
	$sql=$sql."map_no, ";
	$sql=$sql."map_rank) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$max_map_idx."', ";
	$sql=$sql."'".$req_map_service."', ";
	$sql=$sql."'".$max_map_no."', ";
	$sql=$sql."'".$max_map_idx."') ";
	$result=mysql_query($sql);

}else if ($work=="map_sort"){

	$req_idx = $_REQUEST["idx"];
	$req_rank = $_REQUEST["rank"];

	$arr_idx = explode(",",$req_idx);
	$arr_rank = explode(",",$req_rank);

	$sql = " update map_normal ";
	$sql .= "    set map_rank = ";
	$sql .= "        case ";
	for ($num = 0; $num < count($arr_idx); $num++)
	{
		$sql .= "             when map_idx = " . $arr_idx[$num] . " then " . $arr_rank[$num];
	}
	$sql .= "        end";
	$sql .= " where map_idx in (" . $req_idx . ") ";
	$result=mysql_query($sql);

}
?>