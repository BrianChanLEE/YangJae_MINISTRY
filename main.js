$.ajax({
    url: "cookiecheck.php",  // 서버에 요청을 보낼 URL, 사용자의 쿠키를 확인하는 스크립트
    success: function(result) {  // 서버 요청이 성공했을 때 실행되는 콜백 함수

        // 서버 응답 결과가 'nothing', 빈 문자열, 또는 null이 아닌 경우 처리
        if (result != "nothing" && result != "" && result != null) {
            var res = result.split("|");  // 응답 데이터를 '|' 문자를 기준으로 분할하여 배열로 저장

            // 배열의 첫 번째 요소(사용자 이름)가 빈 문자열이거나 null이 아닌지 확인
            if (res[0] == "" || res[0] == null) {
                return;  // 사용자 이름이 없으면 함수 종료
            }

            // 로그인 폼의 사용자 이름 필드에 쿠키에서 가져온 이름을 설정
            $("#m_name").val(res[0]);
            // 로그인 폼의 비밀번호 필드에 쿠키에서 가져온 비밀번호를 설정
            $("#m_pw").val(res[1]);
            // 자동 로그인 체크박스를 체크 상태로 설정
            $("#m_logincheck").prop("checked", true);
            // 자동으로 로그인 폼을 제출하도록 설정 (현재 주석 처리됨)
//          $("form").submit();
        } else {
            // 쿠키가 존재하지 않거나 유효하지 않은 경우 자동 로그인 체크박스를 해제
            $("#m_logincheck").prop("checked", false);
        }
    }
});