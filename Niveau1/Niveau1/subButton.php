<?php
$suiveurId = $_SESSION['connected_id'];
echo $userId;

if ($userId != $suiveurId) {
    $selection = "SELECT * FROM followers WHERE followed_user_id = ".$userId." AND following_user_id = ".$suiveurId." LIMIT 1;";
    $ok2 = $mysqli->query($selection);
   // echo $selection;
    //echo "ok2";
    if($ok2) {
        while ($f = $ok2->fetch_assoc()) {
            echo $f;
            if (count($f) > 0) {
                echo "Vous suivez";
            } else {
                echo "Vous suivez pas";
            }
        }
    }else {
        echo "Une erreur";
    }
    ?>
        <form id="follow" method="POST" action="wall.php?user_id=<?php echo $userId ?>">
            <input type="hidden" name="following" value="true" />
            <button style="float:left;" id="follow_btn" class="follow_btn" type="submit" name="follow">Follow</button>
        </form>

<?php
        if ($_POST['following'] == 'true') {
            $follow = "INSERT INTO followers "
                . "(id, followed_user_id, following_user_id) "
                . "VALUES (NULL,"
                . $userId . ",
        " . $suiveurId . ")";
            $ok1 = $mysqli->query($follow);
            if (!$ok1) {
                echo "Impossible de suivre cette abonné: " . $mysqli->error;
            } else {
                echo "C'est bon, tu vas pouvoir stalker:";
            }
        }
    }
 ?>