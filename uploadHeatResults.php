<html><body>
    <?php

    include(".info.php");

    if(!isset($_POST["submit"]) || !(isset($_GET["p"]) && $_GET["p"] == $admin_psw)) {
        echo "You're not allowed here!";
        exit();
    }

    if(isset($_POST["url"])) {

        exec("wget " . $_POST["url"] . " -P results/");

        echo "File downloaded and imported.";

    } else {

        for($i = 0; $i < 1; $i++) {
            $target_dir = "results/";
            $target_file = $target_dir . basename($_FILES["userfile"]["name"][$i]);
            //$target_file = "results/file" . strval($i) . ".json";

            if (move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], $target_file)) {
                echo "The file ". basename( $_FILES["userfile"]["name"][$i]). " has been uploaded.<br>";

            } else {
                echo "A problem occurred during upload... Try again soon.<br>";
                //exit();
            }
        }

        //exec("python create_feat.py");
    }
    ?>
    <a href="manage.php">Return to the management page</a>

    </body></html>
