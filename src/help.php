<?php


if(DEBUG == "ON") {
	if(isset($_GET['help'])) {
		switch($_GET['help']) {

			case "phpinfoplease":
				die(phpinfo());
				break;
			

		}
	}
}

?>