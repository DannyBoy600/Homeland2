<?php  
include "db_site_specific.php";

function db_login($user,$pass)
{
	$_SESSION["sess_userID"] = 0;
  $sql = "
SELECT UserID, CONCAT(FirstName,' ',LastName) AS FN, CompanyID
FROM User 
WHERE UPPER(UserName)='" . strtoupper($user) . "' AND UPPER(PassWord)='" . strtoupper($pass) . "'";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res)) 
  {
    $_SESSION["sess_userID"] = $row->UserID;
    $_SESSION["sess_full_name"] = $row->FN;
    $_SESSION["sess_companyID"] = $row->CompanyID;
    return true;
  }
  return false;
}

function db_loginMember($user,$pass)
{
	$_SESSION["sess_userID"] = 0;
	if ($user != "" && $pass != "")
	{
    $sql = "
SELECT ContactID, Name, CompanyID
FROM PageContact
WHERE UPPER(UserName)='" . strtoupper($user) . "' AND UPPER(PassWord)='" . strtoupper($pass) . "'";
    $res = mysql_query ($sql);
    if ($row = mysql_fetch_object($res)) 
    {
      $_SESSION["sess_userID"] = $row->ContactID;
      $_SESSION["sess_full_name"] = $row->Name;
      $_SESSION["sess_companyID"] = $row->CompanyID;
      return true;
    }
  }
  return false;
}

function db_logout($userID)
{
  /*
  $sql = "UPDATE LoginInfo SET LogoutTime = now() WHERE UserID = {$u->UserID} ORDER BY LoginTime DESC LIMIT 1";
  $res = mysql_query ($sql);

  $sql = "DELETE FROM LoginInfo WHERE ABS(DATEDIFF(LoginTime,now())) > 180";
  $res = mysql_query ($sql);
  */
} 

function db_getCurrentDate()
{
  $sql=	"SELECT now() AS CurrentDate";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res))
    return $row->CurrentDate; 
  else
    return "";    
}

function db_getCurrentYear()
{
  $sql = "SELECT Year(now()) AS CurYear";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res))
    return $row->CurYear;
  else
    return "0";
}
  
function db_getCurrentWeekNo()
{
  $sql=	"SELECT WEEK(NOW(),1) AS WeekNo";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res))
    return $row->WeekNo; 
  else
    return "0";    
}

function db_getCurrentDayNo()
{
  $sql=	"SELECT WEEKDAY(NOW()) AS WeekDay";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_object($res))
    return $row->WeekDay + 1; 
  else
    return "0";
}

function fixLogString($s,$maxlen)
{
  if (is_null($s)) return "";
  if (strlen($s)>$maxlen) $s = substr($s,0,$maxlen);    
  $s = strtr($s, "'", "&#039;");	
  $s = strtr($s, '"', '&quot;');
  return $s;
}

function db_logError($unitID,$userID,$msg,$errorNo,$errorText,$file,$function,$line)
{
	if (is_null($unitID)) $unitID = 0;
  if (is_null($userID)) $userID = 0;
  $msg = fixLogString($msg,1000000);
  $errorText = fixLogString($errorText,255);
  $file = strtr($file, "\\", "/");	
  $sql = "
INSERT ErrorLog (CompanyID,UserID,Msg,ErrorNo,ErrorText,PHPFile,PHPFunction,PHPLine,Browser,InsDate)
VALUES({$unitID}, {$userID}, '{$msg}', {$errorNo}, '{$errorText}', '{$file}', '{$function}', '{$line}', '', now()) ";
  $res = mysql_query ($sql);
}

function db_logSqlError($sql,$file,$function,$line)
{
  $errorNo = mysql_errno(); 
  $errorText = mysql_error();
  $cid = 0;
  $userID = 0;
  if (isset($_SESSION["sess_companyID"])) $cid = $_SESSION["sess_companyID"];
  if ($errorNo != 0)
  {
    db_logError($cid,$userID,$sql,$errorNo,$errorText,$file,$function,$line);
  }
  return $errorNo;	
}

function getmicrotime()
{ 
  list($usec, $sec) = explode(" ",microtime()); 
  return ((float)$usec + (float)$sec); 
} 

$time_start = 0;
function starttimer()
{ 
  global $time_start;
  list($usec, $sec) = explode(" ",microtime()); 
  $time_start = (float)$usec + (float)$sec; 

} 
function endtimer($s)
{
  global $time_start;
  $time = floor(1000*(getmicrotime() - $time_start));
  echo "$s " . $time . " milliseconds<br>";
}

?>