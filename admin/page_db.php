<?php

function pag_deleteText($pageID)
{
	if ($pageID > 0)
	{	  
	  $sql = "DELETE FROM PageText WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    return $errorNo;
  }
	return 0;
}

function pag_deleteDocuments($pageID)
{
	if ($pageID > 0)
	{	  
	  $sql = "DELETE FROM PageDoc WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    return $errorNo;
  }
	return 0;
}

function pag_deleteLinks($pageID)
{
	if ($pageID > 0)
	{	  
	  $sql = "DELETE FROM PageLink WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    return $errorNo;
  }
	return 0;
}

function pag_deleteContacts($pageID)
{
	if ($pageID > 0)
	{	  
	  $sql = "DELETE FROM PageContact WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    return $errorNo;
  }
	return 0;
}

function pag_deleteActions($pageID)
{
	if ($pageID > 0)
	{	  
	  $sql = "DELETE FROM PageAction WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
    return $errorNo;
  }
	return 0;
}

function pag_deletePage($pageID)
{
	if ($pageID > 0)
	{
	  $errorNo = pag_deleteText($pageID);
	
	  $errorNo = pag_deleteDocuments($pageID);
	
	  $errorNo = pag_deleteLinks($pageID);
	  
	  $errorNo = pag_deleteContacts($pageID);
	  
	  $errorNo = pag_deleteActions($pageID);
	  
	  $sql = "DELETE FROM Page WHERE PageID = {$pageID}";
    $res = mysql_query ($sql);
    $errorNo = db_logSqlError($sql,__FILE__,__FUNCTION__,__LINE__);
  }
  
	return 0;
}
?>