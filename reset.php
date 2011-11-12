<?php
require_once("php/db_connect.php");
require_once("php/tpl_setup.php");
require_once("php/FamilyMember.class.php");
require_once("php/User.class.php");
require_once("php/Search.class.php");

// Create a new user object
$user = new User();

// Set the page title
tpl_set("page_title","Reset Password | Khandan Directory");

// Log the visit
$user->log_activity("visit",NULL,NULL);

/*********************************** Start Page Specific Code *******************************************/

// Set the message
$message = $_GET["m"];
tpl_set("message",$message);

// Check to see if token is active
$token = $_GET["token"];
$uid = $_GET["uid"];
if($token and $uid) {
    $token_id = $user->check_forgot_password_token($uid,$token);
}

// If there is no token or user id, show the request form, otherwise show the password reset form
if(!($token or $uid)) {
    // Set the form flags
    tpl_set("reset_request",true);
    tpl_set("reset_password",false);
    
    // If the password reset request form has been submitted, create the token id and email the user if they exist
    if($_POST["submit_form"]) {
        // Get the user id from the user name or email
        $credential = $_POST["user_name"];
        $user_id = $user->get_user_id($credential);
        
        // If a user id exists, send the user an email, otherwise set the message flag
        if($user_id) {
            // Create the email details
            $email[0] = $user->get_user_email($user_id);
            $token = $user->create_forgot_password_token($user_id);
            $subject = "Password Reset Instructions";
            $body = "Your username is " . $user->get_user_name($user_id) . "\r\n\r\nClick on the following link to reset your password.\r\n\r\nhttp://www.khandandirectory.com/reset.php?uid=$user_id&token=$token";
            
            // Send the email
            $user->send_email($email,$subject,$body);

            // Set the message flag
            tpl_set("message","pe");
        } else {
            // Set the message flag
            tpl_set("message","bun");
        }
    }
} else if($token_id) {
    // Set the form flags
    tpl_set("reset_request",false);
    tpl_set("reset_password",true);
    
    // If the password reset form has been submitted, reset the password
    if($_POST["submit_form"]) {
        // Get the user id from the given user name
        $user_name = $_POST["user_name"];
        $password = $_POST["password_1"];
        $user_id = $user->get_user_id($user_name);
        
        // Reset the password, disable the token, and redirect the user
        $user->reset_password($user_id,"",$password);
        $user->disable_forgot_password_token($user_id);
        header("location:/");
    }
} else {
    header("location:/");
    exit;
}

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/reset.tpl");
?>