<?php
include "head.php";
include "../db.php";
include "../common.php";

$contactID = com_getReqParamInt("contactID");
if ($contactID == 0) die();

$_SESSION["sess_homeland_logged_in"] = true;

$_SESSION["sess_book_userID"] = $contactID;

header("Location: ../book_main.php");
?>
