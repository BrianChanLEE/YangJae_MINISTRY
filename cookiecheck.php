<?php
// 클라이언트의 브라우저에 'jw_ministry'라는 이름의 쿠키가 설정되어 있는지 확인
if (!isset($_COOKIE["jw_ministry"])) {
    // 'jw_ministry' 쿠키가 설정되어 있지 않은 경우
    echo "nothing";  // "nothing" 문자열을 출력
    exit;  // 스크립트 실행을 종료
} else {
    // 'jw_ministry' 쿠키가 설정되어 있는 경우
    echo $_COOKIE["jw_ministry"];  // 쿠키의 값을 출력
    exit;  // 스크립트 실행을 종료
}
?>