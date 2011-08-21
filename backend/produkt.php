<?php

if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."produkt WHERE ID='$ID'");
	//evtl auch belaege loeschen, die zu der kategorie gehoeren?
	
}

elseif(isset($_POST['save'])) {
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$kat_ID = $_POST['kat_ID'];
		
	safe_query("INSERT INTO ".PREFIX."produkt (name,comment,kat_ID)
	            values('$name','$comment','$kat_ID')");
	
  $_GET['ID'] = mysql_insert_id();    
  $ID = $_GET['ID'];                //Wird an action['edit'] übergeben
   
  // Die ProduktPreis Tabelle wird mit den jeweiligen Größen der Produktkategorie gefüllt 
  $sizeq = mysql_query("SELECT size FROM size WHERE produktkat_ID='$kat_ID'");
  while($size = mysql_fetch_array($sizeq)){                
    $foo = $size['size'];
    safe_query("INSERT INTO ".PREFIX."produktpreis (produkt_ID,size)
	            values('$ID','$foo')");                         
  }  
}

elseif(isset($_POST['edit_basic'])) {
	$name = $_POST['name'];
	$comment = $_POST['comment'];
  $kat_ID = $_POST['kat_ID'];
	$ID = $_POST['ID'];
	
	safe_query("UPDATE ".PREFIX."produkt SET name='$name', comment='$comment', kat_ID='$kat_ID' WHERE ID='$ID' ");
	
}

elseif(isset($_POST['save_preis'])) {
  $produkt_ID = $_POST['produkt_ID'];
  $size = $_POST['size'];
  $preis = $_POST['preis'];

  safe_query("INSERT INTO ".PREFIX."produktpreis (produkt_ID,size,preis)
	            values('$produkt_ID','$size','$preis')");
} 


elseif(isset($_POST['edit_preis'])) {
	
	for($i=0; $i<count($_POST['size']); $i++)
  { 
	   $produktpreis_ID = $_POST['produktpreis_ID'][$i];
     $size = $_POST['size'][$i];
     $preis = $_POST['preis'][$i];
    
    safe_query("UPDATE ".PREFIX."produktpreis SET preis='$preis', size='$size' WHERE ID='$produktpreis_ID' ");
	}
}

elseif(isset($_GET['delete_preis'])) {
	$ID = $_GET['delete_preis'];
	
	safe_query("DELETE FROM ".PREFIX."produktpreis WHERE ID='$ID'");	
}

elseif(isset($_POST['edit_belag'])) {
	
	// heeeeeeeeeeeeeelp :D
}


if(isset($_GET['action'])) {
	if($_GET['action']=="add") {
  /*  $CAPCLASS = new Captcha;
    $CAPCLASS->create_transaction();
    $hash = $CAPCLASS->get_hash();  */

      
    echo '<h1>Produkt hinzuf&uuml;gen</h1>
    <form method="post" action="index.php?site=produkt&action=edit" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Produktes</b></td>
        <td width="85%"><input type="text" name="name" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr> 
      <tr>
        <td colspan="2"><input type="submit" name="save" value="weiter" /></td>
      </tr>
    </table>
    </form>';
	}

	elseif($_GET['action']=="edit") {

	 $produktq = mysql_query("SELECT * FROM produkt WHERE ID=". $_GET["ID"] . "");
   $produkt = mysql_fetch_array($produktq);
  
  echo'<h1>Produkt editieren</h1>';

    echo '<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="1" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Produktes</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $produkt["name"] . '" />
        <input type="hidden" name="ID" value="'. $produkt["ID"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" value="'. $produkt["comment"] .'" /></td>
      </tr>             
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $produkt["kat_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
       <tr>
        <td colspan="2"><input type="submit" name="edit_basic" value="Basics editieren" /></td>
      </tr>
    </table>
    </form>';
      
      echo'<h1>Preis editieren</h1>';
      echo '<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
       <table width="100%" border="1" cellspacing="1" cellpadding="3">';
      
      $produktpreisq = mysql_query("SELECT * FROM produktpreis WHERE produkt_ID=" . $produkt["ID"] . "");
      while($produktpreis = mysql_fetch_array($produktpreisq)){
        echo '<tr>
              <td width="15%">
              <input type="hidden" name="produktpreis_ID[]" value="'. $produktpreis['ID'] .'" />
              <select name="size[]" size="1">';
        
                          $sizeq = mysql_query("SELECT size,name FROM size");
                          while($size = mysql_fetch_array($sizeq)){                
                          
                            echo '<option'; 
                            if($size["size"] == $produktpreis["size"]) echo  " selected ";
                            echo ' value='. $size["size"] . '>'. $size["name"] . '</option>'; 
                          }
                    echo '</select></td>
                    <td width="15%"><input type="text" name="preis[]" size="10" value="'. $produktpreis["preis"] .'" /> Euro</td>
                    <td width="70%"><a href="?site=produkt&action=edit&ID='. $produkt["ID"] . '&delete_preis=' . $produktpreis["ID"] . '"> delete</a></td>
            
            </tr>   ';
      }
      echo' <tr>
        <td colspan="3"><input type="submit" name="edit_preis" value="Preis editieren" /></td>
      </tr>
    </table>
    </form>';
      
      echo'<h3>Preis hinzuf&uuml;gen</h3>';
      echo '<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
       <table width="100%" border="1" cellspacing="1" cellpadding="3">
         <tr>
            <td width="15%">
              <input type="hidden" name="produkt_ID" value="'. $produkt['ID'] .'" />
              <select name="size" size="1">';
        
                          $sizeq = mysql_query("SELECT size,name FROM size");
                          while($size = mysql_fetch_array($sizeq)){                
                          
                            echo '<option value='. $size["size"] . '>'. $size["name"] . '</option>'; 
                          }
                    echo '</select></td>
                    <td width="15%"><input type="text" name="preis" size="10" /> Euro</td>
                    <td width="70%"><input type="submit" name="save_preis" value="hinzuf&uuml;gen" /></td>
            </tr>       
    </table>
    </form>';
    
    echo '<h1>Zutaten</h1>';
    $katq = mysql_query("SELECT * FROM belagkat");
   echo '<form method="post" action="index.php?site=produkt&action=edit&ID=' . $_GET['ID'] . '" id="post" name="post" enctype="multipart/form-data" ">
         <table width="100%" border="0" cellspacing="1" cellpadding="3">
         <input type="hidden" name="produkt_ID" value="'. $produkt['ID'] .'" />';
   while($kat = mysql_fetch_array($katq)){
     echo "<tr>
            <td><h3>" . $kat["name"] . "</h3></td>
          </tr>";
                             
      $produktq = mysql_query("SELECT * FROM belag WHERE kat_ID=" . $kat["ID"] . "");
      while($belag = mysql_fetch_array($produktq)){
        echo '<tr>
                <td><input type="checkbox" name="belag_ID[]" value="'. $belag['ID'] .'">' . $belag["name"] . '</td>
              </tr>';    
      }
  }
  echo' <tr>
        <td><input type="submit" name="edit_belag" value="edit" /></td>
      </tr>
    </table>
    </form>';
      
      
      /*echo '<form method="post" action="index.php?site=produkt" id="post" name="post" enctype="multipart/form-data" ">
       <table width="100%" border="1" cellspacing="1" cellpadding="3">';
      
      $produktsizeq = mysql_query("SELECT ID,size FROM produktpreis WHERE produkt_ID=" . $produkt["ID"] . "");
      while($produktsize = mysql_fetch_array($produktsizeq)){
      echo '<tr>
              <td width="15%"><b>Gr&ouml;sse</b></td>
              <td width="85%"><select name="size" size="1">';
        
                          $sizeq = mysql_query("SELECT size,name FROM size");
                          while($size = mysql_fetch_array($sizeq)){                
                          
                            echo '<option'; 
                            if($size["size"] == $produktsize["size"]) echo  " selected ";
                            echo ' value='. $size["size"] . '>'. $size["name"] . '</option>'; 
                          }
                    echo '</select></td>
            </tr>   ';
      }
      
      $produktpreisq = mysql_query("SELECT ID,preis FROM produktpreis WHERE produkt_ID=" . $produkt["ID"] . "");
  
      while($produktpreis = mysql_fetch_array($produktpreisq)){
      echo '<tr>
              <td width="15%"><b>Preis</b></td>
              <td width="85%"><input type="text" name="comment" size="60" value="'. $produktpreis["preis"] .'" /></td>
            </tr>';
      }  
       
      echo '<tr>
        <td colspan="2"><input type="submit" name="edit_size" value="Gr&ouml;ssen editieren" /></td>
      </tr>
    </table>
    </form>'; */
  
  
  
  /*	$faqcatID = $_GET['faqcatID'];

		$ergebnis=safe_query("SELECT * FROM ".PREFIX."faq_categories WHERE faqcatID='$faqcatID'");
		$ds=mysql_fetch_array($ergebnis);
    
    $CAPCLASS = new Captcha;
    $CAPCLASS->create_transaction();
    $hash = $CAPCLASS->get_hash();
    
    $_language->read_module('bbcode', true);
    
    eval ("\$addbbcode = \"".gettemplate("addbbcode", "html", "admin")."\";");
    eval ("\$addflags = \"".gettemplate("flags_admin", "html", "admin")."\";");
    
    echo'<h1>&curren; <a href="admincenter.php?site=faqcategories" class="white">'.$_language->module['faq_categories'].'</a> &raquo; '.$_language->module['edit_category'].'</h1>';

    echo '<script language="JavaScript" type="text/javascript">
					<!--
						function chkFormular() {
							if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
								return false;
							}
						}
					-->
				</script>';
    
    echo '<form method="post" action="admincenter.php?site=faqcategories" id="post" name="post" onsubmit="return chkFormular();">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>'.$_language->module['category_name'].'</b></td>
        <td width="85%"><input type="text" name="faqcatname" size="60" value="'.htmlspecialchars($ds['faqcatname']).'" /></td>
      </tr>
      <tr>
        <td colspan="2"><b>'.$_language->module['description'].'</b>
	        <table width="99%" border="0" cellspacing="0" cellpadding="0">
			      <tr>
			        <td valign="top">'.$addbbcode.'</td>
			        <td valign="top">'.$addflags.'</td>
			      </tr>
			    </table>
	        <br /><textarea id="message" rows="10" cols="" name="message" style="width: 100%;">'.htmlspecialchars($ds['description']).'</textarea>
	      </td>
      </tr>
      <tr>
        <td colspan="2"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="faqcatID" value="'.$faqcatID.'" /><input type="submit" name="saveeditcat" value="'.$_language->module['edit_category'].'" /></td>
      </tr>
    </table>
    </form>'; */
	}
}
else{ // standard auflistung aller Beläge bei keiner action

  echo '<a href="?site=produkt&action=add">Neuen Produkt anlegen</a>';
  
  $katq = mysql_query("SELECT * FROM produktkat");
  echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
  while($kat = mysql_fetch_array($katq)){
   echo "<tr>
          <td colspan='4'><h3>" . $kat["name"] . "</h3></td>
        </tr>";
    
                               
    $produktq = mysql_query("
    SELECT * FROM produkt
    WHERE kat_ID=" . $kat["ID"] . "");
    while($produkt = mysql_fetch_array($produktq)){
      echo '<tr>
              <td width="15%">' . $produkt["name"] . '</td>
              
              <td width="5%"><a href="?site=produkt&action=edit&ID=' . $produkt["ID"] . '"> edit</a></td>
              <td align="left"><a href="?site=produkt&delete=' . $produkt["ID"] . '"> delete</a></td>
            </tr>';    
    }
  }
  echo '</table>';

}
?>