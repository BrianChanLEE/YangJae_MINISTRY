<?
header('Content-Type: text/html; charset=utf-8');  // 응답의 콘텐츠 타입을 UTF-8로 설정하여 한글이 깨지지 않도록 함
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

session_check();  // 사용자가 로그인되어 있는지 확인하는 함수 호출

$work = $_REQUEST["work"];  // 요청에서 'work' 파라미터를 가져와 변수에 저장
$req_map_sub_idx = $_REQUEST["map_sub_idx"];  // 요청에서 'map_sub_idx' 파라미터를 가져와 변수에 저장

// 'ministry_start' 작업을 처리
if ($work == "ministry_start"){

	$m_idx = $_SESSION['m_idx'];  // 현재 세션에서 'm_idx' 값을 가져와 변수에 저장

	// 주어진 'map_sub_idx'에 해당하는 'ministry_list' 레코드를 조회
	$sql = "SELECT * FROM ministry_list WHERE map_sub_idx='$req_map_sub_idx'";
	$result = mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);
	$ministry_member_idx = $rs['ministry_member_idx'];

	$arr_m_m_idx = "";
	$arr_m_m_idx = explode(",", $ministry_member_idx);  // 현재 참여 중인 멤버들을 배열로 분할
	$arr_m_m_cnt = count($arr_m_m_idx);  // 멤버 수를 계산

	// 사용자가 이미 참여 중인지 확인
	$is_member = false;
	for ($i = 0; $i < $arr_m_m_cnt; $i++) {
		if ($arr_m_m_idx[$i] == $m_idx) {
			$is_member = true;
			break;
		}
	}

	if ($is_member) {  // 사용자가 이미 참여 중인 경우
		echo "이미 참여중입니다.";
		exit;
	} else {
		$add_m_idx = "";
		if ($ministry_member_idx == "") {
			$add_m_idx = $m_idx;  // 기존 참여자가 없으면 현재 사용자를 추가
		} else {
			$add_m_idx = $ministry_member_idx . "," . $m_idx;  // 기존 참여자 목록에 현재 사용자를 추가
		}
	}

	// 업데이트 쿼리를 실행하여 새로운 참여자 목록을 저장
	$sql = "UPDATE ministry_list SET ";
	$sql .= "ministry_member_idx='$add_m_idx' ";
	$sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
	$result = mysql_query($sql);

} else if ($work == "ministry_list") {

	// 'ministry_list'에 해당하는 'map_sub_idx'를 조회
	$sql1 = "SELECT * FROM ministry_list WHERE map_sub_idx='$req_map_sub_idx'";
	$result1 = mysql_query($sql1);
	$rs1 = mysql_fetch_array($result1, MYSQL_ASSOC);
	
	// 조건 확인
	$c_map_list_house_idx = $rs1["map_list_house_idx"];  // 가구 형태
	$c_map_list_visit = $rs1["map_list_visit"];  // 부재자 여부
	$c_map_list_enter = $rs1["map_list_enter"];  // 방문 거절 여부

	?>
	
	<div class="marginbottom50">
	<table class="table">
	<tr>
		<th>길이름</th>
		<th>지번</th>
		<th>층</th>
		<th>이름/호</th>
		<th></th>
		<th>봉사<br>기록</th>
		<th>방문</th>
		<th>부재</th>
		<th>방문<br>거절</th>
	</tr>
	<?
	// map_normal_list에서 해당 구역의 상세 정보를 조회
	$sql2 = "SELECT * 
			,(SELECT house_type FROM db_house_type WHERE house_idx=a.map_list_house_idx ) AS house_type 
			,(SELECT count(*) FROM map_normal_list_content WHERE map_list_idx=a.map_list_idx) AS map_content_cnt
			FROM map_normal_list a 
			WHERE map_sub_idx='$req_map_sub_idx' ";

	// 필터 조건이 주어졌을 경우 쿼리 조건에 추가 (현재는 주석 처리됨)
//	if ($c_map_list_house_idx > 0){
//		$sql2 .= "AND map_list_house_idx='$c_map_list_house_idx'";
//	}
//
//	if ($c_map_list_visit != ""){
//		$sql2 .= "AND map_list_visit='$c_map_list_visit'";
//	}
//
//	if ($c_map_list_enter != ""){
//		$sql2 .= "AND map_list_enter='$c_map_list_enter'";
//	}

	$sql2 .= " ORDER BY map_list_rank";
	$result2 = mysql_query($sql2);

	while ($rs2 = mysql_fetch_array($result2)) {
		
		$map_sub_idx = $rs2['map_sub_idx'];		
		$map_list_idx = $rs2['map_list_idx'];
		$map_list_line = $rs2['map_list_line'];
		$map_list_road = $rs2['map_list_road'];
		$map_list_road_no = $rs2['map_list_road_no'];
		$map_list_floor = $rs2['map_list_floor'];
		$map_list_info = $rs2['map_list_info'];
		$map_list_visit = $rs2['map_list_visit'];
		$map_list_enter = $rs2['map_list_enter'];
		$map_list_visit_c = $rs2['map_list_visit_c'];
		$map_list_enter_c = $rs2['map_list_enter_c'];
		$house_type = $rs2['house_type'];
		$map_content_cnt = $rs2['map_content_cnt'];

		$map_info = $map_list_road . " " . $map_list_road_no . " " . $map_list_floor . " " . $map_list_info;

		// 줄 스타일 설정 (첫 번째 줄 강조)
		if ($map_list_line == "1") {
			$map_list_line_css = "person-map-list-line-css";
		} else {
			$map_list_line_css = "";
		}
		?>
		<tr>
			<td class="road <?=$map_list_line_css ?>"><?=$map_list_road ?></td>
			<td class="road-no <?=$map_list_line_css ?>"><?=$map_list_road_no ?></td>
			<td class="<?=$map_list_line_css ?>"><?=$map_list_floor ?></td>
			<td class="<?=$map_list_line_css ?>"><?=$map_list_info ?></td>
			
			<td class="<?=$map_list_line_css ?>"><?=$house_type ?></td>
			<td class="<?=$map_list_line_css ?>"><a href="javascript:;" onclick="map_info_view('<?=$map_list_idx ?>','<?=$map_info ?>')" class="btn btn-success btn-xs"> <?=$map_content_cnt ?> </a></td>
			<td class="<?=$map_list_line_css ?>">
				<input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "Y") ? "checked" : "" ?>>
			</td>
			<td class="<?=$map_list_line_css ?>">
				<input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "N") ? "checked" : "" ?>>
			</td>
			<td class="<?=$map_list_line_css ?>">
				<input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "Y") ? "checked" : "" ?>>
			</td>
		</tr>
		<?
	}
	?>

	</table>
	</div>
