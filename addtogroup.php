<?php
// Include config file
require_once "database/config.php";
 
// Define variables and initialize with empty values
$userid= $groupid="";
$err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $userid = mysqli_real_escape_string($link,$_POST['userid']);
    $groupid = mysqli_real_escape_string($link,$_POST['groupid']);

    // Validate username
    if(empty(trim($userid)) || empty(trim($groupid)) ){
        $err = "An error occurred. Please try again!";
    } else{
        // Prepare a select statement
        $sql = "SELECT * FROM GroupMembership WHERE user_id = ? AND group_id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_userid, $param_groupid);
            
            // Set parameters
            $param_userid = trim($userid);
            $param_groupid = trim($groupid);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) > 0){
                    $err = "Already joined the group!";
                } else{
                    $userid = trim($userid);
                    $groupid = trim($groupid);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Check input errors before inserting in database
    if(empty($err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO GroupMembership (user_id, group_id) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_userid, $param_groupid);
            
            // Set parameters
            $param_userid = $userid;
            $param_groupid = $groupid;
            

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $sql = "UPDATE Groups SET signed_up_users = signed_up_users + 1 WHERE group_id = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_groupid);
                    $param_groupid = $groupid;
                    if(mysqli_stmt_execute($stmt)){
                        echo "Success!";
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>