<?php
include "../header_popup.php";
include "../db.php";
include "../common.php";

$u = unserialize($_SESSION['sess_user']);

$maxSize		= $_POST["maxSize"];  //kB
$butNo		 	= $_POST["butNo"];
$chapter		= $_POST["chapter"];
if (is_null($chapter)) $chapter = 1;

$db = db_connect();

// also exists in forum_db.php
function printPictureButton($butNo,$pic,$jsRoutine,$class,$style,$space,$title)
{
  ($style != "") ? $style="cursor:pointer;{$style}" : $style="cursor:pointer";
  ($space > 0) ? $space = "<TD width='{$space}'></TD>" : $space = "";
  ($title == "") ? $titleStr = "" : $titleStr = "title='{$title}'";
  if ($pic == "")
    $img = "<IMG id='img_b{$butNo}' width='30' height='30' src='../images/dot.jpg'>";
  else
    $img = "<IMG id='img_b{$butNo}' width='30' height='30' src='{$pic}'>";
  return "<TD style='{$style}' {$titleStr} onMouseDown='javascript:{$jsRoutine}' class='{$class}' id='td_b{$butNo}' align='center' nowrap>{$img}</TD>{$space}";
}

// system pictures
$systemID = $_SESSION["sess_systemID"];
$commonPics[] = "../images/dot.jpg"; // empty pic
$path = "../images/form/s{$systemID}/";
if ($handle = opendir($path)) 
{
  while (false !== ($file = readdir($handle)))
    if ($file != "." && $file != ".." && !is_dir($path . $file))
      $commonPics[] = $path . $file;
}
closedir($handle);

// school pictures
$schoolName = common_deEthnical($u->SchoolName);
$path .= "{$schoolName}";
if (is_dir($path))
{
  $path .= "/";
  if ($handle = opendir($path)) 
  {
    while (false !== ($file = readdir($handle)))
      if ($file != "." && $file != ".." && !is_dir($path . $file))
        $schoolPics[] = $path . $file;
  }
  closedir($handle);
}

// user pictures
$path .= "user";
$maxChapter = 0;
if (is_dir($path))
{
  $path .= "/";
  $i = 0;
  $startIndex = ($chapter - 1) * 24;
  $stopIndex  = $startIndex + 24;
  if ($handle = opendir($path))
  {
    while (false !== ($file = readdir($handle)))
      if ($file != "." && $file != "..")
      {
        if ($i >= $startIndex && $i < $stopIndex)
          $userPics[] = $path . $file;
        $i++;
      }
  }
  $maxChapter = ceil($i/24);
  closedir($handle);  
}

?>    

<HTML>

<HEAD>
<TITLE>Välj bild</TITLE>
<LINK REL=stylesheet TYPE="text/css" HREF="../styles.css">
<SCRIPT SRC="jscripts.js"></SCRIPT>
<SCRIPT>
var butNo = "<?php echo $butNo?>";

function selectPicture(selButNo,ev)
{
  var img = document.getElementById("img_b" + selButNo);
  if (ev.ctrlKey)
  {
    var props = "width=800,height=600,resizable,scrollbars=1,menubar=0,location=0,status=0"; 
    win = window.open(img.src, "showTotalPicture" + selButNo, props);
  }
  else
  {
    var remoteImg = top.opener.document.getElementById("img_b" + butNo);
    remoteImg.src = img.src;
    top.opener.top.changed = true;
    top.close();
  }
}

function selectUserPicture(ev)
{
  var picname = document.getElementById("fil_picture");	
  if (ev.ctrlKey || picname.value == "")
    selectPicture(0,ev);
  else
    document.forms[0].submit();
}

function pictureSelected()
{
  var picname = document.getElementById("fil_picture");
  var btn = document.getElementById("img_b0");
  btn.src = picname.value;
}


function selectChapter(chapterNo)
{
  var maxSize = document.forms[0].hid_maxSize.value;
  var butNo = document.forms[0].hid_butNo.value;
  loadFrame(self,'select_picture.php','maxSize',maxSize,'butNo',butNo,'chapter',chapterNo);
}

function doOk()
{
  // make sure the user does not fire twice!
  document.forms[0].btn_ok.disabled = true;
  
  window.document.forms[0].submit();	
}

function onLoad()
{
}
</SCRIPT>
</HEAD>

