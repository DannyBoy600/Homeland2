<?php

// some colors
define("MOUSE_SELECT_COLOR_K","#7A7A7A");
define("MOUSE_OVER_COLOR_K","#7A7A7A");

function com_fixLineBreak($s)
{
  return str_replace("\n", "<br>", $s);
}

function com_shorten($s,$size)
{
  if (is_null($s)) return $s;
  if (strlen($s) > $size) return substr($s,0,$size) . "..";
  return $s;
}

function com_printButton($id,$value,$jsRoutine,$cssClass,$tdStyle,$spaces,$info,$disabled)
{
  $sp = "";
  for ($i=0;$i<$spaces;$i++) 
    $sp .= "&nbsp;";
  ($disabled) ? $dis = "disabled" : $dis="";
  return "<TD><INPUT {$dis} type='button' id='{$id}' class='{$cssClass}' style='{$tdStyle}' value='{$value}' title='{$info}' onClick='javascript:{$jsRoutine}'>{$sp}</TD>";
}

function com_printButtonPlain($id,$value,$jsRoutine,$cssClass,$tdStyle,$spaces,$info,$disabled)
{
  $sp = "";
  for ($i=0;$i<$spaces;$i++) 
    $sp .= "&nbsp;";
  ($disabled) ? $dis = "disabled" : $dis="";
  return "<INPUT {$dis} type='button' id='{$id}' class='{$cssClass}' style='{$tdStyle}' value='{$value}' title='{$info}' onClick='javascript:{$jsRoutine}'>{$sp}";
}

function com_printLink($strLabel,$strTitle,$strId,$strJFunc,$strClass,$strStyle,$bDisabled)
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  return "<TD nowrap><A {$dis} href='javascript:{$strJFunc}' id='{$strId}' class='{$strClass}' style='{$strStyle}' title='{$strTitle}'>{$strLabel}</A></TD>";
}

function com_printInputText($strLabel,$strTitle,$strId,$strName,$strValue,$strSize,$strMaxLength,$bDisabled,$jsRoutine="pictureChanged()")
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  $td = "";
  if ($strLabel != null)
  {
    if ($strLabel != "") $strLabel .= "&nbsp;"; 
    $td = "<TD class='normalBold' title='{$strTitle}' nowrap>{$strLabel}</TD>";
  }
  return "{$td}<TD><INPUT {$dis} type='text' class='inputText' value='{$strValue}' id='{$strId}' name='{$strName}' size={$strSize} maxLength={$strMaxLength} onKeyDown='javascript:{$jsRoutine}'></TD>";
}

function com_printInputTextPlain($strId,$strName,$strValue,$strSize,$strMaxLength,$bDisabled,$jsRoutine="pictureChanged()")
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  return "<INPUT {$dis} type='text' class='inputText' value='{$strValue}' id='{$strId}' name='{$strName}' size={$strSize} maxLength={$strMaxLength} onKeyDown='javascript:{$jsRoutine}'>";
}

function com_printInputPassword($strLabel,$strTitle,$strId,$strName,$strValue,$strSize,$strMaxLength,$bDisabled)
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  if ($strLabel != "") $strLabel .= "&nbsp;";
  return "<TD class='normalBold' title='{$strTitle}' nowrap>{$strLabel}</TD><TD><INPUT {$dis} type='password' class='normal' style='font-size:9px' value='{$strValue}' id='{$strId}' name='{$strName}' size={$strSize} maxLength={$strMaxLength} onKeyDown='javascript:pictureChanged()'></TD>";
}

function com_printTextArea($strLabel,$strTitle,$strId,$strName,$strValue,$strRows,$strCols,$bReadOnly,$bDisabled)
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  $td = "";
  if ($strLabel != null)
  {
    if ($strLabel != "") $strLabel .= "&nbsp;"; 
    $td = "<TD valign='top' class='normalBold' title='{$strTitle}' nowrap>{$strLabel}</TD>";
  }
  $strAction = "onKeyDown='javascript:pictureChanged()'";
  $strStyle = "";
  if ($bReadOnly)
  {
  	$strStyle = "background-Color:#BDD2D2;";
    $strAction = "";
  }
  return "{$td}<TD><TEXTAREA class='textarea' style='{$strStyle}' {$dis}' id='{$strId}' name='{$strName}' rows={$strRows} cols={$strCols} {$strAction}>{$strValue}</TEXTAREA></TD>";
}

