<?php

  $katq = mysql_query("SELECT * FROM produktkat WHERE top_ID IS NULL");
  while($kat = mysql_fetch_array($katq)){
     echo '<p>';
     if(isset($_GET['catID'])){
        if($_GET['catID'] == $kat['ID']){
          echo '--> ';                     // irgendeine Art von highlighting
        }
     }
     echo '<a href="index.php?site=category&catID='.$kat['ID'].'">'.$kat['name'].'</a></p>';
  }



/*$category_q = mysql_query("SELECT * FROM produktkat");
	
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
	
	} */
	
?>