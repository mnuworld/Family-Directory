<?php
require_once("php/db_connect.php");
require_once("php/tpl_setup.php");
require_once("php/FamilyMember.class.php");
require_once("php/User.class.php");

// Create a new user object to make sure they are not already logged in
$user = new User();

// Set the page title
tpl_set("page_title","Login | Khandan Directory");

// Log the visit
$user->log_activity("visit",NULL,0);

/*********************************** Start Page Specific Code *******************************************/

// If user is already logged in, redirect them
$user_name = $user->get_user_name();
if(!empty($user_name)) {
    header("location:/");
}

// If the form has been submitted, check for a valid log in
if($_POST["login"]) {
    $user->authenticate_user($_POST);
}

// Set the error message
tpl_set("message",$_GET["m"]);

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/login.tpl");
?>