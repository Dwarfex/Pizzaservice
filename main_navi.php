<?php
 
  if(isset($_GET['catID'])){
    $_SESSION['catID'] = $_GET['catID'];
  }
  
  $katq = mysql_query("SELECT * FROM produktkat WHERE top_ID IS NULL");
  echo'<li>';
  while($kat = mysql_fetch_array($katq)){
     echo '<ul><p>';
     if(isset($_SESSION['catID'])){
        if($_GET['site'] == 'category' && $_SESSION['catID'] == $kat['ID']){
          echo '--> ';                     // irgendeine Art von highlighting
        }
     }
     echo '<a class="navilink" href="index.php?site=category&catID='.$kat['ID'].'">'.$kat['name'].'</a></p></ul>';
  }
   echo'<li>';
?>