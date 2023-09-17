<?php
session_start();	

$cid = $_SESSION["sess_companyID"]; // remember this one

session_unset();

echo "<HTML><BODY onLoad='javascript:parent.location.href=\"index.php?cid={$cid}\"'></BODY></HTML>";
?>