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

 $db = array("1","2","3");
 $box = array("2","4");
 
 $insert = array_values(array_diff($box,$db));
 $delete = array_values(array_diff($db,$box));

 
 echo "zum insert: ";
 print_r($insert);
 echo "<br> zum delete: ";
 print_r($delete);

?>

</body>
</html>


    
