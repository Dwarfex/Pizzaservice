<?php

if(isset($_GET['delete'])) {
	$ID = $_GET['delete'];
	
	safe_query("DELETE FROM ".PREFIX."size WHERE size='$ID'");
}

elseif(isset($_POST['save'])) {
	$name = $_POST['name'];
	$produktkat_ID = $_POST['produktkat_ID'];
	$comment = $_POST['comment'];
	
	safe_query("INSERT INTO ".PREFIX."size ( name,produktkat_ID,comment )
	            values( '$name','$produktkat_ID','$comment' )");
	
}

elseif(isset($_POST['saveedit'])) {
	$name = $_POST['name'];
	$comment = $_POST['comment'];
  $produktkat_ID = $_POST['produktkat_ID'];
  $ID = $_POST['ID'];
	
	safe_query("UPDATE ".PREFIX."size SET produktkat_ID='$produktkat_ID', name='$name', comment='$comment' WHERE size='$ID' ");
}

if(isset($_GET['action'])) {
	
	if($_GET['action']=="add") {
	  
    echo'<h1>Gr&ouml;sse hinzuf&uuml;gen</h1>';

    echo '<form method="post" action="index.php?site=size" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="produktkat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                            echo '<option value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
      <tr>
        <td width="15%"><b>Name</b></td>
        <td width="85%"><input type="text" name="name" size="60" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" /></td>
      </tr>
      
      <tr>
        <td colspan="2"><input type="submit" name="save" value="Gr&ouml;sse hinzuf&uuml;gen" /></td>
      </tr>
    </table>
    </form>';
  } 
  elseif($_GET['action']=="edit") {
  		
    $sizeq = mysql_query("SELECT * FROM size WHERE size=". $_GET["ID"] . "");
    $size = mysql_fetch_array($sizeq);
    
    echo'<h1>Gr&ouml;sse &auml;ndern</h1>';

    echo '<form method="post" action="index.php?site=size" id="post" name="post" enctype="multipart/form-data" ">
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="15%"><b>Kategorie</b></td>
        <td width="85%"><select name="produktkat_ID" size="1">';
        
                          $katq = mysql_query("SELECT ID,name FROM produktkat");
                          while($kat = mysql_fetch_array($katq)){                
                          
                            echo '<option'; 
                            if($kat["ID"] == $size["produktkat_ID"]) echo  " selected ";
                            echo ' value='. $kat["ID"] . '>'. $kat["name"] . '</option>'; 
                          }
                    echo '</select></td>
       </tr>
      <tr>
        <td width="15%"><b>Name der Gr&ouml;sse</b></td>
        <td width="85%"><input type="text" name="name" size="60" value="'. $size["name"] .'" />
        <input type="hidden" name="ID" value="'. $size["size"] .'" /></td>
      </tr>
      <tr>
        <td width="15%"><b>Comment</b></td>
        <td width="85%"><input type="text" name="comment" size="60" value="'. $size["comment"] .'"/></td>
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
    
    echo '<a href="?site=size&action=add">Neue Gr&ouml;sse hinzuf&uuml;gen</a>';
    
    $katq = mysql_query("SELECT * FROM produktkat");
    echo '<table width="100%" border="0" cellspacing="1" cellpadding="3">';
    while($kat = mysql_fetch_array($katq)){
    echo "<tr>
          <td colspan='4'><h3>" . $kat["name"] . "</h3></td>
        </tr>";
    
                               
    $sizeq = mysql_query("
    SELECT * FROM size
    WHERE produktkat_ID=" . $kat["ID"] . "");
    while($size = mysql_fetch_array($sizeq)){
      echo '<tr>
              <td width="15%">' . $size["name"] . '</td>
              <td width="5%"><a href="?site=size&action=edit&ID=' . $size["size"] . '"> edit</a></td>
              <td align="left"><a href="?site=size&delete=' . $size["size"] . '"> delete</a></td>
          </tr>';      
    }
  }
  echo '</table>';
}
?>