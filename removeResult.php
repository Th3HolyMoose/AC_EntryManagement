<?php

include('.info.php');

$pass = $_GET["pass"];
$file = $_GET["file"];

if($pass != $admin_psw) {
	 echo "Incorrect password";
	 exit();
}


if(unlink("results/" . $file)) {
		     echo "Delete Success.";
} else {
  echo "Delete failure.";
}

?>