function com_printSelect($strLabel,$strTitle,$strId,$strName,$bMultiple,$strSize,$arrOptions,$selValue,$strJFunc,$bZeroOption,$bDisabled)
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  ($bMultiple) ? $mul = "multiple" : $mul = "";
  $td = "";
  if ($strLabel != null)
  {
    if ($strLabel != "") $strLabel .= "&nbsp;";
    if ($strSize > 1) 
      $td = "<TD valign='top' class='normalBold' title='{$strTitle}' nowrap>{$strLabel}</TD>";
    else
      $td = "<TD valign='center' class='normalBold' title='{$strTitle}' nowrap>{$strLabel}</TD>";
  }  
  $s = "{$td}<TD><SELECT {$dis} {$mul} size={$strSize} class='normal' style='font-size:10px' id='{$strId}' name='{$strName}' onChange='javascript:{$strJFunc}'>";
  if ($bZeroOption) $s .= "<OPTION value='0'></OPTION>";
  if (isset($arrOptions))
  {
    $intSize = count($arrOptions);
    // special; $selValue can be both single value and array
    if (!is_array($selValue))
      $arrSelValue[] = $selValue;
    else
      $arrSelValue = $selValue;
    for ($i=0;$i<$intSize;$i++)
    {
    	$sel = "";
    	for ($j=0;$j<count($arrSelValue);$j++)
        if ($arrSelValue[$j] == $arrOptions[$i][0]) $sel = "selected";
      // style, optional
      (isset($arrOptions[$i][2])) ? $style = $arrOptions[$i][2] : $style = "";
      $s .= "<OPTION value='{$arrOptions[$i][0]}' style='{$style}' {$sel}>{$arrOptions[$i][1]}</OPTION>";
    }
  }
  $s .= "</SELECT></TD>";
  return $s;
}

function com_setChecked($currID, $checkedID)
{    
  if ($currID == $checkedID)
    return "checked";
  else
    return "";
}

function com_setSelected($currID, $selectedID)
{    
  if ($currID == $selectedID)
    return "selected";
  else
    return "";
}

function com_setYesNo($answer)
{
  if ($answer == '1') 
    return "Ja";
  else 
    return "Nej";
}

function com_setBool($currID, $trueID)
{
  if ($currID == $trueID)
    return 1;
  else
    return 0;
}

function com_printCheckbox($strId,$strName,$bChecked,$strValue,$strJFunc,$bReadOnly,$bDisabled)
{
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  $strStyle = "Verdana, Arial, Helvetica, sans-serif;font-size:11px;";
  if ($bReadOnly) $strStyle .= "background-Color:#BDD2D2;";
  ($bChecked) ? $strChecked = "checked" : $strChecked = "";
  ($strValue != "") ? $val = "value='{$strValue}'" : $val = ""; 
  return "<INPUT type='checkbox' {$dis} class='normal' style='{$strStyle}' id='{$strId}' name='{$strName}' {$strChecked} {$val} onClick='javascript:{$strJFunc}'>";
}

function com_printYesNo($strLabel,$strTitle,$strId,$strName,$bYes,$jFunc,$bReadOnly,$bDisabled)
{
  if (is_null($bYes)) $bYes = -1;
  ($bDisabled) ? $dis = "disabled" : $dis = "";
  if ($strLabel != "") $strLabel .= "&nbsp;";
  $strStyle = "Verdana, Arial, Helvetica, sans-serif;font-size:11px;";
  if ($bReadOnly) $strStyle .= "background-Color:#BDD2D2;";
  ($jFunc != "") ? $jf = $jFunc : $jf = "pictureChanged()";
  return "
<TD valign='center' class='normalBold' title='{$strTitle}'>{$strLabel}</TD>
<TD class='normal'>
<INPUT type='radio' id='{$strId}' name='{$strName}' value='1' " . com_setChecked($bYes, 1) . " onClick='javascript:{$jf}'>Ja&nbsp;
<INPUT type='radio' id='{$strId}' name='{$strName}' value='0' " . com_setChecked($bYes, 0) . " onClick='javascript:{$jf}'>Nej&nbsp;
</TD>";
}

