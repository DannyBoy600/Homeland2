<?php
include "head.php";
include "../common.php";
include "../db.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];

$companyName = "";

if ($cid > 0)
{
  $sql = "SELECT Name FROM Company WHERE CompanyID = {$cid}";
  $res = mysql_query ($sql);
  if ($row = mysql_fetch_row($res)) 
  {
  	$companyName = $row[0];
  }
}
  
?>
<HTML>
<HEAD>
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>

function highlight(ind)
{
	var i = 0;
	var td = document.getElementById("btn_" + i); 
	while (td != null)
	{
		if (i == ind)
		{
	    td.style.color = "darkblue";
	  }
	  else
	  {
	    td.style.color = "";
	  }
	  i++;
	  td = document.getElementById("btn_" + i); 
	}
}

function showMenu()
{
	if (top.changed)
    if ( !confirm("Vill du lämna bilden utan att spara?") ) return;	
  top.changed = false;
  
	highlight(0);
	parent.center.location.href = "mainmenu_main.php";
}

function showPages()
{
	if (top.changed)
    if ( !confirm("Vill du lämna bilden utan att spara?") ) return;	
  top.changed = false;
  
	highlight(1);
	parent.center.location.href = "pages_main.php";
}

function showAdmin()
{
	if (top.changed)
    if ( !confirm("Vill du lämna bilden utan att spara?") ) return;	
  top.changed = false;
  
	highlight(2);
	parent.center.location.href = "users_main.php";
}

function showCompany()
{
	if (top.changed)
    if ( !confirm("Vill du lämna bilden utan att spara?") ) return;	
  top.changed = false;
  
	highlight(3);
	parent.center.location.href = "company_main.php";
}

function doLogout()
{
	if (top.changed)
    if ( !confirm("Vill du lämna bilden utan att spara?") ) return;	
  top.changed = false;
  
	loadFrame(parent.control,"logout.php");
}

function onLoad()
{
	showPages();
}
</SCRIPT>

<LINK REL=stylesheet TYPE="text/css" HREF="admin.css">
</HEAD>

<?php
echo "
<BODY class='menu' onLoad='javascript:onLoad()'><FORM>

<TABLE border='0' cellpadding='0' cellspacing='0' valign='center' height='100%' width='100%'>
  <TR>
    <TD>
      <TABLE border='0' cellpadding='0' cellspacing='0' valign='center'>
        <TR>";

echo com_printButton("btn_0","Huvudmeny","showMenu()","but_menu","",1,"",false);
echo com_printButton("btn_1","Sidor","showPages()","but_menu","",1,"",false);
echo com_printButton("btn_2","Administratörer","showAdmin()","but_menu","",1,"",false);
echo com_printButton("btn_3","Föreningen","showCompany()","but_menu","",1,"",false);
echo "<td>&nbsp;<img src='../images/ball_white.gif'>&nbsp;&nbsp;</td>";
echo com_printButton("logout","Logga ut","doLogout()","but_menu","",1,"",false);

echo "
          <TD class='head' style='color:white'>&nbsp;&nbsp;&nbsp;&nbsp;Administrera {$companyName}</TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM></BODY></HTML>";
?>