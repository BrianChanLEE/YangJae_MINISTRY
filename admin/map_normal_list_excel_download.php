<?php
include "../include/db_connect.php";
include "../include/function.php";

session_check();

if (!mgr_check("1,2,3")){
	echo "<script>location.href='/home/';</script>";
	exit;
}

$req_map_idx=$_REQUEST["map_idx"];
$req_map_sub_idx=$_REQUEST["map_sub_idx"];


$sql="SELECT * FROM map_normal_sub WHERE map_sub_idx='$req_map_sub_idx'";
$result_type=mysql_query($sql);
$rs=mysql_fetch_array($result_type);
$map_sub_no=$rs['map_sub_no'];
$map_sub_info=$rs['map_sub_info'];
$map_sub_cnt=$rs['map_sub_cnt'];
$map_sub_addr=$rs['map_sub_addr'];

$map_sub_no=strtolower($map_sub_no);
$map_sub_no=str_replace("<br>"," ",$map_sub_no);
$map_sub_no=str_replace("\\"," ",$map_sub_no);
$map_sub_no=str_replace("/"," ",$map_sub_no);
$map_sub_no=str_replace("?"," ",$map_sub_no);
$map_sub_no=str_replace("*"," ",$map_sub_no);
$map_sub_no=str_replace("["," ",$map_sub_no);
$map_sub_no=str_replace("]"," ",$map_sub_no);






/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Seoul');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("JW MINISTRY")
							 ->setLastModifiedBy("JW MINISTRY")
							 ->setTitle("MINISTRY MAP")
							 ->setSubject("MINISTRY MAP LIST")
							 ->setDescription("THE FIELD SERVICE")
							 ->setKeywords("FIELD SERVICE MAP LIST")
							 ->setCategory("MAP LIST");


// Add some data
//$objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A1', 'Hello')
//            ->setCellValue('B2', 'world!')
//            ->setCellValue('C1', 'Hello')
//            ->setCellValue('D2', 'world!');


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '신규/수정/삭제')
            ->setCellValue('B1', '일련번호')
            ->setCellValue('C1', '구분선')
            ->setCellValue('D1', '길이름')
            ->setCellValue('E1', '건물번호')
            ->setCellValue('F1', '층')
            ->setCellValue('G1', '이름/호')
            ->setCellValue('H1', '순서')
            ->setCellValue('I1', '방문/부재')
            ->setCellValue('J1', '거절/금지')
            ->setCellValue('K1', '구역형태');


$sql="SELECT * FROM map_normal_list WHERE 1=1 ";
$sql.=" and map_sub_idx='$req_map_sub_idx'";
$sql.=" order by map_list_rank";
$result=mysql_query($sql);

$i=2;

$map_list_idx="";

while($rs=mysql_fetch_array($result)){

	$map_list_idx=$rs['map_list_idx'];
	$map_list_line=$rs['map_list_line'];
	$map_list_road=$rs['map_list_road'];
	$map_list_road_no=$rs['map_list_road_no'];
	$map_list_floor=$rs['map_list_floor'];
	$map_list_info=$rs['map_list_info'];
	$map_list_rank=$rs['map_list_rank'];
	$map_list_visit=$rs['map_list_visit'];
	$map_list_visit_c=$rs['map_list_visit_c'];
	$map_list_enter=$rs['map_list_enter'];
	$map_list_enter_c=$rs['map_list_enter_c'];
	$map_list_house_idx=$rs['map_list_house_idx'];

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A$i", "U")
				->setCellValue("B$i", $map_list_idx)
				->setCellValue("C$i", $map_list_line)
				->setCellValue("D$i", $map_list_road)
				->setCellValue("E$i", $map_list_road_no)
				->setCellValue("F$i", $map_list_floor)
				->setCellValue("G$i", $map_list_info)
				->setCellValue("H$i", $map_list_rank)
				->setCellValue("I$i", $map_list_visit)
				->setCellValue("J$i", $map_list_enter)
				->setCellValue("K$i", $map_list_house_idx);

				$i++;
}



// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O1', '*설정값 안내')

            ->setCellValue('O3', '신규/수정/삭제')
            ->setCellValue('P3', '값')
            ->setCellValue('O4', '신규')
            ->setCellValue('P4', 'N')
            ->setCellValue('O5', '수정')
            ->setCellValue('P5', 'U')
            ->setCellValue('O6', '삭제')
            ->setCellValue('P6', 'D')

            ->setCellValue('O8', '일련번호')
            ->setCellValue('P8', '각 항목의 고유값')
            ->setCellValue('P9', '신규는 공백')

            ->setCellValue('O11', '구분선')
            ->setCellValue('P11', '값')
            ->setCellValue('O12', '있음')
            ->setCellValue('P12', '1')
            ->setCellValue('O13', '없음')
            ->setCellValue('P13', '0')

            ->setCellValue('O15', '순서')
            ->setCellValue('P15', '번호순으로 정렬됨')
            ->setCellValue('P16', '신규는 공백')

            ->setCellValue('O18', '방문/부재')
            ->setCellValue('P18', '값')
            ->setCellValue('O19', '방문')
            ->setCellValue('P19', 'Y')
            ->setCellValue('O20', '부재')
            ->setCellValue('P20', 'N')

            ->setCellValue('O22', '거절/금지')
            ->setCellValue('P22', '값')
            ->setCellValue('O23', '거절')
            ->setCellValue('P23', 'Y')
            ->setCellValue('O24', '금지')
            ->setCellValue('P24', 'N')

            ->setCellValue('O26', '구역형태')
            ->setCellValue('P26', '값')
            ->setCellValue('O27', '지정안됨')
            ->setCellValue('P27', '0');



$sql="SELECT * FROM db_house_type";
$result=mysql_query($sql);

$j=28;

$map_list_idx="";

while($rs=mysql_fetch_array($result)){

	$house_idx=$rs['house_idx'];
	$house_type=$rs['house_type'];

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("O$j", $house_type)
				->setCellValue("P$j", $house_idx);

				$j++;
}


$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$objPHPExcel->getActiveSheet()->getStyle('A1:K'.--$i)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O3:P6')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O8:P9')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O11:P13')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O15:P16')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O18:P20')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O22:P24')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('O26:P34')->applyFromArray($styleArray);
unset($styleArray);




$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);

$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($map_sub_no);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="map_'.$req_map_idx.'_'.$req_map_sub_idx.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
