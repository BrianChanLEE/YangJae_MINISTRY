<?php
// MySQL 데이터베이스 연결 및 공통 함수 포함
include "include/db_connect.php";
include "include/function.php";

// bbs 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `bbs`;";
$result = mysql_query($sql);

// bbs 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `bbs` (
  `bbs_idx` int(11) NOT NULL AUTO_INCREMENT,  // 기본 키, 자동 증가
  `bbs_subject` varchar(200) NOT NULL,  // 게시물 제목
  `bbs_content` mediumtext NOT NULL,  // 게시물 내용
  `bbs_regdate` datetime NOT NULL,  // 등록일
  `bbs_editdate` datetime NOT NULL,  // 수정일
  `bbs_isNotice` varchar(1) NOT NULL,  // 공지사항 여부
  `bbs_isElder` varchar(1) NOT NULL,  // 장로 게시물 여부
  PRIMARY KEY (`bbs_idx`)  // 기본 키 설정
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// congregation 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `congregation`;";
$result = mysql_query($sql);

// congregation 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `congregation` (
  `cong_idx` int(11) NOT NULL,  // 회중 ID, 기본 키
  `cong_name` varchar(50) NOT NULL,  // 회중 이름
  `cong_id` varchar(50) NOT NULL,  // 회중 식별자
  PRIMARY KEY (`cong_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// congregation 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `congregation` (`cong_idx`, `cong_name`, `cong_id`) VALUES
(1, '경기화성병점서부', '');
";
$result = mysql_query($sql);

// db_house_type 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `db_house_type`;";
$result = mysql_query($sql);

// db_house_type 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `db_house_type` (
  `house_idx` int(11) NOT NULL,  // 주거 형태 ID, 기본 키
  `house_type` varchar(50) NOT NULL,  // 주거 형태 이름
  PRIMARY KEY (`house_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// db_house_type 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `db_house_type` (`house_idx`, `house_type`) VALUES
(1, '주택'),
(2, '상가'),
(3, '편의점'),
(4, '병원'),
(5, '주유소'),
(6, '타종교'),
(7, '공장');
";
$result = mysql_query($sql);

// db_service_type 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `db_service_type`;";
$result = mysql_query($sql);

// db_service_type 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `db_service_type` (
  `service_idx` int(11) NOT NULL AUTO_INCREMENT,  // 서비스 유형 ID, 자동 증가
  `service_type` varchar(20) NOT NULL,  // 서비스 유형 이름
  `service_rank` int(11) NOT NULL,  // 서비스 순위
  PRIMARY KEY (`service_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
";
$result = mysql_query($sql);

// db_service_type 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `db_service_type` (`service_idx`, `service_type`, `service_rank`) VALUES
(1, '호별(일반)', 1),
(2, '전시대', 3),
(3, '상가', 4),
(4, '비공식', 5),
(5, '호별(아파트)', 2),
(6, '오후', 6),
(7, '대회', 7);
";
$result = mysql_query($sql);

// map_normal 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `map_normal`;";
$result = mysql_query($sql);

// map_normal 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `map_normal` (
  `map_idx` int(11) NOT NULL,  // 지도 ID, 기본 키
  `map_service` varchar(10) NOT NULL,  // 서비스 유형
  `map_no` varchar(100) NOT NULL,  // 지도 번호
  `map_rank` int(11) NOT NULL,  // 지도 순위
  PRIMARY KEY (`map_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// map_normal 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `map_normal` (`map_idx`, `map_service`, `map_no`) VALUES
(1, 'apt', '1');
";
$result = mysql_query($sql);

// map_normal_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `map_normal_list`;";
$result = mysql_query($sql);

// map_normal_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `map_normal_list` (
  `map_sub_idx` int(11) NOT NULL,  // 지도 서브 ID
  `map_list_idx` int(11) NOT NULL,  // 지도 리스트 ID, 기본 키
  `map_list_line` int(11) DEFAULT NULL,  // 지도 리스트 라인
  `map_list_road` varchar(100) NOT NULL,  // 도로 이름
  `map_list_road_no` varchar(100) NOT NULL,  // 도로 번호
  `map_list_floor` varchar(20) NOT NULL,  // 층
  `map_list_info` varchar(100) NOT NULL,  // 추가 정보
  `map_list_rank` int(11) NOT NULL,  // 리스트 순위
  `map_list_visit` varchar(2) NOT NULL,  // 방문 여부
  `map_list_visit_c` varchar(1) NOT NULL,  // 방문 여부 임시 저장
  `map_list_enter` varchar(2) NOT NULL,  // 방문 거절 여부
  `map_list_enter_c` varchar(1) NOT NULL,  // 방문 거절 여부 임시 저장
  `map_list_house_idx` int(11) NOT NULL,  // 주거 형태 ID
  PRIMARY KEY (`map_list_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// map_normal_list_content 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `map_normal_list_content`;";
$result = mysql_query($sql);

// map_normal_list_content 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `map_normal_list_content` (
  `map_list_idx` int(11) NOT NULL,  // 지도 리스트 ID
  `map_content_idx` int(11) NOT NULL AUTO_INCREMENT,  // 지도 컨텐츠 ID, 자동 증가, 기본 키
  `map_content_content` text NOT NULL,  // 지도 컨텐츠 내용
  `map_content_date` datetime NOT NULL,  // 컨텐츠 생성일
  `map_member_idx` int(11) NOT NULL,  // 멤버 ID
  PRIMARY KEY (`map_content_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// map_normal_list_record 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `map_normal_list_record`;";
$result = mysql_query($sql);

// map_normal_list_record 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `map_normal_list_record` (
  `map_sub_idx` int(11) NOT NULL,  // 지도 서브 ID
  `map_list_idx` int(11) NOT NULL,  // 지도 리스트 ID
  `map_list_visit_c` varchar(1) NOT NULL,  // 방문 여부 임시 저장
  `map_list_date` date NOT NULL  // 방문 날짜
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// map_normal_sub 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `map_normal_sub`;";
$result = mysql_query($sql);

// map_normal_sub 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `map_normal_sub` (
  `map_idx` int(11) NOT NULL,  // 지도 ID
  `map_sub_idx` int(11) NOT NULL,  // 지도 서브 ID, 기본 키
  `map_sub_no` varchar(100) NOT NULL,  // 지도 서브 번호
  `map_sub_info` varchar(100) NOT NULL,  // 지도 서브 정보
  `map_sub_addr` varchar(200) NOT NULL,  // 지도 서브 주소
  `map_sub_polygon` text NOT NULL,  // 지도 다각형 정보
  PRIMARY KEY (`map_sub_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// meeting_location 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `meeting_location`;";
$result = mysql_query($sql);

// meeting_location 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `meeting_location` (
  `meeting_idx` int(11) NOT NULL AUTO_INCREMENT,  // 만남 위치 ID, 기본 키, 자동 증가
  `meeting_name` varchar(100) NOT NULL,  // 만남 장소 이름
  `meeting_addr` varchar(300) NOT NULL,  // 만남 장소 주소
  PRIMARY KEY (`meeting_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// member 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `member`;";
$result = mysql_query($sql);

// member 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `member` (
  `m_idx` int(11) NOT NULL AUTO_INCREMENT,  // 멤버 ID, 기본 키, 자동 증가
  `m_name` varchar(50) NOT NULL,  // 멤버 이름
  `m_pw` varchar(50) NOT NULL,  // 멤버 비밀번호
  `m_hp` varchar(20) NOT NULL,  // 멤버 전화번호
  `m_mgr` varchar(10) NOT NULL,  // 멤버 관리자 역할
  `m_position` varchar(1) NOT NULL,  // 멤버 직위
  `m_pioneer` varchar(1) NOT NULL,  // 멤버 파이어니어 여부
  `m_sex` varchar(1) NOT NULL,  // 멤버 성별
  `m_sos_call` int(1) NOT NULL,  // SOS 호출 가능 여부
  `m_regdate` datetime NOT NULL,  // 등록일
  `m_editdate` datetime NOT NULL,  // 수정일
  PRIMARY KEY (`m_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
";
$result = mysql_query($sql);

// member 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `member` (`m_idx`, `m_name`, `m_pw`, `m_hp`, `m_mgr`, `m_position`, `m_pioneer`, `m_sex`, `m_sos_call`, `m_regdate`, `m_editdate`) VALUES
(1, '장요섭', 'qqqq', '01037994119', '1,5', '3', '2', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '홍길동', '1914', '01023456789', '1,3,5', '3', '2', '1', 1, '2018-00-00 00:00:00', '0000-00-00 00:00:00');
";
$result = mysql_query($sql);

// member_login_log 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `member_login_log`;";
$result = mysql_query($sql);

// member_login_log 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `member_login_log` (
  `login_idx` int(11) NOT NULL AUTO_INCREMENT,  // 로그인 로그 ID, 기본 키, 자동 증가
  `m_name` varchar(50) NOT NULL,  // 멤버 이름
  `login_date` datetime NOT NULL,  // 로그인 날짜
  `m_idx` varchar(50) NOT NULL,  // 멤버 ID
  `login_useragent` varchar(1000) DEFAULT NULL,  // 사용자 에이전트 정보
  `login_remoteaddr` varchar(20) DEFAULT NULL,  // 원격 IP 주소
  PRIMARY KEY (`login_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// ministry_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `ministry_list`;";
$result = mysql_query($sql);

// ministry_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `ministry_list` (
  `map_sub_idx` int(11) DEFAULT NULL,  // 지도 서브 ID
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,  // 봉사 ID, 기본 키, 자동 증가
  `ministry_date` date DEFAULT NULL,  // 봉사 날짜
  `ministry_start_date` date DEFAULT NULL,  // 봉사 시작 날짜
  `ministry_end_date` date DEFAULT NULL,  // 봉사 종료 날짜
  `ministry_ing_cnt` int(11) NOT NULL,  // 봉사 진행 카운트
  `ministry_service_idx` int(11) DEFAULT NULL COMMENT '봉사형태 - db_service_type 참조',  // 봉사 유형 ID
  `map_list_house_idx` int(11) DEFAULT NULL COMMENT '조건 - 가구형태 db_house_type 참조',  // 주거 형태 ID
  `map_list_visit` varchar(1) DEFAULT NULL COMMENT '조건-방문(Y),부재중(N)',  // 방문 여부
  `map_list_enter` varchar(1) DEFAULT NULL COMMENT '조건-방문거절(Y), 방문금지(N)',  // 방문 거절 여부
  `ministry_member_idx` varchar(100) DEFAULT NULL COMMENT '참여중인 전도인',  // 참여중인 멤버 ID
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// ministry_list_record 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `ministry_list_record`;";
$result = mysql_query($sql);

// ministry_list_record 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `ministry_list_record` (
  `idx` int(11) NOT NULL,  // 봉사 리스트 기록 ID, 기본 키
  `map_service` varchar(50) NOT NULL,  // 지도 서비스 유형
  `map_sub_idx` int(11) NOT NULL,  // 지도 서브 ID
  `ministry_start_date` date DEFAULT NULL,  // 봉사 시작 날짜
  `ministry_end_date` date DEFAULT NULL  // 봉사 종료 날짜
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// ministry_person_number_report 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `ministry_person_number_report`;";
$result = mysql_query($sql);

// ministry_person_number_report 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `ministry_person_number_report` (
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,  // 봉사 보고서 ID, 기본 키, 자동 증가
  `ministry_date` date NOT NULL,  // 봉사 날짜
  `ministry_cnt` int(11) NOT NULL,  // 봉사 인원 수
  `ministry_service_idx` int(11) NOT NULL,  // 봉사 서비스 ID
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// ministry_person_participation_report 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `ministry_person_participation_report`;";
$result = mysql_query($sql);

// ministry_person_participation_report 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `ministry_person_participation_report` (
  `ministry_idx` int(11) NOT NULL AUTO_INCREMENT,  // 봉사 참여 보고서 ID, 기본 키, 자동 증가
  `ministry_date` date NOT NULL,  // 봉사 날짜
  `ministry_member_idx` int(11) NOT NULL,  // 봉사 참여 멤버 ID
  `ministry_member_name` varchar(100) DEFAULT NULL,  // 봉사 참여 멤버 이름
  `ministry_datetime` datetime DEFAULT NULL,  // 봉사 날짜와 시간
  PRIMARY KEY (`ministry_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// month_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `month_list`;";
$result = mysql_query($sql);

// month_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `month_list` (
  `month_idx` int(11) NOT NULL AUTO_INCREMENT,  // 월간 리스트 ID, 기본 키, 자동 증가
  `month_date` date NOT NULL,  // 월간 날짜
  `month_ampm` varchar(1) NOT NULL,  // 오전/오후 구분
  `month_time` varchar(20) NOT NULL,  // 시간
  `month_guide1` int(11) NOT NULL,  // 가이드1 ID
  `month_guide2` int(11) NOT NULL,  // 가이드2 ID
  `month_service` int(11) NOT NULL,  // 서비스 ID
  `month_location` int(11) NOT NULL,  // 위치 ID
  `month_content` text,  // 내용
  PRIMARY KEY (`month_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// site_option 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `site_option`;";
$result = mysql_query($sql);

// site_option 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `site_option` (
  `option_idx` int(11) NOT NULL,  // 옵션 ID, 기본 키
  `option_name` varchar(50) NOT NULL,  // 옵션 이름
  `option_fullname` varchar(50) NOT NULL,  // 옵션 전체 이름
  `option_yn` int(11) NOT NULL,  // 옵션 여부
  PRIMARY KEY (`option_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// site_option 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `site_option` (`option_idx`, `option_name`, `option_fullname`, `option_yn`) VALUES
(1, 'tel_service_show', '전화봉사', 1),
(2, 'sos_show', '긴급전화', 1),
(3, 'ministry_list_map_show', '구역 지도보기', 1);
";
$result = mysql_query($sql);

// tel_content_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `tel_content_list`;";
$result = mysql_query($sql);

// tel_content_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `tel_content_list` (
  `tel_list_idx` int(11) NOT NULL,  // 전화 리스트 ID
  `tel_content_idx` int(11) NOT NULL AUTO_INCREMENT,  // 전화 내용 ID, 기본 키, 자동 증가
  `tel_content_content` text NOT NULL,  // 전화 내용
  `tel_content_member_idx` int(11) NOT NULL,  // 전화 내용 작성자 ID
  `tel_content_date` datetime NOT NULL,  // 전화 내용 작성 날짜
  PRIMARY KEY (`tel_content_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql);

// tel_info 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `tel_info`;";
$result = mysql_query($sql);

// tel_info 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `tel_info` (
  `tel_idx` int(11) NOT NULL,  // 전화 ID
  `tel_info` varchar(1000) DEFAULT NULL,  // 전화 정보
  PRIMARY KEY (`tel_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// tel_info 테이블에 초기 데이터 삽입
$sql = "
INSERT INTO `tel_info` (`tel_idx`, `tel_info`) VALUES
(1, '<p><span style=\'color:#e67e22\'>● 전화봉사를 하기 원하시는 분들은 봉사감독자에게 먼저 연락주시기 바랍니다~</span></p>\n');
";
$result = mysql_query($sql);

// tel_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `tel_list`;";
$result = mysql_query($sql);

// tel_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `tel_list` (
  `tel_idx` int(11) NOT NULL,  // 전화 ID
  `tel_list_idx` int(11) NOT NULL,  // 전화 리스트 ID, 기본 키
  `tel_list_name` varchar(50) NOT NULL,  // 전화 리스트 이름
  `tel_list_number` varchar(50) NOT NULL,  // 전화 리스트 번호
  `tel_list_addr` varchar(200) NOT NULL,  // 전화 리스트 주소
  `tel_list_refusal` varchar(1) NOT NULL COMMENT '전화거절',  // 전화 거절 여부
  `tel_list_ban` varchar(1) NOT NULL COMMENT '전화금지',  // 전화 금지 여부
  `tel_list_rank` int(11) NOT NULL,  // 전화 리스트 순위
  PRIMARY KEY (`tel_list_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// tel_ministry_stat 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `tel_ministry_stat`;";
$result = mysql_query($sql);

// tel_ministry_stat 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `tel_ministry_stat` (
  `tel_idx` int(11) NOT NULL COMMENT '전화봉사구역번호',  // 전화 봉사 구역 번호
  `tel_ministry_idx` int(11) NOT NULL COMMENT '일련번호',  // 전화 봉사 일련번호, 기본 키
  `tel_ministry_name` varchar(50) NOT NULL COMMENT '전도인이름',  // 전도인 이름
  `tel_ministry_date` date NOT NULL COMMENT '봉사일자',  // 봉사 날짜
  `tel_ministry_start` varchar(20) NOT NULL COMMENT '시작시간',  // 시작 시간
  `tel_ministry_end` varchar(20) NOT NULL COMMENT '완료시간',  // 완료 시간
  `tel_ministry_time` varchar(10) NOT NULL COMMENT '봉사시간',  // 봉사 시간
  PRIMARY KEY (`tel_ministry_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// tel_service 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `tel_service`;";
$result = mysql_query($sql);

// tel_service 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `tel_service` (
  `tel_idx` int(11) NOT NULL,  // 전화 서비스 ID, 기본 키
  `tel_no` varchar(50) NOT NULL,  // 전화 번호
  `tel_member_idx` varchar(11) NOT NULL,  // 전화 멤버 ID
  `tel_service_date` datetime NOT NULL,  // 전화 서비스 날짜
  PRIMARY KEY (`tel_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// week_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `week_list`;";
$result = mysql_query($sql);

// week_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `week_list` (
  `week_idx` int(11) NOT NULL AUTO_INCREMENT,  // 주간 리스트 ID, 기본 키, 자동 증가
  `week_yoil` int(11) NOT NULL,  // 요일
  `week_ampm` varchar(1) NOT NULL,  // 오전/오후 구분
  `week_time` varchar(20) NOT NULL,  // 시간
  `week_guide1` int(11) NOT NULL,  // 가이드1 ID
  `week_guide2` int(11) NOT NULL,  // 가이드2 ID
  `week_service` int(11) NOT NULL,  // 서비스 ID
  `week_location` int(11) NOT NULL,  // 위치 ID
  PRIMARY KEY (`week_idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;
";
$result = mysql_query($sql);

// work_list 테이블이 이미 존재할 경우 삭제
$sql = "DROP TABLE IF EXISTS `work_list`;";
$result = mysql_query($sql);

// work_list 테이블을 새롭게 생성
$sql = "
CREATE TABLE IF NOT EXISTS `work_list` (
  `work_date` varchar(10) NOT NULL,  // 작업 날짜, 기본 키
  `work_datetime` datetime DEFAULT NULL,  // 작업 날짜와 시간
  `work_member` varchar(50) DEFAULT NULL,  // 작업자
  PRIMARY KEY (`work_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
$result = mysql_query($sql);

// congregation 테이블의 cong_name 필드를 '서울수서'로 업데이트
$sql = "update congregation set cong_name='서울수서' where cong_idx=1;";
$result = mysql_query($sql);

// member 테이블의 m_name과 m_pw 필드를 특정 값으로 업데이트
$sql = "
update member set m_name='조광호', m_pw='8350', m_hp='01087918350' where m_idx=2;
";
$result = mysql_query($sql);

// 작업 완료 메시지 출력 및 스크립트 종료
echo "db insert complete";
exit;

?>