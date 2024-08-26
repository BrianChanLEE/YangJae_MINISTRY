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
$req_tel_no=$_REQUEST["tel_no"];
$req_tel_info=$_REQUEST["tel_info"];




if ($work=="tel_del"){

	//전화대분류삭제
	try {
		$sql = "DELETE FROM tel_service WHERE tel_idx='$req_tel_idx'";
		$result=mysql_query($sql);	
	}catch (Exception $e) {
		echo "Caught exception 1: ".  $e->getMessage();
		exit;
	}

	//전화목록전체삭제
	try {
		$sql = "DELETE FROM tel_list WHERE tel_idx='$req_tel_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 2: ".  $e->getMessage();
		exit;
	}

}else if ($work=="tel_save"){

	$sql = "UPDATE tel_service SET ";
	$sql.="tel_no='$req_tel_no' ";
	$sql.="WHERE tel_idx='$req_tel_idx'";
	$result=mysql_query($sql);	

}else if ($work=="tel_add"){

	$sql="SELECT MAX(tel_idx) FROM tel_service";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_tel_idx=$max_fetch[0]+1;

	$sql = "INSERT INTO tel_service(";
	$sql.="tel_idx, ";
	$sql.="tel_no) ";
	$sql.="VALUES(";
	$sql.="'".$max_tel_idx."', ";
	$sql.="'".$max_tel_idx."') ";
	$result=mysql_query($sql);

}else if ($work=="tel_content_info"){

	$req_tel_content_idx=$_REQUEST["tel_content_idx"];
	
	$sql="select * from tel_content_list where tel_content_idx='$req_tel_content_idx' ";
	$result_type=mysql_query($sql);
	$rs=mysql_fetch_array($result_type);
	$tel_content_content=$rs[tel_content_content];
	$tel_content_content=str_replace("<br />","", $tel_content_content);

	echo $tel_content_content;


}else if ($work=="tel_content_edit"){

	$req_tel_content_idx=$_REQUEST["tel_content_idx"];
	$req_tel_content_content=$_REQUEST["tel_content_content"];
	$req_tel_content_content=str_replace("'", "''", $req_tel_content_content);
	$req_tel_content_content=nl2br($req_tel_content_content);

	$sql="UPDATE tel_content_list SET ";
	$sql.="tel_content_content='$req_tel_content_content', ";
	$sql.="tel_content_date=now() ";
	$sql.="WHERE tel_content_idx='$req_tel_content_idx'";
	$result=mysql_query($sql);
	echo $req_tel_content_content;

}else if ($work=="tel_content_del"){

	$req_tel_content_idx=$_REQUEST["tel_content_idx"];

	$sql="DELETE FROM tel_content_list ";
	$sql.="WHERE tel_content_idx='$req_tel_content_idx'";
	$result=mysql_query($sql);

}else if ($work=="tel_info_save"){

	$sql = "UPDATE tel_info SET ";
	$sql.="tel_info='$req_tel_info' ";
	$sql.="WHERE tel_idx='$req_tel_idx'";

	$result=mysql_query($sql);
}
?>