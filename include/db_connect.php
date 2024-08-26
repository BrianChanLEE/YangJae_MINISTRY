<?
define("MINISTRY_RECYCLE_SECOND", 1000*15); /*15초*/  // 15초를 밀리초 단위로 정의한 상수
define("DAUM_MAP_API", "ac06e512d4b93df81a4081cc20f6dd5c");  // Daum 지도 API 키를 상수로 정의
define("JW_MANUAL", "jw_ministry_manual_vol_1.1.pdf");  // JW 매뉴얼 파일 이름을 상수로 정의

// 데이터베이스 접속 정보를 설정
$host="localhost";  // 데이터베이스 호스트 이름
$user="root";  // 데이터베이스 사용자 이름
$password="Avalon@@";  // 데이터베이스 사용자 비밀번호
$dbname="yangjae1914";  // 데이터베이스 이름

// 데이터베이스 연결을 설정
$objConn=mysql_connect("$host", "$user", "$password");  // MySQL 서버에 연결
//mysql_query("SET NAMES euckr"); // 한글 깨짐을 방지하기 위한 인코딩 설정 (주석 처리됨)
mysql_select_db("$dbname", $objConn);  // 선택된 데이터베이스를 사용하도록 설정

// 보안상의 이유로 몇 가지 전역 변수를 초기화
$ext_arr = array (
    'PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
    'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
    'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS'
);  // 초기화할 변수들을 배열로 정의
$ext_cnt = count($ext_arr);  // 배열의 길이를 계산
for($i = 0; $i < $ext_cnt; $i++) {  // 배열을 순회하면서 각 전역 변수들을 초기화
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);  // GET 요청에 해당 변수가 있으면 제거
}
@extract($_GET);  // GET 변수들을 현재 스코프로 추출
@extract($_POST);  // POST 변수들을 현재 스코프로 추출
@extract($_SERVER);  // SERVER 변수들을 현재 스코프로 추출
//@extract($_REQUEST);  // REQUEST 변수들을 현재 스코프로 추출 (주석 처리됨)

// 데이터베이스 연결 상태를 확인하고, 실패하면 오류 메시지를 출력하고 종료
if (!$objConn) {
    die("Connection failed: " . mysqli_connect_error());  // 연결 실패 시 오류 메시지 출력
}
//echo "Connected successfully";  // 성공적으로 연결되었을 때 메시지 (주석 처리됨)

session_start();  // 세션 시작

// 오류 보고 수준과 오류 메시지 출력 설정 (현재 주석 처리됨)
//error_reporting(E_ALL);  // 모든 오류를 보고
//ini_set("display_errors", 1);  // 오류 메시지 출력 설정
?>