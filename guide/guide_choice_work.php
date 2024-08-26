<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_ministry_date=$_REQUEST["ministry_date"];
$req_ministry_service_idx=$_REQUEST["ministry_service_idx"];
$req_map_sub_idx=$_REQUEST["map_sub_idx"];




if ($work=="ministry_del"){

	$sql = "DELETE FROM ministry_list ";
	$sql.= "WHERE map_sub_idx in (".$req_map_sub_idx.")";
	$result=mysql_query($sql);

	exit;

}else if ($work=="ministry_add"){

	$sql = "select * from ministry_list where map_sub_idx in (".$req_map_sub_idx.")";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);

	if ($num==0){

		$sql = "INSERT INTO ministry_list(map_sub_idx, ministry_date, ministry_service_idx, ministry_member_idx)
		select map_sub_idx, '".$req_ministry_date."', '".$req_ministry_service_idx."', ''
		from map_normal_sub where 
		map_sub_idx in (".$req_map_sub_idx.")";
		$result=mysql_query($sql);
	
	}else{

		$sql = "UPDATE ministry_list SET 
		ministry_date = '".$req_ministry_date."', 
		ministry_service_idx='".$req_ministry_service_idx."' ,
		map_list_house_idx=NULL,
		map_list_visit=NULL,
		map_list_enter=NULL
		WHERE map_sub_idx in (".$req_map_sub_idx.")";
		$result=mysql_query($sql);

	}

	exit;


}else if ($work=="map_normal_list"){

	$arr_req_map_sub_idx=explode(",",$req_map_sub_idx);

	for($i=0;$i<count($arr_req_map_sub_idx);$i++){
		test($arr_req_map_sub_idx[$i]);
	}

	exit;

}else if ($work=="map_normal_list2"){

	$arr_req_map_sub_idx=explode(",",$req_map_sub_idx);

	for($i=0;$i<count($arr_req_map_sub_idx);$i++){
		test2($arr_req_map_sub_idx[$i]);
	}

	exit;

}else if ($work=="map_list_update"){
	
	//봉사를 하면서
	//방문 or 부재, 방문거절 or 방문금지 체크할 때마다 업데이트

	$req_map_sub_idx=$_REQUEST["map_sub_idx"];
	$req_map_list_idx=$_REQUEST["map_list_idx"];
	$req_field=$_REQUEST["field"];
	$req_field_c=$req_field."_c";
	$req_val=$_REQUEST["val"];

	$sql="UPDATE map_normal_list SET ";
	$sql.="$req_field='$req_val', ";
	$sql.="$req_field_c='$req_val' ";
	$sql.="WHERE map_list_idx='$req_map_list_idx'";
	$result=mysql_query($sql);

	exit;

}else if ($work=="map_save"){

	//구분선 저장

	$req_map_list_line=$_REQUEST["map_list_line"];
	$req_map_list_idx=$_REQUEST["map_list_idx"];

	$sql ="UPDATE map_normal_list SET ";
	$sql.="map_list_line='$req_map_list_line' ";
	$sql.="WHERE map_list_idx='$req_map_list_idx'";
	$result=mysql_query($sql);

	exit;
}

function test($map_sub_idx){

	$sql="select * from map_normal_sub where map_sub_idx='".$map_sub_idx."'";
	$result=mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);
	$map_sub_no=$rs["map_sub_no"];
	$map_sub_info=$rs["map_sub_info"];

	$map_sub_no=strtolower($map_sub_no);
	$map_sub_no=str_replace("<br>"," ",$map_sub_no);
	?>
			<div id="map_sub_no" style="font-weight:bold;font-size:14px;">구역번호 : <?=$map_sub_no ?>, 구역명 : <?=$map_sub_info ?></div>
			<div class="" style="height:400px;overflow-y:auto;">
			<table class="table">
			<tr>
				<th>구분선</th>
				<th>길이름</th>
				<th>지번</th>
				<th>층</th>
				<th>이름/호</th>
				<th></th>
				<th>방문</th>
				<th>부재</th>
				<th>방문<br>거절</th>
				<th>방문<br>금지</th>
			</tr>
	<?
	$sql="select *, (SELECT house_type FROM db_house_type where house_idx=a.map_list_house_idx ) as house_type
			from map_normal_list a 
			where map_sub_idx='".$map_sub_idx."' ";	
	$sql.=" order by map_list_rank";
	$result=mysql_query($sql);

	while($rs=mysql_fetch_array($result)){
		
		$map_list_idx=$rs[map_list_idx];
		$map_list_line=$rs[map_list_line];
		$map_list_road=$rs[map_list_road];
		$map_list_road_no=$rs[map_list_road_no];
		$map_list_floor=$rs[map_list_floor];
		$map_list_info=$rs[map_list_info];
		$map_list_visit=$rs[map_list_visit];
		$map_list_enter=$rs[map_list_enter];
		$map_list_visit_c=$rs[map_list_visit_c];
		$map_list_enter_c=$rs[map_list_enter_c];
		$house_type=$rs[house_type];

		if ($map_list_line=="1"){
			$map_list_line_css="home-map-list-line-css";
			$map_list_line_checked="checked";
		}else{
			$map_list_line_css="";
			$map_list_line_checked="";
		}
		?>
				<tr>
					<td class="<?=$map_list_line_css ?>"><input type="checkbox" name="map_list_line_<?=$map_list_idx ?>" id="map_list_line_<?=$map_list_idx ?>" <?=$map_list_line_checked ?> onclick="map_save('<?=$map_list_idx ?>');"></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_road ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_road_no ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_floor ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_info ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$house_type ?></td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit(<?=$map_list_idx ?>,'Y')" name="map_list_visit" <?=($map_list_visit_c=="Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit(<?=$map_list_idx ?>,'N')" name="map_list_visit" <?=($map_list_visit_c=="N")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c=="Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_N_<?=$map_list_idx ?>" onclick="map_list_enter_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c=="N")?"checked":"" ?>>
					</td>
				</tr>
		<?
		}
		?>

			</table>
			</div>
