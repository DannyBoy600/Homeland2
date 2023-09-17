<?php
include "head.php";
include "../db.php";
include "../common.php";

$pageID = com_getReqParamInt("pageID");
$kind = com_getReqParamStr("kind");

$_SESSION["sess_pageID"] = $pageID;
$_SESSION["sess_kind"] = $kind;
?>
<HTML>
<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="styles.css">
<SCRIPT src="jscripts.js"></SCRIPT>
</HEAD>

<FRAMESET cols="180,*,0" border=0 frameborder=0 framespacing=1>
 <FRAME src="page_left.php" name="left">
 <FRAMESET rows="30,*" border=0 frameborder=0 framespacing=0>
   <FRAME src="" name="navbar" scrolling="no" noresize>
   <FRAME src="" name="center">
 </FRAMESET>
 <FRAME src="" name="control" frameborder=0 scrolling="no" noresize marginwidth="0" marginheight="0">    
</FRAMESET>

</BODY></HTML>