<?php
header('Content-Type: text/html; charset=utf-8');  // 응답 콘텐츠 타입을 UTF-8로 설정
include "../include/db_connect.php";  // 데이터베이스 연결 파일을 포함
include "../include/function.php";  // 유틸리티 함수가 포함된 파일을 포함

define("MINISTRY_NUMBER", site_option("ministry_number"));  // 봉사 참여 최대 인원수를 상수로 정의

$work = $_REQUEST["work"];  // 작업 유형을 요청에서 가져옴
$req_service_idx = $_REQUEST["service_idx"];  // 서비스 인덱스를 요청에서 가져옴
$req_map_sub_idx = $_REQUEST["map_sub_idx"];  // 지도 하위 인덱스를 요청에서 가져옴

function check($req_map_sub_idx) {
    // 봉사 참여 유효성을 확인하는 함수

    $m_idx = $_SESSION['m_idx'];  // 현재 사용자의 인덱스를 세션에서 가져옴

    if ($m_idx == "" || $m_idx == null) {
        // 사용자 인덱스가 없으면 오류 메시지를 출력하고 종료
        echo "member_session_error";
        exit;
    }

    $sql = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";  // 해당 지도 하위 인덱스로 봉사 목록을 조회하는 SQL 쿼리
    $result = mysql_query($sql);  // SQL 쿼리 실행
    $rs = mysql_fetch_array($result, MYSQL_ASSOC);  // 결과를 연관 배열로 가져옴
    $ministry_member_idx = $rs['ministry_member_idx'];  // 봉사 참여자 인덱스를 변수에 저장
    $ministry_date = $rs['ministry_date'];  // 봉사 날짜를 변수에 저장

    if ($ministry_member_idx != "" && $ministry_member_idx != null) {
        // 참여자가 있으면 인덱스를 배열로 분리
        $arr_m_m_idx = explode(",", $ministry_member_idx);
        $arr_m_m_cnt = count($arr_m_m_idx);  // 참여자 수를 계산
    } else {
        // 참여자가 없으면 오류 메시지를 출력하고 종료
        echo "member_empty_error";
        exit;
    }

    $is_member = false;  // 현재 사용자가 참여자인지 여부를 확인하는 변수
    for ($i = 0; $i < $arr_m_m_cnt; $i++) {
        // 현재 사용자가 참여자 목록에 있는지 확인
        if ($arr_m_m_idx[$i] == $m_idx) {
            $is_member = true;
            break;
        }
    }
    if (!$is_member) {
        // 참여자가 아니면 오류 메시지를 출력하고 종료
        echo "member_include_error";
        exit;
    }
}

function member_list($ministry_member_idx) {
    // 봉사자 이름 목록을 반환하는 함수

    $m = "";

    if ($ministry_member_idx == "") {
        // 참여자 인덱스가 비어있으면 함수 종료
        return;
    }

    $sql = "select m_name from member where m_idx in ($ministry_member_idx) order by m_name";  // 참여자 목록에서 이름을 가져오는 SQL 쿼리
    $result = mysql_query($sql);  // SQL 쿼리 실행

    $member_cnt = 0;  // 참여자 수를 초기화

    while ($rs = mysql_fetch_array($result)) {
        // 참여자 목록에서 이름을 가져와 변수에 저장
        if ($req_service_idx != "2") {
            if ($_SESSION['m_position'] > "1") {
                $m .= ", " . $rs['m_name'];  // 특정 조건에 따라 이름을 추가
            } else {
                $m .= ", " . $rs['m_name'];  // 이름을 추가
            }
        } else {
            $m .= ", " . $rs['m_name'];  // 이름을 추가
        }
        $member_cnt++;  // 참여자 수를 증가시킴
    }

    return substr($m, 1) . "|" . $member_cnt;  // 이름 목록과 참여자 수를 반환
}