<?
}


function test2($map_sub_idx){

	$sql="select * from map_normal_sub where map_sub_idx='".$map_sub_idx."'";
	$result=mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);
	$map_sub_no=$rs["map_sub_no"];
	$map_sub_info=$rs["map_sub_info"];

	$map_sub_no=strtolower($map_sub_no);
	$map_sub_no=str_replace("<br>"," ",$map_sub_no);
	?>
			<div id="map_sub_no" style="font-weight:bold;font-size:14px;">구역번호 : <?=$map_sub_no ?>, 구역명 : <?=$map_sub_info ?></div>
			<div class="" style="">
			<table class="table">
			<tr>
				<th>구분선</th>
				<th>길이름</th>
				<th>지번</th>
				<th>층</th>
				<th>이름/호</th>
				<th></th>
				<th>봉사<br>기록</th>
				<th>방문</th>
				<th>부재</th>
				<th>방문<br>거절</th>
				<th>방문<br>금지</th>
			</tr>
	<?
	$sql="select *
			, (SELECT house_type FROM db_house_type where house_idx=a.map_list_house_idx ) as house_type
			,(SELECT count(*) FROM map_normal_list_content WHERE map_list_idx=a.map_list_idx) as map_content_cnt
			from map_normal_list a 
			where map_sub_idx='".$map_sub_idx."' ";	
	$sql.=" order by map_list_rank";
	$result=mysql_query($sql);

	while($rs=mysql_fetch_array($result)){
		
		$map_list_idx=$rs[map_list_idx];
		$map_list_line=$rs[map_list_line];
		$map_list_road=$rs[map_list_road];
		$map_list_road_no=$rs[map_list_road_no];
		$map_list_floor=$rs[map_list_floor];
		$map_list_info=$rs[map_list_info];
		$map_list_visit=$rs[map_list_visit];
		$map_list_enter=$rs[map_list_enter];
		$map_list_visit_c=$rs[map_list_visit_c];
		$map_list_enter_c=$rs[map_list_enter_c];
		$house_type=$rs[house_type];
		$map_content_cnt=$rs[map_content_cnt];
		$map_list_house_idx=$rs[map_list_house_idx];

		if ($map_list_line=="1"){
			$map_list_line_css="home-map-list-line-css";
			$map_list_line_checked="checked";
		}else{
			$map_list_line_css="";
			$map_list_line_checked="";
		}


		if ($map_content_cnt == 0){
			$map_content_cnt_css = "btn-success";
		}else{
			$map_content_cnt_css = "btn-danger";
		}
		?>
				<tr>
					<td class="<?=$map_list_line_css ?>"><input type="checkbox" name="map_list_line_<?=$map_list_idx ?>" id="map_list_line_<?=$map_list_idx ?>" <?=$map_list_line_checked ?> onclick="map_save('<?=$map_list_idx ?>');"></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_road ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_road_no ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_floor ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$map_list_info ?></td>
					<td class="<?=$map_list_line_css ?>"><?=$house_type ?></td>
					<td class="<?=$map_list_line_css ?>"><a href="javascript:;" onclick="map_info_view('<?=$map_list_idx ?>','<?=$map_list_road ?>','<?=$map_list_road_no ?>','<?=$map_list_floor ?>','<?=$map_list_info ?>','<?=$map_list_house_idx?>')" class="btn <?=$map_content_cnt_css ?> btn-xs"> <?=$map_content_cnt ?> </a></td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c=="Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c=="N")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c=="Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_N_<?=$map_list_idx ?>" onclick="map_list_enter_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c=="N")?"checked":"" ?>>
					</td>
				</tr>
		<?
		}
		?>

			</table>
			</div>
<?
}
?>