<?php
include "head.php";
include "../db.php";
include "../common.php";
include "../book_functions.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$msg = "";

$header = com_getReqParamStr("txt_header");
$contactPageID = com_getReqParamInt("sel_contact_pages");
$defaultYear = com_getReqParamInt("sel_default_year");

$cnt = 0;
$sql = "SELECT COUNT(*) as Cnt FROM PageBook WHERE PageID = {$pageID}";
$res = mysql_query ($sql);
$errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
if ($row = mysql_fetch_object($res))
  $cnt = $row->Cnt;
  
if ($cnt == 0)
{
	$sql = "
INSERT PageBook (PageID,MenuID,CompanyID,Header,ContactPageID,IsVisible) 
VALUES ({$pageID},{$menuID},{$cid},'{$header}',{$contactPageID},1)"; 
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}
else if ($cnt > 0)
{
  $sql = "UPDATE PageBook SET Header = '{$header}',ContactPageID = {$contactPageID} WHERE PageID = {$pageID}";
  $res = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}

// make sure this year is initiated (one record for each week)
book_loadCells($pageID,$defaultYear);

// first, get all yellow weeks and their contacts
$defWeeks = null;
$i = 0;
while (isset($_POST["hid_contactID_{$i}"]))
{
	$curContactID = com_getReqParamInt("hid_contactID_{$i}");
  for ($j=1;$j<53;$j++)
  {
    $curDefBooked = com_getReqParamChk("chk_{$i}_{$j}"); 
    if ($curDefBooked == 1)
    	if (!isset($defWeeks[$j]))
    	  $defWeeks[$j] = $curContactID;
  }
  $i++;
}

// now handle each week
$sql = "SELECT CellID,WeekNo,State FROM PageBookCell WHERE PageID = {$pageID} AND YearNo = {$defaultYear} ORDER BY WeekNo";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res))
{
	$curCellID = $row[0];
	$curWeekNo = $row[1];
	$curState = $row[2];
	
	if (isset($defWeeks[$curWeekNo]))
	{
		$curDefaultContactID = $defWeeks[$curWeekNo];
		switch ($curState)
		{
			case 1: $sql = "UPDATE PageBookCell SET DefaultContactID = {$curDefaultContactID}, State = 2 WHERE CellID = {$curCellID}"; break;
			case 2: $sql = "UPDATE PageBookCell SET DefaultContactID = {$curDefaultContactID} WHERE CellID = {$curCellID}"; break;
			case 3: $sql = "UPDATE PageBookCell SET DefaultContactID = {$curDefaultContactID} WHERE CellID = {$curCellID}"; break;
			default;$sql = "UPDATE PageBookCell SET DefaultContactID = {$curDefaultContactID}, State = 2 WHERE CellID = {$curCellID}"; break;
    }
  }
	else
	{
		switch ($curState)
		{
			case 1: $sql = "UPDATE PageBookCell SET DefaultContactID = 0 WHERE CellID = {$curCellID}"; break;
			case 2: $sql = "UPDATE PageBookCell SET DefaultContactID = 0, State = 1 WHERE CellID = {$curCellID}"; break;
			case 3: $sql = "UPDATE PageBookCell SET DefaultContactID = 0 WHERE CellID = {$curCellID}"; break;
			default;$sql = "UPDATE PageBookCell SET DefaultContactID = 0, State = 1 WHERE CellID = {$curCellID}"; break;
    }
  }
	$res2 = mysql_query ($sql);
  $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
var errorNo = <?php echo $errorNo?>;
var msg = "<?php echo $msg?>";
function onLoad()
{
	if (msg != "") alert(msg);
	if (errorNo != 0) alert("Fel vid spara!");
  self.location.href = "page_bookings.php?yearNo=<?php echo $defaultYear?>";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>