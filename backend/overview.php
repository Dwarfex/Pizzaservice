<?php


if(isset($_GET['complete'])){
   $bestell_ID = $_GET['complete'];
   safe_query("UPDATE ".PREFIX."bestellung SET done='1' WHERE ID=".$bestell_ID." ");

   echo "--> bestellung abgearbeitet";
}

if(isset($_GET['action'])){

  if ($_GET['action'] = 'show'){
	$BestellID = $_GET['ID'];
	
  $kundeq = mysql_query("SELECT anrede,vorname,nachname,strasse,hausnummer,plz,stadt,telefon
                         FROM kunde 
                         WHERE ID=" . $_GET['kunde'] . "");
        $kunde = mysql_fetch_array($kundeq);

   echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">
         <tr>
          <td>'.$kunde['anrede'].' '.$kunde['vorname'].' '.$kunde['nachname'].'</td>
         </tr>
         <tr> 
          <td>'.$kunde['strasse'].' '.$kunde['hausnummer'].'</td>
         </tr>
         <tr> 
          <td>'.$kunde['plz'].' '.$kunde['stadt'].'</td>
         </tr>
         <tr> 
          <td>tel: '.$kunde['telefon'].'</td>
         </tr>
        </table><br>';

	
////START PRODUKTAUSGABE  
  echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">
       <tr>
        <td width="5%">&nbsp;</td>
        <td width="30%">Artikel</td>
        <td>Preis</td>
       </tr>';
                            
      // Ausgabe aller Produkte im Warenkorb
      $produktq = mysql_query("(SELECT
                                produktzubestellung.ID AS produktzubestellID,
                                produkt.ID,
                                '' AS kat_name,
                                produkt.name AS produkt,
                                size.size,
                                size.name AS groesse,
                                size.def_preis,
                                COUNT(produktzubestellung.produkt_ID) AS anzahl,
                                SUM(produktpreis.preis) AS summe
                                
                                FROM produktzubestellung, produkt, produktpreis, size
                                WHERE produktzubestellung.bestell_ID = '".$_GET['ID']."'
                                AND produktzubestellung.produkt_ID = produkt.ID
                                AND produkt.ID = produktpreis.produkt_ID
                                AND produktpreis.size = size.size
                                AND size.size = produktzubestellung.size
                                AND size.size = produktpreis.size
                                GROUP BY produktpreis.ID)
                                
                                UNION
                                
                                (SELECT produktzubestellung.ID AS produktzubestellID, 
                                produkt.ID,
                                produktkat.name AS kat_name,
                                produkt.name AS produkt,
                                size.size,
                                size.name AS groesse,
                                size.def_preis,
                                1 AS anzahl,
                                def_preis + SUM(belagpreis.preis) AS summe
                                
                                FROM produktzubestellung, produkt, produktkat,
                                size, belagzuprodukt, belag, belagpreis
                                
                                WHERE produktzubestellung.bestell_ID = '".$_GET['ID']."'
                                AND produktzubestellung.produkt_ID = produkt.ID
                                AND produkt.ID = belagzuprodukt.produkt_ID
                                AND belagzuprodukt.belag_ID = belag.ID
                                AND belag.value = belagpreis.value
                                AND belagpreis.size = size.size
                                AND produktzubestellung.size = size.size
                                AND produkt.kat_ID = produktkat.ID
                                AND produktkat.ID = size.produktkat_ID
                                
                                GROUP BY produktzubestellung.ID)
                                
                                ORDER BY produktzubestellID");
        
        $bestellsumme = 0; // Gesamtsumme
        while($produkt = mysql_fetch_array($produktq)){
          
          echo '<tr>
                  <td>'.$produkt['anzahl'].'x</td>  
                  <td>'.$produkt['kat_name'].' ' . $produkt["produkt"] . ' - ' . $produkt["groesse"];
                  
              
//// START EXTRAS         
         // Ausgabe aller Extras zum jeweiligen Produkt
         $extraq = mysql_query("SELECT produktzubestellung.ID AS produktzubestell_ID,
                                belag.ID AS belag_ID,
                                belag.name AS belag,
                                
                                COUNT(belagzubestellung.belag_ID) AS anzahl,
                                SUM(belagpreis.preis) AS summe 
                                FROM produktzubestellung, belagzubestellung, belag, belagpreis
                                WHERE produktzubestellung.ID = '".$produkt['produktzubestellID']."'
                                AND produktzubestellung.ID = belagzubestellung.produktzubestell_ID
                                AND belagzubestellung.belag_ID = belag.ID
                                AND belag.value = belagpreis.value
                                AND belagpreis.size = produktzubestellung.size
                                GROUP BY belagzubestellung.belag_ID
                                ORDER BY belagzubestellung.ID");
                   
                   $extra_summe = 0;
                   $limit = mysql_num_rows($extraq);
                   if($limit >= 1){
                    echo'<br>Extra - ';
                      $i = 1;
                      while($extra = mysql_fetch_array($extraq)){
                        $extra_summe += $extra['summe']; // extras werden aufsummiert
                        if($extra['anzahl'] > 1) echo $extra['anzahl'].'x ';
                        
                        echo $extra['belag'] . setspacer($limit,$i,', ');
                        $i++;
                      }
                   }       
         
         
         $produkt["summe"] += $extra_summe;  // Produktsumme und Extrasumme werden aufsummiert
         echo'</td> 
              <td>' . $produkt['summe'] . ' &euro;</td>
               </tr>';
    
      $bestellsumme += $produkt['summe'];  // bestellsumme ist die summe aller Produkte plus extras
    }
    
    echo '<tr>
            <td colspan="2" align="right">summe: </td>
            <td>' . $bestellsumme . ' &euro;</td>
          </tr></table>';
 

	
    echo '<br><br><a href=index.php?site=overview&complete='.$_GET['ID'].' class="button"> fertig </a>';  

 
}


}
else {
	$offeneBestellungenq = mysql_query("SELECT * FROM bestellung WHERE done='0'");// ID; kunde_ID; datum; wish; done=0
	if(mysql_num_rows($offeneBestellungenq)>=1){
    echo '<table width="100%" border="0">
            <tr>
              <td colspan="4"><b>Offene Bestellungen f&uuml;r</b></td>
            </tr>';
           
      $i = 1;
      while($offeneBestellungen = mysql_fetch_array($offeneBestellungenq)){
  		
    	  $kunden_infoq = mysql_query("SELECT * FROM kunde WHERE ID=".$offeneBestellungen['kunde_ID'].""); //ID; anrede; vorname; nachname; strasse; hausnummer; plz; stadt; telefon
    		$kunden_info = mysql_fetch_array($kunden_infoq);
    		echo'<tr>
      	  <td width="5%">'.$i.'</td>
      		<td width="10%">'.$offeneBestellungen['datum'].'</td>
      		<td width="20%">'.$kunden_info['vorname'].', '.$kunden_info['nachname'].'</td>
      		
      		<td><a href=index.php?site=overview&action=show&ID='.$offeneBestellungen['ID'].'&kunde='.$offeneBestellungen['kunde_ID'].' class="button"> Bestellung einsehen</a></td>
    	  </tr>';
		
		    $i++;
	   }
	   echo '</table>';
  }
  else{
    echo 'keine Offenen Bestellungen';
  }  
}
?>

