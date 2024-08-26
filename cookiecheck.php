<?
if(!isset($_COOKIE["jw_ministry"])) {
//    echo "Cookie named '" . $cookie_name . "' is not set!";

    echo "nothing";
	exit;
} else {
//    echo "Cookie '" . $cookie_name . "' is set!<br>";
//    echo "Value is: " . $_COOKIE[$cookie_name];

    echo $_COOKIE["jw_ministry"];
	exit;
}
?>

