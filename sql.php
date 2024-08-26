<?
include "include/db_connect.php";
include "include/function.php";



$sql="
DROP TABLE IF EXISTS `bbs`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `bbs` (
  `bbs_idx` int(11) NOT NULL AUTO_INCREMENT,
  `bbs_subject` varchar(200) NOT NULL,
  `bbs_content` mediumtext NOT NULL,
  `bbs_regdate` datetime NOT NULL,
  `bbs_editdate` datetime NOT NULL,
  `bbs_isNotice` varchar(1) NOT NULL,
  `bbs_isElder` varchar(1) NOT NULL,
  PRIMARY KEY (`bbs_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);


$sql="
DROP TABLE IF EXISTS `congregation`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `congregation` (
  `cong_idx` int(11) NOT NULL,
  `cong_name` varchar(50) NOT NULL,
  `cong_id` varchar(50) NOT NULL,
  PRIMARY KEY (`cong_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);


$sql="
INSERT INTO `congregation` (`cong_idx`, `cong_name`, `cong_id`) VALUES
(1, '경기화성병점서부', '');
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `db_house_type`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `db_house_type` (
  `house_idx` int(11) NOT NULL,
  `house_type` varchar(50) NOT NULL,
  PRIMARY KEY (`house_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `db_house_type` (`house_idx`, `house_type`) VALUES
(1, '주택'),
(2, '상가'),
(3, '편의점'),
(4, '병원'),
(5, '주유소'),
(6, '타종교'),
(7, '공장');
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `db_service_type`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `db_service_type` (
  `service_idx` int(11) NOT NULL AUTO_INCREMENT,
  `service_type` varchar(20) NOT NULL,
  `service_rank` int(11) NOT NULL,
  PRIMARY KEY (`service_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `db_service_type` (`service_idx`, `service_type`, `service_rank`) VALUES
(1, '호별(일반)', 1),
(2, '전시대', 3),
(3, '상가', 4),
(4, '비공식', 5),
(5, '호별(아파트)', 2),
(6, '오후', 6),
(7, '대회', 7);
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `map_normal`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `map_normal` (
  `map_idx` int(11) NOT NULL,
  `map_service` varchar(10) NOT NULL,
  `map_no` varchar(100) NOT NULL,
  `map_rank` int(11) NOT NULL,
  PRIMARY KEY (`map_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `map_normal` (`map_idx`, `map_service`, `map_no`) VALUES
(1, 'apt', '1');
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `map_normal_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `map_normal_list` (
  `map_sub_idx` int(11) NOT NULL,
  `map_list_idx` int(11) NOT NULL,
  `map_list_line` int(11) DEFAULT NULL,
  `map_list_road` varchar(100) NOT NULL,
  `map_list_road_no` varchar(100) NOT NULL,
  `map_list_floor` varchar(20) NOT NULL,
  `map_list_info` varchar(100) NOT NULL,
  `map_list_rank` int(11) NOT NULL,
  `map_list_visit` varchar(2) NOT NULL,
  `map_list_visit_c` varchar(1) NOT NULL,
  `map_list_enter` varchar(2) NOT NULL,
  `map_list_enter_c` varchar(1) NOT NULL,
  `map_list_house_idx` int(11) NOT NULL,
  PRIMARY KEY (`map_list_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `map_normal_list_content`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `map_normal_list_content` (
  `map_list_idx` int(11) NOT NULL,
  `map_content_idx` int(11) NOT NULL AUTO_INCREMENT,
  `map_content_content` text NOT NULL,
  `map_content_date` datetime NOT NULL,
  `map_member_idx` int(11) NOT NULL,
  PRIMARY KEY (`map_content_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `map_normal_list_record`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `map_normal_list_record` (
  `map_sub_idx` int(11) NOT NULL,
  `map_list_idx` int(11) NOT NULL,
  `map_list_visit_c` varchar(1) NOT NULL,
  `map_list_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `map_normal_sub`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `map_normal_sub` (
  `map_idx` int(11) NOT NULL,
  `map_sub_idx` int(11) NOT NULL,
  `map_sub_no` varchar(100) NOT NULL,
  `map_sub_info` varchar(100) NOT NULL,
  `map_sub_addr` varchar(200) NOT NULL,
  `map_sub_polygon` text NOT NULL,
  PRIMARY KEY (`map_sub_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `meeting_location`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `meeting_location` (
  `meeting_idx` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_name` varchar(100) NOT NULL,
  `meeting_addr` varchar(300) NOT NULL,
  PRIMARY KEY (`meeting_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `member`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `member` (
  `m_idx` int(11) NOT NULL AUTO_INCREMENT,
  `m_name` varchar(50) NOT NULL,
  `m_pw` varchar(50) NOT NULL,
  `m_hp` varchar(20) NOT NULL,
  `m_mgr` varchar(10) NOT NULL,
  `m_position` varchar(1) NOT NULL,
  `m_pioneer` varchar(1) NOT NULL,
  `m_sex` varchar(1) NOT NULL,
  `m_sos_call` int(1) NOT NULL,
  `m_regdate` datetime NOT NULL,
  `m_editdate` datetime NOT NULL,
  PRIMARY KEY (`m_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `member` (`m_idx`, `m_name`, `m_pw`, `m_hp`, `m_mgr`, `m_position`, `m_pioneer`, `m_sex`, `m_sos_call`, `m_regdate`, `m_editdate`) VALUES
(1, '장요섭', 'qqqq', '01037994119', '1,5', '3', '2', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '홍길동', '1914', '01023456789', '1,3,5', '3', '2', '1', 1, '2018-00-00 00:00:00', '0000-00-00 00:00:00');
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `member_login_log`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `member_login_log` (
  `login_idx` int(11) NOT NULL AUTO_INCREMENT,
  `m_name` varchar(50) NOT NULL,
  `login_date` datetime NOT NULL,
  `m_idx` varchar(50) NOT NULL,
  `login_useragent` varchar(1000) DEFAULT NULL,
  `login_remoteaddr` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`login_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `ministry_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `ministry_list` (
  `map_sub_idx` int(11) DEFAULT NULL,
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,
  `ministry_date` date DEFAULT NULL,
  `ministry_start_date` date DEFAULT NULL,
  `ministry_end_date` date DEFAULT NULL,
  `ministry_ing_cnt` int(11) NOT NULL,
  `ministry_service_idx` int(11) DEFAULT NULL COMMENT '봉사형태 - db_service_type 참조',
  `map_list_house_idx` int(11) DEFAULT NULL COMMENT '조건 - 가구형태 db_house_type 참조',
  `map_list_visit` varchar(1) DEFAULT NULL COMMENT '조건-방문(Y),부재중(N)',
  `map_list_enter` varchar(1) DEFAULT NULL COMMENT '조건-방문거절(Y), 방문금지(N)',
  `ministry_member_idx` varchar(100) DEFAULT NULL COMMENT '참여중인 전도인',
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `ministry_list_record`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `ministry_list_record` (
  `idx` int(11) NOT NULL,
  `map_service` varchar(50) NOT NULL,
  `map_sub_idx` int(11) NOT NULL,
  `ministry_start_date` date DEFAULT NULL,
  `ministry_end_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `ministry_person_number_report`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `ministry_person_number_report` (
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,
  `ministry_date` date NOT NULL,
  `ministry_cnt` int(11) NOT NULL,
  `ministry_service_idx` int(11) NOT NULL,
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `ministry_person_participation_report`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `ministry_person_participation_report` (
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,
  `ministry_date` date NOT NULL,
  `ministry_member_idx` int(11) NOT NULL,
  `ministry_member_name` varchar(100) DEFAULT NULL,
  `ministry_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `month_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `month_list` (
  `month_idx` int(11) NOT NULL AUTO_INCREMENT,
  `month_date` date NOT NULL,
  `month_ampm` varchar(1) NOT NULL,
  `month_time` varchar(20) NOT NULL,
  `month_guide1` int(11) NOT NULL,
  `month_guide2` int(11) NOT NULL,
  `month_service` int(11) NOT NULL,
  `month_location` int(11) NOT NULL,
  `month_content` text,
  PRIMARY KEY (`month_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `site_option`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `site_option` (
  `option_idx` int(11) NOT NULL,
  `option_name` varchar(50) NOT NULL,
  `option_fullname` varchar(50) NOT NULL,
  `option_yn` int(11) NOT NULL,
  PRIMARY KEY (`option_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `site_option` (`option_idx`, `option_name`, `option_fullname`, `option_yn`) VALUES
(1, 'tel_service_show', '전화봉사', 1),
(2, 'sos_show', '긴급전화', 1),
(3, 'ministry_list_map_show', '구역 지도보기', 1);
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `tel_content_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `tel_content_list` (
  `tel_list_idx` int(11) NOT NULL,
  `tel_content_idx` int(11) NOT NULL AUTO_INCREMENT,
  `tel_content_content` text NOT NULL,
  `tel_content_member_idx` int(11) NOT NULL,
  `tel_content_date` datetime NOT NULL,
  PRIMARY KEY (`tel_content_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `tel_info`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `tel_info` (
  `tel_idx` int(11) NOT NULL,
  `tel_info` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`tel_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
INSERT INTO `tel_info` (`tel_idx`, `tel_info`) VALUES
(1, '<p><span style='color:#e67e22'>● 전화봉사를 하기 원하시는 분들은 봉사감독자에게 먼저 연락주시기 바랍니다~</span></p>\n');
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `tel_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `tel_list` (
  `tel_idx` int(11) NOT NULL,
  `tel_list_idx` int(11) NOT NULL,
  `tel_list_name` varchar(50) NOT NULL,
  `tel_list_number` varchar(50) NOT NULL,
  `tel_list_addr` varchar(200) NOT NULL,
  `tel_list_refusal` varchar(1) NOT NULL COMMENT '전화거절',
  `tel_list_ban` varchar(1) NOT NULL COMMENT '전화금지',
  `tel_list_rank` int(11) NOT NULL,
  PRIMARY KEY (`tel_list_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `tel_ministry_stat`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `tel_ministry_stat` (
  `tel_idx` int(11) NOT NULL COMMENT '전화봉사구역번호',
  `tel_ministry_idx` int(11) NOT NULL COMMENT '일련번호',
  `tel_ministry_name` varchar(50) NOT NULL COMMENT '전도인이름',
  `tel_ministry_date` date NOT NULL COMMENT '봉사일자',
  `tel_ministry_start` varchar(20) NOT NULL COMMENT '시작시간',
  `tel_ministry_end` varchar(20) NOT NULL COMMENT '완료시간',
  `tel_ministry_time` varchar(10) NOT NULL COMMENT '봉사시간',
  PRIMARY KEY (`tel_ministry_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `tel_service`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `tel_service` (
  `tel_idx` int(11) NOT NULL,
  `tel_no` varchar(50) NOT NULL,
  `tel_member_idx` varchar(11) NOT NULL,
  `tel_service_date` datetime NOT NULL,
  PRIMARY KEY (`tel_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `week_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `week_list` (
  `week_idx` int(11) NOT NULL AUTO_INCREMENT,
  `week_yoil` int(11) NOT NULL,
  `week_ampm` varchar(1) NOT NULL,
  `week_time` varchar(20) NOT NULL,
  `week_guide1` int(11) NOT NULL,
  `week_guide2` int(11) NOT NULL,
  `week_service` int(11) NOT NULL,
  `week_location` int(11) NOT NULL,
  PRIMARY KEY (`week_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;
";
$result=mysql_query($sql);

$sql="
DROP TABLE IF EXISTS `work_list`;";
$result=mysql_query($sql);


$sql="
CREATE TABLE IF NOT EXISTS `work_list` (
  `work_date` varchar(10) NOT NULL,
  `work_datetime` datetime DEFAULT NULL,
  `work_member` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`work_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

//echo $sql;
$result=mysql_query($sql);

$sql="
update congregation set cong_name='서울수서' where cong_idx=1;";
$result=mysql_query($sql);


$sql="
update member set m_name='조광호', m_pw='8350', m_hp='01087918350' where m_idx=2;
";

$result=mysql_query($sql);

echo "db insert complete";
exit;


echo "test";

//$sql="DROP TABLE IF EXISTS `site_option`;";
//$result=mysql_query($sql);
//
//$sql="CREATE TABLE IF NOT EXISTS `site_option` (";
//$sql.="  `option_idx` int(11) NOT NULL,";
//$sql.="  `option_name` varchar(50) NOT NULL,";
//$sql.="  `option_fullname` varchar(50) NOT NULL,";
//$sql.="  `option_yn` int(11) NOT NULL,";
//$sql.="  PRIMARY KEY (`option_idx`)";
//$sql.=") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
//$result=mysql_query($sql);
//
//$sql="INSERT INTO `site_option` (`option_idx`, `option_name`, `option_fullname`, `option_yn`) VALUES";
//$sql.="(1, 'tel_service_show', '전화봉사', 1),";
//$sql.="(2, 'sos_show', '긴급전화', 1),";
//$sql.="(3, 'ministry_list_map_show', '구역 지도보기', 1);";
//
//$result=mysql_query($sql);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>  
<?
$sql="SELECT * FROM site_option";
$result=mysql_query($sql);
while($rs2=mysql_fetch_array($result)){
	echo $rs2[option_fullname]." ".$rs2[option_yn]."<br>";
}
?>
</body>
</html>
