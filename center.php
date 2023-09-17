<?php
session_start();
include "common.php";
include "db.php";

$menuID = com_getReqParamInt("menuID");

$_SESSION["sess_homepage_menuID"] = $menuID;

?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

</SCRIPT>
</HEAD>

<FRAMESET rows="25,*" border=0 frameborder=0 framespacing=0>
  <FRAME src="submenu.php" name="submenu" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">
  <FRAME src="page.php" name="page" frameborder=0 scrolling="auto" marginwidth="0" marginheight="0">
</FRAMESET>

</HTML>
