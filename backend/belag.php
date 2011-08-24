<?php

if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."belag WHERE ID='$ID'");	
}

elseif(isset($_POST['save'])) {
	$name = $_POST['name'];
	$kat_ID = $_POST['kat_ID'];
	$value = $_POST['value'];
	
	safe_query("INSERT INTO ".PREFIX."belag (name,kat_ID,value)
	            values('$name','$kat_ID','$value')");
	
}

elseif(isset($_POST['saveedit'])) {
	$name = $_POST['name'];
	$kat_ID = $_POST['kat_ID'];
	$value = $_POST['value'];
	$ID = $_POST['ID'];
	
	safe_query("UPDATE ".PREFIX."belag SET name='$name', kat_ID='$kat_ID', value='$value' WHERE ID='$ID' ");
	
}

if(isset($_GET['action'])) {
	if($_GET['action']=="add") {
  /*  $CAPCLASS = new Captcha;
    $CAPCLASS->create_transaction();
    $hash = $CAPCLASS->get_hash();  */

      
    echo '<h1>Belag hinzuf&uuml;gen</h1>
    <form method="post" action="index.php?site=belag" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Belages</b></td>
        <td width="85%"><input type="text" name="name" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM belagkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
       <tr>
        <td width="15%"><b>Value</b></td>
        <td width="85%"><select name="value" size="1">
                         <option value="0">0</option>
                         <option value="1">1</option>
                         <option value="2">2</option>
                         <option value="3">3</option> 
                         </select></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="save" value="Belag hinzuf&uuml;gen" /></td>
      </tr>
    </table>
    </form>';
	}

	elseif($_GET['action']=="edit") {

	 $belagq = mysql_query("SELECT * FROM belag WHERE ID=". $_GET["ID"] . "");
   $belag = mysql_fetch_array($belagq);
  
  echo'<h1>Belag editieren</h1>';

    echo '<form method="post" action="index.php?site=belag" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name des Belages</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $belag["name"] . '" />
        <input type="hidden" name="ID" value="'. $belag["ID"] .'" /></td>
      </tr>             
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="kat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM belagkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $belag["kat_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
       <tr>
        <td width="15%"><b>Value</b></td>
        <td width="85%"><select name="value" size="1">';
                         
                         for($i=0; $i<4; $i++){
                          echo '<option '; 
                          if($i == $belag["value"]) echo " selected ";
                          echo 'value="'. $i .'">'. $i .'</option>';
                         }
                         
                        echo '</select></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="saveedit" value="Belag editieren" /></td>
      </tr>
    </table>
    </form>';
  
  
  
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

  echo '<a href="?site=belag&action=add">Neuen Belag anlegen</a>';
  
  $katq = mysql_query("SELECT * FROM belagkat");
  echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
  while($kat = mysql_fetch_array($katq)){
   echo "<tr>
          <td colspan='4'><h3>" . $kat["name"] . "</h3></td>
        </tr>";
    
                               
    $produktq = mysql_query("
    SELECT * FROM belag
    WHERE kat_ID=" . $kat["ID"] . "");
    while($belag = mysql_fetch_array($produktq)){
      echo '<tr>
              <td width="15%">' . $belag["name"] . '</td>
              <td width="5%">' . $belag["value"] . '</td>
              <td width="5%"><a href="?site=belag&action=edit&ID=' . $belag["ID"] . '"> edit</a></td>
              <td align="left"><a href="?site=belag&delete=' . $belag["ID"] . '"> delete</a></td>
            </tr>';    
    }
  }
  echo '</table>';

}
?>