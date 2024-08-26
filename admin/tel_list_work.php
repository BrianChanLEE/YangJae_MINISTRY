<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_tel_idx=$_REQUEST["tel_idx"];
$req_tel_list_idx=$_REQUEST["tel_list_idx"];
$req_tel_list_name=$_REQUEST["tel_list_name"];
$req_tel_list_number=$_REQUEST["tel_list_number"];
$req_tel_list_addr=$_REQUEST["tel_list_addr"];
$req_tel_list_rank=$_REQUEST["tel_list_rank"];




if ($work=="tel_list_del"){

	$sql = "DELETE FROM tel_list ";
	$sql.= "WHERE tel_list_idx='$req_tel_list_idx'";
	$result=mysql_query($sql);	

}else if ($work=="tel_list_save"){

	$sql = "UPDATE tel_list SET ";
	$sql=$sql."tel_list_name='$req_tel_list_name', ";
	$sql=$sql."tel_list_number='$req_tel_list_number', ";
	$sql=$sql."tel_list_addr='$req_tel_list_addr' ";
	$sql=$sql."WHERE tel_list_idx='$req_tel_list_idx'";
	$result=mysql_query($sql);	

}else if ($work=="tel_list_add"){

	$sql="SELECT * FROM tel_list WHERE tel_idx='$req_tel_idx' ORDER BY tel_list_idx desc LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);
	$tel_list_name=$rs[tel_list_name];
	$tel_list_number=$rs[tel_list_number];
	$tel_list_addr=$rs[tel_list_addr];

	$sql="SELECT MAX(tel_list_idx) FROM tel_list";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_tel_list_idx=$max_fetch[0]+1;

	$sql = "INSERT INTO tel_list(";
	$sql=$sql."tel_idx, ";
	$sql=$sql."tel_list_idx, ";
	$sql=$sql."tel_list_name, ";
	$sql=$sql."tel_list_number, ";
	$sql=$sql."tel_list_addr, ";
	$sql=$sql."tel_list_rank) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$req_tel_idx."', ";
	$sql=$sql."'".$max_tel_list_idx."', ";
	$sql=$sql."'".$tel_list_name."', ";
	$sql=$sql."'".$tel_list_number."', ";
	$sql=$sql."'".$tel_list_addr."', ";
	$sql=$sql."'".$max_tel_list_idx."') ";
	$result=mysql_query($sql);

}else if ($work=="tel_list_sort"){

	$req_idx = $_REQUEST["idx"];
	$req_rank = $_REQUEST["rank"];

	$arr_idx = explode(",",$req_idx);
	$arr_rank = explode(",",$req_rank);

	$sql = " update tel_list ";
	$sql .= "    set tel_list_rank = ";
	$sql .= "        case ";
	for ($num = 0; $num < count($arr_idx); $num++)
	{
		$sql .= "             when tel_list_idx = " . $arr_idx[$num] . " then " . $arr_rank[$num];
	}
	$sql .= "        end";
	$sql .= " where tel_list_idx in (" . $req_idx . ") ";
	$result=mysql_query($sql);

}else if ($work=="tel_list_up"){

	$sql="SELECT * FROM tel_list WHERE tel_idx='$req_tel_idx' and tel_list_rank < '$req_tel_list_rank' order by tel_list_rank desc LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);

	$tel_list_idx=$rs[tel_list_idx];
	$tel_list_rank=$rs[tel_list_rank];

	if ($tel_list_idx==""){
		echo "no";
	}else{
		$sql="update tel_list set tel_list_rank='$tel_list_rank' where tel_list_idx='$req_tel_list_idx'";
		mysql_query($sql);
		$sql="update tel_list set tel_list_rank='$req_tel_list_rank' where tel_list_idx='$tel_list_idx'";
		mysql_query($sql);
		echo "yes";
	}


}else if ($work=="tel_list_down"){

	$sql="SELECT * FROM tel_list WHERE tel_idx='$req_tel_idx' and tel_list_rank > '$req_tel_list_rank' order by tel_list_rank LIMIT 1";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);

	$tel_list_idx=$rs[tel_list_idx];
	$tel_list_rank=$rs[tel_list_rank];

	if ($tel_list_idx==""){
		echo "no";
	}else{
		$sql="update tel_list set tel_list_rank='$tel_list_rank' where tel_list_idx='$req_tel_list_idx'";
		mysql_query($sql);
		$sql="update tel_list set tel_list_rank='$req_tel_list_rank' where tel_list_idx='$tel_list_idx'";
		mysql_query($sql);
		echo "yes";
	}

}
?>