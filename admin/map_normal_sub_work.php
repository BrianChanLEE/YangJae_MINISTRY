<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

//if (!mgr_check("1,2,3")){
//	echo "<script>location.href='/home/';</script>";
//}

$work=$_REQUEST["work"];
$req_map_idx=$_REQUEST["map_idx"];
$req_map_sub_idx=$_REQUEST["map_sub_idx"];
$req_map_sub_no=$_REQUEST["map_sub_no"];
$req_map_sub_info=$_REQUEST["map_sub_info"];
$req_map_sub_addr=$_REQUEST["map_sub_addr"];
$req_map_sub_notice=$_REQUEST["map_sub_notice"];
$req_ministry_member_idx=$_REQUEST["ministry_member_idx"];
$req_map_sub_polygon=$_REQUEST["map_sub_polygon"];
$req_map_service=$_REQUEST["map_service"];




if ($work=="map_del"){
	//구역을 삭제할 때
	//봉사 지정이 되어 있는 것이 있다면 함께 삭제해야한다.

	//구역카드삭제
	try {
		$sql="delete from map_normal_sub where map_sub_idx='$req_map_sub_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 2: ".  $e->getMessage();
		exit;
	}

	//구역목록전체삭제
	try {
		$sql="delete from map_normal_list where map_sub_idx='$req_map_sub_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 3: ".  $e->getMessage();
		exit;
	}


	
	//봉사지정이 되어있는 구역도 함께 삭제.
	try {
		$sql = "delete from ministry_list where map_sub_idx='$req_map_sub_idx'";
		$result=mysql_query($sql);
	}catch (Exception $e) {
		echo "Caught exception 4: ".  $e->getMessage();
		exit;
	}

}else if ($work=="map_del2"){
	// /admin/person_service.html 파일에서 사용합니다.
	// 관리자 > 구역관리 > 개인구역에서 개인구역을 삭제할때 사용.

	$sql="delete from ministry_list where  map_sub_idx='$req_map_sub_idx'";
	$result=mysql_query($sql);	

}else if ($work=="map_save"){
	//구역카드정보(구역명, 주소 등) 수정

	$sql = "UPDATE map_normal_sub SET ";
	$sql=$sql."map_idx='$req_map_idx', ";
	$sql=$sql."map_sub_no='$req_map_sub_no', ";
	$sql=$sql."map_sub_info='$req_map_sub_info', ";
	$sql=$sql."map_sub_addr='$req_map_sub_addr', ";
	$sql=$sql."map_sub_notice='$req_map_sub_notice' ";
	$sql=$sql."WHERE map_sub_idx='$req_map_sub_idx'";
	$result=mysql_query($sql);

	//개인구역 설정
	$sql = "select * from ministry_list where map_sub_idx ='$req_map_sub_idx'";
	$result=mysql_query($sql);
	$rs=mysql_fetch_array($result);

	$tmp_ministry_date = $rs[ministry_date];
	$tmp_ministry_member_idx = $rs[ministry_member_idx];

	$num=mysql_num_rows($result);

	if ($num==0 && $req_ministry_member_idx!=""){

		//개인구역 지정
		$sql = "INSERT INTO ministry_list(map_sub_idx, ministry_date, ministry_service_idx, ministry_member_idx)
		values('$req_map_sub_idx',null,'99','$req_ministry_member_idx')";
		$result=mysql_query($sql);
	
	}else{

		if ($tmp_ministry_date=="" && $req_ministry_member_idx==""){

			$sql = "DELETE FROM ministry_list WHERE map_sub_idx = '$req_map_sub_idx'";
			$result=mysql_query($sql);

		}else{

			//개인구역 전도인이 변경될 경우 수정
			//지정일이 없고, 전도인이 변경될 경우 수정
			if ($tmp_ministry_date=="" && $tmp_ministry_member_idx != $req_ministry_member_idx)
			{
				//개인구역 수정
				$sql = "UPDATE ministry_list SET 
				ministry_start_date = null,
				ministry_end_date   = null,";

				if ($req_ministry_member_idx!=""){
				$sql.="ministry_service_idx   = '99',";
				}else{
				$sql.="ministry_service_idx   = null,";
				}

				$sql.="ministry_member_idx = '$req_ministry_member_idx'
				WHERE map_sub_idx = '$req_map_sub_idx'";
				$result=mysql_query($sql);
			}

		}

	}

}else if ($work=="map_save2"){
	// /admin/person_service.html 파일에서 사용합니다.
	// 관리자 > 구역관리 > 개인구역에서 개인구역을 수정할때 사용.

	$sql = "select * from ministry_list where map_sub_idx ='$req_map_sub_idx'";
	$result=mysql_query($sql);
	$rs=mysql_fetch_array($result);

	$tmp_ministry_date = $rs[ministry_date];
	$tmp_ministry_member_idx = $rs[ministry_member_idx];

	$num=mysql_num_rows($result);

	if ($num>0){

		if ($req_ministry_member_idx==""){

			$sql = "DELETE FROM ministry_list WHERE map_sub_idx = '$req_map_sub_idx'";
			$result=mysql_query($sql);

		}else{
		
			//개인구역 전도인이 변경될 경우 수정
			//지정일이 없고, 전도인이 변경될 경우 수정
			if ($tmp_ministry_date=="" && $tmp_ministry_member_idx != $req_ministry_member_idx)
			{
				//개인구역 수정
				$sql = "UPDATE ministry_list SET 
				ministry_start_date = null,
				ministry_end_date   = null,
				ministry_member_idx = '$req_ministry_member_idx'
				WHERE map_sub_idx = '$req_map_sub_idx'";
				$result=mysql_query($sql);
			}
		}

	}

}else if ($work=="map_add"){


	$sql="SELECT * FROM map_normal WHERE map_idx='$req_map_idx'";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result);
	$map_no=$rs[map_no];

	$sql="SELECT MAX(map_sub_idx) FROM map_normal_sub";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_sub_idx=$max_fetch[0]+1;

	$sql="SELECT MAX(map_sub_idx) FROM map_normal_sub WHERE map_idx='$req_map_idx'";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_sub_no=$max_fetch[0]+1;

	$max_map_sub_no=$map_no."-".$max_map_sub_no."-new";

	$sql = "INSERT INTO map_normal_sub(";
	$sql=$sql."map_idx, ";
	$sql=$sql."map_sub_idx, ";
	$sql=$sql."map_sub_no) ";
	$sql=$sql."VALUES(";
	$sql=$sql."'".$req_map_idx."', ";
	$sql=$sql."'".$max_map_sub_idx."', ";
	$sql=$sql."'".$max_map_sub_no."') ";
	$result=mysql_query($sql);

}else if ($work=="member_list"){

	$sql="SELECT * FROM member ORDER BY m_name";
	$result=mysql_query($sql);
	?>
	<select name="">
		<option value="">개인구역선택
	<?
	while($rs=mysql_fetch_array($result)){
		$m_idx=$rs[m_idx];
		$m_name=$rs[m_name];
	?>
		<option value="<?=$m_idx ?>"<?=($m_idx==$req_ministry_member_idx)?" selected":"" ?>><?=$m_name ?>
	<?
	}
	?>
	</select>
	<?

}else if ($work=="map_polygon_save"){

	$sql = "UPDATE map_normal_sub SET ";
	$sql=$sql."map_sub_polygon='$req_map_sub_polygon' ";
	$sql=$sql."WHERE map_sub_idx='$req_map_sub_idx'";
	$result=mysql_query($sql);	

	if ($result=="1"){
		echo "ok";
	}else{
		echo "error";
	}

}else if ($work=="map_normal_list"){

	$sql="SELECT * FROM map_normal WHERE map_service='$req_map_service'";
	$result=mysql_query($sql);
	?>
	<select name="">
		<option value="0">상위구역선택
	<?
	while($rs=mysql_fetch_array($result)){
		$map_idx=$rs[map_idx];
		$map_no=$rs[map_no];
	?>
		<option value="<?=$map_idx ?>"<?=($map_idx==$req_map_idx)?" selected":"" ?>><?=$map_no ?>
	<?
	}
	?>
	</select>
	<?

}
?>