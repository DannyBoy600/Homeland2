<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$contactID = com_getReqParamInt("hid_upload_id");

if ($contactID > 0)
{
  $filename = "../docs/company_{$cid}/contacts/contact_{$contactID}.jpg";
  
  if (file_exists ($filename))
    unlink($filename);
}
?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
  self.location.href = "page_contacts.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>