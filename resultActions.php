<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}

include(".info.php");

if(!isset($_GET["p"]) || $_GET["p"] != $admin_psw) {
		      echo "You're not allowed here!";
		      exit();
}

$a = $_GET["a"];

if($a == "ballast") {
      echo exec("python applyBallasts.py");
      echo "\n\nAll done!";
} else if($a == "feature") {
  echo exec("python create_feat.py " . $_GET["grids"]);
  echo "\n\nAll done!";
}



?>