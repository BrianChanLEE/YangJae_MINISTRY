<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1")){
	echo "<script>location.href='/home/';</script>";
}

$work=$_REQUEST["work"];
$req_map_service=$_REQUEST["map_service"];




if ($work=="reset"){

	$sql="SELECT MAX(idx)+1 FROM ministry_list_record";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_idx=$max_fetch[0];

	if ($max_map_idx=="" || $max_map_idx==0){
		$max_map_idx=1;
	}


	//이전에 기록된 것은 삭제하고
	$sql="delete from ministry_list_record where map_service='$req_map_service'";
	$result=mysql_query($sql);	

	//새로운것만 저장한다.
	$sql="insert into ministry_list_record(idx, map_service, map_sub_idx, ministry_start_date, ministry_end_date)
		SELECT $max_map_idx, '$req_map_service', map_sub_idx, 
				case when ministry_start_date is null then null else ministry_start_date end as ministry_start_date, 
				case when ministry_end_date   is null then null else ministry_end_date   end as ministry_end_date
		FROM ministry_list where map_sub_idx in(
													select map_sub_idx from map_normal_sub where map_idx in(
														SELECT map_idx FROM map_normal WHERE map_service='$req_map_service'
													)
												)";
	$result=mysql_query($sql);	


	if ($result==1){

		//봉사지정된 것은 삭제하고
		$sql="delete from ministry_list
			where map_sub_idx in(
									select map_sub_idx from map_normal_sub where map_idx in(
										SELECT map_idx FROM map_normal WHERE map_service='$req_map_service'
									)
								)";
		$result1=mysql_query($sql);	


		//구역카드에 기록된 것은 수정한다.
		$sql="update map_normal_list set 
			map_list_visit='',
			map_list_visit_c=''
			where map_sub_idx in(
									select map_sub_idx from map_normal_sub where map_idx in(
										SELECT map_idx FROM map_normal WHERE map_service='$req_map_service'
									)
								)";
		$result2=mysql_query($sql);	


		echo "리셋이 완료되었습니다.";
		
	}else{

		echo "리셋 실패!!\r\n다시 시도하시거나 운영자에게 문의하세요~";
		
	}

}else if ($work=="reset2"){

	//봉사종료일 날짜는 삭제하고
	$sql="	update ministry_list set 
				ministry_end_date=null
			where map_sub_idx in(
								select map_sub_idx from map_normal_sub where map_idx in(
									SELECT map_idx FROM map_normal WHERE map_service='$req_map_service'
								)
							)";
	$result=mysql_query($sql);	

	if ($result==1){

		//부재자 체크된 항목만 삭제한다.
		$sql="	update map_normal_list set 
					map_list_visit='',
					map_list_visit_c=''
				where map_sub_idx in(
									select map_sub_idx from map_normal_sub where map_idx in(
										SELECT map_idx FROM map_normal WHERE map_service='$req_map_service'
									)
								)
					and (map_list_visit='N' or map_list_visit_c='N') ";
		$result2=mysql_query($sql);	


		if ($result2==1){
		
			echo "부재자 리셋이 완료되었습니다.";

		}else{

			echo "리셋 실패!!\r\n다시 시도하시거나 운영자에게 문의하세요~";
			
		}

	}




}else if ($work=="reset3"){

	$sql = " delete from tel_ministry_stat ";
	$result=mysql_query($sql);	

	if ($result==1){

		echo "리셋이 완료되었습니다.";
		
	}else{

		echo "리셋 실패!!\r\n다시 시도하시거나 운영자에게 문의하세요~!!";
		
	}


}
?>