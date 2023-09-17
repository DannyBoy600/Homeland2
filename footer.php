<?php
session_start();
include "common.php";
include "db.php";

db_connect();

$cid = $_SESSION["sess_companyID"];

$name = "";
$sql = "SELECT Footer FROM Company WHERE CompanyID = {$cid}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
  $name = $row[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>

function doLogin()
{
	parent.center.location.href = "login_member.php";
}

function doLogout()
{
	parent.center.location.href = "logout.php";
}

</SCRIPT>
</HEAD>

<?php

if (isset($_SESSION["sess_homeland_logged_in"]))
  $anc = "<a href='javascript:doLogout()' style='color:#404040;text-decoration:none'>Logga ut</a>";
else
  $anc = "<a href='javascript:doLogin()' style='color:#404040;text-decoration:none'>Logga in</a>";
  
echo "
<body class='footer'>

<table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td class='mini' align='center' valign='center'><b>{$name}</b>".str_repeat("&nbsp;",5)."{$anc}</td>
  </tr>
</table>

</body></html>";
?>