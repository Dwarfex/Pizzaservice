<p>Hier kommen dann bald alle offenen Bestellungen hin :D</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>und links neben mich eine kleine backend navigation ;)</p>

<?php
if(isset($_GET['action'])){
	
if ($_GET['action'] = 'show'){
	$BestellID = $_GET['ID'];
	
	// dazugehoerige Items auflisten 
	// bestellung "fertigstellen" 
}


}
else {
	$offeneBestellungenq = mysql_query("SELECT * FROM bestellung WHERE done=0");// ID; kunde_ID; datum; wish; done=0
	
	
	while($offeneBestellungen = mysql_fetch_array($offeneBestellungenq)){
		
		$kunden_infoq = mysql_query("SELECT * FROM kunde WHERE ID=".$offeneBestellungen['kunde_ID'].""); //ID; anrede; vorname; nachname; strasse; hausnummer; plz; stadt; telefon
		$kunden_info = mysql_fetch_array($kunden_infoq);
		echo'
		<table border="0">
	  <tr>
		<td>Offene Bestellung f&uuml;r:</td>
		<td>'.$kunden_info['anrede'].'</td>
		<td>'.$kunden_info['vorname'].', '.$kunden_info['nachname'].'</td>
		
		<td><a href=index.php?site=overview&action=show&ID='.$offeneBestellungen['kunde_ID'].' class="button"> Bestellung einsehen</a></td>
	  </tr>
	</table>';
		
		
	}
}
?>