<?php
echo "
<BODY CLASS='center' onLoad='javascript:onLoad()'><FORM enctype='multipart/form-data' action='upload_picture.php' method='post' class='unifont'>

<TABLE>
  <TR><TD height='2'></TD></TR>
</TABLE>";

$buttonNo = 0;
echo "
<TABLE border='0' cellpadding='0' cellspacing='0'>
<TR>
  <TD>
  <TABLE border='0' cellpadding='0' cellspacing='0'>
    <TR><TD class='microBold' colspan='3'>Välj egen bild:</TD></TR>
    <TR>" .
      printPictureButton($buttonNo,"","selectUserPicture(event)","butPOld","width:20",5,"") . "
      <TD class='microBold'>
        <INPUT type='file' onChange='javascript:pictureSelected()' style='font-size:10pt' size='60' name='fil_picture'>&nbsp;
      </TD>
    </TR>
    <TR><TD height='5' colspan='3'></TD></TR>
  </TABLE> 
  </TD>
</TR>
</TABLE>
<INPUT type='hidden' name='hid_maxSize' value='{$maxSize}'>
<INPUT type='hidden' name='hid_butNo' value='{$butNo}'>";
$buttonNo++;

if (count($commonPics) > 0)
{
  echo "
<TABLE border='0' cellpadding='0' cellspacing='0' >
  <TR><TD class='microBold' colspan='20'>Allmänna bilder:</TD></TR>";

  $cols = 12;
  $rows = count($commonPics) / $cols;
  $i = 0;
  for ($row=0; $row<$rows; $row++)
  {
    echo "
  <TR>";

    $j = 0; // row of pics
    while($i<count($commonPics) && $j<$cols)
    {
      $picName = dirname($commonPics[$i]) . "/" . rawurlencode(basename($commonPics[$i]));
      echo printPictureButton($buttonNo,$picName,"selectPicture({$buttonNo},event)","butPOld","width:20",5,basename($commonPics[$i]));
      $i++;
      $j++;
      $buttonNo++;
    }
    echo " 
  </TR>";
  }
echo "
</TABLE><br>";
}

if (count($schoolPics) > 0)
{
  echo "
<TABLE border='0' cellpadding='0' cellspacing='0'>
  <TR><TD class='microBold' colspan='20'>Skolans bilder:</TD></TR>";

  $cols = 12;
  $rows = count($schoolPics) / $cols;
  $i = 0;
  for ($row=0; $row<$rows; $row++)
  {
    echo "
  <TR>";

    $j = 0; // row of pics
    while($i<count($schoolPics) && $j<$cols)
    {
      $picName = dirname($schoolPics[$i]) . "/" . rawurlencode(basename($schoolPics[$i]));
      echo printPictureButton($buttonNo,$picName,"selectPicture({$buttonNo},event)","butPOld","width:20",5,basename($schoolPics[$i]));
      $i++;
      $j++;
      $buttonNo++;
    }
    echo " 
  </TR>";
  }
echo "
</TABLE><br>";
}

if (count($userPics) > 0)
{
  echo "
<TABLE border='0' cellpadding='0' cellspacing='0'>
  <TR><TD class='microBold' colspan='20'>Användar-bilder:&nbsp;";
  
  for ($i=1;$i<=$maxChapter;$i++)
  {
    ($i == $chapter) ? $style = "color:blue" : $style= "color:black" ;
    echo "<A style='{$style}' href='javascript:selectChapter({$i})'>{$i}</A>&nbsp;";
  }
  
  echo "</TD></TR>";  

  $cols = 12;
  $rows = count($userPics) / $cols;
  $i = 0;
  for ($row=0; $row<$rows; $row++)
  {
    echo "
  <TR>";

    $j = 0; // row of pics
    while($i<count($userPics) && $j<$cols)
    {
      $picName = dirname($userPics[$i]) . "/" . rawurlencode(basename($userPics[$i]));
      echo printPictureButton($buttonNo,$picName,"selectPicture({$buttonNo},event)","butPOld","width:20",5,basename($userPics[$i]));
      $i++;
      $j++;
      $buttonNo++;
    }
    echo " 
  </TR>";
  }
echo "
</TABLE><BR>";
}

$text = "
Välj bild genom att klicka på densamma.<br>
Använd [Ctrl]+Klick för att se bilden i naturlig storlek i nytt fönster.";

echo common_printInfo(1,400,$text);

echo "   	
</FORM></BODY></HTML>";
?>