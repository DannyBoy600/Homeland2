<?php
session_start();

if (!isset($_SESSION["sess_homeland_admin_logged_in"])) $_SESSION["sess_homeland_admin_logged_in"] = false;

if (!$_SESSION["sess_homeland_admin_logged_in"])
{
  $_SESSION["sess_error_msg"] = "Anv�ndaren �r ej inloggad";
  header("Location: index.php");
} 
                               	
?>
