<?php
include "include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "include/function.php";  // 공통 함수들을 포함

// 'site_option' 테이블에서 'option_fullname' 컬럼의 데이터 타입을 변경하는 SQL 쿼리
$sql = "ALTER TABLE site_option CHANGE option_fullname option_fullname VARCHAR(300) NULL";
$result = mysql_query($sql);
echo "Modify option_fullname change datatype <br>";  // 변경 완료 메시지 출력

// 'site_option' 테이블에서 특정 'option_idx' 값을 가진 레코드가 존재하는지 확인하는 SQL 쿼리
$sql = "SELECT * FROM site_option WHERE option_idx IN (4,5)";
$result = mysql_query($sql);
$num = mysql_num_rows($result);
if ($num == 0) {  // 레코드가 존재하지 않는 경우

	// 'site_option' 테이블에 새로운 옵션을 삽입하는 SQL 쿼리
	$sql = "INSERT INTO site_option (option_idx, option_name, option_fullname, option_yn) VALUES
			(4, 'ministry_number', '봉사적정인원', 5),
			(5, 'ministry_notice', '이 왕국의 좋은 소식이 모든 민족에게 증거되기 위해 사람이 거주하는 온 땅에 전파될 것입니다. 그리고 끝이 올 것입니다. - 마태 24:24', 0);";
	$result = mysql_query($sql);
	echo "Add site option 4,5<br>";  // 삽입 완료 메시지 출력
}

// 'site_option' 테이블에서 추가적인 옵션(6,7)이 존재하는지 확인하는 SQL 쿼리
$sql = "SELECT * FROM site_option WHERE option_idx IN (6,7)";
$result = mysql_query($sql);
$num = mysql_num_rows($result);
if ($num == 0) {  // 레코드가 존재하지 않는 경우

	// 'site_option' 테이블에 새로운 옵션을 삽입하는 SQL 쿼리
	$sql = "INSERT INTO site_option (option_idx, option_name, option_fullname, option_yn) VALUES
			(6, 'guide_choice_show', '구역지정', 1),
			(7, 'guide_extract_show', '구역추출', 1);";
	$result = mysql_query($sql);
	echo "Add site option 6,7<br>";  // 삽입 완료 메시지 출력
}

// 'member' 테이블에서 'm_idx' 값이 1인 레코드가 존재하는지 확인하는 SQL 쿼리
$sql = "SELECT * FROM member WHERE m_idx = 1";
$result = mysql_query($sql);
$num = mysql_num_rows($result);
if ($num == 0) {  // 레코드가 존재하지 않는 경우

	// 'member' 테이블에 관리자 계정을 삽입하는 SQL 쿼리
	$sql = "INSERT INTO `member` (`m_idx`, `m_name`, `m_pw`, `m_hp`, `m_mgr`, `m_position`, `m_pioneer`, `m_sex`, `m_sos_call`, `m_regdate`, `m_editdate`) VALUES
			(1, '장요섭', 'qqqq', '01000000000', '1,5', '3', '2', '1', 0, '2017-11-23 14:43:00', '2017-12-11 19:47:24');";
	$result = mysql_query($sql);
	echo 'Add member<br>';  // 삽입 완료 메시지 출력

	// 관리자 계정의 이름과 전화번호를 업데이트하는 SQL 쿼리
	$sql = "UPDATE `member` SET `m_name`='관리자', `m_hp`='01000000000' WHERE `m_idx`=1";
	$result = mysql_query($sql);
	echo 'Modify member name<br>';  // 업데이트 완료 메시지 출력

} else {  // 레코드가 이미 존재하는 경우

	// 관리자 계정의 이름과 전화번호를 업데이트하는 SQL 쿼리
	$sql = "UPDATE `member` SET `m_name`='관리자', `m_hp`='01000000000' WHERE `m_idx`=1";
	$result = mysql_query($sql);
	echo 'Already exists. Modify member name<br>';  // 이미 존재함을 알리는 메시지 출력
}

// 'map_normal_sub' 테이블에 'map_sub_notice' 컬럼이 존재하는지 확인하는 SQL 쿼리
$sql = "SHOW COLUMNS FROM map_normal_sub LIKE 'map_sub_notice';";
$result = mysql_query($sql);
$num = mysql_num_rows($result);
if ($num == 0) {  // 컬럼이 존재하지 않는 경우

	// 'map_normal_sub' 테이블에 'map_sub_notice' 컬럼을 추가하는 SQL 쿼리
	$sql = "ALTER TABLE map_normal_sub ADD map_sub_notice VARCHAR(500) NULL;";
	$result = mysql_query($sql);
	echo 'Add columns to map_normal_sub table <br>';  // 컬럼 추가 완료 메시지 출력
}

echo "ok";  // 모든 작업이 완료되었음을 알림

// 아파트 봉사 시 주의사항에 대한 안내 (코드 외 주석)
?>