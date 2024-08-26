<?php
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

$work = $_REQUEST["work"];  // 요청 파라미터에서 'work' 값을 가져와 변수에 저장
$req_tel_content_idx = $_REQUEST["tel_content_idx"];  // 요청 파라미터에서 'tel_content_idx' 값을 가져와 변수에 저장

if ($work == "tel_content_del") {  // 'work' 값이 'tel_content_del'인 경우

    // 'tel_content' 테이블에서 주어진 'tel_content_idx'를 가진 레코드를 삭제하는 SQL 쿼리 작성
    $sql = "DELETE FROM tel_content WHERE tel_content_idx='$req_tel_content_idx'";
    $result = mysql_query($sql);  // SQL 쿼리를 실행

    if ($result) {  // 쿼리 실행이 성공한 경우
        echo "ok";  // "ok" 메시지를 반환
    } else {  // 쿼리 실행이 실패한 경우
        echo "error";  // "error" 메시지를 반환
    }
    exit;  // 스크립트 실행 종료
}
?>