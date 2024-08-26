<?php
// 응답의 콘텐츠 타입을 UTF-8로 설정하여 한글이 깨지지 않도록 함
header('Content-Type: text/html; charset=utf-8');

// 데이터베이스 연결 스크립트와 공통 함수들을 포함
include "include/db_connect.php";
include "include/function.php";

// POST 요청으로 전달된 데이터를 보안 처리하여 변수에 저장
$m_name = test_input($_POST["m_name"]);
$m_pw = test_input($_POST["m_pw"]);
$m_logincheck = test_input($_POST["m_logincheck"]);
$m_token = test_input($_POST["m_token"]);

// 세션에 저장된 토큰과 제출된 토큰이 일치하는지 확인
if ($m_token != $_SESSION['m_token']) {
    // 토큰이 일치하지 않으면 오류 메시지를 표시하고 이전 페이지로 돌아감
    echo "<SCRIPT LANGUAGE='JavaScript'>
            alert('로그인 오류');
            history.back();
          </SCRIPT>";
    exit;
}

// 입력된 사용자 이름이 데이터베이스에 존재하는지 확인하는 SQL 쿼리
$sql = "SELECT * FROM member WHERE m_name='{$m_name}'";
$result = mysql_query($sql);
$num = mysql_num_rows($result);

if ($num <= 0) {
    // 사용자가 존재하지 않으면 오류 메시지를 표시하고 이전 페이지로 돌아감
    echo "<SCRIPT LANGUAGE='JavaScript'>
            alert('등록된 전도인이 아닙니다.');
            history.back();
          </SCRIPT>";
    exit;
} else {
    // 사용자 이름과 비밀번호가 일치하는지 확인하는 SQL 쿼리
    $sql = "SELECT * FROM member WHERE m_name='{$m_name}' AND m_pw='{$m_pw}'";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);

    if ($num <= 0) {
        // 비밀번호가 잘못된 경우 오류 메시지를 표시하고 이전 페이지로 돌아감
        echo "<SCRIPT LANGUAGE='JavaScript'>
                alert('비밀번호가 잘못되었습니다.');
                history.back();
              </SCRIPT>";
        exit;
    } else {
        // 세션 시작
        Session_Start();

        // 사용자 정보 배열을 가져와 세션에 저장
        $rs = mysql_fetch_array($result, MYSQL_ASSOC);

        $_SESSION['m_idx'] = $rs["m_idx"];
        $_SESSION['m_name'] = $rs["m_name"];
        $_SESSION['m_mgr'] = $rs["m_mgr"];
        $_SESSION['m_position'] = $rs["m_position"];
        $_SESSION['m_pioneer'] = $rs["m_pioneer"];
        $_SESSION['m_sex'] = $rs["m_sex"];

        // 사용자가 자동 로그인을 선택했을 경우 쿠키에 사용자 정보 저장
        if ($m_logincheck == "on") {
            $cookie_name = "jw_ministry";
            $cookie_value = $rs["m_name"] . "|" . $rs["m_pw"];
            setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/"); // 쿠키 유효 기간: 1년
        } else {
            // 자동 로그인을 선택하지 않았을 경우 쿠키를 삭제
            $cookie_name = "jw_ministry";
            setcookie($cookie_name, "", time() - 3600);
        }

        // 사용자 에이전트 및 IP 주소 정보를 가져옴
        $login_useragent = $_SERVER['HTTP_USER_AGENT'];
        $login_remoteaddr = $_SERVER['REMOTE_ADDR'];

        try {
            // 로그인 로그를 데이터베이스에 저장하는 SQL 쿼리
            $sql = "INSERT INTO member_login_log(m_name, login_date, m_idx, login_useragent, login_remoteaddr) VALUES ('$m_name', now(), '{$rs["m_idx"]}' , '$login_useragent', '$login_remoteaddr')";
            $result = mysql_query($sql);
        } catch (exception $e) {
            // 로그 저장에 실패한 경우 오류 메시지 출력
            echo '로그인 로그 저장 오류';
            exit;
        }

        // 로그인 후 홈 페이지로 리디렉션
        echo "<SCRIPT LANGUAGE='JavaScript'>
                location.href='/home/';
              </SCRIPT>";
        exit;
    }
}
?>