<?
header('Content-Type: text/html; charset=utf-8');
include "include/db_connect.php";
include "include/function.php";

$m_name=test_input($_POST["m_name"]);
$m_pw=test_input($_POST["m_pw"]);
$m_logincheck=test_input($_POST["m_logincheck"]);
$m_token=test_input($_POST["m_token"]);

//mysql_query("SET NAMES [euckr]");


if ($m_token != $_SESSION['m_token']){
	echo "<SCRIPT LANGUAGE='JavaScript'>
		alert('로그인 오류');
		history.back();
		</SCRIPT>";
		exit;
}



$sql="SELECT * FROM member WHERE m_name='{$m_name}'";
$result=mysql_query($sql);
$num=mysql_num_rows($result);

if($num<=0){
echo "<SCRIPT LANGUAGE='JavaScript'>
	alert('등록된 전도인이 아닙니다.');
	history.back();
	</SCRIPT>";
	exit;
}else{

	$sql="SELECT * FROM member WHERE m_name='{$m_name}' AND m_pw='{$m_pw}'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);

	if($num<=0){
	echo "<SCRIPT LANGUAGE='JavaScript'>
		alert('비밀번호가 잘못되었습니다.');
		history.back();
		</SCRIPT>";
		exit;
	}else{

		Session_Start();

		$rs = mysql_fetch_array($result, MYSQL_ASSOC);

		$_SESSION['m_idx'] = $rs["m_idx"];
		$_SESSION['m_name'] = $rs["m_name"];
		$_SESSION['m_mgr'] = $rs["m_mgr"];
		$_SESSION['m_position'] = $rs["m_position"];
		$_SESSION['m_pioneer'] = $rs["m_pioneer"];
		$_SESSION['m_sex'] = $rs["m_sex"];

		
		if ($m_logincheck=="on"){
			$cookie_name = "jw_ministry";
			$cookie_value = $rs["m_name"]."|".$rs["m_pw"];
			setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/"); // 86400 = 1 day
		}else{
			$cookie_name = "jw_ministry";
			setcookie($cookie_name, "", time() - 3600);
		}

		$login_useragent = $_SERVER['HTTP_USER_AGENT'];
		$login_remoteaddr=$_SERVER['REMOTE_ADDR'];


		try{
			//로그인 로그 저장
			$sql="insert into member_login_log(m_name, login_date, m_idx, login_useragent, login_remoteaddr) values('$m_name', now(), '{$rs["m_idx"]}' , '$login_useragent', '$login_remoteaddr')";
			$result=mysql_query($sql);
		} 
		 catch (exception $e) {
			echo '로그인 로그 저장 오류';
			exit;
		}

		echo "<SCRIPT LANGUAGE='JavaScript'>
			location.href='/home/';
			</SCRIPT>";
			exit;
	}
}
?>