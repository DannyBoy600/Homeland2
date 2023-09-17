<?php
include "head.php";
include "../db.php";
include "../common.php";
include "page_db.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$db = db_connect();

$errorNo = pag_deleteText($pageID);

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var errorNo = <?php echo $errorNo?>;
var pageID = <?php echo $pageID?>;
function onLoad()
{
	if (errorNo != 0) alert("Fel vid spara!");
   parent.location.href = "page_main.php?pageID=" + pageID;
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>