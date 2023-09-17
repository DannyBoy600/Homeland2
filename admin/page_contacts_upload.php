<?php
include "head.php";
include "../db.php";
include "../common.php";

$db = db_connect();

$cid = $_SESSION["sess_companyID"];
$menuID = $_SESSION["sess_menuID"];
$pageID = $_SESSION["sess_pageID"];
$logger = $_SESSION["sess_full_name"];

$contactID = com_getReqParamInt("hid_upload_id");

$msg = "";
if ($contactID > 0)
{
  // upload file
  $filename = $_FILES['fil_picture']['tmp_name'];
  if ($filename != "")
  {  
    $errorNo = $_FILES['fil_picture']['error'];
    if ($errorNo != 0)
      $msg = "Fel vid inläsning av dokument";
    
    if ($msg == "")
    {
      $filename = $_FILES['fil_picture']['tmp_name'];
      if (!file_exists ($filename))
        $msg = "Kunde inte hitta filen {$filename}";
    }
    
    if ($msg == "")
    {
      $origfilename = $_FILES['fil_picture']['name'];
      $fileType = $_FILES['fil_picture']['type'];
      $fileSize = $_FILES['fil_picture']['size'];
      
      if ($fileType != "image/jpg" && $fileType != "image/jpeg" && $fileType != "image/pjpeg")
        $msg = "Fel filtyp, skall vara jpg!";
    }
  
    if ($msg == "")
    {
    	$newFilename = "../docs/company_{$cid}/contacts/contact_{$contactID}.jpg";
      if (!move_uploaded_file ($filename, $newFilename))
        $msg = "Kunde inte spara bilden!";
    }
  }
}

?>
<html>
<head>
<script src="jscripts.js"></script>
<script>
function onLoad()
{
	var msg = "<?php echo $msg?>";
	if (msg != "") alert(msg);
  self.location.href = "page_contacts.php";
}
</script>
</head>
<body onLoad="javascript:onLoad()"></body>
</html>