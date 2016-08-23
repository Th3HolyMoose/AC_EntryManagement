<html><body>
    <?php

    include(".info.php");

    if(!isset($_POST["submit"]) || !(isset($_GET["p"]) && $_GET["p"] == $admin_psw)) {
        echo "You're not allowed here!";
        exit();
    }
    if(isset($_GET["entry"])) {
        $e = $_GET["entry"];
        exec("sh /conelanders/ac_loadcfg.sh " . $e);
        exit();
    } else {
        for($i = 0; $i < 1; $i++) {
            $target_dir = "";
            $target_file = $target_dir . "preset_cfg.ini";//basename($_FILES["userfile"]["name"][$i]);
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], $target_file)) {
                echo "The file ". basename( $_FILES["userfile"]["name"][$i]). " has been uploaded.<br>";
            } else {
                echo "A problem occurred during upload... Try again soon.<br>";
                //exit();
            }
        }
    }
       
    ?>
    <a href="manage.php">Return to the management page</a>
    
    </body></html>
