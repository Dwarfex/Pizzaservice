<?php

if(isset($_GET['delete'])) {
	$produktkatID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."produktkat WHERE ID='$produktkatID'");
	//evtl auch belaege loeschen, die zu der kategorie gehoeren?
	
}

elseif(isset($_POST['save'])) {
	$produktkatname = $_POST['produktkatname'];
	if(isset($_POST['top_ID'])){
	  $top_ID = $_POST['top_ID'];
    safe_query("INSERT INTO ".PREFIX."produktkat ( name, top_ID )
	            values( '$produktkatname', '$top_ID' )");
  }
	else{
     safe_query("INSERT INTO ".PREFIX."produktkat ( name )
	            values( '$produktkatname' )");
  }
}

elseif(isset($_POST['saveedit'])) {
	$produktkatname = $_POST['name'];
	$produktkatID = $_POST['ID'];
	
	if(isset($_POST['top_ID'])){
	  $top_ID = $_POST['top_ID'];
    safe_query("UPDATE ".PREFIX."produktkat SET name='$produktkatname', top_ID='$top_ID' WHERE ID='$produktkatID' ");
  }
	else{
    safe_query("UPDATE ".PREFIX."produktkat SET name='$produktkatname' WHERE ID='$produktkatID' ");
  }
}

if(isset($_GET['action'])) {
	
	if($_GET['action']=="add") {
	  
    echo'<h1>Produkt Kategorie hinzuf&uuml;gen</h1>';

    echo '<form method="post" action="index.php?site=produktkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="produktkatname" size="60" /></td>
      </tr>
       <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"> <select name="top_ID" size="3">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td> 
      </tr>
      <tr>
        <td colspan="2"><input type="submit" name="save" value="Kategorie hinzuf&uuml;gen" /></td>
      </tr>
    </table>
    </form>';
    
   
    
  } 
  elseif($_GET['action']=="edit") {
  		
    $produktkatq = mysql_query("SELECT * FROM produktkat WHERE ID=". $_GET["ID"] . "");
    $produktkat = mysql_fetch_array($produktkatq);
    
    echo'<h1>Produkt Kategorie &auml;ndern</h1>';

    echo '<form method="post" action="index.php?site=produktkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $produktkat["name"] .'" />
        <input type="hidden" name="ID" value="'. $produktkat["ID"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Topkategorie</b></td>
        <td width="85%"><select name="top_ID" size="3">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $produktkat["top_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
      
      <tr>
        <td colspan="2"><input type="submit" name="saveedit" value="&auml;ndern" /></td>
      </tr>
    </table>
    </form>';
    
    
    
    
    /*$faqcatID = $_GET['faqcatID'];

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
else{ // standard aufruf wenn action nicht gesetzt
    
    echo '<a href="?site=produktkat&action=add">Neue Kategorie hinzuf&uuml;gen</a>';
    
    $katq = mysql_query("SELECT * FROM produktkat");
    echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
    while($kat = mysql_fetch_array($katq)){
     echo '<tr>
              <td width="15%">' . $kat["name"] . '</td>
              <td width="5%"><a href="?site=produktkat&action=edit&ID=' . $kat["ID"] . '"> edit</a></td>
              <td align="left"><a href="?site=produktkat&delete=' . $kat["ID"] . '"> delete</a></td>
          </tr>';                          
    }
    echo '</table>';
}
?>