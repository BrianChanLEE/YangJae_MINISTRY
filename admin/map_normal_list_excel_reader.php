<?
require_once "Classes/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
$objPHPExcel = new PHPExcel();
require_once "Classes/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
//$filename = './map_13_426.xlsx'; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.


include "../include/db_connect.php";

$req_map_idx = $_REQUEST["map_idx"];
$req_map_sub_idx = $_REQUEST["map_sub_idx"];
$req_filename = $_REQUEST["filename"];

$filename = "../upload/".$req_filename;

	$sql="SELECT MAX(map_list_idx) FROM map_normal_list";
	$result = mysql_query($sql);
	$max_fetch = mysql_fetch_row($result);
	$max_map_list_idx=$max_fetch[0]+1;


try {
  // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
    // 읽기전용으로 설정
    $objReader->setReadDataOnly(true);
    // 엑셀파일을 읽는다
    $objExcel = $objReader->load($filename);
    // 첫번째 시트를 선택
    $objExcel->setActiveSheetIndex(0);
    $objWorksheet = $objExcel->getActiveSheet();
    $rowIterator = $objWorksheet->getRowIterator();
    foreach ($rowIterator as $row) { // 모든 행에 대해서
               $cellIterator = $row->getCellIterator();
               $cellIterator->setIterateOnlyExistingCells(false); 
    }
    $maxRow = $objWorksheet->getHighestRow();
	
	$intNEW=0; $intUPD=0; $intDEL=0;
	$strNEW=""; $strUPD=""; $strDEL="";
	$strComma="";

	$str_map_list_line="";
	$str_map_list_road="";
	$str_map_list_road_no="";
	$str_map_list_floor="";
	$str_map_list_info="";
	$str_map_list_rank="";
	$str_map_list_visit="";
	$str_map_list_enter="";
	$str_map_list_house_idx="";
	$str_map_list_idx="";


	$strEXCEL = "<table border=1>\r\n";

    for ($i = 1 ; $i <= $maxRow ; $i++) {
		$nud					= $objWorksheet->getCell('A' . $i)->getValue(); // A열
		$map_list_idx			= $objWorksheet->getCell('B' . $i)->getValue(); // B열
		$map_list_line			= $objWorksheet->getCell('C' . $i)->getValue(); // C열
		$map_list_road			= $objWorksheet->getCell('D' . $i)->getValue(); // D열
		$map_list_road_no		= $objWorksheet->getCell('E' . $i)->getValue(); // E열
		$map_list_floor			= $objWorksheet->getCell('F' . $i)->getValue(); // F열
		$map_list_info			= $objWorksheet->getCell('G' . $i)->getValue(); // G열
		$map_list_rank			= $objWorksheet->getCell('H' . $i)->getValue(); // H열
		$map_list_visit			= $objWorksheet->getCell('I' . $i)->getValue(); // I열
		$map_list_enter			= $objWorksheet->getCell('J' . $i)->getValue(); // J열
		$map_list_house_idx	= $objWorksheet->getCell('K' . $i)->getValue(); // K열
	
		$strEXCEL .= "<tr>\r\n";
		$strEXCEL .= "	<td>".$nud."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_idx."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_line."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_road."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_road_no."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_floor."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_info."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_rank."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_visit."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_enter."</td>\r\n";
		$strEXCEL .= "	<td>".$map_list_house_idx."</td>\r\n";
		$strEXCEL .= "</tr>\r\n";


		if (strtoupper($nud)=="N"){

			if ($intNEW>0){
			$strNEW.="union \r\n";
			}

			if ($map_list_rank==""){
				$map_list_rank = $max_map_list_idx;
			}

			$strNEW .= "select '$req_map_sub_idx','$max_map_list_idx','$map_list_line','$map_list_road','$map_list_road_no','$map_list_floor','$map_list_info','$map_list_rank','$map_list_house_idx' \r\n";

			$intNEW++;
			$max_map_list_idx++;

		}else if(strtoupper($nud)=="U"){

			if ($intUPD>0){
			$strComma=",";
			$strUPD.=",";
			}

			$strUPD.=$map_list_idx;

			$str_map_list_line.=$strComma.$map_list_line;
			$str_map_list_road.=$strComma.$map_list_road;
			$str_map_list_road_no.=$strComma.$map_list_road_no;
			$str_map_list_floor.=$strComma.$map_list_floor;
			$str_map_list_info.=$strComma.$map_list_info;
			$str_map_list_rank.=$strComma.$map_list_rank;
			$str_map_list_visit.=$strComma.$map_list_visit;
			$str_map_list_enter.=$strComma.$map_list_enter;
			$str_map_list_house_idx.=$strComma.$map_list_house_idx;
			$str_map_list_idx.=$strComma.$map_list_idx;

			$intUPD++;
			
		}else if(strtoupper($nud)=="D"){

			if ($intDEL>0){
			$strDEL.=",";
			}

			$strDEL.=$map_list_idx;
			$intDEL++;
			
		}
      }

	$strEXCEL .= "</table>\r\n";
    //echo $strEXCEL."\r\n";
	//echo $strNEW."\r\n";
	//echo $strUPD."\r\n";
	//echo $strDEL."\r\n";

	// break;


	if ($strNEW!=""){

		$sql = "INSERT INTO map_normal_list(map_sub_idx, map_list_idx, map_list_line, map_list_road, map_list_road_no, map_list_floor, map_list_info, map_list_rank, map_list_house_idx)\r\n";
		$sql .= $strNEW;
		$result=mysql_query($sql);

	// echo $sql;
	}

	if ($strUPD!=""){
		
		$arr_map_list_idx =			explode(",",$str_map_list_idx);
		$arr_map_list_line =		explode(",",$str_map_list_line);
		$arr_map_list_road =		explode(",",$str_map_list_road);
		$arr_map_list_road_no =		explode(",",$str_map_list_road_no);
		$arr_map_list_floor =		explode(",",$str_map_list_floor);
		$arr_map_list_info =		explode(",",$str_map_list_info);
		$arr_map_list_rank =		explode(",",$str_map_list_rank);
		$arr_map_list_visit =		explode(",",$str_map_list_visit);
		$arr_map_list_enter =		explode(",",$str_map_list_enter);
		$arr_map_list_house_idx =	explode(",",$str_map_list_house_idx);

		$sql = " UPDATE map_normal_list \r\n";
		$sql .= "    SET \r\n";
		
		$sql .= excel_update($arr_map_list_idx, " ", "map_list_line",	     $arr_map_list_line);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_road",	     $arr_map_list_road);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_road_no",     $arr_map_list_road_no);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_floor",	     $arr_map_list_floor);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_info",	     $arr_map_list_info);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_rank",	     $arr_map_list_rank);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_visit",	     $arr_map_list_visit);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_enter",	     $arr_map_list_enter);
		$sql .= excel_update($arr_map_list_idx, ",", "map_list_house_idx",   $arr_map_list_house_idx);

		$sql .= " WHERE map_sub_idx='$req_map_sub_idx' AND map_list_idx IN ($str_map_list_idx) \r\n";
		$result=mysql_query($sql);

	}

	if ($strDEL!=""){

		$sql = "DELETE FROM map_normal_list WHERE map_sub_idx='$req_map_sub_idx' AND map_list_idx IN ($strDEL)";
		$result=mysql_query($sql);

	}


	//작업완료 후 업로드 된 엑셀 파일 삭제
	@unlink($filename);
	


} 
 catch (exception $e) {
    echo 'error 엑셀파일을 읽는도중 오류가 발생하였습니다.';
}


function excel_update($arr_map_list_idx, $gubun, $field, $arr_field_item){

	$sql = "$gubun$field = \r\n";
	$sql .= "        case \r\n";
	for ($num = 0; $num < count($arr_map_list_idx); $num++)
	{
		$sql .= "             when map_list_idx = " . $arr_map_list_idx[$num] . " then '" . $arr_field_item[$num] ."'\r\n";
	}
	$sql .= "        end\r\n";	

	return $sql;

}


//$reg_date = PHPExcel_Style_NumberFormat::toFormattedString($reg_date, 'YYYY-MM-DD'); // 날짜 형태의 셀을 읽을때는 toFormattedString를 사용한다.
​?>