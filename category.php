<hr>


<?php


if(isset($_GET['action'])) {
	if($_GET['action']=="add") {
    
    if(!isset($_SESSION['ID'])){  // Neuen Kunden generieren
       safe_query("INSERT INTO ".PREFIX."Kunde () values()");
       $ID = mysql_insert_id();

       $date = date("Y-m-d"); 
       safe_query("INSERT INTO ".PREFIX."bestellung (kunde_ID, datum) values('$ID','$date')");
       $bestell_ID = mysql_insert_id();
       
       $_SESSION['ID'] = $ID;
       $_SESSION['bestell_ID'] = $bestell_ID;
    }
    
    if(isset($_GET['produkt'])){   // Produkte bei denen sich der Preis nicht aus den vorhanden Belägen berechnet
       $bestell_ID = $_SESSION['bestell_ID'];
       $produkt_ID = $_GET['produkt'];
       $size = $_GET['size'];
       
       safe_query("INSERT INTO ".PREFIX."produktzubestellung (bestell_ID, produkt_ID, size)
	                 values('$bestell_ID','$produkt_ID','$size')");
    }
  
    unset($_GET['action']);
  }
  else if($_GET['action']=="delete"){
      $ID = $_GET['produkt'];
      $size = $_GET['size'];
	
	    safe_query("DELETE FROM ".PREFIX."produktzubestellung WHERE produkt_ID='$ID' AND size='$size'");
    unset($_GET['action']);
  }
  else if($_GET['action']=="sub"){
      $produktzubestell_ID = $_GET['produkt'];
      
	
	    safe_query("DELETE FROM ".PREFIX."produktzubestellung WHERE ID='$produktzubestell_ID'");
    unset($_GET['action']);
  }
}
if(!isset($_GET['action'])) {  // standard wenn kategorie ausgewählt wurde

//// Gibt es Subkategorien?
  
  $subcatq = mysql_query("SELECT * FROM produktkat WHERE top_ID=" . $_GET['catID'] . "");
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
  echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">';
                                
    $produktq = mysql_query("SELECT * FROM produkt WHERE kat_ID=" . $kat_ID . "");
    while($produkt = mysql_fetch_array($produktq)){
      echo '<tr>
              <td><h3>' . $produkt["name"] . '</h3> ' . $produkt["comment"] . '</td>
            </tr>';
//// Preis auslesen bei Produkten die einen Eintrag in ProduktPreis haben, (keine Pizzen)            
            $produktpreisq = mysql_query("SELECT produkt_ID, preis, produktpreis.size, size.name 
                                          FROM produktpreis, size 
                                          WHERE produkt_ID=" . $produkt["ID"] . " 
                                          AND produktpreis.size = size.size
                                          ORDER BY preis");
            if(mysql_num_rows($produktpreisq)>=1){  
               while($produktpreis = mysql_fetch_array($produktpreisq)){
              
                 echo '<tr>
                        <td>' . $produktpreis["name"] . '</a> - <a href="index.php?site=category&'.$kat_string.'&action=add&produkt='.$produktpreis['produkt_ID'].'&size='.$produktpreis['size'].'">' . $produktpreis['preis'] . ' &euro; </a></td>
                       </tr>';
               }
            }
            else{
//// Preisausgabe wenn Produktkategorie standardpreis hat (pizza). Preis wird berechnet durch size.def_preis und der summe der Belagpreise in abhängigkeit zur Produktgröße                     
                     $preisq = mysql_query("SELECT size.size, size.name AS name, size.comment AS comment, size.def_preis,
                                            produkt.ID,belagzuprodukt.produkt_ID,belagzuprodukt.belag_ID,
                                            belag.ID,belag.value,
                                            belagpreis.size,belagpreis.value,belagpreis.preis, 
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
                   echo '<tr>
                          <td>' . $preis["name"] . ' ('. $preis["comment"] .') - <a href="#">' . $preis['preis'] . ' &euro; </a></td>
                         </tr>';
                  }
               }  
            }    
//// Belagausgabe    
        $belagq = mysql_query("SELECT belag.name, belag.ID, belagzuprodukt.belag_ID, belagzuprodukt.produkt_ID 
                               FROM belag,belagzuprodukt 
                               WHERE belagzuprodukt.produkt_ID=" . $produkt['ID'] . "
                               AND belagzuprodukt.belag_ID = belag.ID");
        if(mysql_num_rows($belagq)>=1){
           echo '<tr>
                  <td>mit ';
           while($belag = mysql_fetch_array($belagq)){
             echo $belag["name"] . ', ';
                      
           }
           echo '</td>
                </tr>';
        }
    }//while produktausgabe
 
  echo '</table>';

}

?>

<hr>