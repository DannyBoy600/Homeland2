<?php

// make sure there is one cell for each week selected year
function book_loadCells($pageID,$year)
{ 
	if ($pageID > 0 && $year > 0)
	{
	  // find out if cells have already been created
    $cnt = 0;
    $sql = "SELECT COUNT(*) FROM PageBookCell WHERE PageID = {$pageID} AND YearNo = {$year}";
    $res = mysql_query ($sql);
    if ($row = mysql_fetch_row($res)) 
	    $cnt = $row[0];
	  if ($cnt > 0) return;
    
	  for ($i=1;$i<53;$i++)
	  {
	  	$sql = "REPLACE PageBookCell (CellID,PageID,YearNo,WeekNo,DefaultContactID,ContactID,State) VALUES (NULL,{$pageID},{$year},{$i},0,0,1)";
      $res = mysql_query ($sql);
      $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
	  }
  }
}

?>