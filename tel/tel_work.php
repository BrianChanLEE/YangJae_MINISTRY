<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

$work=$_REQUEST["work"];
$req_tel_idx=$_REQUEST["tel_idx"];
$req_tel_ministry_idx=$_REQUEST["tel_ministry_idx"];




if ($work=="tel_start"){

	$m_idx=$_SESSION['m_idx'];

	$sql="select * from tel_service where tel_idx='$req_tel_idx' and tel_member_idx=0";
	$result=mysql_query($sql);
	$num_rows = mysql_num_rows($result);


	if ($num_rows>0){

		$sql="UPDATE tel_service SET ";
		$sql.="tel_member_idx='$m_idx', ";
		$sql.="tel_service_date=now() ";
		$sql.="WHERE tel_idx='$req_tel_idx'";
		$result=mysql_query($sql);




		$sql="SELECT MAX(tel_ministry_idx)+1 FROM tel_ministry_stat";
		$result = mysql_query($sql);
		$max_fetch = mysql_fetch_row($result);
		$max_tel_ministry_idx=$max_fetch[0];

		$tel_ministry_date=date("Y-m-d");
//		$tel_ministry_start=date("Y-m-d H:i");
		$tel_ministry_start=date("H:i");

		$sql="insert into tel_ministry_stat(tel_idx, tel_ministry_idx, tel_ministry_name, tel_ministry_date, tel_ministry_start) values(
		'$req_tel_idx',	
		'$max_tel_ministry_idx',
		'$m_idx',
		'$tel_ministry_date',
		'$tel_ministry_start'
		)";
		$result=mysql_query($sql);

		echo "";

	}else{
		
		echo "다른 전도인이 참여중입니다.";
	}

}else if($work=="tel_end"){

	$m_idx=$_SESSION['m_idx'];

	$sql="UPDATE tel_service SET ";
	$sql.="tel_member_idx='' ";
//	$sql.="tel_service_date=now() ";
	$sql.="WHERE tel_idx='$req_tel_idx' and tel_member_idx='$m_idx'";
	$result=mysql_query($sql);

//	$tel_ministry_end=date("Y-m-d H:i");
	$tel_ministry_end=date("H:i");

	$sql="update tel_ministry_stat set
		tel_ministry_end='$tel_ministry_end'
		where tel_ministry_idx='$req_tel_ministry_idx'";
	$result=mysql_query($sql);

}
?>