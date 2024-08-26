<?
header('Content-Type: text/html; charset=utf-8');
include "../include/db_connect.php";
include "../include/function.php";

session_check();



$work=$_REQUEST["work"];
$req_m_idx=$_REQUEST["m_idx"];



if ($work=="login_history"){

	$sql="SELECT * FROM member_login_log WHERE m_idx='$req_m_idx' ORDER BY login_date DESC LIMIT 30";
	$result=mysql_query($sql);

?>
 <ul class="list-group">
<?

	$i=0;
	while($rs=mysql_fetch_array($result)){
		$login_date=$rs[login_date];
		$login_remoteaddr=$rs[login_remoteaddr];
	?>
		<li class="list-group-item <?=($i==0)?"active":"" ?>"><?=$login_date ?> <span class="badge"><?=$login_remoteaddr ?></span> </li>
	<?
		$i++;
	}
?>
  </ul>
<?
}
?>