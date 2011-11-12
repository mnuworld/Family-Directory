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
tpl_set("page_title","Edit User | Khandan Directory");

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

// Get the user id
$uid = $_GET["uid"];

// Redirect user if no user id is specified
if(!$uid && (!$_POST["hidden_submit_form"] || !$_POST["hidden_password_change_submit"])) {
    header("location:error.php?e=Edit+User+No+User+Id+Specified");
    exit;
}

// Check if either the edit form or password reset form was submitted
if($_POST["hidden_submit_form"]) {
    // Edit the user and log activity
    $user->edit_user();
    $user->log_activity("edit",$uid,$me_info["user_id"]);
    
    // Create the email details
    $edited_name = $_POST["first_name"] . " " . $_POST["last_name"];
    $editor_name = $me_info["first_name"] . " " . $me_info["last_name"];
    $to[0] = "email@server.com";
    $subject = "User $edited_name Edited";
    $body = "User $edited_name has been edited by $editor_name";
    
    // Send email regarding the edit
    $user->send_email($to,$subject,$body);
    
    // Redirect to the edited user's profile page
    header("location:showuser.php?uid=$uid");
    exit;
} else if($_POST["hidden_password_change_submit"]) {
    // Get the passwords
    $password_current = $_POST["password_current"];
    $password_new = $_POST["password_new_1"];
    
    // Check if logged in user is an admin and set the correct parameters for the password reset
    if($user->get_admin_access()) {
        // If logged in user is an admin, we do not need the previous password to change it
        $user->reset_password($uid,null,$password_new);
    } else {
        $user->reset_password($uid,$password_current,$password_new);
    }
    
    // Redirect to the edited user's profile page
    header("location:showuser.php?uid=$uid");
    exit;
}

// Create a new family member object to get the information related to the subject
$subject = new FamilyMember($uid);

// Get the subject's information
$subject_info = $subject->get_user_info();

// Get the subject's father's information
$subject_info["father"] = $subject->get_father_info();

// Get the subject's mother's information
$subject_info["mother"] = $subject->get_mother_info();

// Get the subject's spouse's information
$subject_info["spouse"] = $subject->get_spouse_info();

// Get the subject's children's information
$subject_info["children"] = $subject->get_children_info();

// Set the subject data
tpl_set("subject",$subject_info);

// Set the page title
tpl_set("page_title","Edit " . $subject_info["first_name"] . " " . $subject_info["middle_name"] . " " . $subject_info["last_name"] . " | Khandan Directory");

// Create an array for the months of the year
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
for($i = date("Y"); $i >= 1200; $i--) {
    $years[$i]["id"] = $i;
    $years[$i]["year"] = $i;
}
tpl_set('years',$years);

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/edituser.tpl");
?>