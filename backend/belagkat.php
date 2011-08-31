<?php

if(isset($_GET['delete'])) {
	$belagkatID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."belagkat WHERE ID='$belagkatID'");	
}

elseif(isset($_POST['save'])) {
	$belagkatname = $_POST['belagkatname'];
	
	safe_query("INSERT INTO ".PREFIX."belagkat ( name )
	            values( '$belagkatname' )");
	
}

elseif(isset($_POST['saveedit'])) {
	$belagkatname = $_POST['name'];
	$belagkatID = $_POST['ID'];
	
	safe_query("UPDATE ".PREFIX."belagkat SET name='$belagkatname' WHERE ID='$belagkatID' ");
}

if(isset($_GET['action'])) {
	
	if($_GET['action']=="add") {
	  
    echo'<h1>Belag Kategorie hinzuf&uuml;gen</h1>';

    echo '<form method="post" action="index.php?site=belagkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="belagkatname" size="60" /></td>
      </tr>
      
      <tr>
        <td colspan="2"><input type="submit" name="save" value="Kategorie hinzuf&uuml;gen" /></td>
      </tr>
    </table>
    </form>';
  } 
  elseif($_GET['action']=="edit") {
  		
    $katq = safe_query("SELECT ID,name FROM belagkat WHERE ID=". $_GET["ID"] . "");
    $kat = mysql_fetch_array($katq);
    
    echo'<h1>Belag Kategorie &auml;ndern</h1>';

    echo '<form method="post" action="index.php?site=belagkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $kat["name"] .'" />
        <input type="hidden" name="ID" value="'. $kat["ID"] .'" /></td>
      </tr>
      
      <tr>
        <td colspan="2"><input type="submit" name="saveedit" value="&auml;ndern" /></td>
      </tr>
    </table>
    </form>';

  }
}
else{ // standard aufruf wenn action nicht gesetzt
    
    echo '<a href="?site=belagkat&action=add">Neue Kategorie hinzuf&uuml;gen</a><br /><br /> ';
    
    $katq = safe_query("SELECT ID,name FROM belagkat");
    echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
    while($kat = mysql_fetch_array($katq)){
     echo '<tr>
              <td width="15%">' . $kat["name"] . '</td>
              <td width="5%"><a href="?site=belagkat&action=edit&ID=' . $kat["ID"] . '" class="button"> edit</a></td>
              <td align="left"><a href="?site=belagkat&delete=' . $kat["ID"] . ' " class="button"> delete</a></td>
          </tr>';                          
    }
    echo '</table>';
}
?>