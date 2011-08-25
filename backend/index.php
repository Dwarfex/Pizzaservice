<?php


chdir('../');
include("_mysql.php");
include("_settings.php");
include("_functions.php");
chdir('backend');



if(isset($_GET['site'])) $site = $_GET['site'];
else
if(isset($site)) unset($site);




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link href="../_stylesheet.css" rel="stylesheet" type="text/css" />

</head>
<body>
   <div id="admin_top"><b>Hallo und Herzlich Willkommen im BACKEND!!!</b></div>
<div id="admin_navi"> Hier NAVI<?php include("navigation.php")?></div>
<div id="admin_pad"><?php
   if(isset($site)){
   $invalide = array('\\','/','//',':','.');
   $site = str_replace($invalide,' ',$site);
   	if(file_exists($site.'.php')) include($site.'.php');
   	else include('overview.php');
   }
   else include('overview.php');
   ?></div>
  
</body>
</html>
