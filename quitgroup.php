<?php
// Include config file
require_once "database/config.php";
session_start();
$mysqli = $link;
// Define variables and initialize with empty values
$userid = $groupid = "";
$err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = mysqli_real_escape_string($link, $_POST['userid']);
    $groupid = mysqli_real_escape_string($link, $_POST['groupid']);

    // Validate username
    if (empty(trim($userid)) || empty(trim($groupid))) {
        $err = "An error occurred. Please try again!";
    } 

    // Check input errors before inserting in database
    if (empty($err)) {
        // Prepare an insert statement
        $results = $mysqli->multi_query("DELETE w FROM SessionMembership w INNER JOIN Sessions s ON w.session_id = s.sess_id WHERE w.user_id = ".$userid." AND s.group_id = ".$groupid."; DELETE w FROM SessionMembership w INNER JOIN Sessions s ON w.session_id = s.sess_id WHERE s.host_id = ".$userid." AND s.group_id = ".$groupid."; DELETE FROM Sessions WHERE host_id = ".$userid." AND group_id = ".$groupid."; DELETE FROM GroupMembership WHERE user_id = ".$userid." AND group_id = ".$groupid."; UPDATE Groups SET signed_up_users = signed_up_users - 1 WHERE group_id = ".$groupid."; ");
        while ($mysqli->next_result()) {;} // flush multi_queries
        $query = "UPDATE Sessions INNER JOIN SessionMembership ON Sessions.sess_id=SessionMembership.session_id SET Sessions.sess_signed_up_users = Sessions.sess_signed_up_users - 1 WHERE Sessions.group_id = ".$groupid." AND SessionMembership.user_id=".$userid."";
        $selectsessions = $mysqli->query($query);
        if ($selectsessions && $results) {
            header("location: joinedgroups.php");
        } else {
            echo "Failed to quit group!";
           
        }
    }

    // Close connection
    mysqli_close($link);
}
