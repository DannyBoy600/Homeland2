<?php
session_start();
include "common.php";
include "db.php";

if (isset($_REQUEST["cid"])) 
{
  $_SESSION["sess_companyID"] = (int)$_REQUEST["cid"];
}
else
{
  header("Location: homeland.php");
  exit;
}

$cid = $_SESSION["sess_companyID"];

$db = db_connect();

// Visitor counting
$sql = "UPDATE Visitors SET Hits = Hits + 1 WHERE YYYY_MM_DD = CURDATE()";
$res = mysql_query ($sql);
if (mysql_affected_rows() == 0)
{
  $sql = "INSERT Visitors VALUES(CURDATE(),1)";
  $res = mysql_query ($sql);
}

$sql = "SELECT Hits FROM Visitors WHERE YYYY_MM_DD = CURDATE()";
$res = mysql_query ($sql);
if ($row = mysql_fetch_object($res)) 
  $hits = $row->Hits;
$_SESSION["sess_hits"] = $hits;

if ($cid > 0)
{
  $sql = "SELECT Name FROM Company WHERE CompanyID = {$cid}";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_row($res)) 
  {
  	$companyName = $row[0];
  }
}

?>
<HTML>
<HEAD>
<TITLE><?php echo $companyName?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="">
<meta name="description" content="">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function onLoad()
{
}
</SCRIPT>
</HEAD>

<FRAMESET rows="20,*,20" border=0 frameborder=0 framespacing=0 onLoad="javascript:onLoad()">
	<FRAME src="space_around.htm" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">
  <FRAMESET cols="*,800,*" border=0 frameborder=0 framespacing=0>
  	<FRAME src="space_around.htm" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">
    <FRAMESET rows="65,*" border=0 frameborder=0 framespacing=0>
      <FRAME src="menu.php" name="menu" scrolling="no" noresize>
      <FRAME src="empty.php" name="center" scrolling="auto" noresize>
    </FRAMESET>
    <FRAME src="space_around.htm" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">
  </FRAMESET>
  <FRAME src="footer.php" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">
</FRAMESET>

</HTML>
