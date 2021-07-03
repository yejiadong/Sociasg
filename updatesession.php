<?php
// Initialize the session
session_start();
// Include config file
require_once "database/config.php";

$mysqli = $link;
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupid = mysqli_real_escape_string($link, $_POST['groupid']);
    // Prepare to pull data
    $sessionquery = "SELECT Sessions.sess_id, Sessions.group_id, Sessions.host_id, Sessions.session_name, Sessions.session_start, Sessions.session_end, Sessions.description, 
        Sessions.session_max_cap, Sessions.sess_signed_up_users, Users.username 
        FROM Sessions INNER JOIN Users ON Sessions.host_id=Users.id WHERE Sessions.group_id = " . $groupid . " ORDER by Sessions.sess_id";

    $resultsess = $mysqli->query($sessionquery);
    while ($row = $resultsess->fetch_object()) {
        $vacancy = ($row->session_max_cap) - ($row->sess_signed_up_users);
        $bool = ($row->host_id) == $_SESSION['id'];
        $checkifingroup = "SELECT * FROM SessionMembership WHERE user_id = '" . $_SESSION['id'] . "' AND session_id = '" . $row->sess_id . "'";
        $membershipresult = $mysqli->query($checkifingroup);
        $num = 0;
        if ($membershipresult) {
            // it return number of rows in the table.
            $num = mysqli_num_rows($membershipresult);
        }
        echo '
                                        <div class="mt-3 pt-lg-3 pb-lg-3 pt-3 pb-3 pl-3 pr-3 bg-primary rounded-top rounded-bottom counted">
                                            <div class="container">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                                        <div class="mb-2">
                                                            <h3 class="text-white">' . $row->session_name . '</h3>
                                                            <span class="h5 text-white">' . $vacancy . '</span><span class="text-sm font-weight-semi-bold ml-1 text-white">spots out of ' . $row->session_max_cap . ' left</span>
                                                            <p class="text-white text-sm mb-1">' . $row->description . '</p>
                                                            <p class="text-sm font-weight-semi-bold text-white"><i class="mdi mdi-account mr-1"></i>' . $row->username . '</p>

                                                            <span><i class="mdi mdi-clock-start mr-1" style="color: orange"></i><span class="text-white mb-0"><b>' . $row->session_start . '</b></span><br>
                                                            <span><i class="mdi mdi-clock-end mr-1" style="color: orange;"></i><span class="text-white mb-0"><b>' . $row->session_end . '</b></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 d-flex align-items-center">
                                                        <div class="">
                                                        <form class="joinsession">
                                                            <input type="hidden" id="userid" name="userid" class="userid" value="' . $_SESSION["id"] . '">
                                                            <input type="hidden" id="sessionid" name="sessionid" class="sessionid" value="' . $row->sess_id . '">
                                                            <input type="hidden" id="groupid" name="groupid" class="groupid" value="' . $groupid . '">
                                                            ';
        if ($bool == true || $num > 0) {
            echo '<button class="btn btn-white" disabled>You have already joined!</button>';
        } else {
            echo '<input type="submit" class="btn btn-white joinbtn" value="Join Session">';
        }
        echo '
                                                        </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>';
    }
}
