<?php

if(isset($_GET['delete'])) {
	$produktkat_ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."produktkat WHERE ID='$produktkat_ID'");	
}

elseif(isset($_POST['save'])) {
	$produktkatname = $_POST['produktkatname'];
	
  
  
  safe_query("INSERT INTO ".PREFIX."produktkat ( name, top_ID )
	            values( '$produktkatname', " . sql_value($_POST['top_ID']) . " )");
  
  $_GET['ID'] = mysql_insert_id(); 
  
  if(isset($_POST['belagkat_ID'])){
    $formular = $_POST['belagkat_ID']; //checkboxarray
    
    $produktkat_ID = mysql_insert_id();    
    foreach ($formular as $belagkat_ID) {
       safe_query("INSERT INTO ".PREFIX."belagkatzuproduktkat (produktkat_ID,belagkat_ID)
	               values('$produktkat_ID','$belagkat_ID')");
    }
  } 
  
}

elseif(isset($_POST['saveedit'])) {
	$produktkatname = $_POST['name'];
	$produktkat_ID = $_POST['ID'];
		
  
  safe_query("UPDATE ".PREFIX."produktkat SET name='$produktkatname', top_ID=" . sql_value($_POST['top_ID']) . " WHERE ID='$produktkat_ID' ");
  
    
    if(isset($_POST['belagkat_ID'])) $formular = $_POST['belagkat_ID'];
    else $formular = array();
    
    $belagkatq = mysql_query("SELECT belagkat_ID FROM belagkatzuproduktkat WHERE produktkat_ID='$produktkat_ID'");
    $dbout = array();
    $i = 0;
    while($belagkat = mysql_fetch_array($belagkatq)){
       $dbout[$i] = $belagkat['belagkat_ID'];
       $i++;    
    }
  
    $insert = array_values(array_diff($formular,$dbout));
    $delete = array_values(array_diff($dbout,$formular));
    
    foreach ($insert as $belagkat_ID) {
       safe_query("INSERT INTO ".PREFIX."belagkatzuproduktkat (produktkat_ID,belagkat_ID)
  	               values('$produktkat_ID','$belagkat_ID')");
    }
    
    foreach ($delete as $belagkat_ID) {
       safe_query("DELETE FROM ".PREFIX."belagkatzuproduktkat WHERE produktkat_ID='$produktkat_ID' AND belagkat_ID='$belagkat_ID'");
    } 
  
}

//// SIZE

if(isset($_GET['delete_size'])) {
	$size_ID = $_GET['delete_size'];
	
	safe_query("DELETE FROM ".PREFIX."size WHERE size='$size_ID'");	
}

elseif(isset($_POST['save_size'])) {
	$produktkat_ID = $_POST['produktkat_ID'];
  $name = $_POST['size_name'];
	$comment = $_POST['size_comment'];
	$preis_val = $_POST['preis_val']; // array
	$def_preis = $_POST['size_def_preis'];
	
	safe_query("INSERT INTO ".PREFIX."size ( name,produktkat_ID,comment,def_preis )
	            values( '$name','$produktkat_ID','$comment',". sql_value($def_preis)." )");
	
  $size_ID = mysql_insert_id();
  
  for($i=0; $i<count($preis_val); $i++){   
    if(!is_blank($preis_val[$i])){
       $value = $i+1;
       safe_query("INSERT INTO ".PREFIX."belagpreis (size,value,preis)
	               values('$size_ID','$value','$preis_val[$i]')");
    }
  }

}

elseif(isset($_POST['edit_size'])) {
   $size_ID  = $_POST['size_ID']; // array
   $size_name = $_POST['size_name']; // array
   $size_comment = $_POST['size_comment']; // array
   $size_def_preis =$_POST['size_def_preis']; // array
   $preis_val = $_POST['preis_val']; // array 2-dimensionales
   
   for($i=0; $i<count($size_ID); $i++){
     safe_query("UPDATE ".PREFIX."size SET name='$size_name[$i]', comment='$size_comment[$i]', def_preis=". sql_value($size_def_preis[$i]) ." WHERE size='$size_ID[$i]' "); 
   } 

   for($i=0; $i<count($preis_val); $i++){
     $size = $size_ID[$i];
     for($j=0; $j<count($preis_val[$i]); $j++){
       $value = $j+1;
       $preis = $preis_val[$i][$j];
       
       safe_query("UPDATE ".PREFIX."belagpreis SET preis='$preis' WHERE size='$size' AND value='$value' ");
     }
   }
  
}

if(isset($_GET['action'])) {
	
	if($_GET['action']=="add") {
	  
    echo'<h1>Produkt Kategorie hinzuf&uuml;gen</h1>';

    echo '<form method="post" action="index.php?site=produktkat&action=edit" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="produktkatname" size="60" /></td>
      </tr>
       <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"> <select name="top_ID" size="1">
        <option value=""></option>';
        
                          $katq = safe_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td> 
      </tr>
    </table>';
    
  
    $belagkatq = safe_query("SELECT * FROM belagkat");
    echo '<h3>Belagkategorien</h3>';
   echo'<table width="100%" border="0" cellspacing="1" cellpadding="3">';
     while($belagkat = mysql_fetch_array($belagkatq)){
         
         echo '<tr>
                <td><input type="checkbox" name="belagkat_ID[]" value="'. $belagkat['ID'] .'">' . $belagkat["name"] . '</td>
              </tr>';
      }
      echo' <tr>
            <td><input type="submit" name="save" value="Kategorie hinzuf&uuml;gen" /></td>
          </tr>
        </table>
        </form>';
    
   
    
  } 
  elseif($_GET['action']=="edit") {
  		
    $produktkatq = safe_query("SELECT * FROM produktkat WHERE ID=". $_GET["ID"] . "");
    $produktkat = mysql_fetch_array($produktkatq);
    
    echo'<h1>Produktkategorie &auml;ndern</h1>';

    echo '<form method="post" action="index.php?site=produktkat&action=edit&ID='. $_GET["ID"]. '" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $produktkat["name"] .'" />
        <input type="hidden" name="ID" value="'. $produktkat["ID"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"><select name="top_ID" size="1">
        <option value=""></option>';
        
                          $katq = safe_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $produktkat["top_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
    </table>';
       
 //auslesen der BelagZuProdukt Tabelle um checkboxen aktiv zu setzen
    $checkq = safe_query("SELECT belagkat_ID FROM belagkatzuproduktkat WHERE produktkat_ID=" . $produktkat["ID"] . "");
    $checkarr = array();
    $i = 0;
    while($check = mysql_fetch_array($checkq)){
       $checkarr[$i] = $check['belagkat_ID'];
       $i++;    
    }
    
    
    
    $belagkatq = safe_query("SELECT * FROM belagkat");
    echo '<h3>Belagkategorien</h3>';
   echo'<table width="100%" border="0" cellspacing="1" cellpadding="3">
         <input type="hidden" name="produkt_ID" value="'. $produktkat['ID'] .'" />';
     while($belagkat = mysql_fetch_array($belagkatq)){
         if(in_array($belagkat['ID'],$checkarr)) $checked = " checked ";
         else $checked="";
         echo '<tr>
                <td><input type="checkbox" name="belagkat_ID[]" value="'. $belagkat['ID'] .'" '. $checked. '>' . $belagkat["name"] . '</td>
              </tr>';
      }
      echo' <tr>
            <td><input type="submit" name="saveedit" value="&auml;ndern" /></td>
          </tr>
        </table>
        </form>';
	
///// GROESSEN EDITIEREN
       
      $sizeq = safe_query("SELECT * FROM size WHERE produktkat_ID=" . $produktkat["ID"] . "");
       if(mysql_num_rows($sizeq) >= 1){
           
           echo '<br><form method="post" action="index.php?site=produktkat&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
                 <table width="100%" border="0" cellspacing="1" cellpadding="3">
                   <tr>
                     <td><b>Gr&ouml;sse</b></td>
                     <td><b>Comment</b></td>
                     <td><b>Standardpreis</b></td>
                     <td><b>Belagvalues</b></td>
                   </tr>';
              $i = 0;
              while($size = mysql_fetch_array($sizeq)){
                echo '<tr>
                            <td width="15%"><input type="hidden" name="size_ID[]" value="'. $size['size'] .'" />
                            <input type="text" name="size_name[]" size="10" value="'. $size["name"] .'" /></td>
                            <td width="15%"><input type="text" name="size_comment[]" size="10" value="'. $size["comment"] .'" /></td>
                            <td width="15%"><input type="text" name="size_def_preis[]" size="10" value="'. $size["def_preis"] .'" /></td>
                            <td width="70%">';
                            $valueq = safe_query("SELECT * FROM belagpreis WHERE size=" . $size["size"] . "");
                             if(mysql_num_rows($valueq) >= 1){ 
                                while($value = mysql_fetch_array($valueq)){
                                   echo ' Val'.$value['value'].'  <input type="text" name="preis_val['.$i.'][]" size="10" value='.$value['preis'].' />';
                                }
                             }
                            
                            echo '<a href="?site=produktkat&action=edit&ID='. $produktkat["ID"] . '&delete_size=' . $size["size"] . '"> delete</a></td>
                    </tr>   ';
              $i++;
              }
            echo' <tr>
              <td colspan="4"><input type="submit" name="edit_size" value="Gr&ouml;sse editieren" /></td>
            </tr>
          </table>
          </form>';      
       }
       

///// GROESSE HINZUFUEGEN
      
      echo'<h3>Gr&ouml;sse hinzuf&uuml;gen</h3>';
      echo '<form method="post" action="index.php?site=produktkat&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
         <tr>
            <td width="15%">
              <input type="hidden" name="produktkat_ID" value="'. $produktkat['ID'] .'" />
              Name <input type="text" name="size_name" size="10" /></td>
                    
            <td width="15%">Comment <input type="text" name="size_comment" size="10" /></td>
            <td width="15%">Standardpreis <input type="text" name="size_def_preis" size="10" /></td>
            <td width="45%">Val1 <input type="text" name="preis_val[]" size="10" />
            Val2 <input type="text" name="preis_val[]" size="10" />
            Val3 <input type="text" name="preis_val[]" size="10" /></td>
            <td width="25%"><input type="submit" name="save_size" value="hinzuf&uuml;gen" /></td>       
            </tr>       
    </table>
    </form>';
	
	
  }
}
else{ //Standardaufruf wenn action nicht gesetzt
    
    echo '<a href="?site=produktkat&action=add">Neue Kategorie hinzuf&uuml;gen</a>';
    
    $katq = safe_query("SELECT * FROM produktkat WHERE top_ID IS NULL");
    echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
    while($kat = mysql_fetch_array($katq)){
     echo '<tr>
              <td width="15%">' . $kat["name"] . '</td>
              <td width="5%"><a href="?site=produktkat&action=edit&ID=' . $kat["ID"] . '"> edit</a></td>
              <td align="left"><a href="?site=produktkat&delete=' . $kat["ID"] . '"> delete</a></td>
          </tr>';                          
          
          $subkatq = safe_query("SELECT * FROM produktkat WHERE top_ID = '".$kat['ID']."'");
          while($subkat = mysql_fetch_array($subkatq)){
            echo '<tr>
                    <td width="15%">-- ' . $subkat["name"] . '</td>
                    <td width="5%"><a href="?site=produktkat&action=edit&ID=' . $subkat["ID"] . '"> edit</a></td>
                    <td align="left"><a href="?site=produktkat&delete=' . $subkat["ID"] . '"> delete</a></td>
                  </tr>'; 
          }
          
          
    }
    echo '</table>';
}
?>