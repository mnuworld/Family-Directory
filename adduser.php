<?php
require_once("php/db_connect.php");
require_once("php/tpl_setup.php");
require_once("php/FamilyMember.class.php");
require_once("php/User.class.php");
require_once("php/Search.class.php");

// Create a new user object
$user = new User();

// Make sure user is logged in before proceding
$user->require_login();

// Get the name of the current logged in user
$me_info = $user->get_user_info();

// Set the first and last name of the logged in user
tpl_set("user_first_name",$me_info["first_name"]);
tpl_set("user_last_name",$me_info["last_name"]);

// Set the authentication values
tpl_set("user_id",$user->get_user_id());
tpl_set("user_name",$user->get_user_name());
tpl_set("admin_access",$user->get_admin_access());
tpl_set("add_access",$user->get_add_access());
tpl_set("edit_access",$user->get_edit_access());

// Set the page title
tpl_set("page_title","Add User | Khandan Directory");

// Create a new search object to populate the search form
$search = new Search();

// Set the search form data
tpl_set("search_cities",$search->get_cities());
tpl_set("search_states",$search->get_states());
tpl_set("search_countries",$search->get_countries());
tpl_set("search_educations",$search->get_educations());
tpl_set("search_professions",$search->get_professions());
tpl_set("search_marital_statuses",$search->get_marital_statuses());

// Log the visit
$user->log_activity("visit",NULL,$me_info["user_id"]);

/*********************************** Start Page Specific Code *******************************************/

// Check if the add form was submitted
if($_POST["hidden_submit_form"]) {
    // Add the user and log activity
    $uid = $user->add_user();
    $user->log_activity("add",$uid,$me_info["user_id"]);
    
    // Create the email details
    $added_name = $_POST["first_name"] . " " . $_POST["last_name"];
    $adder_name = $me_info["first_name"] . " " . $me_info["last_name"];
    $to[0] = "email@server.com";
    $subject = "User $added_name Edited";
    $body = "User $added_name has been edited by $adder_name";
    
    // Send email regarding the add
    $user->send_email($to,$subject,$body);
    
    // Redirect to the added user's profile page
    header("location:showuser.php?uid=$uid");
    exit;
}

// Set the father tile
$father[0]["user_id"] = 0;
$father[0]["picture_uri"] = "images/faces/no_image_male.jpg";
$father[0]["phrase"] = "Select Father";
tpl_set("father",$father);

// Set the mother tile
$mother[0]["user_id"] = 0;
$mother[0]["picture_uri"] = "images/faces/no_image_female.jpg";
$mother[0]["phrase"] = "Select Mother";
tpl_set("mother",$mother);

// Set the spouse tile
$spouse[0]["user_id"] = 0;
$spouse[0]["picture_uri"] = "images/faces/no_image_male.jpg";
$spouse[0]["phrase"] = "Select Spouse";
tpl_set("spouse",$spouse);

// Create an array for the months on the year
$months[0]["id"] = 0;
$months[0]["month"] = "";
for($i = 1; $i <= 12; $i++) {
    $months[$i]["id"] = $i;
    $months[$i]["month"] = date("M",strtotime($i . "/1/2000"));
}
tpl_set('months',$months);

// Create an array for the days of the month
$dates[0]["id"] = 0;
$dates[0]["date"] = "";
for($i = 1; $i <= 31; $i++) {
    $dates[$i]["id"] = $i;
    $dates[$i]["date"] = $i;
}
tpl_set('dates',$dates);

// Create an array for the years
$years[0]["id"] = 0;
$years[0]["year"] = "";
for($i = date("Y"); $i >= 1700; $i--) {
    $years[$i]["id"] = $i;
    $years[$i]["year"] = $i;
}
tpl_set('years',$years);

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/adduser.tpl");
?>