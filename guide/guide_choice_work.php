<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";  // 데이터베이스 연결 파일 포함
include "../include/function.php";  // 공통 함수 파일 포함

session_check();  // 세션 확인

if (!mgr_check("1,2,3")){  // 관리 권한 확인 (1, 2, 3 등급만 접근 가능)
	echo "<script>location.href='/home/';</script>";  // 권한이 없으면 홈으로 리다이렉트
}

$work = $_REQUEST["work"];  // 요청 작업 변수
$req_ministry_date = $_REQUEST["ministry_date"];  // 요청된 봉사 날짜
$req_ministry_service_idx = $_REQUEST["ministry_service_idx"];  // 요청된 봉사 서비스 인덱스
$req_map_sub_idx = $_REQUEST["map_sub_idx"];  // 요청된 서브 구역 인덱스

// 요청된 작업에 따라 다른 작업 수행
if ($work == "ministry_del"){

	// 구역 삭제
	$sql = "DELETE FROM ministry_list ";
	$sql.= "WHERE map_sub_idx in (".$req_map_sub_idx.")";  // 요청된 서브 구역 인덱스를 기준으로 삭제
	$result = mysql_query($sql);  // 쿼리 실행

	exit;  // 작업 종료

}else if ($work == "ministry_add"){

	// 새로운 구역 추가 또는 기존 구역 업데이트
	$sql = "select * from ministry_list where map_sub_idx in (".$req_map_sub_idx.")";
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);

	if ($num == 0){

		// 구역이 존재하지 않을 경우 새로 추가
		$sql = "INSERT INTO ministry_list(map_sub_idx, ministry_date, ministry_service_idx, ministry_member_idx)
		select map_sub_idx, '".$req_ministry_date."', '".$req_ministry_service_idx."', ''
		from map_normal_sub where 
		map_sub_idx in (".$req_map_sub_idx.")";
		$result = mysql_query($sql);

	}else{

		// 구역이 이미 존재할 경우 업데이트
		$sql = "UPDATE ministry_list SET 
		ministry_date = '".$req_ministry_date."', 
		ministry_service_idx = '".$req_ministry_service_idx."' ,
		map_list_house_idx = NULL,
		map_list_visit = NULL,
		map_list_enter = NULL
		WHERE map_sub_idx in (".$req_map_sub_idx.")";
		$result = mysql_query($sql);

	}

	exit;

}else if ($work == "map_normal_list"){

	// 선택된 구역의 상세 정보 가져오기
	$arr_req_map_sub_idx = explode(",", $req_map_sub_idx);  // 서브 구역 인덱스를 배열로 분리

	for($i = 0; $i < count($arr_req_map_sub_idx); $i++){
		test($arr_req_map_sub_idx[$i]);  // 각 서브 구역에 대해 test 함수 호출
	}

	exit;

}else if ($work == "map_normal_list2"){

	// map_normal_list와 유사하지만 다른 상세 정보를 가져옴
	$arr_req_map_sub_idx = explode(",", $req_map_sub_idx);  // 서브 구역 인덱스를 배열로 분리

	for($i = 0; $i < count($arr_req_map_sub_idx); $i++){
		test2($arr_req_map_sub_idx[$i]);  // 각 서브 구역에 대해 test2 함수 호출
	}

	exit;

}else if ($work == "map_list_update"){

	// 봉사 도중 방문 상태 업데이트
	$req_map_sub_idx = $_REQUEST["map_sub_idx"];  // 서브 구역 인덱스
	$req_map_list_idx = $_REQUEST["map_list_idx"];  // 방문 목록 인덱스
	$req_field = $_REQUEST["field"];  // 업데이트할 필드
	$req_field_c = $req_field . "_c";  // 업데이트할 필드의 체크 여부 저장 필드
	$req_val = $_REQUEST["val"];  // 업데이트할 값

	$sql = "UPDATE map_normal_list SET ";
	$sql.= "$req_field = '$req_val', ";  // 선택한 필드를 업데이트
	$sql.= "$req_field_c = '$req_val' ";  // 체크 여부 필드도 업데이트
	$sql.= "WHERE map_list_idx = '$req_map_list_idx'";
	$result = mysql_query($sql);

	exit;

}else if ($work == "map_save"){

	// 구분선 저장
	$req_map_list_line = $_REQUEST["map_list_line"];  // 구분선 체크 여부
	$req_map_list_idx = $_REQUEST["map_list_idx"];  // 방문 목록 인덱스

	$sql = "UPDATE map_normal_list SET ";
	$sql.= "map_list_line = '$req_map_list_line' ";  // 구분선 업데이트
	$sql.= "WHERE map_list_idx = '$req_map_list_idx'";
	$result = mysql_query($sql);

	exit;
}

