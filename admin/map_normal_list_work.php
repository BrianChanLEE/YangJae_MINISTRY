<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_map_sub_idx=$_REQUEST["map_sub_idx"];
$req_map_list_idx=$_REQUEST["map_list_idx"];
$req_map_list_line=$_REQUEST["map_list_line"];
$req_map_list_road=$_REQUEST["map_list_road"];
$req_map_list_road_no=$_REQUEST["map_list_road_no"];
$req_map_list_floor=$_REQUEST["map_list_floor"];
$req_map_list_info=$_REQUEST["map_list_info"];
$req_map_list_rank=$_REQUEST["map_list_rank"];
$req_map_list_house_idx=$_REQUEST["map_list_house_idx"];


if ($work=="map_del"){

	//구역목록개별삭제
	try {
		$sql="delete from map_normal_list where map_list_idx='$req_map_list_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 5: ".  $e->getMessage();
		exit;
	}

}else if ($work=="map_save"){

	$sql = "UPDATE map_normal_list SET ";
	$sql=$sql."map_list_line='$req_map_list_line', ";
	$sql=$sql."map_list_road='$req_map_list_road', ";
	$sql=$sql."map_list_road_no='$req_map_list_road_no', ";
	$sql=$sql."map_list_floor='$req_map_list_floor', ";
	$sql=$sql."map_list_info='$req_map_list_info', ";
	$sql=$sql."map_list_house_idx='$req_map_list_house_idx' ";
	$sql=$sql."WHERE map_list_idx='$req_map_list_idx'";
	$result=mysql_query($sql);

}else if ($work=="map_add"){

	$sql="SELECT * FROM map_normal_list WHERE map_sub_idx='$req_map_sub_idx' ORDER BY map_list_idx desc LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);
	$map_list_road=$rs[map_list_road];
	$map_list_road_no=$rs[map_list_road_no];
	$map_list_floor=$rs[map_list_floor];
	$map_list_info=$rs[map_list_info];
	$map_list_house_idx=$rs[map_list_house_idx];


	$sql="SELECT MAX(map_list_idx) FROM map_normal_list";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_list_idx=$max_fetch[0]+1;

	$sql = "INSERT INTO map_normal_list(";
	$sql=$sql."map_sub_idx, ";
	$sql=$sql."map_list_idx, ";
	$sql=$sql."map_list_line, ";
	$sql=$sql."map_list_road, ";
	$sql=$sql."map_list_road_no, ";
	$sql=$sql."map_list_floor, ";
	$sql=$sql."map_list_info, ";
	$sql=$sql."map_list_rank, ";
	$sql=$sql."map_list_house_idx) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$req_map_sub_idx."', ";
	$sql=$sql."'".$max_map_list_idx."', ";
	$sql=$sql."0, ";
	$sql=$sql."'".$map_list_road."', ";
	$sql=$sql."'".$map_list_road_no."', ";
	$sql=$sql."'".$map_list_floor."', ";
	$sql=$sql."'".$map_list_info."', ";
	$sql=$sql."'".$max_map_list_idx."', ";
	$sql=$sql."'".$map_list_house_idx."') ";
	$result=mysql_query($sql);

}else if ($work=="map_sort"){

	$req_idx = $_REQUEST["idx"];
	$req_rank = $_REQUEST["rank"];

	$arr_idx = explode(",",$req_idx);
	$arr_rank = explode(",",$req_rank);

	$sql = " update map_normal_list ";
	$sql .= "    set map_list_rank = ";
	$sql .= "        case ";
	for ($num = 0; $num < count($arr_idx); $num++)
	{
		$sql .= "             when map_list_idx = " . $arr_idx[$num] . " then " . $arr_rank[$num];
	}
	$sql .= "        end";
	$sql .= " where map_list_idx in (" . $req_idx . ") ";
	$result=mysql_query($sql);

}else if ($work=="map_up"){

	$sql="SELECT * FROM map_normal_list WHERE map_sub_idx='$req_map_sub_idx' and map_list_rank < '$req_map_list_rank' order by map_list_rank desc LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);

	$map_list_idx=$rs[map_list_idx];
	$map_list_rank=$rs[map_list_rank];

	if ($map_list_idx==""){
		echo "no";
	}else{
		$sql="update map_normal_list set map_list_rank='$map_list_rank' where map_list_idx='$req_map_list_idx'";
		mysql_query($sql);
		$sql="update map_normal_list set map_list_rank='$req_map_list_rank' where map_list_idx='$map_list_idx'";
		mysql_query($sql);
		echo "yes";
	}

}else if ($work=="map_down"){

	$sql="SELECT * FROM map_normal_list WHERE map_sub_idx='$req_map_sub_idx' and map_list_rank > '$req_map_list_rank' order by map_list_rank LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);

	$map_list_idx=$rs[map_list_idx];
	$map_list_rank=$rs[map_list_rank];

	if ($map_list_idx==""){
		echo "no";
	}else{
		$sql="update map_normal_list set map_list_rank='$map_list_rank' where map_list_idx='$req_map_list_idx'";
		mysql_query($sql);
		$sql="update map_normal_list set map_list_rank='$req_map_list_rank' where map_list_idx='$map_list_idx'";
		mysql_query($sql);
		echo "yes";
	}

}else if ($work=="map_normal_list"){
	
	$sql="SELECT * FROM map_normal_list WHERE 1=1 ";
	$sql.=" and map_sub_idx='$req_map_sub_idx'";
	$sql.=" order by map_list_rank";
	$result=mysql_query($sql);

	$str="";
	$str.="<select class='form-control' id='move_map_list_idx'>\r\n";
		$str.="<option value='0'>맨위</option>\r\n";


	while($rs=mysql_fetch_array($result)){

		$map_list_idx=$rs[map_list_idx];
		$map_list_line=$rs[map_list_line];
		$map_list_road=$rs[map_list_road];
		$map_list_road_no=$rs[map_list_road_no];
		$map_list_floor=$rs[map_list_floor];
		$map_list_info=$rs[map_list_info];
		$map_list_rank=$rs[map_list_rank];

		if ($map_list_line=="1"){
			$map_list_line_checked="checked";
		}else{
			$map_list_line_checked="";
		}

		$map_str=$map_list_road." ".$map_list_road_no." ".$map_list_floor." ".$map_list_info;

		$str.="<option value='$map_list_idx'>$map_str</option>\r\n";

	}

	$str.="</select>\r\n";

	echo $str;
}
?>