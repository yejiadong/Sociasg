<?php
    // Initialize the session
    session_start();
    // Include config file
    require_once "database/config.php";
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php");
        exit;
    }

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
        $groupname = mysqli_real_escape_string($link,$_POST['groupname']);
        $country= mysqli_real_escape_string($link,$_POST['country']);
        $city = mysqli_real_escape_string($link,$_POST['city']);
        $description = mysqli_real_escape_string($link,$_POST['description']);
        $creator= mysqli_real_escape_string($link,$_POST['creator']);
        $vacancy= mysqli_real_escape_string($link,$_POST['vacancy']);
        $max_cap= mysqli_real_escape_string($link,$_POST['max_cap']);
        $photo= mysqli_real_escape_string($link,$_POST['photo']);

        $groupquery = "SELECT description FROM Groups WHERE group_id = " . $groupid."";
        $groupresult = $mysqli->query($groupquery);


        // Prepare to pull data
        $sessionquery = "SELECT Sessions.sess_id, Sessions.group_id, Sessions.session_name, Sessions.session_start, Sessions.session_end, Sessions.description, 
        Sessions.session_max_cap, Sessions.sess_signed_up_users, Users.username 
        FROM Sessions INNER JOIN Users ON Sessions.host_id=Users.id WHERE Sessions.group_id = " . $groupid . " ORDER by Sessions.sess_id";

        $resultsess = $mysqli->query($sessionquery);

        if ($resultsess ) { 
            // it return number of rows in the table. 
            $numsess = mysqli_num_rows($resultsess); 
        } 
    }
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Find activities with other Singaporeans!">
    <title>Group</title>
     <link rel="shortcut icon" href="assets/images/smalllogo.png" type="image/x-icon"> 
    <!-- Bootstrap CSS -->    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

     <!-- Theme CSS -->
     <link rel="stylesheet" href="./assets/css/theme.min.css">

     <style>

         .welcomelogo {
             width: 170px;
             height: 50px;
         }

         .imgg {
            width: 100%; /* You can set the dimensions to whatever you want */
            height: 330px;
            object-fit: cover;
        }

     </style>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
    $(function () {

        $('#addsession').on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'addsession.php',
            data: $('#addsession').serialize(),
            success: function (data) {
                $("#successaddsession").modal("show");
                $('#sessions').replaceWith(data);
                var numdiv = $("div[class*='counted']").length;
                $('.replace').text(numdiv);
                $('html, body').animate({ scrollTop: 0 }, 0);
            }
        });

        });

    });
    </script>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="successaddsession" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Add Session Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>You've successfully added the session!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    <div class="main-wrapper">
        <!-- header start -->
        <div class="header-classic">
            <!-- navigation start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <nav class="navbar navbar-expand-lg navbar-classic">
                            <a class="navbar-brand" href="welcome.php"> <img src="assets/images/logo 4.png" class="welcomelogo" alt=""></a>
                            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar-classic" aria-controls="navbar-classic" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="icon-bar top-bar mt-0"></span>
                                <span class="icon-bar middle-bar"></span>
                                <span class="icon-bar bottom-bar"></span>
                            </button>
                            <h6 class="h6 ml-5 mt-2">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome!</h1>
                            <div class="collapse navbar-collapse" id="navbar-classic">
                                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 mr-3">
                                    <li class="nav-item ">
                                        <a class="nav-link" href="welcome.php">
                                            Homepage
                                        </a>
                                        
                                    </li>
                                   
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="menu-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Groups </a>
                                        <ul class="dropdown-menu" aria-labelledby="menu-4">
                                            <li class="dropdown-item">
                                                <a class="dropdown-link" href="joinedgroups.php">
                                                    Groups Joined</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="nav-item ">
                                        <a class="nav-link" href="logout.php">
                                            Logout
                                        </a>
                                        
                                    </li>
                                 
                                </ul>
                                <div class="header-btn">
                                    <a href="create.php" class="btn btn-primary">Create a Group</a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- navigation close -->
        </div>
        <!-- header close -->
        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7">
            <div class="row">
                    <!-- section heading start  -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="mb-3 text-center ml-10">
                            <h2 class="h3"><?php echo $groupname; ?></h2>
                            <span class="text-dark h5"><?php echo $vacancy; ?></span><span class="text-sm font-weight-semi-bold ml-1">spots out of <?php echo $max_cap; ?> left</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="mb-3 ml-11">
                            <h2 class="h3">Available Sessions</h2>
                            <span class="text-dark h5 ml-7 replace"><?php echo $numsess; ?></span><span class="text-sm font-weight-semi-bold ml-1">sessions available</span>
                        </div>
                    </div>
                    <!-- section heading close  -->
                </div>
            <div class="container">
                <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <img src ="uploads/<?php echo $photo; ?>" alt="" class="img-fluid imgg rounded-top rounded-bottom">
                                <div class="media align-items-center mt-3">
                                        <img src="assets/images/avatar-1.jpg" alt="" class="avatar-xs rounded-circle mr-2">
                                        <span class="text-sm text-dark font-weight-semi-bold"><?php echo $creator; ?></span>
                                        <p class="mt-3 ml-10 text-sm font-weight-semi-bold"><i class="mdi mdi-map-marker mr-1"></i><?php echo $country; ?> . <?php echo $city; ?></p>  
                                </div>     
                                <p class="mb-2 text-sm text-dark"><?php while ($row = $groupresult->fetch_assoc()) {
                                                                        echo $row['description']."<br>";
                                                                    }?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                        <div id="sessions">
                            <?php
                                while ( $row = $resultsess->fetch_object() ) {
                                    $vacancy = ($row->session_max_cap) - ($row->sess_signed_up_users);   
                                        echo'
                                        <div class="mt-3 pt-lg-5 pb-lg-4 pt-5 pb-4 pl-5 pr-5 bg-primary rounded-top rounded-bottom counted">
                                            <div class="container">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                                        <div class="mb-2">
                                                            <h3 class="text-white">'.$row->session_name.'</h3>
                                                            <span class="h5 text-white">' . $vacancy . '</span><span class="text-sm font-weight-semi-bold ml-1 text-white">spots out of '.$row->session_max_cap.' left</span>
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
                        ?>
                        </div>
                    </div>          
            </div>
        </div>
        </div>
        </div>
                </div>
            </div>
        </div>
        <div class="pt-lg-8 pb-lg-8 pt-7 pb-7 bg-primary">
            <div class="container">
                <div class="row d-flex justify-content-between text-center">
                            <h2 class="text-white">Add Session</h2>
                            <p class="text-white mb-2">Make sure the information is accurate.</p>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="mb-2">
                            <form id="addsession">
                            <input type="hidden" id="groupid" name="groupid" class="groupid" value=<?php echo $groupid; ?>>
                            <input type="hidden" id="id" name="id" class="id" value=<?php echo $_SESSION['id']; ?>>
                            <div class="form-group mt-3">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Session Name" required>
                            </div>
                            <div class="form-group mt-3">
                                    <input type="text" id="desc" name="desc" class="form-control" placeholder="A Short Description" required>
                            </div>
                            <div class="form-group mt-3 text-left">
                                <p class="text-white mb-2">Start datetime:</p>
                                <input type="datetime-local" id="start" name="start" class="form-control" placeholder="Start Datetime" required>
                            </div>
                            </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="mb-2">
                            <div class="form-group mt-3">
                                    <input type="text" id="location" name="location" class="form-control" placeholder="Specify the Location" required>
                            </div>
                            <div class="form-group">
                                <input type="number" id="max_cap" name="max_cap" class="form-control" placeholder="Max Participants (Default of 100 participants)">
                            </div>
                            <div class="form-group mt-3 text-left">
                                    <p class="text-white mb-2">End datetime:</p>
                                    <input type="datetime-local" id="end" name="end" class="form-control" placeholder="End Datetime" required>
                            </div>
                            <input type="submit" name="submit" id="submit" class="btn btn-primary btn-white ml-15 mt-3" value="Add Session">
                            </div>
                        
                    </div>
                    </form>
                </div>
            </div>
            
        </div>
        <!-- footer section start -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 col-12">
                        <!-- footer widget  -->
                        <div class="footer-widget">
                            <h3 class="footer-widget-title">Explore Sociasg</h3>
                            <div class="footer-links">
                                <ul class="list-group list-unstyled">
                                    <li class="list-group-item"><a href="create.php" class="list-group-link">Create a Group</a></li>
                                    <li class="list-group-item"><a href="joinedgroups.php" class="list-group-link">View your Groups</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Trust & Safety</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-6 col-6">
                        <div class="footer-widget">
                            <h3 class="footer-widget-title">Company</h3>
                            <div class="footer-links">
                                <ul class="list-group list-unstyled">
                                    <li class="list-group-item"><a href="#" class="list-group-link">About us</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Help Center</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Press</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- footer widget  -->
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                        <div class="footer-widget">
                            <h3 class="footer-widget-title">Social Media</h3>
                            <div class="footer-links">
                                <ul class="list-group list-unstyled">
                                    <li class="list-group-item"><a href="#" class="list-group-link">Facebook</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Twitter</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Linkedin</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Google</a></li>
                                    <li class="list-group-item"><a href="#" class="list-group-link">Instagram</a></li>
                                </ul>
                            </div>
                            <!-- footer widget  -->
                        </div>
                    </div>
                    <!-- footer widget  -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="footer-widget">
                            <h3 class="footer-widget-title">Subscribe to newsletter ?</h3>
                            <p>Get updates for your groups and activities! </p>
                            <form>
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Enter Your Email" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2"><span class="mdi mdi-send"></span></span>
                                    </div>
                                </div>
                            </form>
                            <!-- footer widget  -->
                        </div>
                    </div>
                </div>
                <!-- tiny footer  -->
                <div class="tiny-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                                <p class="tiny-footer-text">© 2021 Jiadong. Template Adapted from Rentkit</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer section close  -->
            </div>
            <!-- footer section close -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Libs JS -->
        <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <!-- <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
        <script src="./assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="./assets/libs/select2/dist/js/select2.min.js"></script>         
        <script src="./assets/libs/moment/min/moment.min.js"></script>    
        <script src="./assets/libs/lightpick/lightpick.js"></script> 

        <!-- Theme JS -->
        <script src="./assets/js/theme.min.js"></script>