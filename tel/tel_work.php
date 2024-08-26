<?php
header('Content-Type: text/html; charset=utf-8');  // 응답의 콘텐츠 타입을 UTF-8로 설정하여 한글이 깨지지 않도록 함
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

session_check();  // 사용자가 로그인되어 있는지 확인하는 함수 호출

// 요청 파라미터에서 필요한 데이터를 변수에 저장
$work = $_REQUEST["work"];
$req_tel_idx = $_REQUEST["tel_idx"];
$req_tel_ministry_idx = $_REQUEST["tel_ministry_idx"];

if ($work == "tel_start") {  // 'work' 값이 'tel_start'인 경우

	$m_idx = $_SESSION['m_idx'];  // 현재 세션에서 사용자의 ID를 가져옴

	// 현재 전도인이 참여 중인지 확인하는 SQL 쿼리
	$sql = "SELECT * FROM tel_service WHERE tel_idx='$req_tel_idx' AND tel_member_idx=0";
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	if ($num_rows > 0) {  // 다른 전도인이 참여 중이지 않은 경우

		// 'tel_service' 테이블에서 해당 전화 서비스 항목을 업데이트하는 SQL 쿼리
		$sql = "UPDATE tel_service SET ";
		$sql .= "tel_member_idx='$m_idx', ";
		$sql .= "tel_service_date=now() ";
		$sql .= "WHERE tel_idx='$req_tel_idx'";
		$result = mysql_query($sql);

		// 'tel_ministry_stat' 테이블에서 새로운 봉사 기록을 추가하기 위해 최대 'tel_ministry_idx' 값을 가져오는 SQL 쿼리
		$sql = "SELECT MAX(tel_ministry_idx)+1 FROM tel_ministry_stat";
		$result = mysql_query($sql);
		$max_fetch = mysql_fetch_row($result);
		$max_tel_ministry_idx = $max_fetch[0];

		$tel_ministry_date = date("Y-m-d");  // 오늘 날짜를 저장
		$tel_ministry_start = date("H:i");  // 현재 시간을 저장

		// 'tel_ministry_stat' 테이블에 새로운 봉사 기록을 삽입하는 SQL 쿼리
		$sql = "INSERT INTO tel_ministry_stat (tel_idx, tel_ministry_idx, tel_ministry_name, tel_ministry_date, tel_ministry_start) VALUES (
			'$req_tel_idx',	
			'$max_tel_ministry_idx',
			'$m_idx',
			'$tel_ministry_date',
			'$tel_ministry_start'
		)";
		$result = mysql_query($sql);

		echo "";  // 빈 문자열을 반환하여 성공을 알림

	} else {  // 다른 전도인이 이미 참여 중인 경우
		echo "다른 전도인이 참여중입니다.";
	}

} else if ($work == "tel_end") {  // 'work' 값이 'tel_end'인 경우

	$m_idx = $_SESSION['m_idx'];  // 현재 세션에서 사용자의 ID를 가져옴

	// 'tel_service' 테이블에서 해당 전도인의 참여를 종료하는 SQL 쿼리
	$sql = "UPDATE tel_service SET ";
	$sql .= "tel_member_idx='' ";
	$sql .= "WHERE tel_idx='$req_tel_idx' AND tel_member_idx='$m_idx'";
	$result = mysql_query($sql);

	$tel_ministry_end = date("H:i");  // 현재 시간을 봉사 종료 시간으로 저장

	// 'tel_ministry_stat' 테이블에서 해당 봉사 기록의 종료 시간을 업데이트하는 SQL 쿼리
	$sql = "UPDATE tel_ministry_stat SET
		tel_ministry_end='$tel_ministry_end'
		WHERE tel_ministry_idx='$req_tel_ministry_idx'";
	$result = mysql_query($sql);
}
?>