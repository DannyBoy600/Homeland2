<?php
include "../header.php";
include "../db.php";
include "../common.php";
include "../version.php";
include "print_functions.php";

db_connect();

$cutName = com_getReqParamStr("cutName");
$cutKind = com_getReqParamStr("cutKind");
$cutPrio = com_getReqParamStr("cutPrio");
$cutFin = com_getReqParamStr("cutFin");
$cutNotFin = com_getReqParamStr("cutNotFin");
$cutProjName = com_getReqParamStr("cutProjName");
$isExternal = com_getReqParamInt("isExternal");

$companyID = $_SESSION["user_companyID"];

$errorNo = db_getCompanyInfo($companyID,$ci);

$status = $_SESSION["list_projectStatus"];
$leaderID = $_SESSION["list_projectLeader"];

$statusText = "";
if ($status > 0) $statusText = db_getText("PRO_STATUS_EXPL") . ": " . db_getStatusText($status);

$leaderText = "";
if ($leaderID > 0) 
{
  $sql = "SELECT CONCAT(Firstname,' ', LastName) FROM User WHERE UserID = {$leaderID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  if ($row = mysql_fetch_array($res))
    $leaderText = db_getText("PRO_PROJECT_LEADER") . ": " . $row[0];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE><?php echo PROGHEADER_K?></TITLE>
<LINK REL="stylesheet" TYPE="text/css" HREF="../styles.css">
</HEAD>

<?php
  echo "
<BODY class='print'><FORM>" .

  com_printHeader($ci,db_getText("REQ_REQ") . " " . $_SESSION["req_productName"]) . "

<TABLE border='0' cellpadding='0' cellspacing='1'>
  <TR><TD class='breadhead'>".db_getText("COM_SEARCH_CRITIERAS").":</TD></TR>";

  if ($cutName != "") echo "<TR><TD>".db_getText("REQ_NAME").":&nbsp;{$cutName}</TD></TR>";
  if ($cutKind != "") echo "<TR><TD>".db_getText("REQ_KIND").":&nbsp;{$cutKind}</TD></TR>";
  if ($cutPrio != "") echo "<TR><TD>".db_getText("COM_PRIO_SHORT").":&nbsp;{$cutPrio}</TD></TR>";
  echo "<TR><TD>Klar:&nbsp;";
  if ($cutFin == "1") echo db_getText("COM_YES")."&nbsp;";
  if ($cutNotFin == "1") echo db_getText("COM_NO")."&nbsp;";
  echo "</TD></TR>";
  if ($cutProjName != "") echo "<TR><TD>".db_getText("PRO_PROJECT").":&nbsp;{$cutProjName}</TD></TR>";
  echo "<TR><TD>&nbsp;</TD></TR>";

  $reqs = $_SESSION["req_list"];  
  $timeTot = 0;
  for ($i=0;$i<count($reqs);$i++)
  {
  	$curTime = $reqs[$i][8];
  	if ($curTime > 0) $timeTot += $curTime;
    echo "<TR><TD>" . printRequirement($reqs[$i][0],$isExternal) . "<br></TD></TR>";
  }
  
  if ($isExternal == 0) 
    echo "<TR><TD class='normal'><b>".db_getText("REQ_TIME_TOTAL").":</b>&nbsp;{$timeTot}&nbsp;".strtolower(db_getText("REQ_HOURS"))."</TD></TR>";
    
  echo "
</TABLE>

</FORM></BODY></HTML>";
?>