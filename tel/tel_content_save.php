<?php
include "../include/db_connect.php";  // 데이터베이스 연결 스크립트를 포함
include "../include/function.php";  // 공통 함수들을 포함

$work = $_REQUEST["work"];  // 요청 파라미터에서 'work' 값을 가져와 변수에 저장

if ($work == "tel_content_save") {  // 'work' 값이 'tel_content_save'인 경우

    // 요청 파라미터에서 필요한 데이터를 변수에 저장
    $req_tel_idx = $_REQUEST["tel_idx"];
    $req_tel_list_idx = $_REQUEST["tel_list_idx"];
    $req_tel_content_content = $_REQUEST["tel_content_content"];

    $req_tel_content_content = str_replace("'", "''", $req_tel_content_content);  // 내용에서 작은 따옴표를 이중 따옴표로 변환하여 SQL 인젝션 방지

    // 현재 세션에서 사용자의 ID를 가져옴
    $tel_member_idx = $_SESSION['m_idx'];

    // 'tel_content' 테이블에 새로운 전화 내용 레코드를 삽입하는 SQL 쿼리 작성
    $sql = "INSERT INTO tel_content (tel_idx, tel_list_idx, tel_content_content, tel_content_date, tel_member_idx) VALUES ( ";
    $sql .= "'$req_tel_idx', ";
    $sql .= "'$req_tel_list_idx', ";
    $sql .= "'$req_tel_content_content', ";
    $sql .= "now(), ";
    $sql .= "'$tel_member_idx')";

    $result = mysql_query($sql);  // SQL 쿼리를 실행

    if ($result) {  // 쿼리 실행이 성공한 경우
        echo "ok";  // "ok" 메시지를 반환
    } else {  // 쿼리 실행이 실패한 경우
        echo "error";  // "error" 메시지를 반환
    }
    exit;  // 스크립트 실행 종료
}
?>