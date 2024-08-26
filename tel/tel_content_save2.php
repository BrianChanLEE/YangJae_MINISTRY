<?php
header('Content-Type: text/html; charset=utf-8');  // 응답의 콘텐츠 타입을 UTF-8로 설정하여 한글이 깨지지 않도록 함
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

session_check();  // 사용자가 로그인되어 있는지 확인하는 함수 호출

// 요청 파라미터에서 필요한 데이터를 변수에 저장
$req_tel_write = $_REQUEST["tel_write"];
$req_tel_idx = $_REQUEST["tel_idx"];
$req_tel_list_idx = $_REQUEST["tel_list_idx"];
$req_tel_content_idx = $_REQUEST["tel_content_idx"];
$req_tel_content_content2 = $_REQUEST["tel_content_content2"];

// 'tel_content_list' 테이블에서 특정 'tel_content_idx'를 가진 레코드의 내용을 업데이트하는 SQL 쿼리 작성
$sql = "UPDATE tel_content_list SET ";
$sql .= "tel_content_content='$req_tel_content_content2' ";
$sql .= "WHERE tel_content_idx='$req_tel_content_idx'";
$result = mysql_query($sql);  // SQL 쿼리를 실행하고 결과를 변수에 저장

// 쿼리 실행이 성공한 경우
if ($result == "1") {
?>
	<script type="text/javascript">
	<!--
		location.href="tel_content.html?tel_write=<?=$req_tel_write ?>&tel_idx=<?=$req_tel_idx ?>&tel_list_idx=<?=$req_tel_list_idx ?>";  // 성공 시 지정된 페이지로 리디렉션
	//-->
	</script>
<?php
} else {  // 쿼리 실행이 실패한 경우
?>
	<script type="text/javascript">
	<!--
		alert("저장오류");  // 오류 메시지를 알림창으로 표시
		history.back();  // 이전 페이지로 이동
	//-->
	</script>
<?php
}
?>