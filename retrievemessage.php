<?php
session_start();

require_once "database/config.php";
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupid = trim(mysqli_real_escape_string($link, $_POST['groupid']));

    $mysqli = $link;
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $query = $result = "";

    if (!empty($groupid)) {
        $chatquery = "SELECT Groupchats.user_id, Groupchats.chat_msg, Groupchats.chat_date, Users.username, Users.usr_photo_link
                        FROM Groupchats INNER JOIN Users ON Groupchats.user_id=Users.id WHERE Groupchats.chat_group_id = '" . $groupid . "' ORDER by Groupchats.chat_date";
        $chatresult = $mysqli->query($chatquery);
        if ($chatresult) {
            while ($chatrow = $chatresult->fetch_object()) {
                echo '<div class="mt-2 pt-2 pl-2 pr-2 bg-light border rounded">
                    <img src="profilepics/' . $chatrow->usr_photo_link . '" alt="" class="avatar-xs rounded-circle mr-2">
                    <span class="text-sm text-dark font-weight-semi-bold"><b>' . $chatrow->username . ' - (' . $chatrow->chat_date . ')</b></span>
                    <p class="text-dark ml-7"> ' . $chatrow->chat_msg . '</p>
                    </div>';
            }
        } else {
            echo "An error occurred";
        }
    } else {
        echo "An error occurred";
    }
}
