<?php

if(isset($_SESSION['bestell_ID'])){

echo '<table width="100%" border="1" cellspacing="1" cellpadding="3">
       <tr>
        <td>&nbsp;</td>
        <td>Artikel</td>
        <td>Preis</td>
        <td>Editieren</td>
       </tr>';
                                
     $produktq = mysql_query("SELECT produktzubestellung.ID AS produktzubestell_ID,
                              produktzubestellung.bestell_ID,
                              produktzubestellung.produkt_ID,
                              produkt.ID,
                              produkt.name AS produkt,
                              produktpreis.preis,
                              size.size,
                              size.name AS groesse,
                              COUNT(produktzubestellung.produkt_ID) AS anzahl,
                              SUM(produktpreis.preis) AS summe
                              
                              FROM produktzubestellung, produkt, produktpreis, size
                              WHERE produktzubestellung.bestell_ID = '".$_SESSION['bestell_ID']."'
                              AND produktzubestellung.produkt_ID = produkt.ID
                              AND Produkt.ID = produktpreis.produkt_ID
                              AND produktpreis.size = size.size
                              AND size.size = produktzubestellung.size
                              AND size.size = produktpreis.size
                              GROUP BY produktpreis.ID
                              ORDER BY produktzubestellung.ID");
    $produktsumme = 0;
    while($produkt = mysql_fetch_array($produktq)){
      $produktsumme += $produkt['summe'];
      echo '<tr>
              <td width="5%">'.$produkt['anzahl'].'x</td>  
              <td width="20%">' . $produkt["produkt"] . ' - ' . $produkt["groesse"] . '</td> 
              <td width="5%">' . $produkt["summe"] . ' &euro;</td>
              <td><a href="index.php?site=category&'.$kat_string.'&action=add&produkt='.$produkt['produkt_ID'].'&size='.$produkt['size'].'">+</a>&nbsp;
                  <a href="index.php?site=category&'.$kat_string.'&action=sub&produkt='.$produkt['produktzubestell_ID'].'">-</a>&nbsp;
                  <a href="index.php?site=category&'.$kat_string.'&action=delete&produkt='.$produkt['produkt_ID'].'&size='.$produkt['size'].'">x</a></td>
            </tr>';
    }
    echo '<tr>
            <td colspan="2" align="right">summe: </td>
            <td>' . $produktsumme . ' &euro;</td>
            <td>&nbsp;</td>
          </tr>';
    echo '</table>';


}
?>