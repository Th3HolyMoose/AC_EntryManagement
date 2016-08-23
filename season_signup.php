<?php


?>

<div style="background-color: #222222; opacity: 1.0; height: 500px;">
    <h1 class="header">Signups for the next season is open!</h1>
    <p class="bread">Season #3 of Conelanders Assetto Corsa League is expected to start 17th of July and has opened it's signups! Please make sure you have read the rules <a href="https://docs.google.com/document/d/10vqTCX3zqAaLKRBSqB0HPHMA4QGnoO9GEOXf1XBDgic/edit?usp=sharing">here</a> before applying.</p>

    <div style="position: relative; left: 35px;">
        <form>
            <p class="bread itt">Your username: </p><input type="text" class="bread it" name="username"><br><br>
            <p class="bread itt">Your Steam GUID (?): </p><input type="text" class="bread it" name="guid"><br><br>
            <p class="bread itt">Your Driver Number: </p>
            <select name="number" class="bread it">
                <option selected="selected" value="-1">Please Select a number</option>

                <?php

                $os = "<option value='";
                $oe = "</option>";

                for($i = 0; $i < 100; $i++) {
                    if(file_exists("drivers/" . $i . ".acd")) continue;
                    echo $os . $i . "'>" . $i . $oe;
                }

                ?>
            </select><br><br>
            <p class="bread itt">A link to your Youtube Channel: </p><input type="text" class="bread it" name="ytc"><br><br>
            <p class="bread itt">Your Discord username: </p><input type="text" class="bread it" name="discord"><br><br>
        </form>
    </div>
</div>

