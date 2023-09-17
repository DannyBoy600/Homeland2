<?php  

function db_connect()
{	
  $link = mysql_connect("localhost","root","Rosetta25$","");
  
  mysql_select_db("homeland");
  
  mysql_set_charset('latin1',$link);
  
  return $link;
}

?>
