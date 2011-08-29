<?php

$mindestbestellwert = 5;

// -- LOGIN SESSION -- //

systeminc('session');

// -- GLOBAL FUNCTIONS -- //

function is_blank($value) {
    return empty($value) && !is_numeric($value);
}

function sql_value($value) {
  if (empty($value)) {
    return "NULL";
  }
  return $value;
}

function setspacer($limit, $i, $spacer){
  if($i<$limit) return $spacer;
}



function gettemplate($template,$endung="html", $calledfrom="root") {
	$templatefolder = "templates";
	if($calledfrom=='root') {
		return str_replace("\"", "\\\"", $GLOBALS['_language']->replace(file_get_contents($templatefolder."/".$template.".".$endung)));
	}
	elseif($calledfrom=='backend') {
		return str_replace("\"", "\\\"", $GLOBALS['_language']->replace(file_get_contents("../".$templatefolder."/".$template.".".$endung)));
	}
	
}


function validate_url($url) {
	return preg_match("/^(ht|f)tps?:\/\/([^:@]+:[^:@]+@)?(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+(:[0-9]{2,5})?(\/[^#\?]*(\?[^#\?]*)?(#.*)?)?$/sui", $url);
}
function validate_email($email){
	return preg_match("/^(?!\.)(\.?[\p{L}0-9!#\$%&'\*\+\/=\?^_`\{\|}~-]+)+@(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+\.[\p{L}0-9]{2,}$/sui",$email);
}

function getinput($text) {
	//$text = stripslashes($text);
	$text = htmlspecialchars($text);

	return $text;
}

function getforminput($text) {
	$text = str_replace(array('\r','\n'),array("\r","\n"),$text);
	$text = stripslashes($text);
	$text = htmlspecialchars($text);

	return $text;
}



/* counts empty variables in an array */

function countempty($checkarray) {
	
	$ret = 0;
		
	foreach($checkarray as $value) {
		if(is_array($value)) $ret += countempty($value);
		elseif(trim($value) == "") $ret++;
	}
		
	return $ret;
}

/* checks, if given request-variables are empty */

function checkforempty($valuearray) {

	$check = Array();
	foreach($valuearray as $value) {
		$check[] = $_REQUEST[$value];
	}

	if(countempty($check) > 0) return false;
	return true;

}

// -- SITE VARIABLE -- //

if(isset($_GET['site'])) $site = $_GET['site'];
else $site = '';
if(!isset($site)) $site="main";
if(!isset($_SERVER['HTTP_REFERER'])) {
	$_SERVER['HTTP_REFERER'] = "";
}

// MORE SPECIAL FUNCTIONFILES INCLUDES //

// -- HELP MODE -- //

systeminc('help');



?>