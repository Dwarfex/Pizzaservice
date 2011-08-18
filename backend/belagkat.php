<?php



if(isset($_GET['delkat'])) {
	$belagkatID = $_GET['belagkatID'];
	
	safe_query("DELETE FROM ".PREFIX."belagkat WHERE ID='$belagkatID'");
	//evtl auch belaege loeschen, die zu der kategorie gehoeren?
	
}

elseif(isset($_POST['savekat'])) {
	$belagkatname = $_POST['belagkatname'];
	
	safe_query("INSERT INTO ".PREFIX."belagkat ( name )
	            values( '$belagkatname' )");
	
}

elseif(isset($_POST['saveeditkat'])) {
	$belagkatname = $_POST['belagkatname'];
	$belagkatID = $_POST['belagkatID'];
	
	safe_query("UPDATE ".PREFIX."belagkat SET name='$belagkatname' WHERE ID='$belagkatID' ");
	
}

if(isset($_GET['action'])) {
	if($_GET['action']=="addkat") {
    
echo'<h1>Belag Kategorie hinzuf&uuml;gen</h1>';
    
    
    
    echo '<form method="post" action="index.php?site=belagkat" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Name der Kategorie</b></td>
        <td width="85%"><input type="text" name="belagkatname" size="60" /></td>
      </tr>
      
      <tr>
        <td colspan="2"><input type="submit" name="savekat" value="Kategorie hinzuf&uuml;gen" /></td>
      </tr>
    </table>
    </form>';
	}

	elseif($_GET['action']=="editcat") {

		$faqcatID = $_GET['faqcatID'];

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
    </form>';
	}
}
?>