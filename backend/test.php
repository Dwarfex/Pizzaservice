<?php   

if(isset($_POST['edit_size'])) {
	for($i=0; $i<count($_POST['size']); $i++)
    { 
        echo $_POST['produktpreis_ID'][$i] . " size: ";
        echo $_POST['size'][$i] . " preis: ";
        echo $_POST['preis'][$i] . " <br>";
    } 
	
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Mafiatorte" />



<title>Mafiatorte</title>
<!-- <link href="_stylesheet.css" rel="stylesheet" type="text/css" /> -->



</head>
<body>
<?php

 echo '<form method="post" action="test.php" id="post" name="post" enctype="multipart/form-data" ">
       <table width="100%" border="1" cellspacing="1" cellpadding="3">';
      
      $i = 1;
      while($i < 3){
      echo '<tr>
              <td width="15%">
              <input type="hidden" name="produktpreis_ID[]" value="'. $i .'" />
              <select name="size[]" size="1">
                              <option value="4">small</option>
                              <option value="5">medium</option>
                              <option value="6">large</option>
                          
                    </select></td>
                    <td width="85%"><input type="text" name="preis[]" size="10" value="' . $i . '" /> Euro</td>
            </tr>   ';
      $i++;
      }
      
      
      echo '<tr>
        <td colspan="2"><input type="submit" name="edit_size" value="Preis editieren" /></td>
      </tr>
    </table>
    </form>';

?>

</body>
</html>


    
