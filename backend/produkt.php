<?php

if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."produkt WHERE ID='$ID'");	
}

elseif(isset($_POST['save'])) {
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$kat_ID = $_POST['kat_ID'];
		
	safe_query("INSERT INTO ".PREFIX."produkt (name,comment,kat_ID)
	            values('$name','$comment','$kat_ID')");
	
  $_GET['ID'] = mysql_insert_id();    
  $ID = $_GET['ID'];                //Wird an action['edit'] übergeben
   
  // Die ProduktPreis Tabelle wird mit den jeweiligen Größen der Produktkategorie gefüllt, 
  // jedoch nur wenn kein standardpreis in der size Tabelle vorhanden ist. 
  $sizeq = safe_query("SELECT size,def_preis FROM size WHERE produktkat_ID='$kat_ID'");
  while($size = mysql_fetch_array($sizeq)){                
    $foo = $size['size'];
    if(!isset($size['def_preis'])){
       
       safe_query("INSERT INTO ".PREFIX."produktpreis (produkt_ID,size)
	            values('$ID','$foo')");
    }
                             
  }  
}

elseif(isset($_POST['edit_basic'])) {
	$name = $_POST['name'];
	$comment = $_POST['comment'];
  $kat_ID = $_POST['kat_ID'];
	$ID = $_POST['ID'];
	
	safe_query("UPDATE ".PREFIX."produkt SET name='$name', comment='$comment', kat_ID='$kat_ID' WHERE ID='$ID' ");
	
}

elseif(isset($_POST['save_preis'])) {
  $produkt_ID = $_POST['produkt_ID'];
  $size = $_POST['size'];
  $preis = $_POST['preis'];

  safe_query("INSERT INTO ".PREFIX."produktpreis (produkt_ID,size,preis)
	            values('$produkt_ID','$size','$preis')");
} 


elseif(isset($_POST['edit_preis'])) {
	
	for($i=0; $i<count($_POST['size']); $i++)
  { 
	   $produktpreis_ID = $_POST['produktpreis_ID'][$i];
     $size = $_POST['size'][$i];
     $preis = $_POST['preis'][$i];
    
    safe_query("UPDATE ".PREFIX."produktpreis SET preis='$preis', size='$size' WHERE ID='$produktpreis_ID' ");
	}
}

elseif(isset($_GET['delete_preis'])) {
	$ID = $_GET['delete_preis'];
	
	safe_query("DELETE FROM ".PREFIX."produktpreis WHERE ID='$ID'");	
}

elseif(isset($_POST['edit_belag'])) {
	$produkt_ID = $_POST['produkt_ID'];
  
	  if(isset($_POST['belag_ID'])) $formular = $_POST['belag_ID'];
    else $formular = array();
    
    $belagq = mysql_query("SELECT belag_ID FROM belagzuprodukt WHERE produkt_ID='$produkt_ID'");
    $dbout = array();
    $i = 0;
    while($belag = mysql_fetch_array($belagq)){
       $dbout[$i] = $belag['belag_ID'];
       $i++;    
    }
  
  $insert = array_values(array_diff($formular,$dbout));
  $delete = array_values(array_diff($dbout,$formular));
  
  foreach ($insert as $belag_ID) {
     safe_query("INSERT INTO ".PREFIX."belagzuprodukt (produkt_ID,belag_ID)
	               values('$produkt_ID','$belag_ID')");
  }
  
  foreach ($delete as $belag_ID) {
     safe_query("DELETE FROM ".PREFIX."belagzuprodukt WHERE produkt_ID='$produkt_ID' AND belag_ID='$belag_ID'");
  } 
}


//// PRODUKT HINZUFUEGEN

if(isset($_GET['action'])) {
	if($_GET['action']=="add") {
      
    echo '<h1>Produkt hinzuf&uuml;gen</h1>
    <form method="post" action="index.php?site=produkt&action=edit" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Produktes</b></td>
        <td width="85%"><input type="text" name="name" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = safe_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr> 
      <tr>
        <td colspan="2"><input type="submit" name="save" value="weiter" /></td>
      </tr>
    </table>
    </form>';
	}


///// PRODUKT EDITIEREN

	elseif($_GET['action']=="edit") {

	 $produktq = safe_query("SELECT * FROM produkt WHERE ID=". $_GET["ID"] . "");
   $produkt = mysql_fetch_array($produktq);
  
  echo'<h1>Produkt editieren</h1>';
	//<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
    echo '<form method="post" action="index.php?site=produkt" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Produktes</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $produkt["name"] . '" />
        <input type="hidden" name="ID" value="'. $produkt["ID"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" value="'. $produkt["comment"] .'" /></td>
      </tr>             
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = safe_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $produkt["kat_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
       <tr>
        <td colspan="2"><input type="submit" name="edit_basic" value="Basics editieren" /></td>
      </tr>
    </table>
    </form>';
    
/////// ZUTATEN
    
    //auslesen der BelagZuProdukt Tabelle um checkboxen aktiv zu setzen
    $checkq = safe_query("SELECT belag_ID FROM belagzuprodukt WHERE produkt_ID=" . $produkt["ID"] . "");
    $checkarr = array();
    $i = 0;
    while($check = mysql_fetch_array($checkq)){
       $checkarr[$i] = $check['belag_ID'];
       $i++;    
    }
    
    
    
    $produkt_ID = $produkt["ID"];
    $katq = safe_query("SELECT belagkat.ID,belagkat.name 
      FROM belagkat,belagkatzuproduktkat,produktkat,produkt 
      WHERE produkt.ID ='$produkt_ID'
      AND produkt.kat_ID = produktkat.ID
      AND produktkat.ID = belagkatzuproduktkat.produktkat_ID
      AND belagkatzuproduktkat.belagkat_ID = belagkat.ID
      ORDER BY belagkat.ID");
   
    if(mysql_num_rows($katq)>=1){        
       //<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
	   echo '<h1>Zutaten</h1>
             
			 <form method="post" action="index.php?site=produkt" id="post" name="post" enctype="multipart/form-data" ">
             <table width="100%" border="0" cellspacing="1" cellpadding="3">
             <input type="hidden" name="produkt_ID" value="'. $produkt['ID'] .'" />';
         while($kat = mysql_fetch_array($katq)){
             echo "<tr>
                    <td><h3>" . $kat["name"] . "</h3></td>
                  </tr>";
                                     
              $produktq = safe_query("SELECT * FROM belag WHERE kat_ID=" . $kat["ID"] . "");
              while($belag = mysql_fetch_array($produktq)){
                
                if(in_array($belag['ID'],$checkarr)) $checked = " checked ";
                else $checked="";
                
                echo '<tr>
                        <td><input type="checkbox" name="belag_ID[]" value="'. $belag['ID'] .'" '. $checked. '>' . $belag["name"] . '</td>
                      </tr>';    
              }
        }
        echo' <tr>
              <td><input type="submit" name="edit_belag" value="edit" /></td>
            </tr>
          </table>
          </form>';
    }


///// PREIS EDITIEREN
      
      
      $produktkat_ID = $produkt['kat_ID'];
      $produktpreisq = safe_query("SELECT * FROM produktpreis WHERE produkt_ID=" . $produkt["ID"] . " ORDER BY size");
      
      if(mysql_num_rows($produktpreisq)>=1){
          echo'<h1>Preis editieren</h1>';
          //<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
		  echo '<form method="post" action="index.php?site=produkt" id="post" name="post" enctype="multipart/form-data" ">
           <table width="100%" border="0" cellspacing="1" cellpadding="3">';
          while($produktpreis = mysql_fetch_array($produktpreisq)){
            echo '<tr>
                  <td width="15%">
                  <input type="hidden" name="produktpreis_ID[]" value="'. $produktpreis['ID'] .'" />
                  <select name="size[]" size="1">';
            
                              $sizeq = safe_query("SELECT size,name FROM size WHERE produktkat_ID ='$produktkat_ID'");
                              while($size = mysql_fetch_array($sizeq)){                
                              
                                echo '<option'; 
                                if($size["size"] == $produktpreis["size"]) echo  " selected ";
                                echo ' value='. $size["size"] . '>'. $size["name"] . '</option>'; 
                              }
                        echo '</select></td>
                        <td width="15%"><input type="text" name="preis[]" size="10" value="'. $produktpreis["preis"] .'" /> Euro</td>
                        <td width="70%"><a href="?site=produkt&action=edit&ID='. $produkt["ID"] . '&delete_preis=' . $produktpreis["ID"] . '"> delete</a></td>
                
                </tr>   ';
          }
          echo' <tr>
            <td colspan="3"><input type="submit" name="edit_preis" value="Preis editieren" /></td>
          </tr>
        </table>
        </form>';
      }
      

///// PREIS HINZUFUEGEN
      
      $sizeq = safe_query("SELECT size.size,size.name FROM size 
                            WHERE size.def_preis IS NULL
                            AND produktkat_ID ='".$produkt['kat_ID']."'
                            AND NOT EXISTS(SELECT produktpreis.size FROM produktpreis
                            WHERE produkt_ID ='".$produkt['ID']."'
                            AND produktpreis.size = size.size)  ");
      
      if(mysql_num_rows($sizeq)>=1){
          echo'<h3>Preis hinzuf&uuml;gen</h3>';
          echo '<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
           <table width="100%" border="0" cellspacing="1" cellpadding="3">
             <tr>
                <td width="15%">
                  <input type="hidden" name="produkt_ID" value="'. $produkt['ID'] .'" />
                  <select name="size" size="1">';
                              
                              
                              while($size = mysql_fetch_array($sizeq)){                
                              
                                echo '<option value='. $size["size"] . '>'. $size["name"] . '</option>'; 
                              }
                        echo '</select></td>
                        <td width="15%"><input type="text" name="preis" size="10" /> Euro</td>
                        <td width="70%"><input type="submit" name="save_preis" value="hinzuf&uuml;gen" /></td>
                </tr>       
        </table>
        </form>';
      
      }
      


   
  
      
	}
}
else{ // standard auflistung aller Beläge bei keiner action

  echo '<a href="?site=produkt&action=add">Neues Produkt anlegen</a>';
  
  $katq = safe_query("SELECT * FROM produktkat WHERE top_ID IS NULL");
  echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
  while($kat = mysql_fetch_array($katq)){
     echo "<tr>
            <td colspan='4'><h3>" . $kat["name"] . "</h3></td>
          </tr>";
          $subkatq = safe_query("SELECT * FROM produktkat WHERE top_ID = '".$kat['ID']."'");
          while($subkat = mysql_fetch_array($subkatq)){
             echo "<tr>
                    <td colspan='4'><h3>-- " . $subkat["name"] . "</h3></td>
                  </tr>";
          
                $produktq = safe_query("SELECT * FROM produkt
                                        WHERE kat_ID=" . $subkat["ID"] . "");
                while($produkt = mysql_fetch_array($produktq)){
                  echo '<tr>
                          <td width="15%">' . $produkt["name"] . '</td>
                          
                          <td width="5%"><a href="?site=produkt&action=edit&ID=' . $produkt["ID"] . '" class="button"> edit</a></td>
                          <td align="left"><a href="?site=produkt&delete=' . $produkt["ID"] . '" class="button"> delete</a></td>
                        </tr>';    
                }
        }
                               
    $produktq = safe_query("SELECT * FROM produkt
                            WHERE kat_ID=" . $kat["ID"] . "");
    while($produkt = mysql_fetch_array($produktq)){
      echo '<tr>
              <td width="15%">' . $produkt["name"] . '</td>
              
              <td width="5%"><a href="?site=produkt&action=edit&ID=' . $produkt["ID"] . '" class="button"> edit</a></td>
              <td align="left"><a href="?site=produkt&delete=' . $produkt["ID"] . '" class="button"> delete</a></td>
            </tr>';    
    }
  }
  echo '</table>';

}
?>