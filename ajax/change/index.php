<?php
require_once("../../php/db_connect.php");
require_once("../../php/FamilyMember.class.php");

$tile = $_GET["t"];
$gender = $_GET["g"];
$id = $_GET["i"];

$query = mysql_query("SELECT user_id FROM dir_users_data WHERE gender = $gender AND user_id != $id ORDER BY first_name, last_name") or die(header("location:../../error.php?e=Error+Querying+User+Database+For+Ajax+Change"));

if($id) {
    echo '<div onclick="selectUser(this,\'' . $tile . '\',0,' . $gender . ')">';
    echo '<div id="0" class="user_tile" onMouseOver="highlightSelection(this,1)" onMouseOut="highlightSelection(this,0)">';
    if($gender) {
        echo '<img src="images/faces/no_image_male.jpg" />';
    } else {
        echo '<img src="images/faces/no_image_female.jpg" />';
    }
    if($tile == "father") {
        echo '<div class="user_tile_phrase">No Father On File</div>';
    } else if($tile == "mother") {
        echo '<div class="user_tile_phrase">No Mother On File</div>';
    } else if($tile == "spouse") {
        if($gender) {
            echo '<div class="user_tile_phrase">No Husband On File</div>';
        } else {
            echo '<div class="user_tile_phrase">No Wife On File</div>';
        }
    }
    echo '</div>';
    echo '</div>';
}

while($data = mysql_fetch_array($query)) {
    $user = new FamilyMember($data["user_id"]);
    $user_info = $user->get_user_info();
    
    echo '<div onclick="selectUser(this,\'' . $tile . '\',' . $user_info["user_id"] . ',' . $gender . ')">';
    echo '<div id="' . $user_info["user_id"] . '" class="user_tile" onMouseOver="highlightSelection(this,1)" onMouseOut="highlightSelection(this,0)">';
    echo '<img src="' . $user_info["picture_uri"] . '" />';
    if ($user_info["user_id"] == 0) { 
        echo '<div class="user_tile_phrase">' . $user_info["phrase"] . '</div>';
    } else {
        echo '<div>' . $user_info["preferred_name"] . ' ' . $user_info["last_name"] . '</div>';
        if ($user_info["marital_status"] == "Deceased") {
            echo '<div>Deceased</div>';
        } else if ($user_info["profession"] != "NA" || $user_info["education"] != "NA") {
            if ($user_info["profession"] != "NA" && $user_info["education"] != "NA") {
                echo '<div>' . $user_info["profession"] . ' ' . $user_info["education"] . '</div>';
            } else if($user_info["profession"] != "NA") {
                echo '<div>' . $user_info["profession"] . '</div>';
            } else if($user_info["education"] != "NA") {
                echo '<div>' . $user_info["education"] . '</div>';
            }
        }
        
        echo '<div>';
            if($user_info["city"] != "NA") {
                echo $user_info["city"];
            }
            
            if($user_info["city"] != "NA" && ($user_info["state"] != "NA" || $user_info["country"] != "NA")) {
                echo ', ';
            }
            
            if($user_info["state"] != "NA") {
                echo $user_info["state"];
            }
            
            if($user_info["state"] != "NA" && $user_info["country"] != "NA") {
                echo ', ';
            }
            
            if($user_info["country"] != "NA") {
                echo $user_info["country"];
            }
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}
?>