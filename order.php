<hr>
<?php

if(isset($_POST['send'])){
	 $anrede = $_POST['anrede'];
	 $vorname = $_POST['vorname'];
	 $nachname = $_POST['nachname'];
	 $strasse = $_POST['strasse'];
	 $hausnummer = $_POST['hausnummer'];
	 $plz = $_POST['plz'];
	 $stadt = $_POST['stadt'];
	 $telefon = $_POST['telefon'];
       
   safe_query("UPDATE ".PREFIX."kunde SET anrede= '$anrede',
                                          vorname = '$vorname',
                                          nachname = '$nachname',
                                          strasse = '$strasse',
                                          hausnummer = '$hausnummer',
                                          plz = '$plz',
                                          stadt = '$stadt',
                                          telefon = '$telefon'  
                                          WHERE ID=".$_SESSION['ID']." ");
   
   
   safe_query("UPDATE ".PREFIX."bestellung SET done=0 WHERE ID=".$_SESSION['bestell_ID']." ");

   unset($_SESSION['bestell_ID']);
}
else{
    echo '
    <form method="post" action="index.php?site=order" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%">Anrede</td>
        <td width="85%"><input type="text" name="anrede" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Vorname</td>
        <td width="85%"><input type="text" name="vorname" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Nachname</td>
        <td width="85%"><input type="text" name="nachname" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Strasse</td>
        <td width="85%"><input type="text" name="strasse" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Hausnummer</td>
        <td width="85%"><input type="text" name="hausnummer" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">PLZ</td>
        <td width="85%"><input type="text" name="plz" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Stadt</td>
        <td width="85%"><input type="text" name="stadt" size="30" /></td>
      </tr>
      <tr>
        <td width="15%">Telefon</td>
        <td width="85%"><input type="text" name="telefon" size="30" /></td>
      </tr>  
      <tr>
        <td colspan="2"><input type="submit" name="send" value="weiter" /></td>
      </tr>
    </table>
    </form>';

}

?>
<hr>