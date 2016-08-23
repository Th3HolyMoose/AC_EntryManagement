<?php

include(".info.php");

if(isset($_GET["p"]) && $_GET["p"] == $admin_psw && isset($_GET["avgrid"])) {
  exec("python createHeats.py " . $_GET["avgrid"]);
  echo "Success!";
} else {
  echo "Invalid request.";
}


?>