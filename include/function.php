<?
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = str_replace("'","",$data);
	$data = str_replace("=","",$data);
	$data = str_replace("&","",$data);
	$data = str_replace("","",$data);
	$data = str_replace("-","",$data);
	$data = str_replace("#","",$data);
	$data = str_replace(" ","",$data);
	$data = str_replace(";","",$data);
	return $data;
}

function paging($ipage, $total_row, $page_size, $page_list_size, $query_string){
	//총페이지 계산
	if ($total_row<=0){
	  $total_row=0; //총 게시물의 값이 없을경우 0으로 셋팅
	}
//	$total_page=floor($total_row/$page_size)+1;
	$total_page=ceil($total_row/$page_size);

	//현재페이지 계산
	$current_page=(floor(($ipage - 1) / $page_list_size)) * $page_list_size + 1;


	if($current_page > 1){ 
		$first_list = 1;
		$str_first="<li><a href=\"$PHP_SELF?page=$first_list$query_string\" title=\"처음\"><<</a></li>\n";
	}

	if (($current_page - $page_list_size) > 0){
		$prev_list = $current_page - $page_list_size;
		$str_prev="<li><a href=\"$PHP_SELF?page=$prev_list$query_string\" title=\"이전\"><</a></li>\n";
	}
	
	for ($i=$current_page;$i<$current_page+$page_list_size;$i++){

		if ($i <= $total_page){
			if ($i == $ipage){
				$str_list.="<li class='active'><a href=\"$PHP_SELF?page=$i$query_string\">$i</a></li>\n";
			}else{
				$str_list.="<li><a href=\"$PHP_SELF?page=$i$query_string\">$i</a></li>\n";
			}
		}
	
	}

	if (($current_page + $page_list_size) <= $total_page){
		$next_list = $current_page + $page_list_size;
		$str_next="<li><a href=\"$PHP_SELF?page=$next_list$query_string\" title=\"다음\">></a></li>\n";
	}

	if($ipage < $total_page){ 
		$last_list = $total_page;
		$str_last="<li><a href=\"$PHP_SELF?page=$last_list$query_string\" title=\"마지막\">>></a></li>\n";
	}

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

	if ($map_service=="normal"){
		echo "일반";
	}else if ($map_service=="apt"){
		echo "아파트";
	}else if ($map_service=="display"){
		echo "전시대";
	}

}

function session_home_check(){
	
	$m_idx=$_SESSION['m_idx'];

	if ($m_idx!="" && $m_idx!=null){
		echo "<script>document.location.href='/home/';</script>";
		exit;
	}

}

function session_check(){
	
	$m_idx=$_SESSION['m_idx'];

	if ($m_idx=="" || $m_idx==null ){
		echo "<script>document.location.href='/';</script>";
		exit;
	}

}

function mgr_check($str){

	$m_mgr=$_SESSION['m_mgr'];
	$arrStr = split(",",$str);

	$isTrue=false;
	
	for($i=0;$i< sizeof($arrStr);$i++){
		
		if (strpos($m_mgr,$arrStr[$i])>-1){
			$isTrue=true;
			break;
		}

	}	

	return $isTrue;
}

function chkdate($dd){

	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dd)) {
		return true;
	} else {
		return false;
	}
}

function site_option($option_name){

	$sql="select * from site_option where option_name='$option_name'";
	$result_type=mysql_query($sql);
	$rs=mysql_fetch_array($result_type);
	$option_yn=$rs[option_yn];

	return $option_yn;

}
?>