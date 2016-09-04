<?php
include("../.info.php");
if(!isset($_GET["f"]) || !$season_signup) exit();

$guid = $_GET["f"];
$file = "drivers/" . $guid . ".acd";

if(file_exists($file)) {
    exec("mv " . $file . " signed_up/" . $guid . ".acd");
    echo "Successfully signed up for GT3!";
} else if(file_exists("signed_up/" . $guid . ".acd")) {
    echo "You've already signed up for GT3!";
} else {
    echo "Contact Moose, somethings wrong!";
}


?>