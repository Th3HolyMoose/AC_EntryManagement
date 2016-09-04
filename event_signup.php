<?php

include(".info.php");
//if(!$events_signup) exit();

function es_endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
function esSW($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}


$guids = array();
$numbers = array();
$acd = array();

$files = scandir("drivers/");

for($i = 0; $i < count($files); $i++) {
    if(es_endsWith($files[$i], ".acd")) {
        array_push($acd, $files[$i]);
    }
}

for($i = 0; $i < count($acd); $i++) {
    $handle = fopen("drivers/" . $acd[$i], "r");
    array_push($numbers, "-1");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            // process the line read.

            if(esSW($line, "SKIN=")) {
                $numbers[$i] = substr($line,5);
                $numbers[$i] = explode("\n", $numbers[$i])[0];
                if (strlen($numbers[$i]) == 1) {
                    $numbers[$i] = "0" . $numbers[$i];
                }
            }
            if(esSW($line, "DRIVERNAME=")) {
                $numbers[$i] = $numbers[$i] . " - " . explode("\n", substr($line,11))[0];
            }
        }

        fclose($handle);
    } else {
        // error opening the file.
    } 
}

$sorted = $numbers;//->getArrayCopy();
sort($sorted);

$fn = array();
$fa = array();

for($i = 0; $i < count($sorted); $i++) {
    for($j = 0; $j < count($sorted); $j++) {
        if($sorted[$i] == $numbers[$j]) {
            array_push($fn, $numbers[$j]);
            array_push($fa, substr($acd[$j], 0, 17));
            break;
        }
    }
}


?>

