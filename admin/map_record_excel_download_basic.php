<?php
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$req_map_service=$_REQUEST["map_service"];






$sql="select c.map_no, AB.ministry_start_date, AB.ministry_end_date from  ";
$sql.=" ( ";
$sql.=" 	SELECT  ";
$sql.=" 		a.map_idx,  ";
$sql.=" 		min(b.ministry_start_date) as ministry_start_date,  ";
$sql.=" 		max(b.ministry_end_date) as ministry_end_date ";
$sql.=" 	FROM map_normal_sub a left join ministry_list b on a.map_sub_idx=b.map_sub_idx ";
$sql.=" 	group by a.map_idx ";
$sql.=" ) AB right join map_normal c on AB.map_idx=c.map_idx ";
$sql.=" where c.map_service='$req_map_service' ";
$sql.=" order by c.map_idx ";
$result=mysql_query($sql);


$str="<table border=1>
		<tr>\r\n
		  <td>구역번호</td>\r\n
		  <td>받은날짜</td>\r\n
		  <td>반환날짜</td>\r\n
		</tr>\r\n";

while($rs=mysql_fetch_array($result)){

	$map_no=$rs['map_no'];
	$ministry_start_date=$rs['ministry_start_date'];
	$ministry_end_date=$rs['ministry_end_date'];


	$str.="<tr>\r\n

	       <td>$map_no</td>\r\n
	       <td>$ministry_start_date</td>
	       <td>$ministry_end_date</td>
		  </tr>\r\n";
}

$str.="</table>";


echo $str;

?>