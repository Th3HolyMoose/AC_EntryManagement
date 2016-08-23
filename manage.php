<?php
$open = true;
include '.info.php';

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function spitEntryList($file) {
    $c = 0;
    $handle = fopen($file, "r");
    if($handle) {
        $ball = 0;
        while(($line = fgets($handle)) !== false) {
            if(startsWith($line, "DRIVERNAME=")) {
                //echo " (" . $ball;
                //if (strlen($ball) == 1) echo "&nbsp;&nbsp;";
                //if (strlen($ball) == 2) echo "&nbsp;";
                //echo " kg): ";
                echo substr($line, 11);
                echo "<br>";
                $c += 1;
            } else if(startsWith($line, "BALLAST=")) {
                //$ball = substr($line, 8);
            }
        }
        fclose($handle);
    }
    echo "<br>";
    echo "<h4>Total: " . $c . "</h4>";
    return $c;
}

?>


<html>
    <head>

    </head>

    <script type="text/javascript">
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
        function updateHeatGenButton(text) {
            document.getElementById("uhgb").value = "Heat entry lists update status: " + text;
            console.log("UHGB Response: " + text);
            location.reload();
        }

    </script>

    <body>



        <h1>Conelanders Assetto Corsa League Signup Management</h1>

        <?php

        $pass = "";
        $incorrectManagePass = false;
        if(!isset($_POST["p"])) {
            //echo "poopface";
            include 'manage_signin.php';
            exit();
        }

        if(isset($_POST["p"])) $pass = $_POST["p"];

        if($pass != $admin_psw) {
            echo("Incorrect password!");
            $incorrectManagePass = true;
            include 'manage_signin.php';
            exit();
        }



        ?>

        <br><br><br><br><br>


        <br><br><br>

        <br>
        <h2>Available grid slots:</h2>
        <select id="av_grid" onchange="saveAvGrids(this)">
            <option selected="selected" value="8">8</option>
            <?php

            $os = "<option value='";
            $oe = "</option>";

            for($i = 9; $i < 65; $i++) {
                echo $os . $i . "'>" . $i . $oe;
            }

            ?>
        </select>

        <script type="text/javascript">

            var ved = document.getElementById("av_grid");
            var vvv = localStorage.getItem("av_grid");
            if(vvv) {
                ved.selectedIndex = vvv;
            }

            function saveAvGrids(e) {
                localStorage.setItem("av_grid", e.selectedIndex);
            }

        </script>

        <br>

        <h2>Heats</h2>
        <input type="submit" value="Create heat entry lists (last updated <?php echo intval((time() - filectime("H1_entry_list.ini")) / 60);?> minutes ago)" onclick="httpGetAsync('createHeats.php?p=<?php echo $admin_psw; ?>&avgrid=' + ved.options[ved.selectedIndex].value, updateHeatGenButton);" id="uhgb">
        <br><br>
        Download: <a href="H1_entry_list.ini" download="H1_entry_list.ini">Heat 1</a>, <a href="H2_entry_list.ini" download="H2_entry_list.ini"> Heat 2</a>, <a href="H3_entry_list.ini" download="H3_entry_list.ini"> Heat 3.</a>

        <br>
        <input type="submit" onclick="window.open('H_text.txt', '_blank');" value="Get reddit/discord entry list text">

        <br><br>

        <div style="width: 70%; display: inline-block; float: left;">
            <div style="float: left; display: inline-block; width: 30%; background-color: #DFDFDF;">
                <h3>Heat 1 Entry List:</h3>
                <?php
                $c1 = spitEntryList("H1_entry_list.ini");
                ?>

            </div>
            <div style="float: left; display: inline-block; width: 30%; background-color: #DFDFDF;">
                <h3>Heat 2 Entry List:</h3>
                <?php
                $c2 = spitEntryList("H2_entry_list.ini");
                ?>

            </div>
            <div style="display: inline-block; width: 30%; background-color: #DFDFDF;">
                <h3>Heat 3 Entry List</h3>
                <?php
                $c3 = spitEntryList("H3_entry_list.ini");
                ?>
            </div>
            
            <?php
            $minC = min(min($c1, $c2), $c3);
            $maxC = max(max($c1, $c2), $c3);
            for($i = 0; $i < ($maxC - $minC); $i++) {
                echo "<br>";
            }
            ?>
            
            <br><br>
            <div>
                <!--
                <h3>How to use the downloaded entry lists:</h3>
                Open up acServerManager and set the settings for the track, passwords and what-not. Also set the "Maximum clients allowed" to the number of people participating in the upcoming heat. Just add any cars without specific info to fill up the slots.<br><br>
                Select "Export" in the bottom part of the window and export the files into .../*AcServerDirectory*/cfg/. When done, open up that directory and remove the file called "entry_list" (.ini). Then copy the downloaded entry list file and paste it into that directory, and rename it to "entry_list" (needs .ini extension).<br><br>
                Now the server is ready to go, and launch it by double-clicking "acServer.exe" in your AC server directory rather than through the acServerManager application. It's the exact same process, should be no differences.<br><br>

                When doing the next heat, the only neccessary change is replacing the cfg/entry_list.ini file with the new one, and updating the "max clients allowed" part, which can be done with your text editor in the file cfg/server_cfg.ini.<br><br>
            -->
            </div>

            
            <br>
            
            <div style="background-color: #DFDFDF;">
                Download feature: <a href="entry_list.ini" download="entry_list.ini">Main Entry List (<?php echo intval((time() - filectime("entry_list.ini")) / 60); ?> minutes old)</a>, 
                <a href="entry_list_2.ini" download="entry_list_2.ini">Secondary Entry List (<?php echo intval((time() - filectime("entry_list_2.ini")) / 60); ?> minutes old)</a>
                <h3>Currently uploaded files</h3>
                <?php
                $fentry = '<a href="results/%name%" target="_blank">%name%</a>  <input type="submit" onclick="removeResult(\'%name%\');" value="Remove file">';

                $files = scandir("results/");
                sort($files);

                for($i = 0; $i < count($files); $i++)
                {
                    if(endsWith($files[$i], ".json")) {
                        echo str_replace("%name%", $files[$i], $fentry) . "<br>";
                    }
                }

                ?>
                <script type="text/javascript">
                    function alertCallback(resp) {
                        alert("Server says: " + resp);
                        window.location = window.location + "";
                    }
                    function removeResult(name)
                    {
                        if(confirm("Are you sure you want to permanently delete " + name + "?")) {
                            httpGetAsync("removeResult.php?file=" + encodeURIComponent(name) + "&pass=<?php echo $admin_psw; ?>", alertCallback);
                        } else {

                        }
                    }

                </script>

                <h3>File actions</h3>

                <input type="submit" onclick="if(confirm('Are you sure you want to apply ballasts? It will apply using all results in the files above!')) {httpGetAsync('resultActions.php?p=<?php echo $admin_psw; ?>&a=ballast', alertCallback);}" value="Apply Ballasts">
                ---
                <input type="submit" onclick="httpGetAsync('resultActions.php?p=<?php echo $admin_psw; ?>&a=feature&grids=' + document.getElementById('av_grid').options[document.getElementById('av_grid').selectedIndex].value, alertCallback);" value="Create Feature">


                <br>
                <h3>Upload Heat results</h3>
                <form action="uploadHeatResults.php?p=<?php echo $admin_psw; ?>" method="post" enctype="multipart/form-data">
                    Upload heat race results file (.json): <br />
                    <input name="userfile[]" type="file" /><br />
                    <input type="submit" value="Upload Result" name="submit">
                </form>
                <br><br>
                <form action="uploadHeatResults.php?p=<?php echo $admin_psw; ?>" method="post" enctype="multipart/form-data">
                    Import heat race results from URL:
                    <input name="url" type="text">
                    <input type="submit" value="Import from URL" name="submit">
                </form>
            </div>

            <br><br>

            <div style="display: inline-block; float: left; width: 50%; background-color: #DFDFDF;">
                <h3>Main Feature Entry List</h3>
                <?php
                $c1 = spitEntryList("entry_list.ini");
                ?>
            </div>
            <div style="display: inline-block; width: 50%; background-color: #DFDFDF;">
                <h3>Second Feature Entry List</h3>
                <?php
                $c2 = spitEntryList("entry_list_2.ini");
                ?>
            </div>

            <?php
            
            for($i = 0; $i < (max($c1, $c2) - min($c1, $c2)); $i++) {
                echo "<br>";
            }
            ?>

            <br><br><br>
            
            <div style="background-color: #DFDFDF;">
                <h2>Server Configuration</h2>
                
                <h3>Upload server_cfg.ini</h3>
                <form action="uploadServerCFG.php?p=<?php echo $admin_psw; ?>" method="post" enctype="multipart/form-data">
                    <input name="userfile[]" type="file" /><br />
                    <input type="submit" value="Upload cfg" name="submit">
                </form>
                <br><br>
                <form action="uploadServerCFG.php?p=<?php echo $admin_psw; ?>&entry=H2" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Load Heat 2 and Start Server" name="submit">
                </form>
                <br><br>
                <form action="uploadServerCFG.php?p=<?php echo $admin_psw; ?>&entry=F2" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Load B Feature and Start Server" name="submit">
                </form>
                <br>
                <br><br>
                <h3>Currently uploaded cfg</h3>
                <?php
                $handle = fopen("preset_cfg.ini", "r");
                if($handle) {
                    while(($line = fgets($handle)) !== false) {
                        echo $line . "<br>";
                    }
                }
                fclose($handle);
                ?>
                
            </div>
        
            
        </div>
        
        



        <div style="display: inline-block; background-color: #BBBBBB; width: 25%; position: absolute; left: 75%;">

            <?php

            //include 'event_info.php';
            //getList();

            echo '<h2 style="position: relative; left: 8px;" id="dlh">Signed up drivers</h2>';

            echo '<div style="position: relative; left: 12px;">';

            echo "2016 technology advancements: ALPHABETICAL SORTING";

            $acd = array();
            $names = array();
            $guids = array();
            $cont = array();

            $files = scandir("drivers/");

            for($i = 0; $i < count($files); $i++) {
                if(endsWith($files[$i], ".acd")) {
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
                        if(startsWith($line, "DRIVERNAME=")) {
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



            if(true) {
                $dc = 0;
                $dy = 0;
                for ($i = 0; $i < count($fc); $i++) {
                    if(true) {

                        $data = '<div style="background-color: %color%;"><form id="%formID%"><h2 style="display: inline;">%driver%</h2><a target="_blank" href="http://steamcommunity.com/profiles/%guid%">(Steam)</a><br><p style="display: inline;">Username</p><input style="display: inline; right: %inputAlign%; position: absolute;" type="text" name="user" id="user" value="%driver%"><br><p style="display: inline;">GUID</p><input style="display: inline; right: %inputAlign%; position: absolute;" type="text" name="guid" id="guid" value="%guid%"><br><p style="display: inline;">Car number</p><input style="display: inline; right: %inputAlign%; position: absolute;" type="text" name="number" id="numb" value="%number%"><br><p style="display: inline;">Attending</p><input type="checkbox" name="att" style="display: inline; position: relative; left: 32px;" value="y" %check%><br><p style="display: inline;">Ballast</p><input style="display: inline; right: %inputAlign%; position: absolute;" type="text" name="ballast" id="ballast" value="%ballast%"></form><input type="submit" onclick="driverSubmit(%formID%, %guid%)" value="Save" style="display: inline;"><br></div>';
                        $formID = $dc + 0;

                        $dc += 1;
                        $no = 0;
                        $data = str_replace("%inputAlign%", "100px", $data);
                        $data = str_replace("right: ", "left: ", $data);
                        $guid = "";
                        //echo "yp";
                        $exp = explode("\n", $fc[$i]);
                        for($j = 0; $j < count($exp); $j++) {
                            $line = $exp[$j];
                            if(startsWith($line, "DRIVERNAME=")) {
                                $data = str_replace("%driver%", substr($line,11), $data);
                            } else if (startsWith($line, "BALLAST=")) {
                                $data = str_replace("%ballast%", substr($line,8), $data);
                            } else if (startsWith($line, "GUID=")) {
                                //$data = str_replace("%guid%", substr($line,5), $data);
                                $guid = substr($line,5);
                            } else if (startsWith($line, "ATTENDANCE=")) {
                                $no = 0;
                                
                                if(strlen($line) >= 12) {
                                    $s = substr($line,11);
                                    if($line[11] == "y") { $s = "checked"; $dy += 1; } else if($line[11] == "n") { $s = ""; $no = 1; } else {$no = 2; $s = ""; }
                                } else {
                                    $s = "";
                                    $no = 2;    
                                }
                                $data = str_replace("%check%", $s, $data);
                            } else if (startsWith($line, "SKIN=")) {
                                $data = str_replace("%number%", substr($line, 5), $data);
                            }
                        }
                        $color = "#BEBEBE";
                        if($dc % 2 == 0) $color = "#DFDFDF";
                        if($no == 1) $color = "#F57878";
                        if($no == 2) $color = "#DBE07B";
                        $data = str_replace("%color%", $color, $data);
                        $data = str_replace("%formID%", "driverForm_" . $formID . "_" . $guid, $data);
                        $data = str_replace("%guid%", $guid, $data);
                        echo $data;
                    }
                }
            }

            echo '<script type="text/javascript">';

            echo 'document.getElementById("dlh").innerHTML += " (Yes: ' . $dy . ", out of " . $dc . ')";';

            echo '</script>';

            ?>
            <br>
            <input type="submit" value="Set all drivers to 'not attending'" onclick="resetAtt()">

            <script type="text/javascript">

                var dr = true;
                var sendAtt = true;
                function resetAtt() {
                    if(!confirm("Are you sure? This will reset everyones attending status!")) return;
                    console.log("Resetting attendance...");
                    //return;
                    dr = false;
                    sendAtt = false;
                    var forms = document.getElementsByTagName("form");

                    for(var i = 0; i < forms.length; i++) {
                        if(forms[i].id.startsWith("driverForm_")) {
                            forms[i].att.checked = false;
                            driverSubmit(forms[i], forms[i].guid.value);
                        }
                    }
                    sendAtt = true;
                    dr = true;
                }

                function replySubmit(msg) {
                    if(dr == true) {alert("Update response: " + msg);} else {console.log(msg);}
                }

                function driverSubmit(form, og) {
                    //var form = document.getElementById(fid);
                    //console.log(form);
                    //console.log(fid);
                    og = form.id.split("_")[2];
                    //console.log(og);

                    var url = "updateDriver.php?p=<?php echo $admin_psw; ?>";
                    url += "&name=" + form.user.value;
                    url += "&guid=" + form.guid.value;
                    url += "&og=" + og;
                    url += "&ballast=" + form.ballast.value;
                    url += "&number=" + form.number.value;
                    if(sendAtt) url += "&attendance=" + form.att.checked;

                    console.log(url);
                    httpGetAsync(url, replySubmit);
                }
            </script>
        </div>
        </div>
    </body>
</html>