<div style="background-color: #222222; opacity: 1.0; height: 500px;">
    <?php
    if($events_signup == false) {
        echo '<h1 class="header">Event Signups are CLOSED!</h1>';
        exit();
    }
    ?>
    <h1 class="header">Signups for the coming event!</h1>
    <p class="bread">You MUST have signed up and been accepted for the actual season to sign up for an event! If you wish to join mid-season, talk to ConeDodger240!</p>

    <div style="position: relative; left: 35px;">
        <form id="form" onsubmit="return false;">
            <p class="bread itt">Your Driver Number: </p>
            <select name="number" class="bread it" id="nms">
                <option selected="selected" value="-1">Please Select your number</option>
                <?php

                $os = "<option value='";
                $oe = "</option>";

                for($i = 0; $i < count($fa); $i++) {
                    echo $os . $fa[$i] . "'>" . $fn[$i] . $oe;
                }

                ?>
            </select><br><br>

            <p class="bread itt">Will you be in attendance?</p>
            <select class="bread it" name="attendance" id="ats" onchange="enable(this);">
                <option selected="selected" value="-1">Select Attendance</option>
                <option value="y">I am attending</option>
                <option value="n">I am NOT attending</option>
            </select>
            <br>
            <br>
            <br>
            <input class="bread it" onclick="save();" type="submit" id="send" disabled="true">
        </form>
        <br>
        <p class="bread">(Is a number for a driver incorrect? Message Th3HolyMoose ASAP!)</p>

        <script type="text/javascript">

            function validate()
            {
                var e = document.getElementById("nms");
                return e.selectedIndex > 0;
            }

            function httpGetAsync(theUrl, callback)
            {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                        callback(xmlHttp.responseText);
                }
                xmlHttp.open("GET", theUrl, true); // true for asynchronous
                xmlHttp.send(null);
            }
            function scall(resp)
            {
                alert("You've successfully updated your attending status!\n(" + resp + ")");
                //document.location.href = "index.php";
            }

            function submitDriver(e)
            {
                if(!validate()) {
                    alert("You must select a number!");
                    return;
                }
                var k = document.getElementById("ats");
                var url = "uploadDriver.php?f=" + e.options[e.selectedIndex].value + "&att=" + k.options[k.selectedIndex].value;
                //console.log(url);
                httpGetAsync(url, scall);


            }


            function sc(key, val) {

                localStorage.setItem(key, val);
            }
            function g(key) { return localStorage.getItem(key); }
            function onLoad() {
                var e = document.getElementById("nms");
                var v = g("number");
                console.log(v);
                for(var i = 0; i < e.options.length; i++) {
                    if(v == e.options[i].value) {
                        e.selectedIndex = i;
                        break;
                    }
                }



            }
            function save() {




                sc("number", document.getElementById("nms").options[document.getElementById("nms").selectedIndex].value);
                //sc("yt", form.yt.checked ? "1" : "0");
                submitDriver(document.getElementById("nms"));
            }
            function enable(e)
            {
                document.getElementById("send").disabled = e.selectedIndex == 0;
            }
            onLoad();
        </script>

        <br>



    </div>

    <div style="background-color: #222222; opacity: 1.0;">

        <h2 class="header">Signed up drivers</h2>

        <?php

        $acd = array();
        $names = array();
        $guids = array();
        $cont = array();

        $files = scandir("drivers/");

        for($i = 0; $i < count($files); $i++) {
            if(es_endsWith($files[$i], ".acd")) {
                array_push($acd, $files[$i]);
            }
        }

        for($i = 0; $i < count($acd); $i++) {
            $handle = fopen("drivers/" . $acd[$i], "r");
            array_push($names, "");
            $fc = "";
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    // process the line read.
                    $fc = $fc . $line . "\n";
                    if(esSW($line, "DRIVERNAME=")) {
                        $names[$i] = strtolower(substr($line,11)); 
                    }
                }

                fclose($handle);
            } else {
                // error opening the file.
            }
            array_push($cont, $fc);
        }
        $sorted = $names;//->getArrayCopy();
        sort($sorted);

        $fn = array();
        $fa = array();
        $fc = array();

        for($i = 0; $i < count($sorted); $i++) {
            for($j = 0; $j < count($sorted); $j++) {
                if($sorted[$i] == $names[$j]) {
                    array_push($fn, $names[$j]);
                    array_push($fa, substr($acd[$j], 0, 17));
                    array_push($fc, $cont[$j]);
                    break;
                }
            }
        }





        $ns = '<p class="bread" style="text-decoration: underline; font-size: 24px; margin-bottom: -12px; color: %color%;">';
        $nt = '<p class="bread" style="font-size: 16px; margin-bottom: -12px;">';
        $nc = '<p class="bread" style="font-size: 20px; margin-bottom: -12px; color: %color%;">';

        $attY = 0;
        $attN = 0;
        $attU = 0;
        $attT = 0;

        for($i = 0; $i < count($fn); $i++) {
            $lines = explode("\n", $fc[$i]);
            for($j = 0; $j < count($lines); $j++) {
                $line = $lines[$j];
                if(esSW($line, "ATTENDANCE=")) {
                    $att = substr($line, 11);
                    $attT++;
                    if(strlen($att) == 0) $attU++;
                    if($att == "y") $attY++;
                    if($att == "n") $attN++;
                }
            }

        }

        $us = " / " . $attT;

        echo str_replace("%color%", "#FFFFFF", $ns) . "Attendance Status: " . "</p>";
        echo str_replace("%color%", "#00FF00", $nc) . "Coming: " . $attY . $us . "</p>";
        echo str_replace("%color%", "#FF0000", $nc) . "Not coming: " . $attN . $us . "</p>";
        echo str_replace("%color%", "#FFFF00", $nc) . "Not reported: " . $attU . $us . "</p>";


        echo "<br><br>";
        for($i = 0; $i < count($fn); $i++) {
            $name = $fn[$i];
            $guid = $fa[$i];


            $ball = "N/A";
            $numb = "N/A";
            $color = "#FFFFFF";
            $lines = explode("\n", $fc[$i]);
            for($j = 0; $j < count($lines); $j++) {
                $line = $lines[$j];
                if(esSW($line, "BALLAST=")) {
                    $ball = substr($line, 8) . " kg";
                } else if(esSW($line, "SKIN=")) {
                    $numb = substr($line, 5);
                } else if(esSW($line, "ATTENDANCE=")) {
                    $att = substr($line, 11);

                    if(strlen($att) == 0) $color = "#FFFF00";
                    if($att == "y") $color = "#00FF00";
                    if($att == "n") $color = "#FF0000";
                }
            }

            echo str_replace("%color%", $color, $ns) . $name . "</p>";
            echo $nt . "Number: " . $numb . "</p>";
            echo $nt . "Ballast: " . $ball . "</p><br>";
        }



        ?>

        <br><br><br><br>
    </div>
</div>

