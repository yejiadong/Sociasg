<?php
// Initialize the session
session_start();
// Include config file
require_once "database/config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Find activities with other Singaporeans!">
    <title>Homepage</title>
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
            width: 100%;
            /* You can set the dimensions to whatever you want */
            height: 330px;
            object-fit: cover;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
        $(function() {

            $('#groupidform').on('submit', function(e) {

                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: 'addtogroup.php',
                    data: $('#groupidform').serialize(),
                    success: function(data) {
                        $("#groupinfo").modal("hide");
                        $("#successjoingroup").modal("show");
                    }
                });

            });

        });
    </script>
    <script>
        $(function() {

            $('#groupsearchform').on('submit', function(e) {

                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: 'groupsearch.php',
                    data: $('#groupsearchform').serialize(),
                    success: function(data) {
                        $(".groups").replaceWith(data);
                        $(".toggle").prop('checked', true);
                        $(".groups").scrollintoview;
                        $('html, body').animate({
                            scrollTop: $(".scrollview").offset().top
                        }, 10);
                    }
                });

            });

        });

        $(document).ready(function() {
            $('.toggle').click(function() {
                if ($('.toggle').prop('checked')) {
                    // something when checked
                    var formData = $('#groupsearchform').serialize();
                } else {
                    var formData = $('#toggleform').serialize();
                }

                $.ajax({
                    url: 'groupsearch.php',
                    data: formData,
                    type: 'post',
                    success: function(data) {
                        $('.groups').fadeOut("slow", function() {
                            $(this).replaceWith(data);
                            $('.groups').fadeIn("slow");
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            $(".toggle").prop('checked', false);
        });
    </script>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="successjoingroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Join Group Success</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <p>You've successfully joined the group! Head over now!</p>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <a href="joinedgroups.php"> <input type="submit" class="btn btn-primary" value="View Groups"></a>
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
        <div class="bg-img-hero" style="background: url(assets/images/hero-slide-img-1.jpg) no-repeat;">
            <div class="container">
                <!-- hero section start  -->
                <!-- hero serach area start  -->
                <div class="row">
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 pt-md-4 pb-md-4">
                            <div class="card card-body">
                                <h1 class="h2 mb-4">Search for exciting groups and activities.</h1>
                                <form id="groupsearchform">
                                    <input type="hidden" name="countrysearch" value="" />
                                    <select class="select2 form-control custom-select" name="countrysearch" id="countrysearch">
                                        <option value="" disabled selected>Country:</option>
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
                                    <div class="form-group mt-3">
                                        <input type="text" id="interests" name="interests" class="form-control" placeholder="Interests">
                                    </div>
                                    <div class="text-right mt-3">
                                        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Search">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- hero serach area close  -->
            </div>
        </div>
        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 scrollview">
            <div class="container">
                <div class="row">
                    <!-- section heading start  -->
                    <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-5 text-center">
                            <h2 class="h3">Join a Group!</h2>
                            <p>Take part in activities organied by your fellow Singaporeans! </p>
                            <div class="row">
                                <div class="col-xl-11 col-lg-11 col-md-12 col-sm-12 col-12">
                                    <!-- Rounded switch -->
                                    <label class="switch">
                                        <form id="toggleform">
                                            <input type="hidden" id="countrysearch" name="countrysearch" class="countrysearch" value="">
                                            <input type="hidden" id="interests" name="interests" class="interests" value="">
                                            <input type="checkbox" class="toggle">
                                            <span class="slider round">
                                                <h6 class="h6 ml-10 mt-1">Search</h6>
                                            </span>
                                        </form>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- section heading close  -->
                </div>

                <div class="row">
                    <!-- Modal -->
                    <div class="modal fade" id="groupinfo" name="groupinfo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="groupinfoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="groupinfoLabel">Modal title</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="" alt="" class="img-fluid modalimage rounded-top">
                                    <h5 class="h5 mt-3">Location:</h3>
                                        <p class="text-sm font-weight-semi-bold location"></p>
                                        <h5 class="h5 mt-3">Creator:</h3>
                                            <p class="text-sm font-weight-semi-bold creator"></p>
                                            <h5 class="h5 mt-3">Brief Description:</h3>
                                                <p class="description"></p>
                                                <span class="text-dark h5 vacancy"></span><span class="text-sm font-weight-semi-bold ml-1 totalslot"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <form id="groupidform">
                                        <input type="hidden" id="userid" name="userid" class="userid" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" id="groupid" name="groupid" class="groupid" value="hi">
                                        <input type="submit" class="btn btn-primary" value="Join Group">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        var groupModal = document.getElementById('groupinfo')
                        groupModal.addEventListener('show.bs.modal', function(event) {
                            // Button that triggered the modal
                            var button = event.relatedTarget
                            // Extract info from data-bs-* attributes
                            var groupname = button.getAttribute('data-bs-whatever')
                            var groupid = button.getAttribute('data-bs-groupid')
                            var groupphoto = button.getAttribute('data-bs-groupphoto')
                            var groupphoto = button.getAttribute('data-bs-groupphoto')
                            var groupcity = button.getAttribute('data-bs-city')
                            var groupcountry = button.getAttribute('data-bs-country')
                            var groupcreator = button.getAttribute('data-bs-creator')
                            var groupdesc = button.getAttribute('data-bs-description')
                            var groupvacancy = button.getAttribute('data-bs-vacancy')
                            var groupmax_cap = button.getAttribute('data-bs-max_cap')

                            var modalTitle = groupModal.querySelector('.modal-title')
                            var modalgroupphoto = groupModal.querySelector('.modal-body .modalimage')
                            var modallocation = groupModal.querySelector('.modal-body .location')
                            var modalcreator = groupModal.querySelector('.modal-body .creator')
                            var modaldescription = groupModal.querySelector('.modal-body .description')
                            var modaltotalslot = groupModal.querySelector('.modal-body .totalslot')
                            var modalvacancy = groupModal.querySelector('.modal-body .vacancy')
                            var modalGroupid = groupModal.querySelector('.modal-footer .groupid')

                            modalTitle.textContent = groupname
                            modalgroupphoto.src = "uploads/" + groupphoto
                            modallocation.textContent = groupcountry + " . " + groupcity
                            modalcreator.textContent = groupcreator
                            modaldescription.textContent = groupdesc
                            modaltotalslot.textContent = "spots out of " + groupmax_cap + " left"
                            modalvacancy.textContent = groupvacancy
                            modalGroupid.value = groupid
                        })
                    </script>
                    <div class="row groups">
                        <?php
                        $mysqli = $link;
                        /* check connection */
                        if (mysqli_connect_errno()) {
                            printf("Connect failed: %s\n", mysqli_connect_error());
                            exit();
                        }
                        // Prepare to pull data
                        $query = "SELECT Groups.group_id, Groups.groupname, Groups.creator_id, Groups.country, 
                        Groups.city, Groups.max_cap, Groups.signed_up_users, Groups.description, Groups.photo_link, Users.username 
                        FROM Groups INNER JOIN Users ON Groups.creator_id=Users.id ORDER by group_id";
                        $result = $mysqli->query($query);

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
                        ?>
                    </div>

                </div>
            </div>
        </div>


        <div class="pt-lg-11 pb-lg-11 pt-7 pb-7 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <div class="mb-5">
                            <h2 class="h3">Featured Countries </h2>
                            <p>Discover activities in 50+ countries.</p>
                        </div>
                    </div>
                </div>
                <div class="location-gallery ">
                    <div class="row no-gutters">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 pr-2">
                            <a href="#">
                                <div class="overlay-bg mb-2 zoom-img">
                                    <img src="assets/images/location-img-1.jpg" alt="" class="w-100 rounded">
                                    <div class="overlay-content">
                                        <h4 class="text-white mb-1">USA</h4>
                                        <p class="text-white">87 activities</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#">
                                <div class="overlay-bg mb-2 zoom-img">
                                    <img src="assets/images/location-img-2.jpg" alt="" class="w-100 rounded">
                                    <div class="overlay-content">
                                        <h4 class="text-white mb-1">France</h4>
                                        <p class="text-white">221 activities</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pr-2">
                            <a href="#">
                                <div class=" overlay-bg mb-2 zoom-img">
                                    <img src="assets/images/location-img-3.jpg" alt="" class="w-100 rounded">
                                    <div class="overlay-content">
                                        <h4 class="text-white mb-1">Japan</h4>
                                        <p class="text-white">68 activities</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                            <a href="#">
                                <div class="overlay-bg mb-2 zoom-img">
                                    <img src="assets/images/location-img-4.jpg" alt="" class="w-100 rounded">
                                    <div class="overlay-content">
                                        <h4 class="text-white mb-1">Australia</h4>
                                        <p class="text-white">54 activities</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#">
                                <div class="overlay-bg mb-2 zoom-img">
                                    <img src="assets/images/location-img-5.jpg" alt="" class="w-100 rounded">
                                    <div class="overlay-content">
                                        <h4 class="text-white mb-1">United Kingdom</h4>
                                        <p class="text-white">143 activities</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
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
                                <p class="tiny-footer-text">© 2021 Sociasg. Template Adapted from Rentkit</p>
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