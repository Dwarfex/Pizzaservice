<?php

session_name('mafiakorb');
session_start();

//Wenn Erster Kundenbesuch, wird bei abgabe der Bestellung ein Cookie gesetzt
if(!isset($_COOKIE['kunde_ID'])){
  if(isset($_GET['site'])){
    if($_GET['site'] == 'order'){
      if(isset($_POST['send'])){
        $t = time() + 60 * 60 * 24 * 365 * 10; // Cookie ist 10 Jahre haltbar  
		
		// ein 10 - Jahres Cookie ?! WTF? ;)
        setcookie("kunde_ID", $_SESSION['ID'], $t);
      }
    }
  }
}

?>