<?	

} else if ($work == "map_list_update") {
	
	// 봉사를 하면서 방문 or 부재, 방문거절 or 방문금지 체크할 때마다 데이터를 업데이트
	$req_map_sub_idx = $_REQUEST["map_sub_idx"];
	$req_map_list_idx = $_REQUEST["map_list_idx"];
	$req_field = $_REQUEST["field"];
	$req_field_c = $req_field . "_c";  // 임시 공간 필드 이름 생성
	$req_val = $_REQUEST["val"];

	$sql = "UPDATE map_normal_list SET ";
	$sql .= "$req_field_c='$req_val' ";
	$sql .= "WHERE map_list_idx='$req_map_list_idx'";
	$result = mysql_query($sql);

	// 봉사 시작일을 저장 (처음 체크할 때 봉사 시작일을 설정)
	$req_ministry_start_date_isnull = $_REQUEST["ministry_start_date_isnull"];

	if ($req_ministry_start_date_isnull == "Y"){		
		$set_ministry_start_date = date("Y-m-d");
		$sql = "UPDATE ministry_list SET ministry_start_date='$set_ministry_start_date' WHERE map_sub_idx='$req_map_sub_idx'";
		$result = mysql_query($sql);
	}

} else if ($work == "map_info_view_del") {

	// 각각의 집에 대한 정보를 삭제
	$req_map_content_idx = $_REQUEST["map_content_idx"];

	$sql = "DELETE FROM map_normal_list_content WHERE map_content_idx='$req_map_content_idx'";
	$result = mysql_query($sql);	

	echo "ok";
	exit;

} else if ($work == "map_info_view") {

	// 각각의 집에 대한 정보(내용)를 가져오기
	$req_map_list_idx = $_REQUEST["map_list_idx"];
	$m_idx = $_SESSION['m_idx'];

	$sql = "SELECT *, (SELECT m_name FROM member WHERE m_idx=a.map_member_idx) AS map_member_name FROM map_normal_list_content a WHERE map_list_idx='$req_map_list_idx' ORDER BY map_content_date DESC";
	$result_type = mysql_query($sql);

	while ($rs = mysql_fetch_array($result_type)) {

		$map_content_idx = $rs['map_content_idx'];
		$map_content_content = $rs['map_content_content'];
		$map_content_date = $rs['map_content_date'];
		$map_member_idx = $rs['map_member_idx'];
		$map_member_name = $rs['map_member_name'];
		
		$txt = "<div id='map_content_idx_" . $map_content_idx . "' style='border-bottom:1px solid #b1b1b1;padding:15px 0; font-size:12px;'>";
		$txt .= "<strong>" . $map_member_name . "</strong> " . $map_content_date . "<br>";
		$txt .= $map_content_content;
		
		// 본인이 작성했거나 관리자 권한이 있는 경우 삭제 가능
		if ($map_member_idx == $m_idx || mgr_check("1,2,3")){
			$txt .= "<a href='javascript:;' class='btn btn-danger btn-sm pull-right' onclick='map_info_view_del(" . $map_content_idx . ")'>삭제</a>";
		}
		
		$txt .= "</div>";

		echo $txt;
	}

} else if ($work == "map_info_save") {

	// 각각의 집에 대한 정보를 업데이트
	$m_idx = $_SESSION['m_idx'];
	$req_map_list_idx = $_REQUEST["map_list_idx"];
	$req_map_content_content = $_REQUEST["map_content_content"];
	$req_map_content_content = str_replace("'", "''", $req_map_content_content);  // 작은따옴표 처리
	$req_map_content_content = nl2br($req_map_content_content);  // 줄바꿈을 <br> 태그로 변환

	$sql = "INSERT INTO map_normal_list_content(map_list_idx, map_content_content, map_content_date, map_member_idx) VALUES ( ";
	$sql .= "'$req_map_list_idx', ";
	$sql .= "'$req_map_content_content', ";
	$sql .= "now(), ";
	$sql .= "'$m_idx')";

	$result = mysql_query($sql);

} else if ($work == "ministry_complete") {

	// 봉사하면서 체크한 항목들을 실제 저장 필드로 복사
	// map_list_visit_c(임시공간) -> map_list_visit 으로 복사
	// map_list_enter_c(임시공간) -> map_list_enter 으로 복사
	$sql = "UPDATE map_normal_list SET map_list_visit=map_list_visit_c, map_list_enter=map_list_enter_c WHERE map_sub_idx='$req_map_sub_idx'";
	$result = mysql_query($sql);

	// 모든 방문 or 부재 항목이 체크되어 있는지 확인
	$sql = "SELECT count(*) FROM map_normal_list WHERE map_sub_idx='$req_map_sub_idx' AND map_list_visit=''";
	$result_count = mysql_query($sql);
	$result_row = mysql_fetch_row($result_count);
	$total_row = $result_row[0];

	if ($total_row > 0) {

		// 일부 항목이 빠져있다면 날짜는 null
		$sql = "UPDATE ministry_list SET ministry_end_date=null WHERE map_sub_idx='$req_map_sub_idx'";
		$result = mysql_query($sql);

		echo "수고하셨습니다.";
		exit;

	} else {

		// 모든 항목이 체크되어 있다면 날짜 입력
		$set_ministry_end_date = date("Y-m-d");

		$sql = "UPDATE ministry_list SET ministry_end_date='$set_ministry_end_date' WHERE map_sub_idx='$req_map_sub_idx'";
		$result = mysql_query($sql);

		echo "봉사를 완료했습니다.";
		exit;
	}
}
?>