if ($work == "main_list") {
    // 메인 목록 작업을 처리하는 코드

    $req_month_date = $_REQUEST["month_date"];  // 요청된 날짜를 가져옴
    $m_idx = $_SESSION['m_idx'];  // 세션에서 사용자 인덱스를 가져옴

    $sql3 = "
    select * from(
        select *,
        (select map_sub_no from map_normal_sub where map_sub_idx=a.map_sub_idx) as map_sub_no,
        (select map_sub_info from map_normal_sub where map_sub_idx=a.map_sub_idx) as map_sub_info,
        (select map_sub_addr from map_normal_sub where map_sub_idx=a.map_sub_idx) as map_sub_addr,
        (SELECT COUNT(map_sub_idx) FROM map_normal_list WHERE map_sub_idx=a.map_sub_idx) AS tot_cnt,
        (SELECT COUNT(map_sub_idx) FROM map_normal_list WHERE map_sub_idx=a.map_sub_idx AND map_list_visit_c='') AS cnt,
        (SELECT COUNT(map_sub_idx) FROM map_normal_list WHERE map_sub_idx=a.map_sub_idx AND map_list_visit_c='Y') AS visit_cnt,
        (SELECT COUNT(map_sub_idx) FROM map_normal_list WHERE map_sub_idx=a.map_sub_idx AND map_list_visit_c='N') AS empty_cnt

        from ministry_list a 
        where ministry_date='$req_month_date' and ministry_service_idx='$req_service_idx' 
    ) t
    order by map_sub_no";  // 각 지도 하위 인덱스에 대한 정보 및 방문 상태를 조회하는 SQL 쿼리

    $result3 = mysql_query($sql3);  // SQL 쿼리 실행
    $num = mysql_num_rows($result3);  // 결과 행의 수를 가져옴

    if ($num > 0) {
        // 결과가 존재할 경우 실행

?>
        <div class="">
        <table class="table table-hover" >
<?php
        while ($rs = mysql_fetch_array($result3)) {
            // 결과를 반복하며 테이블로 출력

            $map_sub_idx = $rs['map_sub_idx'];  // 지도 하위 인덱스를 가져옴
            $map_sub_no = $rs['map_sub_no'];  // 지도 하위 번호를 가져옴
            $map_sub_no2 = strip_tags($rs['map_sub_no']);  // HTML 태그를 제거
            $map_sub_info = strip_tags($rs['map_sub_info']);  // HTML 태그를 제거
            $map_sub_addr = $rs['map_sub_addr'];  // 지도 하위 주소를 가져옴
            $ministry_start_date = $rs['ministry_start_date'];  // 봉사 시작 날짜를 가져옴
            $ministry_end_date = $rs['ministry_end_date'];  // 봉사 종료 날짜를 가져옴
            $map_list_house_idx = $rs['map_list_house_idx'];  // 지도 하위 주택 인덱스를 가져옴
            $map_list_visit = $rs['map_list_visit'];  // 지도 하위 방문 상태를 가져옴
            $map_list_enter = $rs['map_list_enter'];  // 지도 하위 입장 상태를 가져옴
            $ministry_member_idx = $rs['ministry_member_idx'];  // 봉사 참여자 인덱스를 가져옴

            $ministry_tot_cnt = $rs['tot_cnt'];  // 전체 집 수를 가져옴
            $ministry_ing_cnt = $rs['cnt'];  // 진행 중인 집 수를 가져옴
            $ministry_visit_cnt = $rs['visit_cnt'];  // 방문한 집 수를 가져옴
            $ministry_empty_cnt = $rs['empty_cnt'];  // 부재인 집 수를 가져옴

            $ministry_p = (($ministry_tot_cnt - $ministry_ing_cnt) / $ministry_tot_cnt) * 100;  // 봉사 진행률을 계산
            $ministry_p = round($ministry_p, 1);  // 소수점 첫째 자리까지 반올림

            $arr_m_m_cnt = 0;  // 참여자 수를 초기화
            $arr_m_m_idx = "";  // 참여자 인덱스를 초기화

            $ministry_member_idx = str_replace(" ", "", $ministry_member_idx);  // 참여자 인덱스에서 공백 제거
            $ministry_member_idx = str_replace(",,", ",", $ministry_member_idx);  // 잘못된 쉼표 제거
            $ministry_member_idx = trim($ministry_member_idx, " ,");  // 앞뒤의 쉼표와 공백 제거
            $ministry_member_idx = trim($ministry_member_idx, ", ");  // 앞뒤의 쉼표와 공백 제거

            if ($ministry_member_idx != "") {
                $arr_m_m_idx = explode(",", $ministry_member_idx);  // 참여자 인덱스를 배열로 분리
                $arr_m_m_cnt = count($arr_m_m_idx);  // 참여자 수를 계산
            }

            $is_member = false;  // 현재 사용자가 이 봉사에 참여 중인지 확인하는 변수
            for ($i = 0; $i < $arr_m_m_cnt; $i++) {
                if ($arr_m_m_idx[$i] == $m_idx) {
                    $is_member = true;  // 현재 사용자가 참여자 목록에 있음을 확인
                    break;
                }
            }

            // 실제 참여자 명단과 참여자 수를 가져오기
            $m_list = member_list($ministry_member_idx);
            $arr_m_list = explode("|", $m_list);
            $real_member_list = $arr_m_list[0];  // 참여자 명단
            $real_member_cnt = ($arr_m_list[1] == "") ? 0 : $arr_m_list[1];  // 참여자 수

            $href = "";

            if ($is_member) {
                $href = "ministry_list.html?map_sub_idx=" . $map_sub_idx . "";  // 참여자일 경우 링크 설정
                $href2 = "onclick=\"location.href='ministry_list.html?map_sub_idx=" . $map_sub_idx . "&service_idx=" . $req_service_idx . "&month_date=" . $req_month_date . "'\"";
            } else {
                $href = "#";
                $href2 = "";
            }

            // 구역 추출 여부를 표시
            $label_condition = "";
            if ($map_list_house_idx != null || $map_list_visit != null || $map_list_enter != null) {
                $label_condition = "<span class=\"label label-info\" data-toggle='tooltip' data-placement='top' title='조건에 의해 추출한\n일부구역만 목록에 나옵니다.'>추출</span>";
            }
?>
            <tr style="cursor:pointer;">
                <td width="30%">
                    <div class="home-map-sub-no"><?php //=$map_sub_idx ?>
                        <?=$map_sub_no ?><br>
                        <button type="button" class="btn btn-default btn-xs" onclick="map_view2('<?=$map_sub_no2 ?>', '<?=$map_sub_idx ?>', '<?=$map_sub_info ?>', '<?=$map_sub_addr ?>')"><span class="glyphicon glyphicon-map-marker"></span> 지도보기</button>
                    </div>
                </td>
                <td <?=$href2 ?>>
                    <div class="home-map-sub-info">
                        <?php if ($map_sub_info != "") { ?>
                            [ <?=$map_sub_info ?> ] <?=$label_condition ?>
                        <?php } else { ?>
                            [ 구역명없음 ]
                        <?php } ?>
                    </div>

                    <div class="home-member-name-list">
                        <?=$real_member_list ?> [<?=$real_member_cnt ?>/<?=MINISTRY_NUMBER ?>]

                        <?php if (mgr_check("1,2,3") && $real_member_cnt > 0) { ?>
                            <button type="button" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-cog" onclick="ministry_mgr('<?=$map_sub_idx ?>')"></span></button>
                        <?php } ?>
                    </div>

                    <?php if ($req_service_idx != "2") { ?>
                        <div class="home-progress-percent">
                            <?php echo "<span class='label label-default '>전체 ".$ministry_tot_cnt." 방문 ".$ministry_visit_cnt ." 부재 ".$ministry_empty_cnt ." 남은집 ".$ministry_ing_cnt ."</span>"; ?>
                        </div>
                    <?php } ?>

                    <div class="home-progress-percent">
                        <?php if ($req_service_idx != "2") {
                            if ($ministry_ing_cnt != 0 && $ministry_tot_cnt != $ministry_ing_cnt) {
                                echo "<span class='label label-warning'>봉사중 ( ".$ministry_p."% )</span>";
                            }

                            if ($ministry_ing_cnt == 0 && $ministry_start_date != null && $ministry_end_date == null) {
                                echo "<span class='label label-info'>완료 ( ".$ministry_p."% )</span> <span class='text-info glyphicon glyphicon-info-sign' data-toggle=\"tooltip\" data-placement=\"top\" title=\"'저장'을 눌러 봉사를 완료하세요\"></span> ";
                            } else if ($ministry_ing_cnt == 0 && $ministry_start_date != null && $ministry_end_date != null) {
                                echo "<span class='label label-danger'>완료 ( ".$ministry_p."% )</span>";
                            }
                        } ?>
                    </div>
                </td>
                <td align="right">
                    <?php if ($is_member == true) { ?>

                    <?php } else { ?>
                        <?php if ($real_member_cnt < MINISTRY_NUMBER) { ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="ministry_start('<?=$map_sub_idx ?>')">참여</button>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
<?php
        } // while 종료
?>
        </table>
        </div>
<?php
    }

} else if ($work == "ministry_start") {

    $m_idx = $_SESSION['m_idx'];
    $m_name = $_SESSION['m_name'];

    if ($m_idx == "" || $m_idx == null) {
        echo "member_session_error";
        exit;
    }

    $sql = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result = mysql_query($sql);
    $rs = mysql_fetch_array($result, MYSQL_ASSOC);
    $ministry_member_idx = $rs['ministry_member_idx'];
    $ministry_date = $rs['ministry_date'];

    $arr_m_m_idx = "";
    $arr_m_m_idx = explode(",", $ministry_member_idx);
    $arr_m_m_cnt = count($arr_m_m_idx);

    // 실제 참여자 명단과 참여자 수를 가져오기
    $m_list = member_list($ministry_member_idx);
    $arr_m_list = explode("|", $m_list);

    if ($arr_m_list[1] == "") {
        $real_member_cnt = 0;
    } else {
        $real_member_cnt = $arr_m_list[1];
    }

    if ($real_member_cnt >= MINISTRY_NUMBER) {
        // 참여 인원이 초과되면 종료
        exit;
    }

    // 참여 중인지 여부 확인
    $is_member = false;
    for ($i = 0; $i < $arr_m_m_cnt; $i++) {
        if ($arr_m_m_idx[$i] == $m_idx) {
            $is_member = true;
            break;
        }
    }
    if ($is_member) {
        // 이미 참여 중이면 종료
        exit;
    } else {
        $add_m_idx = "";

        if ($ministry_member_idx == "" || $ministry_member_idx == null) {
            $add_m_idx = $m_idx;
        } else {
            $add_m_idx = $ministry_member_idx . "," . $m_idx;
        }

        // 참여한 전도인들을 업데이트
        $sql = "UPDATE ministry_list SET ";
        $sql .= "ministry_member_idx='$add_m_idx' ";
        $sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
        $result = mysql_query($sql);

        // 참여 통계 업데이트
        $sql = "select * from ministry_person_participation_report where ministry_date='$ministry_date' and ministry_member_idx='$m_idx'";
        $result = mysql_query($sql);
        $num = mysql_num_rows($result);

        if ($num > 0) {
            // 기존 통계가 있으면 아무것도 하지 않음
        } else {
            // 새로운 통계 기록 생성
            $sql = "insert into ministry_person_participation_report(ministry_date, ministry_member_idx, ministry_member_name, ministry_datetime) value('$ministry_date','$m_idx', '$m_name', now())";
            $result = mysql_query($sql);
        }
    }
} else if ($work == "ministry_list") {

    check($req_map_sub_idx);  // 참여 유효성을 확인

    $sql1 = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result1 = mysql_query($sql1);
    $rs1 = mysql_fetch_array($result1, MYSQL_ASSOC);

    // 조건 확인
    $c_map_list_house_idx = $rs1["map_list_house_idx"];  // 가구 형태
    $c_map_list_visit = $rs1["map_list_visit"];  // 부재자
    $c_map_list_enter = $rs1["map_list_enter"];  // 방문 거절, 방문 금지
?>
    <div class="marginbottom50">
        <table class="table">
        <tr>
            <th class="road">길이름</th>
            <th class="road-no">지번</th>
            <th>층</th>
            <th>이름/호</th>
            <th></th>
            <th>봉사<br>기록</th>
            <th>방문</th>
            <th>부재</th>
            <th>방문<br>거절</th>
        </tr>
<?php
    $sql2 = "select *";
    $sql2 .= ",(SELECT house_type FROM db_house_type WHERE house_idx=a.map_list_house_idx ) as house_type";
    $sql2 .= ",(SELECT count(*) FROM map_normal_list_content WHERE map_list_idx=a.map_list_idx) as map_content_cnt";
    $sql2 .= " from map_normal_list a";
    $sql2 .= " where map_sub_idx='" . $req_map_sub_idx . "' ";

    if ($c_map_list_house_idx > 0) {
        $sql2 .= "and map_list_house_idx='$c_map_list_house_idx'";
    }

    if ($c_map_list_visit != "") {
        $sql2 .= "and (map_list_visit='$c_map_list_visit' or map_list_visit='')";
    }

    if ($c_map_list_enter != "") {
        $sql2 .= "and map_list_enter='$c_map_list_enter'";
    }

    $sql2 .= " order by map_list_rank";
    $result2 = mysql_query($sql2);

    while ($rs2 = mysql_fetch_array($result2)) {

        $map_sub_idx = $rs2['map_sub_idx'];
        $map_list_idx = $rs2['map_list_idx'];
        $map_list_line = $rs2['map_list_line'];
        $map_list_road = $rs2['map_list_road'];
        $map_list_road_no = $rs2['map_list_road_no'];
        $map_list_floor = $rs2['map_list_floor'];
        $map_list_info = $rs2['map_list_info'];
        $map_list_visit = $rs2['map_list_visit'];
        $map_list_enter = $rs2['map_list_enter'];
        $map_list_visit_c = $rs2['map_list_visit_c'];
        $map_list_enter_c = $rs2['map_list_enter_c'];
        $house_type = $rs2['house_type'];
        $map_content_cnt = $rs2['map_content_cnt'];
        $map_list_house_idx = $rs2['map_list_house_idx'];

        $map_info = $map_list_road . " " . $map_list_road_no . " " . $map_list_floor . " " . $map_list_info;

        if ($map_list_line == "1") {
            $map_list_line_css = "home-map-list-line-css";
        } else {
            $map_list_line_css = "";
        }

        if ($map_content_cnt == 0) {
            $map_content_cnt_css = "btn-success";
        } else {
            $map_content_cnt_css = "btn-danger";
        }
?>
        <tr>
            <td class="road <?=$map_list_line_css ?>"><b><?=$map_list_road ?></b></td>
            <td class="road-no <?=$map_list_line_css ?>"><b><?=$map_list_road_no ?></b></td>
            <td class="<?=$map_list_line_css ?>"><?=$map_list_floor ?></td>
            <td class="<?=$map_list_line_css ?>"><?=$map_list_info ?></td>

            <td class="<?=$map_list_line_css ?>"><?=$house_type ?></td>
            <td class="<?=$map_list_line_css ?>"><a href="javascript:;" onclick="map_info_view('<?=$map_list_idx ?>','<?=$map_list_road ?>','<?=$map_list_road_no ?>','<?=$map_list_floor ?>','<?=$map_list_info ?>','<?=$map_list_house_idx?>')" class="btn <?=$map_content_cnt_css ?> btn-xs"> <?=$map_content_cnt ?> </a></td>

            <?php if ($map_list_enter_c == "N") { ?>
                <td class="<?=$map_list_line_css ?>" colspan="3"><span class="label label-danger">방문금지</span></td>
            <?php } else { ?>
                <td class="<?=$map_list_line_css ?>">
                    <input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" onclick="map_list_visit_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "Y")?"checked":""?> class="map_list_visit_Y" map_list_idx="<?=$map_list_idx ?>">
                </td>
                <td class="<?=$map_list_line_css ?>">
                    <input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" onclick="map_list_visit_N(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_visit" <?=($map_list_visit_c == "N")?"checked":""?> class="map_list_visit_N" map_list_idx="<?=$map_list_idx ?>">
                </td>
                <td class="<?=$map_list_line_css ?>">
                    <input type="checkbox" id="map_list_enter_Y_<?=$map_list_idx ?>" onclick="map_list_enter_Y(<?=$map_sub_idx ?>, <?=$map_list_idx ?>)" name="map_list_enter" <?=($map_list_enter_c == "Y")?"checked":""?>>
                </td>
            <?php } ?>
        </tr>
<?php
    }
?>
        </table>
    </div>
<?php

} else if ($work == "map_list_update") {

    // 봉사를 진행하면서
    // 방문 or 부재, 방문거절 or 방문금지 체크할 때마다 업데이트

    $req_map_sub_idx = $_REQUEST["map_sub_idx"];
    $req_map_list_idx = $_REQUEST["map_list_idx"];
    $req_field = $_REQUEST["field"];
    $req_field_c = $req_field . "_c";
    $req_val = $_REQUEST["val"];
    $req_month_date = $_REQUEST["month_date"];

    check($req_map_sub_idx);  // 참여 유효성 확인

    $sql = "UPDATE map_normal_list SET ";
    $sql .= "$req_field_c='$req_val' ";
    $sql .= "WHERE map_list_idx='$req_map_list_idx'";
    $result = mysql_query($sql);

    // 봉사 시작일 저장
    // 방문 or 부재, 방문거절 or 방문금지 체크를 처음할 때 봉사 시작일을 저장
    $req_ministry_start_date_isnull = $_REQUEST["ministry_start_date_isnull"];

    if ($req_ministry_start_date_isnull == "Y") {		
        $set_ministry_start_date = date("Y-m-d");
        $sql = "update ministry_list set ministry_start_date='$set_ministry_start_date' where map_sub_idx='$req_map_sub_idx'";
        $result = mysql_query($sql);
    }

    // 봉사를 진행하면서 남은 집의 갯수를 구해서 저장
    // 하루가 지난 구역의 경우 봉사는 완료했는데 완료일이 저장되지 않았을 경우 완료일을 저장하기 위한 작업
    $sql = "select *, ";
    $sql .= "(SELECT COUNT(map_sub_idx) FROM map_normal_list WHERE map_sub_idx=a.map_sub_idx AND map_list_visit_c='' and map_list_enter<>'N') AS cnt ";
    $sql .= "from ministry_list a ";
    $sql .= "where map_sub_idx='$req_map_sub_idx' ";
    $result = mysql_query($sql);
    $rs = mysql_fetch_array($result, MYSQL_ASSOC);
    $ministry_ing_cnt = $rs['cnt'];  // 봉사 진행 중인 집 수를 계산

    $sql = "update ministry_list set ministry_ing_cnt='$ministry_ing_cnt' where map_sub_idx='$req_map_sub_idx'";
    $result = mysql_query($sql);

    // 이전 봉사 기록을 위해 저장
    // 어떤 집을 언제 방문했는지, 만났는지/못 만났는지에 대한 데이터를 기록
    $sql = "SELECT * FROM map_normal_list_record where map_list_date = '$req_month_date' and map_list_idx='$req_map_list_idx'";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);

    if ($num > 0) {
        $sql = "update map_normal_list_record set map_list_visit_c='$req_val', map_list_date='$req_month_date' where map_list_idx='$req_map_list_idx' and map_list_date = '$req_month_date'";
        $result = mysql_query($sql);
    } else {
        $sql = "insert into map_normal_list_record(map_sub_idx, map_list_idx, map_list_visit_c, map_list_date) values('$req_map_sub_idx', '$req_map_list_idx', '$req_val', '$req_month_date')";
        $result = mysql_query($sql);
    }

    echo $sql;

} else if ($work == "map_info_edit") {

    // 구역 정보 수정

    $req_map_list_idx = $_REQUEST["map_list_idx"];
    $req_map_list_road = $_REQUEST["map_list_road"];
    $req_map_list_road_no = $_REQUEST["map_list_road_no"];
    $req_map_list_floor = $_REQUEST["map_list_floor"];
    $req_map_list_info = $_REQUEST["map_list_info"];
    $req_map_list_house_idx = $_REQUEST["map_list_house_idx"];

    $sql = "update map_normal_list set ";
    $sql .= "map_list_road='$req_map_list_road', ";
    $sql .= "map_list_road_no='$req_map_list_road_no', ";
    $sql .= "map_list_floor='$req_map_list_floor', ";
    $sql .= "map_list_info='$req_map_list_info', ";
    $sql .= "map_list_house_idx='$req_map_list_house_idx' ";
    $sql .= "where map_list_idx='$req_map_list_idx'";
    $result = mysql_query($sql);

    exit;

} else if ($work == "map_info_view_del") {

    // 각각의 집에 대한 봉사 기록 삭제

    $req_map_content_idx = $_REQUEST["map_content_idx"];

    $sql = "delete from map_normal_list_content where map_content_idx='$req_map_content_idx'";
    $result = mysql_query($sql);	

    echo "ok";
    exit;

} else if ($work == "map_info_view") {

    // 각각의 집에 대한 봉사 기록 가져오기

    $req_map_list_idx = $_REQUEST["map_list_idx"];

    $m_idx = $_SESSION['m_idx'];

    $sql = "SELECT *, (select m_name from member where m_idx=a.map_member_idx) as map_member_name FROM map_normal_list_content a WHERE map_list_idx='$req_map_list_idx' order by map_content_date desc";
    $result_type = mysql_query($sql);

    while ($rs = mysql_fetch_array($result_type)) {

        $map_content_idx = $rs['map_content_idx'];
        $map_content_content = $rs['map_content_content'];
        $map_content_date = $rs['map_content_date'];
        $map_member_idx = $rs['map_member_idx'];
        $map_member_name = $rs['map_member_name'];

        $txt = "<div id='map_content_idx_" . $map_content_idx . "' class='borderbottom1 paddingtop15 paddingbottom15 fontsize12'>";
        $txt .= "<strong>" . $map_member_name . "</strong> " . $map_content_date . "<br>";
        $txt .= "<div>" . $map_content_content . "</div>";

        // 본인이 작성한 경우 삭제 가능
        if ($map_member_idx == $m_idx || mgr_check("1,2,3")) {
            $txt .= "<div class='text-right'><a href='javascript:;' class='btn btn-danger btn-xs ' onclick='map_info_view_del(" . $map_content_idx . ")'>삭제</a></div>";
        }

        $txt .= "</div>";

        echo $txt;
    }

} else if ($work == "map_info_save") {

    // 각각의 집에 대한 봉사 기록 저장

    $m_idx = $_SESSION['m_idx'];

    $req_map_list_idx = $_REQUEST["map_list_idx"];
    $req_map_content_content = $_REQUEST["map_content_content"];
    $req_map_content_content = str_replace("'", "''", $req_map_content_content);
    $req_map_content_content = nl2br($req_map_content_content);

    $sql = "INSERT INTO map_normal_list_content(map_list_idx, map_content_content, map_content_date, map_member_idx) VALUES ( ";
    $sql .= "'$req_map_list_idx', ";
    $sql .= "'$req_map_content_content', ";
    $sql .= "now(), ";
    $sql .= "'$m_idx') ";

    $result = mysql_query($sql);

} else if ($work == "ministry_complete") {

    // 봉사를 마무리하면서 '저장' 버튼을 눌렀을 때
    // 봉사 참여에서 빠져나옴

    $m_idx = $_SESSION['m_idx'];

    $sql1 = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result1 = mysql_query($sql1);
    $rs1 = mysql_fetch_array($result1, MYSQL_ASSOC);
    $ministry_member_idx = $rs1["ministry_member_idx"];

    // 실제 참여자 명단과 참여자 수를 가져오기
    $m_list = member_list($ministry_member_idx);
    $arr_m_list = explode("|", $m_list);

    if ($arr_m_list[1] == "") {
        $real_member_cnt = 0;
    } else {
        $real_member_cnt = $arr_m_list[1];
    }

    // 남아있는 봉사자 수가 1명일 때 수행할 작업
    if ($real_member_cnt == 1) {
        // 봉사하면서 체크한 항목들을 실제 저장 필드로 복사
        $sql = "update map_normal_list set map_list_visit=map_list_visit_c, map_list_enter=map_list_enter_c where map_sub_idx='$req_map_sub_idx' ";
        $result = mysql_query($sql);
    }

    $arr_ministry_member_idx = explode(",", $ministry_member_idx);

    $c_ministry_member_idx = "";

    for ($i = 0; $i < count($arr_ministry_member_idx); $i++) {
        if ($arr_ministry_member_idx[$i] != $m_idx) {
            $c_ministry_member_idx .= "," . $arr_ministry_member_idx[$i];
        }
    }

    $c_ministry_member_idx = substr($c_ministry_member_idx, 1);

    // 모든 방문 or 부재 항목이 체크되어 있는지 확인
    $sql = "select count(*) from map_normal_list where map_sub_idx='$req_map_sub_idx' and map_list_visit='' and map_list_enter_c<>'N' ";
    $result_count = mysql_query($sql);
    $result_row = mysql_fetch_row($result_count);
    $total_row = $result_row[0];

    if ($total_row > 0) {

        // 일부 항목이 빠져있다면 날짜는 null로 설정
        $sql = "UPDATE ministry_list SET ";
        $sql .= "ministry_member_idx='$c_ministry_member_idx' ";
        $sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
        $result = mysql_query($sql);

        echo "수고하셨습니다.";
        exit;

    } else {

        if ($real_member_cnt == 1) {

            // 남아있는 봉사자 수가 1명이고 모든 항목이 체크되어 있다면 날짜 입력
            $set_ministry_end_date = date("Y-m-d");
            $sql = "UPDATE ministry_list SET ";
            $sql .= "ministry_end_date='$set_ministry_end_date', ";
            $sql .= "ministry_member_idx='$c_ministry_member_idx' ";
            $sql .= "WHERE map_sub_idx='$req_map_sub_idx' and ministry_start_date IS NOT NULL";
            $result = mysql_query($sql);

            echo "봉사를 완료했습니다.";

        } else {

            // 다른 봉사자가 남아있고 모든 항목이 체크되어 있다면
            $sql = "UPDATE ministry_list SET ";
            $sql .= "ministry_member_idx='$c_ministry_member_idx' ";
            $sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
            $result = mysql_query($sql);

            echo "수고많으셨습니다.";
        }
        exit;
    }

} else if ($work == "ministry_out") {

    // 봉사 참여에서 빠져나올 때

    $m_idx = $_SESSION['m_idx'];

    $sql1 = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result1 = mysql_query($sql1);
    $rs1 = mysql_fetch_array($result1, MYSQL_ASSOC);

    $ministry_member_idx = $rs1["ministry_member_idx"];

    $arr_ministry_member_idx = explode(",", $ministry_member_idx);

    $c_ministry_member_idx = "";

    for ($i = 0; $i < count($arr_ministry_member_idx); $i++) {

        if ($arr_ministry_member_idx[$i] != $m_idx) {
            $c_ministry_member_idx .= "," . $arr_ministry_member_idx[$i];
        }
    }

    $c_ministry_member_idx = substr($c_ministry_member_idx, 1);

    $sql = "UPDATE ministry_list SET ";
    $sql .= "ministry_member_idx='$c_ministry_member_idx' ";
    $sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
    $result = mysql_query($sql);

    exit;

} else if ($work == "ministry_record_get") {

    // 봉사 참여 인원 보고 숫자 가져오기

    $req_ministry_date = $_REQUEST["ministry_date"];

    $tmp = "";

    // 참여 통계
    $sql = "select * from ministry_person_number_report where ministry_date = '$req_ministry_date' order by ministry_service_idx";

    $result = mysql_query($sql);
    while ($rs = mysql_fetch_array($result)) {

        $ministry_cnt = $rs['ministry_cnt'];
        $tmp .= "|" . $ministry_cnt;

    }

    $tmp = substr($tmp, 1);

    echo $tmp;
    exit;

} else if ($work == "ministry_record_save") {

    // 봉사 참여 인원 보고 저장

    $req_ministry_service_idx = $_REQUEST["ministry_service_idx"];
    $req_ministry_cnt = $_REQUEST["ministry_cnt"];
    $req_ministry_date = $_REQUEST["ministry_date"];

    $arr_ministry_service_idx = explode(",", $req_ministry_service_idx);
    $arr_ministry_cnt = explode("|", $req_ministry_cnt);

    // 참여 통계 업데이트
    $sql = "select * from ministry_person_number_report where ministry_date='$req_ministry_date'";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);

    if ($num > 0) {

        $sql = "update ministry_person_number_report \r\n";
        $sql .= " set ministry_cnt = \r\n";
        $sql .= " case ";

        for ($i = 0; $i < count($arr_ministry_cnt); $i++) {
            $sql .= " when ministry_service_idx='$arr_ministry_service_idx[$i]' then '$arr_ministry_cnt[$i]' \r\n";
        }

        $sql .= " end \r\n";
        $sql .= " where ministry_service_idx in (" . $req_ministry_service_idx . ") and ministry_date='$req_ministry_date' ";

    } else {

        $sql = "insert into ministry_person_number_report(ministry_date, ministry_cnt, ministry_service_idx) values \r\n ";

        for ($i = 0; $i < count($arr_ministry_cnt); $i++) {

            if ($i > 0) {
                $sql .= ",";
            }
            $sql .= " ('$req_ministry_date','$arr_ministry_cnt[$i]','$arr_ministry_service_idx[$i]') \r\n ";

        }

    }

    $result = mysql_query($sql);

} else if ($work == "ministry_member_mgr_list") {

    // 각 구역카드에 참여한 전도인 명단 목록

    $req_map_sub_idx = $_REQUEST["map_sub_idx"];

    $sql = "select ministry_member_idx from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result = mysql_query($sql);
    $rs = mysql_fetch_array($result);

    $ministry_member_idx = $rs['ministry_member_idx'];

    $ministry_member_idx = str_replace(" ", "", $ministry_member_idx);
    $ministry_member_idx = str_replace(",,", ",", $ministry_member_idx);
    $ministry_member_idx = trim($ministry_member_idx, " ,");
    $ministry_member_idx = trim($ministry_member_idx, ", ");

    $sql = "SELECT * FROM member WHERE m_idx IN($ministry_member_idx) ORDER BY m_name ";
    $result = mysql_query($sql);
?>
    <ul class="list-group">
<?php
    while ($rs = mysql_fetch_array($result)) {

        $m_idx = $rs['m_idx'];
        $m_name = $rs['m_name'];
?>
        <li class="list-group-item" id="ministry_member_mgr_idx_<?=$m_idx ?>">
            <?=$m_name ?>
            <button type="button" name="m_idx" id="m_idx_<?=$m_idx ?>" onclick="ministry_member_mgr_del('<?=$req_map_sub_idx ?>', '<?=$m_idx ?>')" class="btn btn-danger btn-xs pull-right">삭제 </button>
        </li>
<?php
    }
?>
    </ul>
<?php

} else if ($work == "ministry_member_mgr_del") {

    // 관리자 혹은 인도자가 각 구역카드에 참여한 전도인을 명단 목록에서 삭제할 경우

    $req_map_sub_idx = $_REQUEST["map_sub_idx"];
    $m_idx = $_REQUEST['m_idx'];

    $sql1 = "select * from ministry_list where map_sub_idx='$req_map_sub_idx'";
    $result1 = mysql_query($sql1);
    $rs1 = mysql_fetch_array($result1, MYSQL_ASSOC);

    $ministry_member_idx = $rs1["ministry_member_idx"];

    $arr_ministry_member_idx = explode(",", $ministry_member_idx);

    $c_ministry_member_idx = "";

    for ($i = 0; $i < count($arr_ministry_member_idx); $i++) {

        if ($arr_ministry_member_idx[$i] != $m_idx) {
            $c_ministry_member_idx .= "," . $arr_ministry_member_idx[$i];
        }
    }

    $c_ministry_member_idx = substr($c_ministry_member_idx, 1);

    $sql = "UPDATE ministry_list SET ";
    $sql .= "ministry_member_idx='$c_ministry_member_idx' ";
    $sql .= "WHERE map_sub_idx='$req_map_sub_idx'";
    $result = mysql_query($sql);

} else if ($work == "map_list_record") {

    $req_map_sub_idx = $_REQUEST["map_sub_idx"];

    $sql = "select map_list_date from map_normal_list_record where map_sub_idx='$req_map_sub_idx' group by map_list_date order by map_list_date desc";
    $result = mysql_query($sql);

    $str = "";
    $str .= "<select class='form-control' id='map_list_date'>\r\n";
    $str .= "<option value=''>날짜선택</option>\r\n";

    while ($rs = mysql_fetch_array($result)) {

        $map_list_date = $rs["map_list_date"];
        $str .= "<option value='$map_list_date'>$map_list_date</option>\r\n";
    }

    $str .= "</select>\r\n";

    echo $str;

} else if ($work == "map_list_record_detail") {

    $req_map_sub_idx = $_REQUEST["map_sub_idx"];
    $req_map_list_date = $_REQUEST["map_list_date"];

?>
    <div class="marginbottom50">
        <table class="table">
        <tr>
            <th class="road">길이름</th>
            <th class="road-no">지번</th>
            <th>층</th>
            <th>이름/호</th>
            <th></th>
            <th>방문</th>
            <th>부재</th>
        </tr>
<?php
    $sql2 = "select * from map_normal_list a join map_normal_list_record b on a.map_list_idx=b.map_list_idx
           where b.map_sub_idx='$req_map_sub_idx' and b.map_list_date='$req_map_list_date'
           order by a.map_list_rank";
    $result2 = mysql_query($sql2);

    while ($rs2 = mysql_fetch_array($result2)) {

        $map_sub_idx = $rs2['map_sub_idx'];
        $map_list_idx = $rs2['map_list_idx'];
        $map_list_line = $rs2['map_list_line'];
        $map_list_road = $rs2['map_list_road'];
        $map_list_road_no = $rs2['map_list_road_no'];
        $map_list_floor = $rs2['map_list_floor'];
        $map_list_info = $rs2['map_list_info'];
        $map_list_visit_c = $rs2['map_list_visit_c'];

        $map_info = $map_list_road . " " . $map_list_road_no . " " . $map_list_floor . " " . $map_list_info;

        if ($map_list_line == "1") {
            $map_list_line_css = "home-map-list-line-css";
        } else {
            $map_list_line_css = "";
        }
?>
        <tr>
            <td class="road <?=$map_list_line_css ?>"><?=$map_list_road ?></td>
            <td class="road-no <?=$map_list_line_css ?>"><?=$map_list_road_no ?></td>
            <td class="<?=$map_list_line_css ?>"><?=$map_list_floor ?></td>
            <td class="<?=$map_list_line_css ?>"><?=$map_list_info ?></td>

            <td class="<?=$map_list_line_css ?>"><?=$house_type ?></td>

            <?php if ($map_list_enter_c == "N") { ?>
                <td colspan="3"><span class="label label-danger">방문금지</span></td>
            <?php } else { ?>
                <td class="<?=$map_list_line_css ?>">
                    <input type="checkbox" id="map_list_visit_Y_<?=$map_list_idx ?>" name="map_list_visit" <?=($map_list_visit_c == "Y")?"checked":""?> disabled>
                </td>
                <td class="<?=$map_list_line_css ?>">
                    <input type="checkbox" id="map_list_visit_N_<?=$map_list_idx ?>" name="map_list_visit" <?=($map_list_visit_c == "N")?"checked":""?> disabled>
                </td>
            <?php } ?>
        </tr>
<?php
    }
?>
        </table>
    </div>
<?php
}
?>