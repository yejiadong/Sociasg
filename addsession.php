<?php
// Include config file
require_once "database/config.php";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Processing form data when form is submitted
    $mysqli = new mysqli("localhost", "root", "", "myapp");
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    $groupid= mysqli_real_escape_string($link,$_POST['groupid']);
    $name = mysqli_real_escape_string($link,$_POST['name']);
    $desc = mysqli_real_escape_string($link,$_POST['desc']);
    $start= date("Y-m-d H:i:s",strtotime(mysqli_real_escape_string($link,$_POST['start'])));
    $end = date("Y-m-d H:i:s",strtotime(mysqli_real_escape_string($link,$_POST['end'])));
    $loc= mysqli_real_escape_string($link,$_POST['location']);
    $creator= mysqli_real_escape_string($link,$_POST['id']);
    $max_cap= mysqli_real_escape_string($link,$_POST['max_cap']);
    $query = "INSERT INTO Sessions (group_id, host_id, session_name, session_start, session_end, description, session_max_cap) VALUES ('".$groupid."','".$creator."', '".$name."', '".$start."', '".$end."', '".$desc."', '".$max_cap."');";
    $result = $mysqli->query($query);
    if ($result) {
        $sessionquery = "SELECT Sessions.sess_id, Sessions.group_id, Sessions.session_name, Sessions.session_start, Sessions.session_end, Sessions.description, 
        Sessions.session_max_cap, Sessions.sess_signed_up_users, Users.username 
        FROM Sessions INNER JOIN Users ON Sessions.host_id=Users.id WHERE Sessions.group_id = " . $groupid . " ORDER by Sessions.sess_id";

        $resultsess = $mysqli->query($sessionquery);
        if ($resultsess) {
            while ( $row = $resultsess->fetch_object() ) {
                $vacancy = ($row->session_max_cap) - ($row->sess_signed_up_users);   
                    echo'
                    <div id="sessions">
                    <div class="mt-3 pt-lg-5 pb-lg-4 pt-5 pb-4 pl-5 pr-5 bg-primary rounded-top rounded-bottom counted">
                        <div class="container">
                            <div class="row d-flex justify-content-between">
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                    <div class="mb-2">
                                        <h3 class="text-white">'.$row->session_name.'</h3>
                                        <span class="text-white h5">' . $vacancy . '</span><span class="text-sm font-weight-semi-bold ml-1 text-white">spots out of '.$row->session_max_cap.' left</span>
                                        <p class="text-white text-sm mb-1">'.$row->description.'</p>
                                        <p class="text-sm font-weight-semi-bold text-white"><i class="mdi mdi-account mr-1"></i>'.$row->username.'</p>

                                        <span><i class="mdi mdi-clock-start mr-1" style="color: orange"></i><span class="text-white mb-0"><b>'.$row->session_start.'</b></span><br>
                                        <span><i class="mdi mdi-clock-end mr-1" style="color: orange;"></i><span class="text-white mb-0"><b>'.$row->session_end.'</b></span>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 d-flex align-items-center">
                                    <div class="">
                                        <button class="btn btn-white">Join Session</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>';
            }
        } else {
            echo "Failure";
        }
    } else {
        echo "Failure";
    }

    
}
?>