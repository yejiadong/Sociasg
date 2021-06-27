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

    // Define variables and initialize with empty values
    $country = $city = $max_cap = $photo = $title = $description = $category = "";
    $groupname_err = $country_err = $photo_err = $description_err = $cat_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $title = mysqli_real_escape_string($link,$_POST['title']);
        $country= mysqli_real_escape_string($link,$_POST['country']);
        $city = mysqli_real_escape_string($link,$_POST['city']);
        $description = mysqli_real_escape_string($link,$_POST['description']);
        $category= mysqli_real_escape_string($link,$_POST['category']);
        $max_cap= mysqli_real_escape_string($link,$_POST['max_cap']);

        // File upload path
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

    // Validate groupname
    if (empty(trim($title))) {
        $groupname_err = "Please enter a title for the group.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($title))){
        $groupname_err = "Title can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT group_id FROM Groups WHERE groupname = ? AND country = ? AND city = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_groupname, $param_country, $param_city);
            
            // Set parameters
            $param_groupname = trim($title);
            $param_country = trim($country);
            $param_city = trim($city);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $groupname_err = "This groupname is already taken in the specified country and city.";
                } else{
                    $title = trim($title);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    }
    // Validate fields not empty
    if(empty(trim($country))){
        $country_err = "Please select a country!";     
    } else{
        $country= trim($country);
    }

    if(empty(trim($city))){
        $city_err = "Please enter a city!";     
    } else{
        $city= trim($city);
    }

    if(empty(trim($description))){
        $description_err = "Please enter some details!";     
    } else{
        $description= trim($description);
    }

    if(empty(trim($category))){
        $cat_err = "Please select a category!";     
    } else{
        $category= trim($category);
    }

    if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
        echo "'<script>console.log(\"$country\")</script>'";
        // Allow certain file formats
        $allowTypes = array('jpg','png','jpeg');
        if(in_array($fileType, $allowTypes)){
            // Upload file to server
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                // Insert image file name into database
                $sql = "INSERT into Groups (groupname, creator_id, country, city, max_cap, description, photo_link, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ssssssss", $param_groupname, $param_creatorid, $param_country, $param_city, $param_maxcap, $param_description, $param_photo_link, $param_cat);
                    
                    // Set parameters
                    $param_groupname = $title;
                    $param_creatorid = $_SESSION["id"];
                    $param_country = $country;
                    $param_city = $city;
                    $param_maxcap = $max_cap;
                    $param_description = $description;
                    $param_photo_link = $fileName;
                    $param_cat= $category;
        
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Redirect to login page
                        header("location: welcome.php");
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
        
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }else{
                $photo_err = "Sorry, there was an error uploading your file.";
            }
        }else{
            $photo_err = 'Sorry, only JPG, JPEG, PNG files are allowed to upload.';
        }
    } else{
        $photo_err = "Please upload a group photo!";  
    }
    // Close connection
    mysqli_close($link);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Find activities with other Singaporeans!">
    <title>Create Group</title>
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

         .fileupload {
             margin-left:20px;
         }

         .warning {
             font-size:14px;
             margin-top:10px;
             text-align:center;
         }

        [hidden] {
            display: none !important;
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
                            <h6 class="h6 ml-5 mt-2">Logged in as <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
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
                <!-- hero section start  -->
                <!-- hero serach area start  -->
                <form id="createform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 pt-md-4 pb-md-4">
                            <div class="card card-body">
                                <h1 class="h2 mb-4">Create a Group</h1>
                                <select name="country" id="country" class="select2 form-control custom-select <?php echo (!empty($country_err)) ? 'is-invalid' : ''; ?>">
                                    <option value="" disabled selected>Country this is held:</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Åland Islands">Åland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-bissau">Guinea-bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-leste">Timor-leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Viet Nam">Viet Nam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $country_err; ?></span>
                                <div class="form-group mt-3">
                                    <input type="text" id="city" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" placeholder="Specify the City" required>
                                    <span class="invalid-feedback"><?php echo $city_err; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="number" id="max_cap" name="max_cap" class="form-control" placeholder="Max Participants">
                                    <p class = "h6 warning">If you do not choose this, a default of 1000 participants will be used.</p>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 pt-md-4 pb-md-4">
                            <div class="card card-body">
                                <h1 class="h5 mb-4">Provide some important information</h1>
                                <div class="form-group">
                                <label for="group"><strong>Upload a nice photo!</strong></label>
                                <label class="btn btn-primary fileupload">
                                    Browse <input type="file" class="form-control-file" id="file" name="file" accept="image/*" onchange="loadFile(event)" hidden>
                                </label>
                                <span class="invalid-feedback"><?php echo $photo_err; ?></span>
                                 <!-- <input type="file" class="form-control-file btn" id="groupphoto" name="groupphoto" accept="image/*" onchange="loadFile(event)"> -->
                                 </div>
                                 <img id="output"/>
                                    <script>
                                    var loadFile = function(event) {
                                        var output = document.getElementById('output');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                        URL.revokeObjectURL(output.src) // free memory
                                        }
                                    };
                                    </script>
                                <div class="form-group">
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Provide a Catchy Title" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="description" form="createform" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" id="description" rows="3" placeholder="Enter a short description / details of the activity." required></textarea>
                                    <span class="invalid-feedback"><?php echo $description_err; ?></span>
                                </div>
                                <input type = "text" name="category" id="category" class="form-control <?php echo (!empty($cat_err)) ? 'is-invalid' : ''; ?>" placeholder="Specify a Category">
                                <span class="invalid-feedback"><?php echo $cat_err; ?></span>
                                <div class="text-right mt-3">
                                    <input type="submit" id="submit" name="submit" class="btn btn-primary" value = "Create Group">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                </form>
                <!-- hero serach area close  -->
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
        <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="./assets/libs/select2/dist/js/select2.min.js"></script>         
        <script src="./assets/libs/moment/min/moment.min.js"></script>    
        <script src="./assets/libs/lightpick/lightpick.js"></script> 
        <!-- Theme JS -->
        <script src="./assets/js/theme.min.js"></script>
        