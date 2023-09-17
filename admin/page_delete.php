<?php
include "head.php";
include "../db.php";
include "../common.php";
include "page_db.php";

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$db = db_connect();

$errorNo = pag_deletePage($pageID);

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var menuID = <?php echo $menuID?>;
function onLoad()
{
  parent.parent.header_sub.location.href = "pages_header_sub.php?menuID=" + menuID;
}
</script>
<body onLoad="javascript:onLoad()">
</body></html>