<?php  

//$link = mysql_connect("mydb15.surf-town.net","hb13301_homeland","Philae15","");
//mysql_select_db("hb13301_homeland");

$link = mysql_connect("localhost","root","rosetta","");
mysql_select_db("homeland");

$companies = null;
$sql = "SELECT CompanyID,Name FROM Company ORDER BY Name";
$res = mysql_query ($sql);
while ($row = mysql_fetch_row($res)) 
{
	$companies[] = $row;
}

print_r($companies);

?>
