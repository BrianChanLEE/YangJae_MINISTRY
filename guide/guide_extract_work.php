<?
// 헤더에 콘텐츠 타입을 HTML로 설정하고 UTF-8 인코딩을 사용함
header('Content-Type: text/html; charset=utf-8');

// 데이터베이스 연결 파일과 함수 파일 포함
include "../include/db_connect.php";
include "../include/function.php";

// 세션이 유효한지 확인하는 함수 호출
session_check();

// 사용자가 관리자 권한을 가지고 있는지 확인, 권한이 없으면 홈으로 리디렉션
if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
}

// 요청된 작업 종류를 가져옴 (ex: ministry_add, ministry_del 등)
$work=$_REQUEST["work"];

// 사용자가 입력한 봉사 날짜를 가져옴
$req_ministry_date=$_REQUEST["ministry_date"];

// 사용자가 선택한 봉사 형태의 인덱스를 가져옴
$req_ministry_service_idx=$_REQUEST["ministry_service_idx"];

// 사용자가 선택한 구역 인덱스를 가져옴 (여러 개일 수 있음)
$req_map_sub_idx=$_REQUEST["map_sub_idx"];

// 필터링 조건: 주택 유형, 방문 상태, 방문 금지 여부를 가져옴
$req_map_list_house_idx=$_REQUEST["map_list_house_idx"];
$req_map_list_visit=$_REQUEST["map_list_visit"];
$req_map_list_enter=$_REQUEST["map_list_enter"];

// 주택 유형이 없을 경우 null로 설정, 있을 경우 해당 값을 설정
if ($req_map_list_house_idx==""){
	$tmp_map_list_house_idx="null";
}else{
	$tmp_map_list_house_idx="'".$req_map_list_house_idx."'";
}

// 방문 상태가 없을 경우 null로 설정, 있을 경우 해당 값을 설정
if ($req_map_list_visit==""){
	$tmp_map_list_visit="null";
}else{
	$tmp_map_list_visit="'".$req_map_list_visit."'";
}

// 방문 금지 여부가 없을 경우 null로 설정, 있을 경우 해당 값을 설정
if ($req_map_list_enter==""){
	$tmp_map_list_enter="null";
}else{
	$tmp_map_list_enter="'".$req_map_list_enter."'";
}

// 작업이 'ministry_del'인 경우
if ($work=="ministry_del"){

	// 선택된 구역 인덱스에 해당하는 봉사 리스트에서 데이터를 삭제
	$sql = "DELETE FROM ministry_list ";
	$sql.= "WHERE map_sub_idx in (".$req_map_sub_idx.")";
	$result=mysql_query($sql);	

	// 작업 완료 후 종료
	exit;	

// 작업이 'ministry_add'인 경우
}else if ($work=="ministry_add"){

	// 선택된 구역 인덱스가 이미 봉사 리스트에 있는지 확인
	$sql = "select * from ministry_list where map_sub_idx in (".$req_map_sub_idx.")";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);

	// 봉사 리스트에 없는 경우, 새로 추가
	if ($num==0){

		// 새로운 봉사 기록을 추가
		$sql = "INSERT INTO ministry_list(map_sub_idx, ministry_date, ministry_service_idx, map_list_house_idx, map_list_visit, map_list_enter)
		select map_sub_idx, 
		'".$req_ministry_date."', 
		'".$req_ministry_service_idx."' ,
		$tmp_map_list_house_idx ,
		$tmp_map_list_visit ,
		$tmp_map_list_enter  
		from map_normal_sub where 
		map_sub_idx in (".$req_map_sub_idx.")";
		$result=mysql_query($sql);
	
	// 봉사 리스트에 이미 있는 경우, 기존 기록을 업데이트
	}else{

		// 기존 봉사 기록을 업데이트
		$sql = "UPDATE ministry_list SET 
		ministry_date = '".$req_ministry_date."', 
		ministry_service_idx='".$req_ministry_service_idx."' ,
		map_list_house_idx=$tmp_map_list_house_idx ,
		map_list_visit=$tmp_map_list_visit,
		map_list_enter=$tmp_map_list_enter 
		WHERE map_sub_idx in (".$req_map_sub_idx.")";
		$result=mysql_query($sql);

	}

	// 작업 완료 후 종료
	exit;

// 작업이 'map_normal_list'인 경우
}else if ($work="map_normal_list"){

	// 구역 인덱스를 콤마로 분리하여 배열로 만듦
	$arr_req_map_sub_idx=explode(",",$req_map_sub_idx);

	// 배열의 각 구역 인덱스에 대해 test 함수 호출
	for($i=0;$i<count($arr_req_map_sub_idx);$i++){

		// test 함수를 호출하여 구역 정보를 출력
		test($arr_req_map_sub_idx[$i], $req_map_list_house_idx, $req_map_list_visit, $req_map_list_enter);
	}

	// 작업 완료 후 종료
	exit;
}

// test 함수 정의, 주어진 구역 인덱스와 필터 조건에 맞는 구역 정보를 출력
function test($map_sub_idx, $map_list_house_idx, $map_list_visit, $map_list_enter){

	// 주어진 구역 인덱스에 대한 구역 정보 조회
	$sql="select * from map_normal_sub where map_sub_idx='".$map_sub_idx."'";
	$result=mysql_query($sql);
	$rs = mysql_fetch_array($result, MYSQL_ASSOC);

	// 구역 번호와 구역 정보를 변수에 저장
	$map_sub_no=$rs["map_sub_no"];
	$map_sub_info=$rs["map_sub_info"];

	// 구역 번호를 소문자로 변환하고, 줄 바꿈을 공백으로 대체
	$map_sub_no=strtolower($map_sub_no);
	$map_sub_no=str_replace("<br>"," ",$map_sub_no);
	?>
			<div id="map_sub_no" style="font-weight:bold;font-size:14px;">구역번호 : <?=$map_sub_no ?>, 구역명 : <?=$map_sub_info ?></div>
			<div class="" style="height:400px;overflow-y:auto;">
			<table class="table">
			<tr>
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
	// 선택된 구역에 속한 집들의 정보를 조회
	$sql="select *, (SELECT house_type FROM db_house_type where house_idx=a.map_list_house_idx ) as house_type
			from map_normal_list a 
			where map_sub_idx='".$map_sub_idx."' ";

	// 주택 유형 필터가 설정된 경우 SQL 조건 추가
	if ($map_list_house_idx!=""){
	$sql.=" and map_list_house_idx in (".$map_list_house_idx.")";
	}

	// 방문 상태 필터가 설정된 경우 SQL 조건 추가
	if ($map_list_visit!=""){
	$sql.=" and (map_list_visit = '".$map_list_visit."' or map_list_visit = '')";
	}

	// 방문 금지 필터가 설정된 경우 SQL 조건 추가
	if ($map_list_enter!=""){
	$sql.=" and map_list_enter = '".$map_list_enter."'";
	}
	
	// 조회된 데이터를 순서대로 출력
	$sql.=" order by map_list_rank";
	$result=mysql_query($sql);

	// 각 집의 데이터를 테이블 행으로 출력
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

		// 해당 항목이 구분선으로 설정된 경우 CSS 클래스 추가
		if ($map_list_line=="1"){
			$map_list_line_css="map_list_line_css";
		}else{
			$map_list_line_css="";
		}
		?>
				<tr>
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
					</td class="<?=$map_list_line_css ?>">
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