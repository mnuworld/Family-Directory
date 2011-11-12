<?php
require_once("../../php/db_connect.php");
require_once("../../php/FamilyMember.class.php");

// Set the timezone to CST
date_default_timezone_set('America/Chicago');

// Get the log type and the user id
$view = $_GET["v"];
$uid = $_GET["i"];

// Set the name to be shown at the top of the records
if($uid == 0) {
    $first_name = "Anonymous";
    $last_name = "Users";
} else {
    $log_user = new FamilyMember($uid);
    $log_user_info = $log_user->get_user_info();
    $first_name = $log_user_info["first_name"];
    $last_name = $log_user_info["last_name"];
}
    
// Query the database
if($view == "visits") {
    $query = mysql_query("SELECT * FROM dir_logs_visits WHERE user_id = $uid ORDER BY visit_date DESC LIMIT 20") or die(header("location:../../error.php?e=Error+Getting+Log+Data+For+User+Visits"));

    // Set the name
    echo "<div id='log_records_header_visits'>$first_name $last_name</div>";
    
    // Loop through the data and output the display
    while($data = mysql_fetch_array($query)) {
        echo "<div>on " . date("M j, Y",strtotime($data["visit_date"])) . " at " . date("g:i a",strtotime($data["visit_date"])) . " - " . $data["ip"] . "</div>";
        echo "<div><a href='" . $data["page_visited"] . "'>" . $data["page_visited"] . "</a></div>";
        echo "<div class='log_records_visits'>" . $data["browser"] . "</div>";
    }
} else {
    // Get the log activity for the user
    $query = mysql_query("SELECT * FROM dir_logs_activity WHERE user_id = $uid") or die(header("location:error.php?e=Error+Getting+Log+Activity+For+User"));
    $data = mysql_fetch_assoc($query);

    // Get the data on the user based on the view
    if($view == "views") {
        $activity_user = new FamilyMember($data["view_by_id"]);
        $activity_date = $data["view_date"];
        
        // Set the header
        echo "<div id='log_records_header'>$first_name $last_name was last viewed by</div>";
    } else if($view == "adds") {
        $activity_user = new FamilyMember($data["add_by_id"]);
        $activity_date = $data["add_date"];
        
        // Set the header
        echo "<div id='log_records_header'>$first_name $last_name was added by</div>";
    } else if($view == "edits") {
        $activity_user = new FamilyMember($data["edit_by_id"]);
        $activity_date = $data["edit_date"];
        
        // Set the header
        echo "<div id='log_records_header'>$first_name $last_name was last edited by</div>";
    }
    $activity_user_info = $activity_user->get_user_info();

    // Set the uri of the profile picture due to the relative path
    if($activity_user_info["show_picture"] && file_exists("../../images/faces/" . $activity_user_info["user_name"] . ".jpg")) {
        $activity_user_info["picture_uri"] = "images/faces/" . $activity_user_info["user_name"] . ".jpg";
    } else if($activity_user_info["gender"]) {
        $activity_user_info["picture_uri"] = "images/faces/no_image_male.jpg";
    } else {
        $activity_user_info["picture_uri"] = "images/faces/no_image_female.jpg";
    }
    
    // Output the user tile here because we can not use the smarty template
?>
    <div id="<?php echo $activity_user_info["user_id"] ?>" class="user_tile" onMouseOver="highlightSelection(this,1)" onMouseOut="highlightSelection(this,0)">
        <img src="<?php echo $activity_user_info["picture_uri"] ?>" />
        <?php if($activity_user_info["user_id"] == 0) { ?>
            <div class="user_tile_phrase"><?php $activity_user_info["phrase"]; ?></div>
        <?php } else { ?>
            <div><?php echo $activity_user_info["preferred_name"] . " " . $activity_user_info["last_name"]; ?></div>
            <?php if($activity_user_info["marital_status"] == "Deceased") { ?>
                <div>Deceased</div>
            <?php } else if($activity_user_info["profession"] != "NA" || $activity_user_info["education"] != "NA") { ?>
                <?php if($activity_user_info["profession"] != "NA" && $activity_user_info["education"] != "NA") { ?>
                    <div><?php echo $activity_user_info["profession"] . " (" . $activity_user_info["education"] . ")"; ?></div>
                <?php } else if($activity_user_info["profession"] != "NA") { ?>
                    <div><?php echo $activity_user_info["profession"]; ?></div>
                <?php } else if($activity_user_info["education"] != "NA") { ?>
                    <div><?php $activity_user_info["education"]; ?></div>
                <?php } ?>
            <?php } ?>
            
            <div>
                <?php if($activity_user_info["city"] != "NA" && $activity_user_info["state"] != "NA" && $activity_user_info["country"] != "NA") { ?>
                    <?php echo $activity_user_info["city"] . ", " . $activity_user_info["state"] . ", " . $activity_user_info["country"]; ?>
                <?php } else if($activity_user_info["city"] != "NA" && $activity_user_info["state"] != "NA") { ?>
                    <?php echo $activity_user_info["city"] . ", " . $activity_user_info["state"]; ?>
                <?php } else if($activity_user_info["city"] != "NA" && $activity_user_info["country"] != "NA") { ?>
                    <?php echo $activity_user_info["city"] . ", " . $activity_user_info["country"]; ?>
                <?php } else if($activity_user_info["state"] != "NA" && $activity_user_info["country"] != "NA") { ?>
                    <?php echo $activity_user_info["state"] . ", " . $activity_user_info["country"]; ?>
                <?php } else if($activity_user_info["city"] != "NA") { ?>
                    <?php echo $activity_user_info["city"]; ?>
                <?php } else if($activity_user_info["state"] != "NA") { ?>
                    <?php echo $activity_user_info["state"]; ?>
                <?php } else if($activity_user_info["country"] != "NA") { ?>
                    <?php echo $activity_user_info["country"]; ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<?php
    echo "<div>on " . date("M j, Y",strtotime($activity_date)) . " at " . date("g:i a",strtotime($activity_date)) . "</div>";
}
?>