function test($map_sub_idx){
	// 구역의 기본 정보와 방문 리스트를 가져와 출력하는 함수

	$sql = "select * from map_normal_sub where map_sub_idx = '".$map_sub_idx."'";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);
	$map_sub_no = $rs["map_sub_no"];  // 서브 구역 번호
	$map_sub_info = $rs["map_sub_info"];  // 서브 구역 정보

	$map_sub_no = strtolower($map_sub_no);  // 서브 구역 번호를 소문자로 변환
	$map_sub_no = str_replace("<br>", " ", $map_sub_no);  // 줄바꿈 태그를 공백으로 대체
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
	$sql = "select *, (SELECT house_type FROM db_house_type where house_idx = a.map_list_house_idx ) as house_type
			from map_normal_list a 
			where map_sub_idx = '".$map_sub_idx."' ";	
	$sql .= " order by map_list_rank";
	$result = mysql_query($sql);

	while($rs = mysql_fetch_array($result)){
		
		$map_list_idx = $rs['map_list_idx'];  // 방문 목록 인덱스
		$map_list_line = $rs['map_list_line'];  // 구분선 여부
		$map_list_road = $rs['map_list_road'];  // 길 이름
		$map_list_road_no = $rs['map_list_road_no'];  // 길 번호
		$map_list_floor = $rs['map_list_floor'];  // 층수
		$map_list_info = $rs['map_list_info'];  // 이름/호 정보
		$map_list_visit = $rs['map_list_visit'];  // 방문 여부
		$map_list_enter = $rs['map_list_enter'];  // 방문 금지 여부
		$map_list_visit_c = $rs['map_list_visit_c'];  // 방문 체크 여부
		$map_list_enter_c = $rs['map_list_enter_c'];  // 방문 금지 체크 여부
		$house_type = $rs['house_type'];  // 주택 유형

		// 구분선이 설정되어 있는지 확인
		if ($map_list_line == "1"){
			$map_list_line_css = "home-map-list-line-css";
			$map_list_line_checked = "checked";
		}else{
			$map_list_line_css = "";
			$map_list_line_checked = "";
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
						<input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit(<?=$map_list_idx ?>,'Y')" name="map_list_visit" <?=($map_list_visit_c == "Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit(<?=$map_list_idx ?>,'N')" name="map_list_visit" <?=($map_list_visit_c == "N")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_N_<?=$map_list_idx ?>" onclick="map_list_enter_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "N")?"checked":"" ?>>
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
	// 구역의 기본 정보와 방문 리스트를 가져와 출력하는 함수 (test 함수와 유사하지만 추가 정보 포함)

	$sql = "select * from map_normal_sub where map_sub_idx = '".$map_sub_idx."'";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);
	$map_sub_no = $rs["map_sub_no"];  // 서브 구역 번호
	$map_sub_info = $rs["map_sub_info"];  // 서브 구역 정보

	$map_sub_no = strtolower($map_sub_no);  // 서브 구역 번호를 소문자로 변환
	$map_sub_no = str_replace("<br>", " ", $map_sub_no);  // 줄바꿈 태그를 공백으로 대체
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
	$sql = "select *
			, (SELECT house_type FROM db_house_type where house_idx = a.map_list_house_idx ) as house_type
			,(SELECT count(*) FROM map_normal_list_content WHERE map_list_idx = a.map_list_idx) as map_content_cnt
			from map_normal_list a 
			where map_sub_idx = '".$map_sub_idx."' ";	
	$sql .= " order by map_list_rank";
	$result = mysql_query($sql);

	while($rs = mysql_fetch_array($result)){
		
		$map_list_idx = $rs['map_list_idx'];  // 방문 목록 인덱스
		$map_list_line = $rs['map_list_line'];  // 구분선 여부
		$map_list_road = $rs['map_list_road'];  // 길 이름
		$map_list_road_no = $rs['map_list_road_no'];  // 길 번호
		$map_list_floor = $rs['map_list_floor'];  // 층수
		$map_list_info = $rs['map_list_info'];  // 이름/호 정보
		$map_list_visit = $rs['map_list_visit'];  // 방문 여부
		$map_list_enter = $rs['map_list_enter'];  // 방문 금지 여부
		$map_list_visit_c = $rs['map_list_visit_c'];  // 방문 체크 여부
		$map_list_enter_c = $rs['map_list_enter_c'];  // 방문 금지 체크 여부
		$house_type = $rs['house_type'];  // 주택 유형
		$map_content_cnt = $rs['map_content_cnt'];  // 봉사 기록 수
		$map_list_house_idx = $rs['map_list_house_idx'];  // 주택 인덱스

		// 구분선이 설정되어 있는지 확인
		if ($map_list_line == "1"){
			$map_list_line_css = "home-map-list-line-css";
			$map_list_line_checked = "checked";
		}else{
			$map_list_line_css = "";
			$map_list_line_checked = "";
		}


		if ($map_content_cnt == 0){
			$map_content_cnt_css = "btn-success";  // 봉사 기록이 없으면 초록색 버튼
		}else{
			$map_content_cnt_css = "btn-danger";  // 봉사 기록이 있으면 빨간색 버튼
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
						<input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "N")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "Y")?"checked":"" ?>>
					</td>
					<td class="<?=$map_list_line_css ?>">
						<input type="checkbox" id="map_list_enter_N_<?=$map_list_idx ?>" onclick="map_list_enter_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "N")?"checked":"" ?>>
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