function com_printComma($s)
{
  if (strlen($s) > 0) 
    return ", ";
  else
    return "";
}

function com_printDelimiter($s,$del)
{
  if (strlen($s) > 0) 
    return $del;
  else
    return "";
}

function com_displayDate($strDate)
{
  if (substr($strDate,0,10) == "1900-01-01") return "";
  if (substr($strDate,0,10) == "0000-00-00") return "";
  if (strlen($strDate) > 16) $strDate = substr($strDate,0,16);
	return $strDate;
}

// returns date as "25/4"
function com_easyDate($strDate)
{
	$d = substr($strDate,8,2);
  if ($d < "10") $d = str_replace("0","",$d);
  $m = substr($strDate,5,2);
  if ($m < "10") $m = str_replace("0","",$m);
  $strDate = $d . "/" . $m;
  return $strDate;
}

// returns date as "25 apr"
function com_easyDate2($strDate)
{
  $monthNames = array("zero","jan","feb","mar","apr","maj","jun","jul","aug","sep","okt","nov","dec");
  $d = substr($strDate,8,2);
  if ($d < "10") $d = str_replace("0","",$d);
  $m = (integer)substr($strDate,5,2);
  $strDate = $d . " " . $monthNames[$m];
  return $strDate;
}

function com_getMyDate($d)
{
	if ($d == "") return "";
	$day = substr($d,8,2);
	$mon = (integer)substr($d,5,2);
	$monName = "";
	switch ($mon)
	{
		case 1 : $monName = "JAN"; break;
		case 2 : $monName = "FEB"; break;
		case 3 : $monName = "MAR"; break;
		case 4 : $monName = "APR"; break;
		case 5 : $monName = "MAY"; break;
		case 6 : $monName = "JUN"; break;
		case 7 : $monName = "JUL"; break;
		case 8 : $monName = "AUG"; break;
		case 9 : $monName = "SEP"; break;
		case 10 : $monName = "OCT"; break;
		case 11 : $monName = "NOV"; break;
		case 12 : $monName = "DEC"; break;
  }
  $s = $day . "<br>" . $monName;
  return $s;
}
function com_dateDiff($interval,$dateTimeBegin,$dateTimeEnd) 
{
  //Parse about any English textual datetime
  
  $dateTimeBegin=strtotime($dateTimeBegin);
  if($dateTimeBegin === -1) {
    return("..begin date Invalid");
  }
  
  $dateTimeEnd=strtotime($dateTimeEnd);
  if($dateTimeEnd === -1) {
    return("..end date Invalid");
  }
  
  $dif=$dateTimeEnd - $dateTimeBegin;
  
  switch($interval) 
  {
    case "s"://seconds
        return($dif);
  
    case "n"://minutes
        return(floor($dif/60)); //60s=1m
  
    case "h"://hours
        return(floor($dif/3600)); //3600s=1h
  
    case "d"://days
        return(floor($dif/86400)); //86400s=1d
  
    case "ww"://Week
        return(floor($dif/604800)); //604800s=1week=1semana
  
    case "m": //similar result "m" dateDiff Microsoft
        $monthBegin=(date("Y",$dateTimeBegin)*12)+
          date("n",$dateTimeBegin);
        $monthEnd=(date("Y",$dateTimeEnd)*12)+
          date("n",$dateTimeEnd);
        $monthDiff=$monthEnd-$monthBegin;
        return($monthDiff);
  
    case "yyyy": //similar result "yyyy" dateDiff Microsoft
        return(date("Y",$dateTimeEnd) - date("Y",$dateTimeBegin));
  
    default:
        return(floor($dif/86400)); //86400s=1d
  }
} 

function com_datePart($interval,$dte)
{
	$dte2=strtotime($dte);
  if ($dte2 === -1) return "";
  
  switch($interval) 
  {
    case "d":
        return (int)substr($dte,8,2);
  
    case "m":
        return (int)substr($dte,5,2);
  
    case "yyyy":
        return (int)substr($dte,0,4);
  
    default:
        return "";
  }    
}

/*
function com_isNav()
{
  $pos = strpos($_SESSION["sess_browser"], "Netscape"); if ($pos === false) $pos = -1;
  if ($pos > -1)
    return true; 
  // allow Gecko too
  $pos = strpos($_SESSION["sess_browser"], "Gecko"); if ($pos === false) $pos = -1;
  if ($pos > -1)
    return true;     
  return false;
}
*/

