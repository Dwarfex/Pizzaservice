<hr>
<?php

if(isset($_SESSION['bestellsumme'])){
  if($_SESSION['bestellsumme'] >= $mindestbestellwert){
    if(isset($_POST['send'])){
    	 $anrede = htmlspecialchars($_POST['anrede']);
    	 $vorname = htmlspecialchars($_POST['vorname']);
    	 $nachname = htmlspecialchars($_POST['nachname']);
    	 $strasse = htmlspecialchars($_POST['strasse']);
    	 $hausnummer = htmlspecialchars($_POST['hausnummer']);
    	 $plz = htmlspecialchars($_POST['plz']);
    	 $stadt = htmlspecialchars($_POST['stadt']);
    	 $telefon = htmlspecialchars($_POST['telefon']);
       if(isset($_POST['wish'])) htmlspecialchars($wish = $_POST['wish']);
       else $wish = '';
          
       
       
       safe_query("UPDATE ".PREFIX."kunde SET anrede= '$anrede',
                                              vorname = '$vorname',
                                              nachname = '$nachname',
                                              strasse = '$strasse',
                                              hausnummer = '$hausnummer',
                                              plz = '$plz',
                                              stadt = '$stadt',
                                              telefon = '$telefon'  
                                              WHERE ID=".$_SESSION['ID']." ");
       
       
       safe_query("UPDATE ".PREFIX."bestellung SET done='0', wish='$wish'  WHERE ID=".$_SESSION['bestell_ID']." ");
       
       echo' Bestellung abgeschickt. - Je nach aktuellem Aufwand betr&auml;gt die Lieferzeit bis zu 120 Minuten :P';
       unset($_SESSION['bestell_ID']);
    }
    else{
        $kundeq = safe_query("SELECT *
                               FROM kunde 
                               WHERE ID=" . $_SESSION['ID'] . "");
        $kunde = mysql_fetch_array($kundeq);
        
        
        echo '
        <form method="post" action="index.php?site=order" id="post" name="post" enctype="multipart/form-data" ">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr>
            <td width="15%">Anrede</td>
            <td width="85%"><input type="text" name="anrede" size="30" value="'. $kunde['anrede'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Vorname</td>
            <td width="85%"><input type="text" name="vorname" size="30" value="'. $kunde['vorname'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Nachname</td>
            <td width="85%"><input type="text" name="nachname" size="30" value="'. $kunde['nachname'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Strasse</td>
            <td width="85%"><input type="text" name="strasse" size="30" value="'. $kunde['strasse'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Hausnummer</td>
            <td width="85%"><input type="text" name="hausnummer" size="30" value="'. $kunde['hausnummer'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">PLZ</td>
            <td width="85%"><input type="text" name="plz" size="30" value="'. $kunde['plz'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Stadt</td>
            <td width="85%"><input type="text" name="stadt" size="30" value="'. $kunde['stadt'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Telefon</td>
            <td width="85%"><input type="text" name="telefon" size="30" value="'. $kunde['telefon'] .'"/></td>
          </tr>
          <tr>
            <td width="15%">Sonstiger Wunsch</td>
            <td width="85%"><textarea name="wish" cols="25" rows="5"></textarea></td>
          </tr>   
          <tr>
            <td colspan="2"><input type="submit" name="send" value="Abschicken" /></td>
          </tr>
        </table>
        </form>';
    
    }
  }
  else{
     system_error('Bestellsumme zu niedrig');
  }
}
else{
  system_error('Bestellsumme nicht gesetzt');
}
?>
<hr>