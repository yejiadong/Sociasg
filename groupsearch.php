<?php
session_start();

require_once "database/config.php";
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $country = mysqli_real_escape_string($link, $_POST['countrysearch']);
    $interest = mysqli_real_escape_string($link, $_POST['interests']);

    $mysqli = $link;
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $query = $result = "";

    if (!empty(trim($country)) && !empty(trim($interest))) {
        // Prepare to pull data
        $query = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
            Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
            FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id WHERE Groups.country = '" . $country . "' AND Groups.category = '" . $interest . " 'ORDER by group_id";
        $result = $mysqli->query($query);
    } else if (!empty(trim($country))) {
        // Prepare to pull data
        $query = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
            Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
            FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id WHERE Groups.country = '" . $country . "' ORDER by group_id";
        $result = $mysqli->query($query);
    } else if (!empty(trim($interest))) {
        // Prepare to pull data
        $query = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
            Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
            FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id WHERE Groups.category = '" . $interest . "' ORDER by group_id";
        $result = $mysqli->query($query);
    } else {
        // Prepare to pull data
        $query = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
            Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
            FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id ORDER by group_id";
        $result = $mysqli->query($query);
    }
    if ($result) {
        echo '<div class="row groups">';
        while ($row = $result->fetch_object()) {
            $vacancy = ($row->max_cap) - ($row->signed_up_users);
            $bool = ($row->creator_id) == $_SESSION['id'];
            $checkifingroup = "SELECT * FROM GroupMembership WHERE user_id = '" . $_SESSION['id'] . "' AND group_id = '" . $row->group_id . "'";
            $membershipresult = $mysqli->query($checkifingroup);
            $num = 0;
            if ($membershipresult) {
                // it return number of rows in the table. 
                $num = mysqli_num_rows($membershipresult);
            }
            echo '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-3">
                    <div class="item">
                        <div class="card">
                            <div class="card-img">';
            echo '<a href=""><img src ="uploads/' . $row->photo_link . '" alt="" class="img-fluid imgg rounded-top"></a>';
            echo ' <div class="btn-wishlist"></div>
                        </div>
                    <div class="card-body">
                        <div class="">';
            echo '<h3 class="h4"> <a href="" class="text-dark">' . $row->groupname . '</a></h3>';
            echo ' <p class="text-sm font-weight-semi-bold"><i class="mdi mdi-map-marker mr-1"></i>' . $row->country . ' . ' . $row->city . '</p>';
            echo ' <p class="text-sm font-weight-semi-bold"><i class="mdi mdi-account mr-1"></i>' . $row->username . '</p>';
            echo ' </div>
                    <div class="d-flex justify-content-between">
                    <div class="">
                                <span class="text-dark h5">' . $vacancy . '</span><span class="text-sm font-weight-semi-bold ml-1">spots out of ' . $row->max_cap . ' left</span>
                            </div>
                            <div class="">
                                <div class="text-right mt-3">';
            if ($bool == true || $num > 0) {
                echo '<button type="button" id="submit" name="submit" class="mt-3 btn btn-sm btn-primary" disabled>Already in the group!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!--   listing block close   -->
                            </div>
                         </div>';
            } else {
                echo '
                                            <button type="button" id="submit" name="submit" class="mt-3 btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" data-bs-target="#groupinfo" data-bs-whatever="' . $row->groupname . '"
                                            data-bs-groupphoto="' . $row->photo_link . '"
                                            data-bs-country="' . $row->country . '"
                                            data-bs-city="' . $row->city . '"
                                            data-bs-description="' . $row->description . '"
                                            data-bs-vacancy="' . $vacancy . '"
                                            data-bs-max_cap="' . $row->max_cap . '"
                                            data-bs-creator="' . $row->username . '"
                                            data-bs-groupid="' . $row->group_id . '"
                                            >View Group</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <!--   listing block close   -->
                                    </div>
                                 </div>';
            }
        }
    } else {
        echo '<div class="row groups">';
        echo '<p> No groups are available that meets your search criteria :( </p>';
        echo '</div>';
    }
}
