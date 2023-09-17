<?php
include "head.php";
include "../common.php";
include "../db.php";

db_connect();

$cid = $_SESSION["sess_companyID"];

$name = "";
$sql = "SELECT Name FROM Company WHERE CompanyID = {$cid}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_row($res))
  $name = $row[0];
  
?>
<HTML>
<TITLE><?php echo $name?></TITLE>
<FRAMESET rows="40,*,0" border=0 frameborder=0 framespacing=0>
 <FRAME src="menu.php"  name="menu" scrolling="no">
 <FRAME src="empty.php" name="center">
 <FRAME src="" name="control" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">    
</FRAMESET>

</HTML>