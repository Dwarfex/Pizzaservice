<?php
  
  $kat_ID = "19"; //pizza
  
  echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">';
//// Produktdaten auslesen                               
    
    $produktq = mysql_query("SELECT * FROM produkt WHERE kat_ID=" . $kat_ID . "");
    while($produkt = mysql_fetch_array($produktq)){
      echo '<tr>
              <td><h3>' . $produkt["name"] . '</h3> ' . $produkt["comment"] . '</td>
            </tr>';
//// Preis auslesen bei Produkten die einen Eintrag in ProduktPreis haben, (keine Pizzen)            
            $produktpreisq = mysql_query("SELECT preis, produktpreis.size, size.name 
                                          FROM produktpreis, size 
                                          WHERE produkt_ID=" . $produkt["ID"] . " 
                                          AND produktpreis.size = size.size
                                          ORDER BY preis");
            if(mysql_num_rows($produktpreisq)>=1){  
               while($produktpreis = mysql_fetch_array($produktpreisq)){
              
                 echo '<tr>
                        <td><a href="#">' . $produktpreis["name"] . '</a> - ' . $produktpreis['preis'] . ' &euro;</td>
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
                          <td><a href="#">' . $preis["name"] . ' ('. $preis["comment"] .')</a> - ' . $preis['preis'] . ' &euro;</td>
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
?>


    
