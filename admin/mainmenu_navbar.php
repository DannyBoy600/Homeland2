<?php
include "head.php";
include "../db.php";
include "../common.php";

?>
<HTML>

<HEAD>
<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT src="jscripts.js"></SCRIPT>
<SCRIPT>

var saveIsRed = false;
function highlightSave()
{
  if (saveIsRed) return;
  var btn = document.getElementById("btn_save");
  if (btn.disabled) return;
  if (btn.style.color == "")
  {
    btn.style.color = "red";
    saveIsRed = true;
  }
}

function doSave()
{
	var doc = parent.center.document;
	
	// validate
	var i = 0;
	var hid = doc.getElementById("hid_id_" + i);
	while (hid != null)
	{
		var chk = doc.getElementById("chk_del_" + i); 
		if (chk != null)
		  if (chk.checked && hid.value > 0)
		    if (!confirm("Vill du verkligen radera meny-valet?")) 
		      return;
		i++;
		hid = doc.getElementById("hid_id_" + i);
  }
		
	top.changed = false;
	doc.forms[0].submit();
}

function onLoad()
{
	parent.center.location.href = "mainmenu_center.php";
}

</SCRIPT>

</HEAD>

<?php

$tabAction = "
<DIV style='position:absolute;top:3px;left:10px'>
<TABLE valign='top' align='left' border='0' cellpadding='0' cellspacing='0'>
<TR>
  <TD><input type='button' id='btn_save' value='&nbsp;Spara&nbsp;' onClick='javascript:doSave()'>&nbsp;</TD>
</TR>
</TABLE>
</DIV>";

echo "
<body class='navbar' onLoad='javascript:onLoad()'><form>

{$tabAction}

</form></body></html>";
?>