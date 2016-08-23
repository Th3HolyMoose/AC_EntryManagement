<html>
    <body>

        <?php
        function startsWith($haystack, $needle) {
            // search backwards starting from haystack length characters from the end
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
        }



        include 'info.php';

        $user = $_POST["user"];
        $guid = $_POST["guid"];
        $att = $_POST["attendance"];
        //$yt = $_POST["yt"];
        $num = $_POST["number"];
        $ci = $_POST["car"];

        $car = $cars[$ci];
        $ballast = $ballast_start[$ci];

        $file = "drivers/" . $guid . ".acd";

        if (file_exists($file)) {
            $handle = fopen($file, "r");
            if($handle) {
                while(($line = fgets($handle)) !== false) {
                    if(startsWith($line, "BALLAST")) {
                        if(strlen($line) <= 8) break;
                        $ballast = substr($line, 8);
                        break;
                    }
                }
                fclose($handle);
            }
        } else {
            echo "<h1>You're not signed up for this season!</h1><h3> (Is this incorrect? Contact /u/Th3HolyMoose, preferably as a PM on Discord or Reddit!)</h3>";
            exit();
        }



        $handle = fopen($file, "w");				   
        if(!$handle) {
            echo "Error opening file!";
            exit();
        }
        $out = "MODEL=" . $car . "\nSKIN=" . $num . "\nSPECTATOR_MODE=0\nDRIVERNAME=" . $user . "\nTEAM=\nGUID=" . $guid . "\nBALLAST=" . $ballast . "\n\n\nATTENDANCE=" . $att . "\n";
        fwrite($handle, $out);
        fclose($handle);
        echo "Wrote " + $file;





        ?>

        <h1>Signup complete, here you can review your info</h1>
        <a href="signup.php">You can always go back and change your info if anything is incorrect or if your plans change!</a>
        <br><br><br>
        <?php echo str_replace("\n", "<br>", $out); ?>

    </body>
</html>
