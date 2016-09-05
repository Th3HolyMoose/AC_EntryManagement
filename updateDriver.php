
<?php

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

include 'info.php';

$car = $_GET["car"];
$team = $_GET["team"];
$user = $_GET["name"];
$guid = $_GET["guid"];
$og = $_GET["og"];
$att = isset($_GET["attendance"]) ? (($_GET["attendance"] == "true") ? "y" : "n") : "";
//$yt = $_GET["yt"];
$num = $_GET["number"];
//$ci = $_GET["car"];

//$car = $cars[0];
$ballast = $_GET["ballast"];;

$file = "drivers/" . $guid . ".acd";
if($guid !== $og) {
    rename("drivers/" . $og . ".acd", "drivers/" . $og . ".acd_deleted~");
    echo "The guid was changed and the files have been updated!\n\n";
}
$handle = fopen($file, "w");				   
if(!$handle) {
    echo "Error opening file!";
    exit();
}
$out = "MODEL=" . $car . "\nSKIN=" . $num . "\nSPECTATOR_MODE=0\nDRIVERNAME=" . $user . "\nTEAM=" . $team . "\nGUID=" . $guid . "\nBALLAST=" . $ballast . "\n\n\nATTENDANCE=" . $att . "\n";
fwrite($handle, $out);
fclose($handle);
echo "Wrote driver file successfully: " . $file;
?>
