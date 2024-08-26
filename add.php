<?
include "include/db_connect.php";
include "include/function.php";


$sql="alter table site_option change option_fullname option_fullname varchar(300) null ";
$result=mysql_query($sql);
echo " Modify option_fullname change datatype <br>";

$sql="select * From site_option where option_idx in (4,5) ";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0)
{
	$sql="	INSERT INTO site_option (option_idx, option_name, option_fullname, option_yn) VALUES
									(4, 'ministry_number', '봉사적정인원', 5),
									(5, 'ministry_notice', '이 왕국의 좋은 소식이 모든 민족에게 증거되기 위해 사람이 거주하는 온 땅에 전파될 것입니다. 그리고 끝이 올 것입니다. - 마태 24:24', 0);";
	$result=mysql_query($sql);
	echo " Add site option 4,5<br>";
}


$sql="select * From site_option where option_idx in (6,7) ";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0)
{
	$sql="	INSERT INTO site_option (option_idx, option_name, option_fullname, option_yn) VALUES
									(6, 'guide_choice_show', '구역지정', 1),
									(7, 'guide_extract_show', '구역추출', 1);";
	$result=mysql_query($sql);
	echo " Add site option 6,7<br>";
}


$sql="select * From member where m_idx = 1 ";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0)
{
	$sql="
	INSERT INTO `member`	(`m_idx`, `m_name`, `m_pw`, `m_hp`, `m_mgr`, `m_position`, `m_pioneer`, `m_sex`, `m_sos_call`, `m_regdate`, `m_editdate`) VALUES
							(1, '장요섭', 'qqqq', '01000000000', '1,5', '3', '2', '1', 0, '2017-11-23 14:43:00', '2017-12-11 19:47:24');
	";
	$result=mysql_query($sql);
	echo 'Add member<br>';

	$sql="UPDATE `member` SET `m_name`='관리자', `m_hp`='01000000000' WHERE `m_idx`=1";
	$result=mysql_query($sql);
	echo 'Modify member name<br>';

}else{

	$sql="UPDATE `member` SET `m_name`='관리자', `m_hp`='01000000000' WHERE `m_idx`=1";
	$result=mysql_query($sql);
	echo 'Already exists. Modify member name<br>';

}


$sql="SHOW COLUMNS FROM map_normal_sub LIKE 'map_sub_notice';";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0)
{
	$sql="ALTER TABLE map_normal_sub ADD map_sub_notice VARCHAR(500) NULL;";
	$result=mysql_query($sql);
	echo 'Add columns to map_normal_sub table <br>';
}

echo "ok";

//아파트 봉사시 주의사항 - 이목끌지말 것, 민원주의, 절대정숙, 분별력사용

?>
