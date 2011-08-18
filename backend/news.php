<?php


if(isset($_GET['action'])) $action = $_GET['action'];
else $action='';
if(isset($_REQUEST['quickactiontype'])) $quickactiontype = $_REQUEST['quickactiontype'];
else $quickactiontype='';

if($action=="new") {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");
	

	safe_query("INSERT INTO ".PREFIX."news (date, poster, saved) VALUES ('".time()."', '".$userID."', '0')");
	$newsID=mysql_insert_id();

	
	$newsrubrics=safe_query("SELECT rubricID, rubric FROM ".PREFIX."news_rubrics ORDER BY rubric");
	while($dr=mysql_fetch_array($newsrubrics)) {
		$rubrics.='<option value="'.$dr['rubricID'].'">'.$dr['rubric'].'</option>';
	}

	

	$message_vars='';
	$headline_vars='';
	

	$url1="http://";
	$url2="http://";
	$url3="http://";
	$url4="http://";
	$link1='';
	$link2='';
	$link3='';
	$link4='';
	$window1_new = 'checked="checked"';
	$window1_self = '';
	$window2_new = 'checked="checked"';
	$window2_self = '';
	$window3_new = 'checked="checked"';
	$window3_self = '';
	$window4_new = 'checked="checked"';
	$window4_self = '';
	

	

	

	$postform = '';
	
	$comments='';
	

	eval ("\$news_post = \"".gettemplate("news_post")."\";");
	echo $news_post;
}
elseif($action=="save") {
	include("_mysql.php");
	include("_settings.php");
	include("_functions.php");
	/// bla bla vbla

}

else {
	
  
	

	
	

	if(isset($_GET['show'])) {
		$result=safe_query("SELECT rubricID FROM ".PREFIX."news_rubrics WHERE rubric='".$_GET['show']."' LIMIT 0,1");
		$dv=mysql_fetch_array($result);
		$showonly = "AND rubric='".$dv['rubricID']."'";
	}
	else $showonly = '';

	$result=safe_query("SELECT * FROM ".PREFIX."news WHERE published='1' AND intern<=".isclanmember($userID)." ".$showonly." ORDER BY date DESC LIMIT 0,".$maxshownnews);

	$i=1;
	while($ds=mysql_fetch_array($result)) {
		

		$date = date("d.m.Y", $ds['date']);
		$time = date("H:i", $ds['date']);
		
		$query=safe_query("SELECT * FROM ".PREFIX."news_contents WHERE newsID='".$ds['newsID']."'");
		while($qs = mysql_fetch_array($query)) {
			$message_array[] = array('lang' => $qs['language'], 'headline' => $qs['headline'], 'message' => $qs['content']);
		}

		

		
		$i=0;
		

		$headline=$message_array[$showlang]['headline'];
		$content=$message_array[$showlang]['message'];
		$newsID=$ds['newsID'];
    
    
        $content = htmloutput($content);
		$content = toggle($content, $ds['newsID']);
		$headline = clearfromtags($headline);
		
		

		eval ("\$news = \"".gettemplate("news")."\";");
		echo $news;

		$i++;

		unset($related);
		unset($comments);
		unset($lang);
		unset($ds);
	}
}
?>