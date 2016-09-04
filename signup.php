<?php
include(".info.php");
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body class="content">
        <div class="content">
            <div class="links">
                <p class="lll">
                    <a target="_blank" class="ll" href="http://farlandsorbust.com">Far Lands or Bust</a>, 
                    <a target="_blank" href="<?php echo $rulesLink; ?>" class="ll">Rules</a>, 
                    <a target="_blank" class="ll" href="<?php echo $pointsLink; ?>">Djomps Points</a>, 
                    <a target="_blank" class="ll" href="https://www.youtube.com/user/kurtjmac">Kurt's Youtube Channel</a>,
                    <a target="_blank" class="ll" href="https://www.youtube.com/user/ConeDodger240">ConeDodger240's Youtube Channel</a>,
                    <a target="_blank" class="ll" href="https://www.reddit.com/r/Conelanders/">Conelanders Reddit</a>


                </p>
            </div>

            <div class="titleDiv">
                <img src="logo.png"></img>
        </div>


        <div class="text">
            <div class="contents">
                <h1 class="large"><?php echo $leagueName; ?> Signups</h1>

                <?php
                if($season_signup == true) {
                    include("season_signup.php");
                }
                if($events_signup == true) {
                    
                }
                include("event_signup.php");
                //exit();

                ?>
            </div>
        </div>
        </div>
    </body>

</html>
