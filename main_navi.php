<?php
$category_q = mysql_query("SELECT * FROM produktkat");
	
	while($category = mysql_fetch_array($category_q)){
	if ($category['top_ID']==''){
		
	
		
	echo'<a href="index.php?site=category&action=show&catID='.$category['ID'].'">'.$category['name'].'</a><br />';
	
	
	
		
	
	}
	$subcatq = mysql_query("SELECT * FROM produktkat");
	while( $subcat = mysql_fetch_array($subcatq)){
	echo '<ul>';
	if($subcat['top_ID']==$category['ID']){
		echo'<li><a href="index.php?site=category&action=show&catID='.$subcat['ID'].'">'.$subcat['name'].'</a></li><br />';
	}
	echo '</ul>';
	}
	
	}