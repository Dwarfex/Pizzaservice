<?php

if(isset($_GET['delete'])) {
	$produktkat_ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."produktkat WHERE ID='$produktkat_ID'");
	safe_query("DELETE FROM ".PREFIX."belagkatzuproduktkat WHERE produktkat_ID='$produktkat_ID'");
	
}

elseif(isset($_POST['save'])) {
	$produktkatname = $_POST['produktkatname'];
	$formular = $_POST['belagkat_ID']; //checkboxarray
  
  if(isset($_POST['top_ID'])){
	  $top_ID = $_POST['top_ID'];
    safe_query("INSERT INTO ".PREFIX."produktkat ( name, top_ID )
	            values( '$produktkatname', '$top_ID' )");
  }
	else{
     safe_query("INSERT INTO ".PREFIX."produktkat ( name )
	            values( '$produktkatname' )");
  }
  
  $produktkat_ID = mysql_insert_id();    
  foreach ($formular as $belagkat_ID) {
     safe_query("INSERT INTO ".PREFIX."belagkatzuproduktkat (produktkat_ID,belagkat_ID)
	               values('$produktkat_ID','$belagkat_ID')");
  }
}

elseif(isset($_POST['saveedit'])) {
	$produktkatname = $_POST['name'];
	$produktkat_ID = $_POST['ID'];
	$formular = $_POST['belagkat_ID']; //checkboxarray
	
	if(isset($_POST['top_ID'])){
	  $top_ID = $_POST['top_ID'];
    safe_query("UPDATE ".PREFIX."produktkat SET name='$produktkatname', top_ID='$top_ID' WHERE ID='$produktkat_ID' ");
  }
	else{
    safe_query("UPDATE ".PREFIX."produktkat SET name='$produktkatname' WHERE ID='$produktkat_ID' ");
  }
  
  
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

if(isset($_GET['action'])) {
	
	if($_GET['action']=="add") {
	  
    echo'<h1>Produkt Kategorie hinzuf&uuml;gen</h1>';

    echo '<form method="post" action="index.php?site=produktkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="produktkatname" size="60" /></td>
      </tr>
       <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"> <select name="top_ID" size="3">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td> 
      </tr>
    </table>';
    
  
    $belagkatq = mysql_query("SELECT * FROM belagkat");
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
  		
    $produktkatq = mysql_query("SELECT * FROM produktkat WHERE ID=". $_GET["ID"] . "");
    $produktkat = mysql_fetch_array($produktkatq);
    
    echo'<h1>Produktkategorie &auml;ndern</h1>';

    echo '<form method="post" action="index.php?site=produktkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $produktkat["name"] .'" />
        <input type="hidden" name="ID" value="'. $produktkat["ID"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"><select name="top_ID" size="3">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $produktkat["top_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
    </table>';
    
 //auslesen der BelagZuProdukt Tabelle um checkboxen aktiv zu setzen
    $checkq = mysql_query("SELECT belagkat_ID FROM belagkatzuproduktkat WHERE produktkat_ID=" . $produktkat["ID"] . "");
    $checkarr = array();
    $i = 0;
    while($check = mysql_fetch_array($checkq)){
       $checkarr[$i] = $check['belagkat_ID'];
       $i++;    
    }
    
    
    
    $belagkatq = mysql_query("SELECT * FROM belagkat");
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
	
  }
}
else{ // standard aufruf wenn action nicht gesetzt
    
    echo '<a href="?site=produktkat&action=add">Neue Kategorie hinzuf&uuml;gen</a>';
    
    $katq = mysql_query("SELECT * FROM produktkat");
    echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
    while($kat = mysql_fetch_array($katq)){
     echo '<tr>
              <td width="15%">' . $kat["name"] . '</td>
              <td width="5%"><a href="?site=produktkat&action=edit&ID=' . $kat["ID"] . '"> edit</a></td>
              <td align="left"><a href="?site=produktkat&delete=' . $kat["ID"] . '"> delete</a></td>
          </tr>';                          
    }
    echo '</table>';
}
?>