<?php
session_start();
include "common.php";
include "db.php";

db_connect();

$cid = 0;

$user = com_getReqParamStr("name");
$pass = com_getReqParamStr("password");

$ok = false;
$msg = "Fel användarnamn eller lösenord";

if (isset($_SESSION["sess_homeland_admin_logged_in"]))
  $msg = "Kan ej vara inloggad som admin samtidigt!";
else
  $ok = db_loginMember ($user,$pass);

if ($ok)
{
  $_SESSION["sess_homeland_logged_in"] = true;
  $cid = $_SESSION["sess_companyID"];
}
else
{
  $_SESSION["sess_error_msg"] = $msg;
  unset($_SESSION["sess_homeland_logged_in"]);  
  header("Location: login_member.php");
  die();
}
?>
<html>
<script>
top.location.href = "index.php?cid=" + "<?php echo $cid?>";
</script>
</html>