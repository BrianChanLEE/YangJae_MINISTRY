<?
header('Content-Type: text/html; charset=utf-8');  // 응답의 콘텐츠 타입을 UTF-8로 설정하여 한글이 깨지지 않도록 함
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

session_check();  // 사용자가 로그인되어 있는지 확인하는 함수 호출

$work = $_REQUEST["work"];  // 요청에서 'work' 파라미터를 가져와 변수에 저장

if ($work == "info_save"){  // 'work' 파라미터가 'info_save' 인 경우

	$m_idx = $_SESSION['m_idx'];  // 현재 세션에서 'm_idx' 값을 가져와 변수에 저장
	$req_pwd = $_REQUEST["pwd"];  // 요청에서 새로운 비밀번호를 가져와 변수에 저장

	// 사용자의 비밀번호를 업데이트하는 SQL 쿼리 작성
	$sql = "UPDATE member SET ";
	$sql .= "m_pw='$req_pwd' ";
	$sql .= "WHERE m_idx='$m_idx'";
	$result = mysql_query($sql);  // SQL 쿼리를 실행하고 결과를 변수에 저장
	
	if ($result == "1"){  // 쿼리가 성공적으로 실행된 경우
		// 세션에서 사용자 정보를 초기화
		$_SESSION['m_idx'] = "";
		$_SESSION['m_name'] = "";
		$_SESSION['m_mgr'] = "";
		$_SESSION['m_position'] = "";
		$_SESSION['m_pioneer'] = "";
		$_SESSION['m_sex'] = "";		
		
		// 쿠키 'jwbs'를 제거
		$cookie_name = "jwbs";
		setcookie("jwbs", "", time() - 3600, "/");

		// 세션을 초기화하고 파괴
		session_unset();
		session_destroy();
	}else{
		// 쿼리가 실패한 경우 'error' 메시지 출력
		echo "error";
	}
}
?>