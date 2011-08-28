<?php

if(!isset($kat_string)){
  $kat_string ='';
}


echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">
       <tr>
        <td>&nbsp;</td>
        <td>Artikel</td>
        <td>Preis</td>
        <td>Editieren</td>
       </tr>';
                                
if(isset($_SESSION['bestell_ID'])){

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
                                WHERE produktzubestellung.bestell_ID = 5
                                AND produktzubestellung.produkt_ID = produkt.ID
                                AND Produkt.ID = produktpreis.produkt_ID
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
                                
                                WHERE produktzubestellung.bestell_ID = 5
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
    $produktsumme = 0;
    while($produkt = mysql_fetch_array($produktq)){
      
      echo '<tr>
              <td width="5%">'.$produkt['anzahl'].'x</td>  
              <td width="30%">'.$produkt['kat_name'].' ' . $produkt["produkt"] . ' - ' . $produkt["groesse"];
              if(isset($produkt['def_preis'])){
                echo ' <a href="index.php?site=category&edit_item=true&item='.$produkt['produktzubestellID'].' ">zutaten erg&auml;nzen</a>';
              }
              
         
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
                        $extra_summe += $extra['summe'];
                        if($extra['anzahl'] > 1) echo $extra['anzahl'].'x ';
                        
                        echo '<a href="index.php?site=category&item='.$produkt['produktzubestellID'].'&del_extra='.$extra['belag_ID'].' ">'.$extra['belag'].'</a>'.setspacer($limit,$i,',').' ';
                        $i++;
                      }
                   }       
         
         
         $produkt["summe"] += $extra_summe;
         echo'</td> 
              <td width="5%">' . $produkt['summe'] . ' &euro;</td>
              <td><a href="index.php?site=category&'.$kat_string.'&action=add&produkt='.$produkt['ID'].'&size='.$produkt['size'].'">+</a>&nbsp;
                  <a href="index.php?site=category&'.$kat_string.'&action=sub&produkt='.$produkt['produktzubestellID'].'">-</a>&nbsp;';
                  if($produkt['anzahl'] > 1){
                    echo '<a href="index.php?site=category&'.$kat_string.'&action=delete&produkt='.$produkt['ID'].'&size='.$produkt['size'].'">x</a></td>';
                  }
                  
           echo'</tr>';
    
    $produktsumme += $produkt['summe'];
    }
    echo '<tr>
            <td colspan="2" align="right">summe: </td>
            <td>' . $produktsumme . ' &euro;</td>
            <td><a href="index.php?site=order">bestellen</a></td>
          </tr>';
}
else{            
  echo '<tr>
          <td colspan="4">keine Artikel im Warenkorb</td>
        </tr>';
}    
    echo '</table>';



?>