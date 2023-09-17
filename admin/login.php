<?php
session_start();
include "../common.php";
include "../db.php";

db_connect();

$user = com_getReqParamStr("name");
$pass = com_getReqParamStr("password");

$ok = false;
$msg = "Fel användarnamn eller lösenord";

if (isset($_SESSION["sess_homeland_logged_in"]))
  $msg = "Kan ej vara inloggad på hemsidan samtidigt!";
else
  $ok = db_login ($user,$pass);

if ($ok)
{
	$_SESSION["sess_screen_width"] = com_getReqParamInt("hid_screen_width");
  $_SESSION["sess_homeland_admin_logged_in"] = true;
  header("Location: main.php");
}
else
{
  $_SESSION["sess_error_msg"] = $msg;
  unset($_SESSION["sess_homeland_admin_logged_in"]);  
  header("Location: index.php");
}
?>