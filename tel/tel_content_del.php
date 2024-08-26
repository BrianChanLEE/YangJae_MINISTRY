<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();


$req_tel_content_idx=$_REQUEST["tel_content_idx"];

$sql="delete from tel_content_list ";
$sql.="WHERE tel_content_idx='$req_tel_content_idx'";
$result=mysql_query($sql);
?>