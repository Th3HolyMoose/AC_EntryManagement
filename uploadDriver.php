<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}


if(!isset($_GET["f"]) || !isset($_GET["att"]) || !$events_signup) exit();

$file = $_GET["f"] . ".acd";
$att = $_GET["att"];


$output = "";
$name = "";

$handle = fopen("drivers/" . $file, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        $ol = $line;
        if(startsWith($line, "ATTENDANCE=")) {
            $ol = "ATTENDANCE=" . $att;
        }
        $output = $output . $ol;
    }
    fclose($handle);
} else {
    // error opening the file.
    echo "There was an error opening your file for reading!";
    exit();
} 
$handle = fopen("drivers/" . $file, "w");
if ($handle) {

    fwrite($handle, $output);
    fclose($handle);

} else {
    echo "There was an error opening your file for writing!";
    exit();
}

echo "Success!";

?>