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
    $mysqli = new mysqli("localhost", "root", "", "myapp");
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    
    // Prepare to pull data
    $creatorquery = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
    Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
    FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id WHERE Groups.creator_id = " . $_SESSION['id'] . " ORDER by group_id";

    $joinedgroupsquery = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
    Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
    FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id WHERE Groups.creator_id <> " . $_SESSION['id'] . " ORDER by group_id";
    $resultcreator = $mysqli->query($creatorquery);
    $resultjoinedgroup = $mysqli->query($joinedgroupsquery);

    $createdgrps = $joinedgrps = "";

    if ($resultcreator && $resultjoinedgroup) { 
        // it return number of rows in the table. 
        $createdgrps = mysqli_num_rows($resultcreator); 
        $joinedgrps = mysqli_num_rows($resultjoinedgroup); 
    } 

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Find activities with other Singaporeans!">
    <title>Joined Groups</title>
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
</head>

<body>
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
                                                <a class="dropdown-link" href="blog/blog-single.html">
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
        <div class="bg-img-hero" style="background: url(assets/images/hero-slide-img-1.jpg) no-repeat;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 pt-md-4 pb-md-4">
                        <div class="card card-body align-items-center">
                            <h1 class="h2 mb-4">Hi <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>!</h1>
                            <h4> You've created exactly <strong><?php echo htmlspecialchars($createdgrps); ?></strong> groups. </h4>
                            <h4> You've joined exactly <strong> <?php echo htmlspecialchars($joinedgrps); ?> </strong>groups. </h4>
                        </div>
                    </div>
                </div>  
            </div>
        </div>

                <!-- hero section start  -->
                <!-- hero serach area start  -->   
                <?php
                while ( $row = $resultcreator->fetch_object() ) {
                        $vacancy = ($row->max_cap) - ($row->signed_up_users);   
                    echo 
                    '<div class="pt-lg-8 pb-lg-8 pt-7 pb-7 bg-primary">
                    <div class="container">
                        <div class="row d-flex justify-content-between">
                            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                                <div class="mb-2">
                                    <h2 class="text-white">'.$row->groupname.'</h2>
                                    <p class="text-white mb-0">'.$row->description.'</p>
                                    <p class="text-white text-sm font-weight-semi-bold mt-2"><i class="mdi mdi-map-marker mr-1"></i>'.$row->country.' . '.$row->city.'</p>
                                    <p class="text-white text-sm font-weight-semi-bold mt-2"><i class="mdi mdi-account mr-1"></i>'.$row->username.'</p>
                                    <span class="text-white h5">' . $vacancy . '</span><span class="text-white ext-sm font-weight-semi-bold ml-1">spots out of ' . $row->max_cap . ' left</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 d-flex align-items-center">
                                <div class="">
                                    <form action="group.php" method="POST">
                                    <input type="hidden" id="groupname" name="groupname" value='.$row->groupname.'>
                                    <input type="hidden" id="description" name="description" value='.$row->description.'>
                                    <input type="hidden" id="country" name="country" value='.$row->country.'>
                                    <input type="hidden" id="city" name="city" value='.$row->city.'>
                                    <input type="hidden" id="creator" name="creator" value='.$row->username.'>
                                    <input type="hidden" id="vacancy" name="vacancy" value='.$vacancy.'>
                                    <input type="hidden" id="max_cap" name="max_cap" value='.$row->max_cap.'>
                                    <input type="hidden" id="groupid" name="groupid" value='.$row->group_id.'>
                                    <input type="hidden" id="photo" name="photo" value='.$row->photo_link.'>
                                    <input type="submit" class="btn btn-white" value="Go to Group">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>';
                        
                }

                while ( $row = $resultjoinedgroup->fetch_object() ) {
                    $vacancy = ($row->max_cap) - ($row->signed_up_users);   
                echo 
                '<div class="pt-lg-8 pb-lg-8 pt-7 pb-7 bg-primary">
                <div class="container">
                    <div class="row d-flex justify-content-between">
                        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                            <div class="mb-2">
                                <h2 class="text-white">'. $row->groupname .'</h2>
                                <p class="text-white mb-0">'. $row->description .'</p>
                                <p class="text-white text-sm font-weight-semi-bold"><i class="mdi mdi-map-marker mr-1"></i>'.$row->country.' . '.$row->city.'</p>
                                <p class="text-white text-sm font-weight-semi-bold"><i class="mdi mdi-account mr-1"></i>'.$row->username.'</p>
                                <span class="text-white h5">' . $vacancy . '</span><span class="text-white text-sm font-weight-semi-bold ml-1">spots out of ' . $row->max_cap . ' left</span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 d-flex align-items-center">
                            <div class="">
                                <form action="group.php" method="POST">
                                <input type="hidden" id="groupname" name="groupname" value='.$row->groupname.'>
                                <input type="hidden" id="country" name="country" value='.$row->country.'>
                                <input type="hidden" id="city" name="city" value='.$row->city.'>
                                <input type="hidden" id="creator" name="creator" value='.$row->username.'>
                                <input type="hidden" id="vacancy" name="vacancy" value='.$vacancy.'>
                                <input type="hidden" id="max_cap" name="max_cap" value='.$row->max_cap.'>
                                <input type="hidden" id="groupid" name="groupid" value='.$row->group_id.'>
                                <input type="hidden" id="photo" name="photo" value='.$row->photo_link.'>
                                <input type="submit" class="btn btn-white" value="Go to Group">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>';
                }
                        
                ?>
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