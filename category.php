<hr>
<?php

if(isset($_GET['action'])) {
	if($_GET['action']=="add") { 
    
    // Beim ersten hinzufügen eines Produktes in den Warenkorb wird Neuer Kunde generiert
    if(!isset($_SESSION['ID'])){
      if(isset($_COOKIE['kunde_ID'])){  // Gibt es vielleicht einen Cookie?
         $_SESSION['ID'] = $_COOKIE['kunde_ID'];   // Wenn Ja, wird die Kunden ID übernommen
      }
      else{
         safe_query("INSERT INTO ".PREFIX."kunde () values()");     // wenn nein, wird neuer Kunde generiert
          $ID = mysql_insert_id();
          $_SESSION['ID'] = $ID;
      }   
    }
    // Ist die letzte Bestellung abgeschlossen und eine neue wird aufgenommen, wird eine neue Bestellung generiert
    if(!isset($_SESSION['bestell_ID'])){ 
       $ID = $_SESSION['ID'];
       $date = date("Y-m-d"); 
       safe_query("INSERT INTO ".PREFIX."bestellung (kunde_ID, datum) values('$ID','$date')");
       
       $bestell_ID = mysql_insert_id();
       $_SESSION['bestell_ID'] = $bestell_ID;
    }
    
    // Produkt zur DB:produktzubestellung hinzufügen
    if(isset($_GET['produkt'])){   // Produkt zur Bestellung hinzufügen
       $bestell_ID = $_SESSION['bestell_ID'];
       $produkt_ID = $_GET['produkt'];
       $size = $_GET['size'];
       
       safe_query("INSERT INTO ".PREFIX."produktzubestellung (bestell_ID, produkt_ID, size)
	                 values('$bestell_ID','$produkt_ID','$size')");
    
       $_GET['item'] = mysql_insert_id();
    }
  }
  // Alle Produkte mit derselben ID werden aus dem Warenkorb gelöscht
  else if($_GET['action']=="delete"){
      $ID = $_GET['produkt'];
      $size = $_GET['size'];
	    safe_query("DELETE FROM ".PREFIX."produktzubestellung WHERE produkt_ID='$ID' AND size='$size'"); 
  }
  // ein einziges Produkt wird gelöscht
  else if($_GET['action']=="sub"){
      $produktzubestell_ID = $_GET['produkt'];
	    safe_query("DELETE FROM ".PREFIX."produktzubestellung WHERE ID='$produktzubestell_ID'");  
  }
}

// Extra zu einem Produkt hinzufügen
if(isset($_GET['add_extra'])){
  $produktzubestell_ID = $_GET['item'];
  $belag_ID = $_GET['add_extra'];
  
  safe_query("INSERT INTO ".PREFIX."belagzubestellung (produktzubestell_ID, belag_ID)
	                 values('$produktzubestell_ID','$belag_ID')");
}

// Extra von einem Produkt löschen
if(isset($_GET['del_extra'])){
  $produktzubestell_ID = $_GET['item'];
  $belag_ID = $_GET['del_extra'];
  
  safe_query("DELETE FROM ".PREFIX."belagzubestellung WHERE produktzubestell_ID='$produktzubestell_ID' AND belag_ID='$belag_ID' LIMIT 1");
}   


// Editierübersicht von Produkten mit Extras
if(isset($_GET['edit_item'])){

   if(isset($_GET['item'])){
     echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
     
//// START BASISINFOS     
     // Ausgabe der Produkt-Basisinfos (name,groesse...) 
     $produktq = safe_query("SELECT produkt.ID,
                              produktkat.name AS kat_name,
                              produkt.name AS produkt,
                              size.size,
                              size.name AS groesse,
                              produktkat.ID AS produktkat_ID,
                              def_preis + SUM(belagpreis.preis) AS summe
                              
                              FROM produktzubestellung, produkt, produktkat,
                              size, belagzuprodukt, belag, belagpreis
                              
                              WHERE produktzubestellung.ID = '".$_GET['item']."'
                              AND produktzubestellung.produkt_ID = produkt.ID
                              AND produkt.ID = belagzuprodukt.produkt_ID
                              AND belagzuprodukt.belag_ID = belag.ID
                              AND belag.value = belagpreis.value
                              AND belagpreis.size = size.size
                              AND produktzubestellung.size = size.size
                              AND produkt.kat_ID = produktkat.ID
                              AND produktkat.ID = size.produktkat_ID
                              
                              GROUP BY produkt.ID");
               
       $produkt = mysql_fetch_array($produktq); 
       echo '<tr>
              <td><b>' . $produkt["kat_name"] . ' &nbsp; '. $produkt['produkt'] .' &nbsp; '. $produkt['groesse'] .'</b></td>
             </tr>';
        // Ausgabe der gewählten Extras                
       $extraq = safe_query("SELECT produktzubestellung.ID AS produktzubestell_ID,
                              belag.ID AS belag_ID,
                              belag.name AS belag,
                              
                              COUNT(belagzubestellung.belag_ID) AS anzahl,
                              SUM(belagpreis.preis) AS summe 
                              FROM produktzubestellung, belagzubestellung, belag, belagpreis
                              WHERE produktzubestellung.ID = '".$_GET['item']."'
                              AND produktzubestellung.ID = belagzubestellung.produktzubestell_ID
                              AND belagzubestellung.belag_ID = belag.ID
                              AND belag.value = belagpreis.value
                              AND belagpreis.size = produktzubestellung.size
                              GROUP BY belagzubestellung.belag_ID
                              ORDER BY belagzubestellung.ID");
                   
       $extra_summe = 0;
       $limit = mysql_num_rows($extraq);
       if($limit >= 1){
         echo '<tr>
                <td>Extras: ';
          $i = 1;
          while($extra = mysql_fetch_array($extraq)){
            $extra_summe += $extra['summe'];    // Extras werden aufsummiert
            if($extra['anzahl'] > 1) echo $extra['anzahl'].'x ';
            
            echo '<a href="index.php?site=category&edit_item=true&item='.$_GET['item'].'&del_extra='.$extra['belag_ID'].' ">'.$extra['belag'].'</a>'.setspacer($limit,$i,',').' ';
            $i++;
          }
       }
       echo '</td>
            </tr>
            <tr>
             <td>Summe: '.($produkt['summe'] + $extra_summe).' &euro;</td>
            </tr></table>';
                   
//// START BELAGAUSWAHL
       // Ausgabe Kategorienamen
       $katq = safe_query("SELECT belagkat.ID,belagkat.name 
                            FROM belagkat,belagkatzuproduktkat,produktkat,produkt 
                            WHERE produkt.ID ='".$produkt['ID']."'
                            AND produkt.kat_ID = produktkat.ID
                            AND produktkat.ID = belagkatzuproduktkat.produktkat_ID
                            AND belagkatzuproduktkat.belagkat_ID = belagkat.ID
                            ORDER BY belagkat.ID");
   
    if(mysql_num_rows($katq)>=1){         
	   echo '<h3>Extras</h3>
             <table width="100%" border="0" cellspacing="1" cellpadding="3">';  
             
         while($kat = mysql_fetch_array($katq)){
             echo "<tr>
                    <td colspan='2'><b>" . $kat["name"] . "</b></td>
                  </tr>";
             // Ausgabe Beläge                        
             $belagq = safe_query("SELECT ID, preis, name  
                                    FROM belag,belagpreis
                                    WHERE belag.kat_ID = '".$kat['ID']."'
                                    AND belagpreis.size = '".$produkt['size']."'
                                    AND belag.value = belagpreis.value");
              while($belag = mysql_fetch_array($belagq)){
                echo '<tr>
                        <td width="45%"><a href="index.php?site=category&edit_item=true&item='.$_GET['item'].'&add_extra='.$belag['ID'].' "><img src="img/add.png" width="10" height="10" alt="add" />' . $belag["name"] . '</a></td>
                        <td>' . $belag["preis"] . '</td>
                      </tr>';    
              }
        } 
        echo '</table>';
      }
   }
}

// Standard wenn kategorie ausgewählt wurde
if(isset($_GET['catID'])) { 

//// Gibt es Subkategorien?
  
  $subcatq = safe_query("SELECT * FROM produktkat WHERE top_ID=" . $_GET['catID'] . "");
  if(mysql_num_rows($subcatq)>=1){
     echo '<h3>';
     $i = 1;
     while($subcat = mysql_fetch_array($subcatq)){ 
        if(isset($_GET['subcatID'])){
          if($_GET['subcatID'] == $subcat['ID']){
           echo '-->';  //irgendein highlighting
          }
        }
        else if($i == 1){      //wenn subcatID nicht gesetzt und erster Durchlauf der while -> produkte der ersten subcat werden anzeigt
          $kat_ID = $subcat['ID'];
          $kat_string = 'catID='.$_GET['catID'].'&subcatID='.$subcat['ID'];
          echo '-->';   // irgendein highlighting
        } 
        echo '<a href="index.php?site=category&catID='.$_GET['catID'].'&subcatID='.$subcat['ID'].'">'. $subcat['name'] .'</a> &nbsp; ';
        $i++;
     }
     echo '</h3>';   
  }
  
  
  if(!isset($kat_ID)){          // falls kat_ID nicht bereits definiert wurde durch den ersten subcat durchlauf
    if(isset($_GET['subcatID'])){
      $kat_ID = $_GET['subcatID'];    //kat_ID ist subcatID falls subcatID gesetzt wurde
      $kat_string = 'catID='.$_GET['catID'].'&subcatID='.$_GET['subcatID'];
    }
    else{
      $kat_ID = $_GET['catID'];       // ansonsten (keine subkategorien vorhanden für diese Kategorie) ist kat_ID die übergebene catID
      $kat_string = 'catID='.$_GET['catID'];
    }
  }
  
  
  

//// Produktdaten auslesen    
  echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
                                
    $produktq = safe_query("SELECT * FROM produkt WHERE kat_ID=" . $kat_ID . "");
    while($produkt = mysql_fetch_array($produktq)){
      echo '<tr>
              <td><h3>' . $produkt["name"] . '</h3> ' . $produkt["comment"] . '</td>
            </tr>';
//// Preis auslesen bei Produkten die einen Eintrag in ProduktPreis haben, (keine Pizzen)            
            $produktpreisq = safe_query("SELECT produkt_ID, preis, produktpreis.size, size.name 
                                          FROM produktpreis, size 
                                          WHERE produkt_ID=" . $produkt["ID"] . " 
                                          AND produktpreis.size = size.size
                                          ORDER BY preis");
            if(mysql_num_rows($produktpreisq)>=1){  
               while($produktpreis = mysql_fetch_array($produktpreisq)){
              
                 echo '<tr>
                        <td>' . $produktpreis["name"] . '</a> - <a href="index.php?site=category&'.$kat_string.'&action=add&produkt='.$produktpreis['produkt_ID'].'&size='.$produktpreis['size'].'">' . $produktpreis['preis'] . ' &euro; &nbsp;&nbsp;<img src="img/cart.png" width="14" height="14" alt="cart" /></a></td>
                       </tr>';
               }
            }
            else{
//// Preisausgabe wenn Produktkategorie standardpreis hat (pizza). Preis wird berechnet durch size.def_preis und der summe der Belagpreise in abhängigkeit zur Produktgröße                     
                     $preisq = safe_query("SELECT size.size, 
                                            size.name AS name, 
                                            size.comment AS comment, 
                                            size.def_preis,
                                            produkt.ID AS produktID, 
                                            belagzuprodukt.produkt_ID,
                                            belagzuprodukt.belag_ID,
                                            belag.ID,belag.value,
                                            belagpreis.size,
                                            belagpreis.value,
                                            belagpreis.preis, 
                                            size.def_preis + SUM(belagpreis.preis) AS preis
                                            
                                            FROM size,produkt,belagzuprodukt,belag,belagpreis 
                                            WHERE size.produktkat_ID = '".$kat_ID."'
                                            AND produkt.ID = '".$produkt['ID']."'
                                            AND belagzuprodukt.produkt_ID = produkt.ID
                                            AND belag.ID = belagzuprodukt.belag_ID
                                            AND belagpreis.value = belag.value
                                            AND belagpreis.size = size.size 
                                            
                                            GROUP BY size.size
                                            ORDER BY preis");
               if(mysql_num_rows($preisq)>=1){
                  while($preis = mysql_fetch_array($preisq)){  
                   if($preis['comment']!='') $preis['comment'] = '('. $preis['comment']. ')';
                   
                   echo '<tr>
                          <td>' . $preis["name"] . ' '. $preis["comment"] .' - <a href="index.php?site=category&action=add&produkt='.$preis['produktID'].'&size='.$preis['size'].'&edit_item=true">' . $preis['preis'] . ' &euro;&nbsp;&nbsp;<img src="img/cart.png" width="14" height="14" alt="cart" /> </a></td>
                         </tr>';
                  }
               }  
            }    
//// Belagausgabe    
        $belagq = safe_query("SELECT belag.name, belag.ID, belagzuprodukt.belag_ID, belagzuprodukt.produkt_ID 
                               FROM belag,belagzuprodukt 
                               WHERE belagzuprodukt.produkt_ID=" . $produkt['ID'] . "
                               AND belagzuprodukt.belag_ID = belag.ID
                               ORDER BY belag.ID");
        $limit = mysql_num_rows($belagq); 
        if($limit >= 1){
           echo '<tr>
                  <td>mit ';
           $i = 1;
           while($belag = mysql_fetch_array($belagq)){
             echo $belag["name"] . setspacer($limit,$i,', ');
             $i++;         
           }
           echo '</td>
                </tr>';
        }
    }//while produktausgabe
 
  echo '</table>';

}

?>

<hr>