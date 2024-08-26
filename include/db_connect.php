<?
define("MINISTRY_RECYCLE_SECOND", 1000*15); /*15초*/
define("DAUM_MAP_API", "ac06e512d4b93df81a4081cc20f6dd5c");
define("JW_MANUAL", "jw_ministry_manual_vol_1.1.pdf");


$host="localhost";
$user="suseo1914";
$password="Jw1935@@";
$dbname="suseo1914";

$objConn=mysql_connect("$host", "$user", "$password");
//mysql_query("SET NAMES euckr"); //한글깨짐방지를 위해
mysql_select_db("$dbname", $objConn);


$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i = 0; $i < $ext_cnt; $i++) {
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//@extract($_REQUEST);



// Check connection
if (!$objConn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

session_start();



//error_reporting(E_ALL);
//ini_set("display_errors", 1);
?>
