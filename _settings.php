<?php


// -- ERROR REPORTING -- //

define('DEBUG', "ON");


// -- SET ENCODING FOR MB-FUNCTIONS -- //

mb_internal_encoding("UTF-8");

// -- SET ENCODING

header('content-type: text/html; charset=utf-8');

// -- CONNECTION TO MYSQL -- //

mysql_connect($host, $user, $pwd) or system_error('ERROR: Can not connect to MySQL-Server');
mysql_select_db($db) or system_error('ERROR: Can not connect to database "'.$db.'"');

mysql_query("SET NAMES 'utf8'");

// -- GENERAL PROTECTIONS -- //

function globalskiller() {		// kills all non-system variables

  $global = array('GLOBALS', '_POST', '_GET', '_COOKIE', '_FILES', '_SERVER', '_ENV',  '_REQUEST', '_SESSION');
  foreach ($GLOBALS as $key=>$val) {
  	if(!in_array($key, $global)) {
  		if(is_array($val)) unset_array($GLOBALS[$key]);
  		else unset($GLOBALS[$key]);
  	}
  }
}

function unset_array($array) {

	foreach($array as $key) {
		if(is_array($key)) unset_array($key);
		else unset($key);
	}
}

globalskiller();

if(isset($_GET['site'])) $site=$_GET['site'];
else $site= null;


function security_slashes(&$array) {
	foreach($array as $key => $value) {
		if(is_array($array[$key])) {
			security_slashes($array[$key]);
		}
		else {
			if(get_magic_quotes_gpc()) {
				$tmp = stripslashes($value);
			}
			else {
				$tmp = $value;
			}
			if(function_exists("mysql_real_escape_string")) {
				$array[$key] = mysql_real_escape_string($tmp);
			}
			else {
				$array[$key] = addslashes($tmp);
			}
			unset($tmp);
		}
	}
}

security_slashes($_POST);
security_slashes($_COOKIE);
security_slashes($_GET);
security_slashes($_REQUEST);

// -- MYSQL QUERY FUNCTION -- //

$_mysql_querys = array();
function safe_query($query="") {
	if(stristr(str_replace(' ', '', $query), "unionselect")===FALSE AND stristr(str_replace(' ', '', $query), "union(select")===FALSE){
		if(empty($query)) return false;
		if(DEBUG == "OFF") $result = mysql_query($query) or die('Query failed!');
		else {
			$result = mysql_query($query) or die('Query failed: '
			.'<li>errorno='.mysql_errno()
			.'<li>error='.mysql_error()
			.'<li>query='.$query);
		}
		return $result;
	}
	else die();
}

// -- SYSTEM ERROR DISPLAY -- //

function system_error($text) {
	
	die('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	  <title>ERROR</title>
  </head>
  <body>
  <center>
  <table border="0" cellpadding="1" cellspacing="1" bgcolor="#eeeeee">
    
    <tr bgcolor="#ffffff">
      <td><div style="color:#333333;font-family:Tahoma,Verdana,Arial;font-size:11px;padding:5px;"><font color="red">'.$text.'</font><br />&nbsp;</div></td>
    </tr>
    
  </table>
  </center>
  </body>
  </html>');
}



// -- SYSTEM FILE INCLUDE -- //

function systeminc($file) {
	if(!include('src/'.$file.'.php')) system_error('Could not get system file for '.$file);
}




?>