function com_isIE()
{	  
  if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") === false)
    return false;
  else
    return true; 
}

// replaces single quotes (') with the numeric entity &#039
// used when populating select lists
function com_createNumericQuotes($s)
{
  $search  = array("\"","'");
  $replace = array("&#034","&#039");
  return str_replace ( $search, $replace, $s);
}

function com_fixTwoDigitTime($s)
{
	$s = trim($s);
  if (strlen($s) == 0) return "00"; 
  if (strlen($s) == 1) return "0" . $s; 
  return $s;
}

function com_getReqParam($paramName)
{
  if (isset($_REQUEST["{$paramName}"]))
    return $_REQUEST["{$paramName}"];
  else 
    return null;
}

function com_getReqParamChk($param)
{
  if (isset($_REQUEST[$param]))
    if (strtoupper($_REQUEST[$param]) == "ON")
      return 1;
    else
      return 0;
  else
    return 0;
}  

function com_getReqParamInt($paramName,$defVal = 0)
{
  if (isset($_REQUEST["{$paramName}"]))
    return $_REQUEST["{$paramName}"];
  else 
    return $defVal;
}

function com_getReqParamStr($paramName,$defVal = "")
{
  if (isset($_REQUEST["{$paramName}"]))
    return trim(str_replace("'","''",$_REQUEST["{$paramName}"]));
  else 
    return $defVal;
}

function com_getReqParamArr($paramName)
{
  if (isset($_REQUEST["{$paramName}"]))
    return $_REQUEST["{$paramName}"];
  else 
    return null;
}

function com_sendMail($to,$subject,$body,$htmlFormat = false)
{
  $header = "From: Göteborgs Tankcontainerservice AB<info@got-tank.com>\r\n";
  
  if ($htmlFormat) $header .= "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";
	
	if ($to != "")
	{
    $ok = mail($to, $subject, $body, $header);
  }
  return true;
}

function com_printHTMLArea($areaNo,$text,$width,$height,$btnImage,$btnHyperLink,$css)
{
  $text = stripslashes($text);
  ($btnImage) ? $btnImageStr = "true" : $btnImageStr = "false"; 
  ($btnHyperLink) ? $btnHyperLinkStr = "true" : $btnHyperLinkStr = "false"; 
  ($css) ? $cssStr = "true" : $cssStr = "false"; 
  $cssFile = '../styles_editor.css';

  return "
<PRE id='idTemporary_{$areaNo}' name='idTemporary_{$areaNo}' style='display:none'><!--
  {$text}
--></PRE>

<SCRIPT>
  var oEdit1_{$areaNo} = new InnovaEditor('oEdit1_{$areaNo}');
  var css = {$cssStr};
  oEdit1_{$areaNo}.width ='{$width}';
  oEdit1_{$areaNo}.height='{$height}';
  if (css) oEdit1_{$areaNo}.css = '{$cssFile}';
  oEdit1_{$areaNo}.btnImage = {$btnImageStr};
  oEdit1_{$areaNo}.btnHyperlink = {$btnHyperLinkStr};
  
  // get string, real tags must be reinstated
  var s = document.getElementById('idTemporary_{$areaNo}').innerHTML;
  s = s.replace(/&lt;/g,\"<\");
  s = s.replace(/&gt;/g,\">\");
  s = s.replace(/&nbsp;/g,\" \");
  s = s.replace(/&amp;/g,\"&\");  
  
  oEdit1_{$areaNo}.RENDER(s);
</SCRIPT>
<INPUT type='hidden' name='inpContent_{$areaNo}'  id='inpContent_{$areaNo}'>";
}

// returns "", "_acrobat", "_excel", "_word", "_picture"
function com_getDocumentExtension($docType)
{
  if ($docType == "image/jpg" || $docType == "image/jpeg" || $docType == "image/pjpeg"|| $docType == "image/gif" || $docType == "image/bmp" || $docType == "image/png") return "_picture";
  if ($docType == "application/msword") return "_word";
  if ($docType == "application/vnd.ms-excel") return "_excel";
  if ($docType == "application/pdf") return "_acrobat";
  return "";
}
?>