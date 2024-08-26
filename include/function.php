<?
function test_input($data) {
	$data = trim($data);  // 입력된 데이터에서 양 끝 공백을 제거
	$data = stripslashes($data);  // 백슬래시(\)를 제거
	$data = htmlspecialchars($data);  // HTML 특수 문자를 HTML 엔티티로 변환
	$data = str_replace("'","",$data);  // 작은따옴표(')를 제거
	$data = str_replace("=","",$data);  // 등호(=)를 제거
	$data = str_replace("&","",$data);  // 앰퍼샌드(&)를 제거
	$data = str_replace("","",$data);  // 빈 문자열을 제거 (의미 없음, 오류로 보임)
	$data = str_replace("-","",$data);  // 하이픈(-)을 제거
	$data = str_replace("#","",$data);  // 해시(#)를 제거
	$data = str_replace(" ","",$data);  // 공백을 제거
	$data = str_replace(";","",$data);  // 세미콜론(;)을 제거
	return $data;  // 처리된 데이터를 반환
}

function paging($ipage, $total_row, $page_size, $page_list_size, $query_string){
	// 총 페이지 수 계산
	if ($total_row<=0){
	  $total_row=0; // 총 게시물 수가 없을 경우 0으로 설정
	}
//	$total_page=floor($total_row/$page_size)+1;  // 정수형 페이지 수 계산 (주석 처리됨)
	$total_page=ceil($total_row/$page_size);  // 총 페이지 수를 올림하여 계산

	// 현재 페이지 그룹의 첫 페이지 계산
	$current_page=(floor(($ipage - 1) / $page_list_size)) * $page_list_size + 1;

	if($current_page > 1){  // 첫 번째 페이지로 이동 링크 생성
		$first_list = 1;
		$str_first="<li><a href=\"$PHP_SELF?page=$first_list$query_string\" title=\"처음\"><<</a></li>\n";
	}

	if (($current_page - $page_list_size) > 0){  // 이전 페이지 그룹으로 이동 링크 생성
		$prev_list = $current_page - $page_list_size;
		$str_prev="<li><a href=\"$PHP_SELF?page=$prev_list$query_string\" title=\"이전\"><</a></li>\n";
	}
	
	for ($i=$current_page;$i<$current_page+$page_list_size;$i++){  // 페이지 번호 링크 생성

		if ($i <= $total_page){  // 유효한 페이지 번호인지 확인
			if ($i == $ipage){  // 현재 페이지 표시
				$str_list.="<li class='active'><a href=\"$PHP_SELF?page=$i$query_string\">$i</a></li>\n";
			}else{
				$str_list.="<li><a href=\"$PHP_SELF?page=$i$query_string\">$i</a></li>\n";
			}
		}
	}

	if (($current_page + $page_list_size) <= $total_page){  // 다음 페이지 그룹으로 이동 링크 생성
		$next_list = $current_page + $page_list_size;
		$str_next="<li><a href=\"$PHP_SELF?page=$next_list$query_string\" title=\"다음\">></a></li>\n";
	}

	if($ipage < $total_page){  // 마지막 페이지로 이동 링크 생성
		$last_list = $total_page;
		$str_last="<li><a href=\"$PHP_SELF?page=$last_list$query_string\" title=\"마지막\">>></a></li>\n";
	}

	// 생성된 페이지 링크를 출력
	echo "<div class='text-center'>\n";
	echo "<ul class='pagination'>\n";
	echo $str_first;
	echo $str_prev;
	echo $str_list;
	echo $str_next;
	echo $str_last;
	echo "</ul>\n";
	echo "</div>\n";
}

function service_name($map_service){
	// 주어진 서비스 코드에 따라 서비스 이름을 출력
	if ($map_service=="normal"){
		echo "일반";
	}else if ($map_service=="apt"){
		echo "아파트";
	}else if ($map_service=="display"){
		echo "전시대";
	}
}

function session_home_check(){
	// 세션에 저장된 사용자 인덱스 확인
	$m_idx=$_SESSION['m_idx'];

	if ($m_idx!="" && $m_idx!=null){  // 유효한 사용자 인덱스가 있는 경우
		echo "<script>document.location.href='/home/';</script>";  // '/home/' 페이지로 리디렉션
		exit;  // 추가 코드 실행 방지
	}
}

function session_check(){
	// 세션에 저장된 사용자 인덱스 확인
	$m_idx=$_SESSION['m_idx'];

	if ($m_idx=="" || $m_idx==null ){  // 유효한 사용자 인덱스가 없는 경우
		echo "<script>document.location.href='/';</script>";  // 홈 페이지로 리디렉션
		exit;  // 추가 코드 실행 방지
	}
}

function mgr_check($str){
	// 세션에 저장된 관리자 권한 확인
	$m_mgr=$_SESSION['m_mgr'];
	$arrStr = split(",",$str);  // 입력된 문자열을 쉼표(,)로 분리하여 배열로 저장

	$isTrue=false;
	
	for($i=0;$i< sizeof($arrStr);$i++){  // 각 권한 코드에 대해 검사
		
		if (strpos($m_mgr,$arrStr[$i])>-1){  // 세션의 권한 코드에 해당 문자열이 포함된 경우
			$isTrue=true;  // 권한이 있다고 표시
			break;
		}
	}	

	return $isTrue;  // 권한 유무 반환
}

function chkdate($dd){
	// 주어진 문자열이 유효한 날짜 형식(YYYY-MM-DD)인지 확인
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dd)) {
		return true;  // 유효한 날짜 형식
	} else {
		return false;  // 유효하지 않은 날짜 형식
	}
}

function site_option($option_name){
	// 데이터베이스에서 주어진 옵션 이름에 해당하는 옵션 값 조회
	$sql="select * from site_option where option_name='$option_name'";
	$result_type=mysql_query($sql);
	$rs=mysql_fetch_array($result_type);
	$option_yn=$rs['option_yn'];  // 옵션 값 저장

	return $option_yn;  // 옵션 값 반환
}
?>