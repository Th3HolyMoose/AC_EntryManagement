<h2>Heats</h2>
<input type="submit" value="Create heat entry lists (last updated <?php echo intval((time() - filectime("H1_entry_list.ini")) / 60);?> minutes ago)" onclick="httpGetAsync('createHeats.php?p=<?php echo $admin_psw; ?>&avgrid=' + ved.options[ved.selectedIndex].value, updateHeatGenButton);" id="uhgb">
<br><br>
Download: <a href="H1_entry_list.ini" download="H1_entry_list.ini">Heat 1</a>, <a href="H2_entry_list.ini" download="H2_entry_list.ini"> Heat 2</a>, <a href="H3_entry_list.ini" download="H3_entry_list.ini"> Heat 3.</a>

<br>
<input type="submit" onclick="window.open('H_text.txt', '_blank');" value="Get reddit/discord entry list text">

<br><br>


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