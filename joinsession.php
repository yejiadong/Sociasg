<?php
// Include config file
session_start();
require_once "database/config.php";

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$mysqli = $link;
// Define variables and initialize with empty values
$userid = $groupid = "";
$err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = mysqli_real_escape_string($link, $_POST['userid']);
    $groupid = mysqli_real_escape_string($link, $_POST['groupid']);
    $sessionid = mysqli_real_escape_string($link, $_POST['sessionid']);

    // Validate username
    if (empty(trim($userid)) || empty(trim($sessionid))) {
        echo "An error occurred. Please try again!";
    } else {
        // Prepare a select statement
        $sql = "SELECT * FROM SessionMembership WHERE user_id = ? AND session_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_userid, $param_sessionid);

            // Set parameters
            $param_userid = trim($userid);
            $param_sessionid = trim($sessionid);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    echo "Already joined the session!";
                } else {
                    $userid = trim($userid);
                    $sessionid = trim($sessionid);
                    $sql = "INSERT INTO SessionMembership (user_id, session_id) VALUES (?, ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "ss", $param_userid, $param_sessionid);

                        // Set parameters
                        $param_userid = $userid;
                        $param_sessionid = $sessionid;

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            $sql = "UPDATE Sessions SET sess_signed_up_users = sess_signed_up_users + 1 WHERE sess_id = ?";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "s", $param_sessionid);
                                $param_groupid = $sessionid;
                                if (mysqli_stmt_execute($stmt)) {
                                    //echo the list of sessions here as ajax return
                                    // Prepare to pull data
                                    $sessionquery = "SELECT SessionMembership.user_id, SessionMembership.session_id, Sessions.session_name, Sessions.session_start, Sessions.session_end, Sessions.host_id, Users.username FROM SessionMembership INNER JOIN Sessions ON Sessions.sess_id=SessionMembership.session_id INNER JOIN Users ON Sessions.host_id=Users.id WHERE SessionMembership.user_id = " . $userid . " AND Sessions.group_id =  " . $groupid . " ORDER by Sessions.session_start";

                                    $resultsess = $mysqli->query($sessionquery);
                                    if ($resultsess) {
                                        while ($row = $resultsess->fetch_object()) {
                                            echo '<div class="mt-3 pt-lg-3 pb-lg-1 pt-3 pb-1 pl-1 pr-1 bg-primary rounded-top rounded-bottom counted">
                                                <div class="container">
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                                            <div class="mb-2">
                                                                <h5 class="text-white">' . $row->session_name . '</h5>
                                                                <p class="text-sm font-weight-semi-bold text-white"><i class="mdi mdi-account mr-1"></i>' . $row->username . '</p>
                                                                <span><i class="mdi mdi-clock-start mr-1" style="color: orange"></i><span class="text-sm text-white mb-0"><b>' . $row->session_start . '</b></span><br>
                                                                    <span><i class="mdi mdi-clock-end mr-1" style="color: orange;"></i><span class="text-sm text-white mb-0"><b>' . $row->session_end . '</b></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 d-flex align-items-center">
                                                            <div class="">
                                                                <form id="quitsession">
                                                                    <input type="hidden" id="userid" name="userid" class="userid" value="' . $userid . '">
                                                                    <input type="hidden" id="sessionid" name="sessionid" class="sessionid" value="' . $row->session_id . '">
                                                                    <input type="hidden" id="groupid" name="groupid" class="groupid" value="' . $groupid . '">
                                                                    <button class="btn btn-white">Quit Session</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    } else {
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

        }
    }

    // Close connection
    mysqli_close($link);
}
