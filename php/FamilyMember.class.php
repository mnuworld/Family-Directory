<?php
/**
 * 
 * Copyright 2011 khandandirectory.com
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 */

/**
 * 
 * Khandani object class contains all objects and methods pertaining to the khandan of any given user.
 * It cotains information on relationships (e.g. father, mother, spouse, children), information of the
 * given user (e.g. first name, last name, email, profession, address, education, etc.), and anything else
 * related to any given khandani.
 * 
 */
class FamilyMember
{
    // Array holding data of the created family member
    protected $user_info;
    
    // Array holding data of the father of the created family member
    protected $father_info;
    
    // Array holding data of the mother of the created family member
    protected $mother_info;
    
    // Array holding data of the spouse of the created family member
    protected $spouse_info;
    
    // Array holding data of the children of the created family member
    protected $children_info;
    
    // Number of children that the user has
    protected $number_of_children;
    
    /**
     * Family Member construct.
     * 
     * If passed a user id, the information for that user will be queryed and saved
     */
    public function __construct($uid = null) {
        if($uid) {
            $this->user_info = $this->set_info($uid);
            $this->father_info[0] = $this->set_info($this->user_info["father_id"]);
            $this->mother_info[0] = $this->set_info($this->user_info["mother_id"]);
            $this->spouse_info[0] = $this->set_info($this->user_info["spouse_id"],$this->user_info["gender"]);
            $this->set_children_info();
        }
    }
    
    /**
     * Set the family member info by querying the database for all information related to the user id
     */
    protected function set_info($uid,$subject_gender = null)
    {
        // If not an empty user tile, query the database
        if($uid) {
            // Query the database for all information related to the user id
            $sql = mysql_query("SELECT 
                                users.user_id, user_name, email, title, first_name, middle_name, last_name, 
                                preferred_name, gender, education, profession, marital_status, 
                                street_1, street_2, street_3, city, state, zipcode, country, 
                                show_picture, show_email, dob, pob, dod, pod, dom, pom, number_home, 
                                number_cell, father_id, mother_id, spouse_id, 
                                admin_access, add_access, edit_access 
                                FROM dir_users_data users 
                                JOIN dir_education education 
                                ON users.education_id = education.education_id 
                                JOIN dir_profession pro 
                                ON users.profession_id = pro.profession_id 
                                JOIN dir_cities cities 
                                ON users.city_id = cities.city_id 
                                JOIN dir_states states 
                                ON users.state_id = states.state_id 
                                JOIN dir_countries countries 
                                ON users.country_id = countries.country_id 
                                JOIN dir_marital_status marital_status 
                                ON users.marital_status_id = marital_status.marital_status_id 
                                JOIN dir_relations relations 
                                ON users.user_id = relations.user_id 
                                JOIN dir_users_access users_access 
                                ON users.user_id = users_access.user_id 
                                WHERE users.user_id = $uid") or die(header("location:error.php?e=Error+Querying+Database+To+Set+User+Data"));
            
            
            // Check to see if the user id is valid
            if(mysql_num_rows($sql) == 0) {
                die(header("location:error.php?e=Show+User+Invalid+User+Id+Specified+".$uid));
            }            
            
            // Create the associative array for the results or redirect to error page if user id is not found
            $sql_array = mysql_fetch_assoc($sql);
            
            // Format the date here to avoid issues with pre-1970 dates
            $dob = $sql_array["dob"];
            $dod = $sql_array["dod"];
            $dom = $sql_array["dom"];
            
            if($dob) {
                $year = substr($dob,0,4);
                $month = substr($dob,5,2);
                $date = substr($dob,8,2);
                
                // Set the year, month, and date separately so we can access them when editing users
                $sql_array["dob_year"] = $year;
                $sql_array["dob_month"] = $month;
                $sql_array["dob_date"] = $date;
                $sql_array["dob"] = date("M",strtotime("2000-$month-1")) . " " . $date . ", " . $year;
            }
            
            if($dod) {
                $year = substr($dod,0,4);
                $month = substr($dod,5,7);
                $date = substr($dod,8,10);
                
                // Set the year, month, and date separately so we can access them when editing users
                $sql_array["dod_year"] = $year;
                $sql_array["dod_month"] = $month;
                $sql_array["dod_date"] = $date;
                $sql_array["dod"] = date("M",strtotime("2000-$month-1")) . " " . $date . ", " . $year;
            }
            
            if($dom) {
                $year = substr($dom,0,4);
                $month = substr($dom,5,7);
                $date = substr($dom,8,10);
                
                // Set the year, month, and date separately so we can access them when editing users
                $sql_array["dom_year"] = $year;
                $sql_array["dom_month"] = $month;
                $sql_array["dom_date"] = $date;
                $sql_array["dom"] = date("M",strtotime("2000-$month-1")) . " " . $date . ", " . $year;
            }
            
            // Set the uri of the profile picture
            if($sql_array["show_picture"] && file_exists("images/faces/" . $sql_array["user_name"] . ".jpg")) {
                $sql_array["picture_uri"] = "images/faces/" . $sql_array["user_name"] . ".jpg";
            } else if($sql_array["gender"]) {
                $sql_array["picture_uri"] = "images/faces/no_image_male.jpg";
            } else {
                $sql_array["picture_uri"] = "images/faces/no_image_female.jpg";
            }
        } else {
            // Create the empty tile information
            $sql_array["user_id"] = 0;
            
            // Set the uri and phrase to be shown of the spouse or child
            if($subject_gender === null) {
                // The family member is a child if gender is not specified
                $sql_array["picture_uri"] = "images/faces/no_image_male.jpg";
                $sql_array["phrase"] = "No Children On File";
            } else if($subject_gender) {
                // The family member is a female spouse if the subject gender is greater than zero
                $sql_array["picture_uri"] = "images/faces/no_image_female.jpg";
                $sql_array["phrase"] = "No Wife On File";
            } else {
                // The family member is a male spouse if the subject gender is not equal to zero (not null)
                $sql_array["picture_uri"] = "images/faces/no_image_male.jpg";
                $sql_array["phrase"] = "No Husband On File";
            }
        }
        
        return $sql_array;
    }
    
    /**
     * Set the information for the children of the created family member object
     */
    protected function set_children_info()
    {
        // Query the database for the children
        $sql = mysql_query("SELECT
                            relations.user_id 
                            FROM dir_relations relations
                            JOIN dir_users_data users
                            ON relations.user_id = users.user_id
                            WHERE relations.father_id = " . $this->user_info['user_id'] . " 
                            OR  relations.mother_id = " . $this->user_info['user_id'] . " 
                            ORDER BY users.dob") or die(header("location:error.php?e=Error+Getting+Children+Info"));
        
        if(mysql_num_rows($sql)) {
            $i = 0;
            while($data = mysql_fetch_assoc($sql)) {
                $this->children_info[$i] = $this->set_info($data["user_id"]);
                $i++;
            }
            $this->number_of_children = $i;
        } else {
            $this->number_of_children = 0;
            $this->children_info[0] = $this->set_info(0);
        }
        
        return $this;
    }
    
    /**
     * Get the array of information related to the user created by the object
     */
    public function get_user_info() { return $this->user_info; }
    
    /**
     * Get the array of information related to the father of the user created by the object
     */
    public function get_father_info() { return $this->father_info; }
    
    /**
     * Get the array of information related to the mother of the user created by the object
     */
    public function get_mother_info() { return $this->mother_info; }
    
    /**
     * Get the array of information related to the spouse of the user created by the object
     */
    public function get_spouse_info() { return $this->spouse_info; }
    
    /**
     * Get the array of information related to the children of the user created by the object
     */
    public function get_children_info() { return $this->children_info; }
    
    /**
     * Get the number of children that the user has
     */
    public function get_number_of_children() { return $this->number_of_children; }
    
    /**
     * Get all the ancestors of a specified user
     */
    public function get_ancestors($uid,$generation_count,$generation,$user_count)
    {
        // Global call to an array to help with pushing the user info to the array
        global $family_members;
        
        // Get the user's information
        $user = new FamilyMember($uid);
        $info = $user->get_user_info();
        
        // Set the generation number
        $info["generation"] = $generation_count;
        
        // Set the user count number for each generation
        $info["user_count"] = $user_count;
        
        // Set the default information for an empty user
        if(!$info["user_id"]) {
            // If the user is unknown, then we also don't know the father or the mother
            $info["user_id"] = 0;
            $info["father_id"] = 0;
            $info["mother_id"] = 0;
            
            // Set the picture and phrase for the specific gender based on the user count
            if($user_count % 2) {
                $info["picture_uri"] = "images/faces/no_image_male.jpg";
                $info["phrase"] = "No Father On File";
            } else {
                $info["picture_uri"] = "images/faces/no_image_female.jpg";
                $info["phrase"] = "No Mother On File";
            }
        }
        
        // Push the user's info to the array
        array_push($family_members,$info);
        
        // Get the user's sibling's info
        if($generation_count == 1) {
            // Get the array of children
            $siblings = $this->get_siblings($info["user_id"],$info["father_id"],$info["mother_id"]);
            
            // Set additional parameters for siblings and push to the array
            $i = 1;
            foreach($siblings as $sibling) {
                $i++;
                $sibling["generation"] = "s";
                $sibling["user_count"] = $i;
                array_push($family_members,$sibling);
            }
        }
        
        // If we haven't reached the last generation, recursively call this function to get ancestors of the father and mother
        if($generation_count < $generation) {
            // Increment the user_count throughout each generations
            if($user_count > 1) {
                $user_count += $user_count - 1;
            }
            
            // Get the father of the user
            $this->get_ancestors($info["father_id"],$generation_count+1,$generation,$user_count);
            
            // Increment the count before getting the mother's information
            $user_count++;
            
            // Get the mother of the user
            $this->get_ancestors($info["mother_id"],$generation_count+1,$generation,$user_count);
        }
    }
    
    /**
     * Get all decendents of a specified user
     */
    public function get_descendants($uid,$generation_count,$generation,$pid) {
        // Global call to an array to help with pushing the user info to the array
        global $family_members;
        
        // Get the user's information
        $user = new FamilyMember($uid);
        $info = $user->get_user_info();
        $children = $user->get_children_info();
        $number_of_children = $user->get_number_of_children();
        $spouse = $user->get_spouse_info();
        
        // Set the generation number
        $info["generation"] = $generation_count;
        
        // Set the parent's id
        $info["parent_id"] = $pid;
        
        // Push the user's info to the array
        array_push($family_members,$info);
        
        // If the user has a spouse, push her info to the array
        //if($spouse[0]["user_id"]) {
            // Set the generation number
            //$spouse[0]["generation"] = $generation_count;
            
            // Set the parent's id
            //$spouse[0]["parent_id"] = 0;
            
            // Clear the spouse's id
            //$spouse[0]["spouse_id"] = 0;
            
            // Push the user's info to the array
            //array_push($family_members,$spouse[0]);
        //}
        
        // If we haven't reached the last generation, recursively call this function to get descendants of each child
        if($generation_count < $generation) {
            // Loop through each child and get their descendants
            for($i = 0; $i < $number_of_children; $i++) {
                $this->get_descendants($children[$i]["user_id"],$generation_count+1,$generation,$info["user_id"]);
            }
        }
    }

    /**
     * Get the siblings of the specified user
     */
    protected function get_siblings($uid,$fid,$mid)
    {
        // Query the database for the user's siblings
        $query = mysql_query("SELECT
                              users.user_id
                              FROM dir_users_data users
                              JOIN dir_relations relations
                              ON relations.user_id = users.user_id
                              WHERE relations.user_id != $uid 
                              AND (relations.father_id = $fid OR relations.mother_id = $mid)
                              AND relations.father_id != 0
                              AND relations.mother_id != 0
                              ORDER BY users.dob") or die(header("location:error.php?e=Error+Querying+Database+For+Users+Siblings"));

        // Get the user's sibling's info
        $i = 0;
        $siblings = array();
        while($data = mysql_fetch_array($query)) {
            $user = new FamilyMember($data["user_id"]);
            $siblings[$i] = $user->get_user_info();
            $i++;
        }
        
        return $siblings;
   }
    
    /**
     * Edit the user using the post data
     */
    public function edit_user()
    {
        $user_id = $_POST["hidden_user_id"];
        $user_name = $_POST["hidden_user_name"];
        $user_name_new = $_POST["user_name"]; 
        $title = $_POST["title"];
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $preferred_name = $_POST["preferred_name"];
        $gender = $_POST["gender"];
        $dob_month = $_POST["dob_month"];
        $dob_date = $_POST["dob_date"];
        $dob_year = $_POST["dob_year"];
        $pob = $_POST["pob"];
        $dod_month = $_POST["dod_month"];
        $dod_date = $_POST["dod_date"];
        $dod_year = $_POST["dod_year"];
        $pod = $_POST["pod"];
        $dom_month = $_POST["dom_month"];
        $dom_date = $_POST["dom_date"];
        $dom_year = $_POST["dom_year"];
        $pom = $_POST["pom"];
        $email = $_POST["email"];
        $education_id = $_POST["education"];
        $education_add = $_POST["education_add"];
        $profession_id = $_POST["profession"];
        $profession_add = $_POST["profession_add"];
        $street_1 = $_POST["street_1"];
        $street_2 = $_POST["street_2"];
        $street_3 = $_POST["street_3"];
        $city_id = $_POST["city"];
        $city_add = $_POST["city_add"];
        $state_id = $_POST["state"];
        $state_add = $_POST["state_add"];
        $zipcode = $_POST["zipcode"];
        $country_id = $_POST["country"];
        $country_add = $_POST["country_add"];
        $number_home = $_POST["number_home"];
        $number_cell = $_POST["number_cell"];
        $marital_status_id = $_POST["marital_status"];
        $father_id = $_POST["hidden_father_id"];
        $mother_id = $_POST["hidden_mother_id"];
        $spouse_id = $_POST["hidden_spouse_id"];
        $show_email = $_POST["show_email"];
        $show_picture = $_POST["show_picture"];
        $delete_picture = $_POST["delete_picture"];
        $admin_access = $_POST["admin_access"];
        $add_access = $_POST["add_access"];
        $edit_access = $_POST["edit_access"];
        $user_admin_access = $_POST["hidden_user_admin_access"];
        $picture_exists = file_exists("images/faces/$user_name.jpg");

        // Change preferred_name if required     
        if(!$preferred_name) {
            $preferred_name = $first_name;
        }
            
        // Update users data
        $user_data_sql = "UPDATE dir_users_data SET title='$title', preferred_name='$preferred_name', gender=$gender";
        
        if($user_admin_access) {
            $user_data_sql .= ", first_name='$first_name'";
            $user_data_sql .= ", last_name='$last_name'";
        }
        if($middle_name) {
            $user_data_sql .= ", middle_name='$middle_name'";
        } else {
            $user_data_sql .= ", middle_name=NULL";
        }
        if($street_1) {
            $user_data_sql .= ", street_1='$street_1'";
        } else {
            $user_data_sql .= ", street_1=NULL";
        }
        if($street_2) {
            $user_data_sql .= ", street_2='$street_2'";
        } else {
            $user_data_sql .= ", street_2=NULL";
        }
        if($street_3) {
            $user_data_sql .= ", street_3='$street_3'";
        } else {
            $user_data_sql .= ", street_3=NULL";
        }
        if($zipcode) {
            $user_data_sql .= ", zipcode='$zipcode'";
        } else {
            $user_data_sql .= ", zipcode=NULL";
        }
        if($city_id != "add") {
            $user_data_sql .= ", city_id=$city_id";
        }
        if($state_id != "add") {
            $user_data_sql .= ", state_id=$state_id";
        }
        if($country_id != "add") {
            $user_data_sql .= ", country_id=$country_id";
        }
        
        if($dob_year) {
            if($dob_month && $dob_month < 10) {
                $dob_month = "0" . $dob_month;
            }
            if($dob_date && $dob_date < 10) {
                $dob_date = "0" . $dob_date;
            }
            
            if($dob_month && $dob_date) {
                $user_data_sql .= ", dob='$dob_year-$dob_month-$dob_date'";
            } else if($dob_month) {
                $user_data_sql .= ", dob='$dob_year-$dob_month-01'";
            } else if($dob_date) {
                $user_data_sql .= ", dob='$dob_year-01-$dob_date'";
            } else {
                $user_data_sql .= ", dob='$dob_year-01-01'";
            }
        } else {
            $user_data_sql .= ", dob=NULL";
        }
        if($pob) {
            $user_data_sql .= ", pob='$pob'";
        } else {
            $user_data_sql .= ", pob=NULL";
        }
        
        if($dod_year) {
            if($dod_month && $dod_month < 10) {
                $dod_month = "0" . $dod_month;
            }
            if($dod_date && $dod_date < 10) {
                $dod_date = "0" . $dod_date;
            }
            
            if($dod_month and $dod_date) {
                $user_data_sql .= ", dod='$dod_year-$dod_month-$dod_date'";
            } else if($dod_month) {
                $user_data_sql .= ", dod='$dod_year-$dod_month-01'";
            } else if($dod_date) {
                $user_data_sql .= ", dod='$dod_year-01-$dod_date'";
            } else {
                $user_data_sql .= ", dod='$dod_year-01-01'";
            }
        } else {
            $user_data_sql .= ", dod=NULL";
        }
        if($pod) {
            $user_data_sql .= ", pod='$pod'";
        } else {
            $user_data_sql .= ", pod=NULL";
        }
        
        if($dom_year) {
            if($dom_month && $dom_month < 10) {
                $dom_month = "0" . $dom_month;
            }
            if($dom_date && $dom_date < 10) {
                $dom_date = "0" . $dom_date;
            }
            
            if($dom_month and $dom_date) {
                $user_data_sql .= ", dom='$dom_year-$dom_month-$dom_date'";
            } else if($dom_month) {
                $user_data_sql .= ", dom='$dom_year-$dom_month-01'";
            } else if($dom_date) {
                $user_data_sql .= ", dom='$dom_year-01-$dom_date'";
            } else {
                $user_data_sql .= ", dom='$dom_year-01-01'";
            }
        } else {
            $user_data_sql .= ", dom=NULL";
        }
        if($pom) {
            $user_data_sql .= ", pom='$pom'";
        } else {
            $user_data_sql .= ", pom=NULL";
        }
        
        if($education_id != "add") {
            $user_data_sql .= ", education_id=$education_id";
        }
        if($profession_id != "add") {
            $user_data_sql .= ", profession_id=$profession_id";
        }
        
        $user_data_sql .= ", marital_status_id=$marital_status_id";
    
        if($number_home) {
            $user_data_sql .= ", number_home='$number_home'";
        } else {
            $user_data_sql .= ", number_home=NULL";
        }
        
        if($number_cell) {
            $user_data_sql .= ", number_cell='$number_cell'";
        } else {
            $user_data_sql .= ", number_cell=NULL";
        }

        // Upload picture if $_FILES["picture"]["name"] exists
        if($picture_exists or $_FILES["picture"]["name"]) {
            if($_FILES["picture"]["name"]) {
                move_uploaded_file($_FILES["picture"]["tmp_name"], "images/faces/$user_name.jpg");
                if($picture_exists) {
                    $user_data_sql .= ", show_picture=$show_picture";
                } else {
                    $user_data_sql .= ", show_picture=1";
                }
            } else if($delete_picture) {
               unlink("images/faces/$user_name.jpg");
               $user_data_sql .= ", show_picture=0";
            } else {
                $user_data_sql .= ", show_picture=$show_picture";
            }
        }
        
        $user_data_sql .= ", show_email=$show_email";
        $user_data_sql .= " WHERE user_id=" . $user_id;
        mysql_query($user_data_sql) or die(header("location:error.php?e=Error+Updating+User+Data+Table"));

        // Update relationships and update spouse's spouse_id
        $relations_sql = "UPDATE dir_relations SET father_id=$father_id, mother_id=$mother_id, spouse_id=$spouse_id WHERE user_id=$user_id";
        mysql_query($relations_sql) or die(header("location: error.php?e=Error+Updating+Relationship+Table"));
        $this->update_spouse($spouse_id,$user_id);
        
        // Update user access
        if($user_admin_access or $email) {
            $user_access_sql = "UPDATE dir_users_access SET";        
            if($user_admin_access) {
                $user_access_sql .= " user_name='$user_name_new'";
                $user_access_sql .= ", admin_access=$admin_access";
                $user_access_sql .= ", add_access=$add_access";
                $user_access_sql .= ", edit_access=$edit_access";
                if($email) {
                    $user_access_sql .= ", email='$email'";
                }
                if($user_name != $user_name_new) {
                    // rename picture if it exists
                    if($picture_exists) {
                        rename("images/faces/$user_name.jpg", "images/faces/$user_name_new.jpg");
                    }
                }
            } else if($email) {
                $user_access_sql .= " email='$email'";
            }
            $user_access_sql .= " WHERE user_id=$user_id";
            mysql_query($user_access_sql) or die(header("location:error.php?e=Error+Updating+User+Access+Table"));
        }
    
        /* Update tables if new entries for city, country, education, profession, or state is entered */
    
        // If a new education value is entered, insert the new education and update the user data
        $this->add_education($education_id,$education_add,$user_id);
        
        // If a new profession value is entered, insert the new profession and update the user data
        $this->add_profession($profession_id,$profession_add,$user_id);
        
        // If a new city value is entered, insert the new city and update the user data
        $this->add_city($city_id,$city_add,$user_id);
        
        // If a new state value is entered, insert the new state and update the user data
        $this->add_state($state_id,$state_add,$user_id);
        
        // If a new country value is entered, insert the new country and update the user data
        $this->add_country($country_id,$country_add,$user_id); 
    }
    
    /**
     * Add user using the post data
     */
    public function add_user()
    {
        $title = $_POST["title"];
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $preferred_name = $_POST["preferred_name"];
        $gender = $_POST["gender"];
        $dob_month = $_POST["dob_month"];
        $dob_date = $_POST["dob_date"];
        $dob_year = $_POST["dob_year"];
        $pob = $_POST["pob"];
        $dod_month = $_POST["dod_month"];
        $dod_date = $_POST["dod_date"];
        $dod_year = $_POST["dod_year"];
        $pod = $_POST["pod"];
        $dom_month = $_POST["dom_month"];
        $dom_date = $_POST["dom_date"];
        $dom_year = $_POST["dom_year"];
        $pom = $_POST["pom"];
        $email = $_POST["email"];
        $education_id = $_POST["education"];
        $education_add = $_POST["education_add"];
        $profession_id = $_POST["profession"];
        $profession_add = $_POST["profession_add"];
        $street_1 = $_POST["street_1"];
        $street_2 = $_POST["street_2"];
        $street_3 = $_POST["street_3"];
        $city_id = $_POST["city"];
        $city_add = $_POST["city_add"];
        $state_id = $_POST["state"];
        $state_add = $_POST["state_add"];
        $zipcode = $_POST["zipcode"];
        $country_id = $_POST["country"];
        $country_add = $_POST["country_add"];
        $number_home = $_POST["number_home"];
        $number_cell = $_POST["number_cell"];
        $marital_status_id = $_POST["marital_status"];
        $father_id = $_POST["hidden_father_id"];
        $mother_id = $_POST["hidden_mother_id"];
        $spouse_id = $_POST["hidden_spouse_id"];
        $show_email = $_POST["show_email"];
        $show_picture = $_POST["show_picture"];
        $delete_picture = $_POST["delete_picture"];
        $admin_access = $_POST["admin_access"];
        $add_access = $_POST["add_access"];
        $edit_access = $_POST["edit_access"];
        $user_admin_access = $_POST["hidden_user_admin_access"];
        
        // Change preferred_name if required     
        if(!$preferred_name)
            $preferred_name = $first_name;
    
        // create a unique user name based on first_name and last_name and create random password
        $user_name = $this->create_user_name($first_name,$last_name);
        $password = $first_name . $last_name;

        // create entry in dir_users_access
        $user_access_sql = "INSERT INTO dir_users_access (user_name, password, email";
        if($admin) {
            $user_access_sql .= ",admin_access,add_access,edit_access";
        }
        $user_access_sql .= ") VALUES ('$user_name', SHA('$password'), '$email'"; 
        if($admin) {
            $user_access_sql .= ",$admin_access, $add_access, $edit_access";
        }
        $user_access_sql .= ")";
        mysql_query($user_access_sql) or die(header('location: error.php?e=Error+Adding+User+To+Access+Table'));
        
        $user_id = mysql_insert_id();
    
        // create entry in dir_users_data    
        $user_data_sql = "INSERT INTO dir_users_data VALUES ($user_id, '$title', '$first_name'";
        
        if($middle_name) {
            $user_data_sql .= ", '$middle_name'";
        } else {
            $user_data_sql .= ", NULL";
        }
    
        $user_data_sql .= ", '$last_name', '$preferred_name', $gender";
    
        if($street_1) {
            $user_data_sql .= ", '$street_1'";
        } else {
            $user_data_sql .= ", NULL";
        }
        if($street_2) {
            $user_data_sql .= ", '$street_2'";
        } else {
            $user_data_sql .= ", NULL";
        }
        if($street_3) {
            $user_data_sql .= ", '$street_3'";
        } else {
            $user_data_sql .= ", NULL";
        }
        if($city_id != "add") {
            $user_data_sql .= ", $city_id";
        } else {
            $user_data_sql .= ", 0";
        }
        if($state_id != "add") {
            $user_data_sql .= ", $state_id";
        } else {
            $user_data_sql .= ", 0";
        }
        if($zipcode) {
            $user_data_sql .= ", '$zipcode'";
        } else {
            $user_data_sql .= ", NULL";
        }
        if($country_id != "add") {
            $user_data_sql .= ", $country_id";
        } else {
            $user_data_sql .= ", 0";
        }
        
        if($dob_year) {
            if($dob_month && $dob_month < 10) {
                $dob_month = "0" . $dob_month;
            }
            if($dob_date && $dob_date < 10) {
                $dob_date = "0" . $dob_date;
            }
            
            if($dob_month and $dob_date) {
                $user_data_sql .= ", '$dob_year-$dob_month-$dob_date'";
            } else if($dob_month) {
                $user_data_sql .= ", '$dob_year-$dob_month-01'";
            } else if($dob_date) {
                $user_data_sql .= ", '$dob_year-01-$dob_date'";
            } else {
                $user_data_sql .= ", '$dob_year-01-01'";
            }
        } else {
            $user_data_sql .= ", NULL";
        }
        if($pob) {
            $user_data_sql .= ", '$pob'";
        } else {
            $user_data_sql .= ", NULL";
        }
        
        if($dod_year) {
            if($dod_month && $dod_month < 10) {
                $dod_month = "0" . $dod_month;
            }
            if($dod_date && $dod_date < 10) {
                $dod_date = "0" . $dod_date;
            }
            
            if($dod_month and $dod_date) {
                $user_data_sql .= ", '$dod_year-$dod_month-$dod_date'";
            } else if($dod_month) {
                $user_data_sql .= ", '$dod_year-$dod_month-01'";
            } else if($dod_date) {
                $user_data_sql .= ", '$dod_year-01-$dod_date'";
            } else {
                $user_data_sql .= ", '$dod_year-01-01'";
            }
        } else {
            $user_data_sql .= ", NULL";
        }
        if($pod) {
            $user_data_sql .= ", '$pod'";
        } else {
            $user_data_sql .= ", NULL";
        }
        
        if($dom_year) {
            if($dom_month && $dom_month < 10) {
                $dom_month = "0" . $dom_month;
            }
            if($dom_date && $dom_date < 10) {
                $dom_date = "0" . $dom_date;
            }
            
            if($dom_month and $dom_date) {
                $user_data_sql .= ", '$dom_year-$dom_month-$dom_date'";
            } else if($dom_month) {
                $user_data_sql .= ", '$dom_year-$dom_month-01'";
            } else if($dom_date) {
                $user_data_sql .= ", '$dom_year-01-$dom_date'";
            } else {
                $user_data_sql .= ", '$dom_year-01-01'";
            }
        } else {
            $user_data_sql .= ", NULL";
        }
        if($pom) {
            $user_data_sql .= ", '$pom'";
        } else {
            $user_data_sql .= ", NULL";
        }
        
        if($education_id != "add") {
            $user_data_sql .= ", $education_id";
        } else {
            $user_data_sql .= ", 0";
        }
    
        if($profession_id != "add") {
            $user_data_sql .= ", $profession_id";
        } else {
            $user_data_sql .= ", 0";
        }
    
        if($number_home) {
            $user_data_sql .= ", '$number_home'";
        } else {
            $user_data_sql .= ", NULL";
        }
        
        if($number_cell) {
            $user_data_sql .= ", '$number_cell'";
        } else {
            $user_data_sql .= ", NULL";
        }
    
        $user_data_sql .= ", $marital_status_id";
    
        // upload picture if $_FILES["picture"]["name"] exists
        if($_FILES["picture"]["name"]) {
            move_uploaded_file($_FILES["picture"]["tmp_name"], "images/faces/$user_name.jpg");
            $user_data_sql .= ", 1";
        } else {
            $user_data_sql .= ", 0";
        }
        
        $user_data_sql .= ", $show_email";
        $user_data_sql .= ")";
        mysql_query($user_data_sql) or die(header('location: error.php?e=Error+Adding+User+Data'));

        // create entry in dir_relations and update spouse's spouse_id
        $relations_sql = "INSERT INTO dir_relations VALUES ($user_id, $father_id, $mother_id, $spouse_id)";
        mysql_query($relations_sql) or die(header('location: error.php?e=Error+Adding+Relationship+Data+For+New+User'));
        $this->update_spouse($spouse_id,$user_id);
        
        // create entry in dir_logs_activity
        $activity_sql = "INSERT INTO dir_logs_activity (user_id) VALUES ($user_id)";
        mysql_query($activity_sql) or die(header('location: error.php?=e=Error+Adding+Activity+Data+For+New+User'));
    
        /* Update tables if new entries for city, country, education, profession, or state is entered */
    
        // if a new education value is entered, insert new value into dir_education and update dir_users_data
        $this->add_education($education_id,$education_add,$user_id);
        
        // if a new profession value is entered, insert new value into dir_profession and update dir_users_data
        $this->add_profession($profession_id,$profession_add,$user_id);
        
        // if a new city value is entered, insert new value into dir_cities and update dir_users_data
        $this->add_city($city_id,$city_add,$user_id);
        
        // if a new state value is entered, insert new value into dir_states and update dir_users_data
        $this->add_state($state_id,$state_add,$user_id);
        
        // if a new country value is entered, insert new value into dir_countries and update dir_users_data
        $this->add_country($country_id,$country_add,$user_id);
    
        return $user_id;
    }

    /**
     * Update the spouse's spouse entry
     */
    protected function update_spouse($sid,$uid)
    {
        // If the spouse id is greater than zero, update the spouse, else set the spouse's spouse id to zero where the previous spouse is the user
        if($sid > 0) {
            mysql_query("UPDATE dir_relations SET spouse_id=$uid WHERE user_id=$sid") or die(header("location:error.php?e=Error+Updating+Spouse+Id+To+Add+Spouse"));
        } else if($sid == 0) {
            // Get the user id of the user who have a spouse id equal to the user
            $query = mysql_query("SELECT user_id FROM dir_relations WHERE spouse_id=$uid") or die(header("location:error.php?e=Error+Updating+Spouse+Id+To+Erase+Spouse"));
            if(mysql_num_rows($query) > 0) {
                while($data = mysql_fetch_array($query)) {
                    // Set the spouse id to zero
                    mysql_query("UPDATE dir_relations SET spouse_id=0 WHERE user_id=" . $data["user_id"]) or die(header("location:error.php?e=Error+Resetting+Spouse+Id"));
                }
            }
        }
    }

    /**
     * Add a new education
     */
    protected function add_education($id,$new,$uid)
    {
        // Add the education if the id is 'add'
        if($id == "add") {
            // Check to see if the education already exists
            $check = mysql_query("SELECT education_id FROM dir_education WHERE education = '$new'") or die(header("location:error.php?e=Error+Checking+Educations+Database+For+Exisiting+Education"));
            if(mysql_num_rows($check) == 0) {
                // If it doesn't exist, insert the new education into the education table and update the user's info
                mysql_query("INSERT INTO dir_education (education) VALUES ('$new')") or die(header("location:error.php?e=Error+Adding+New+Education"));
                mysql_query("UPDATE dir_users_data SET education_id=" . mysql_insert_id() . " WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+With+New+Education"));
            } else if(mysql_num_rows($check) == 1) {
                // If it does exist, then update the user's info with the existing id
                $data = mysql_fetch_array($check);
                $duplicate_id = $data["education_id"];
                mysql_query("UPDATE dir_users_data SET education_id=$duplicate_id WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+Education"));
            }  
        }
    }

    /**
     * Add a new profession
     */
    protected function add_profession($id,$new,$uid)
    {
        if($id == "add") {
            // Check to see if the profession already exists
            $check = mysql_query("SELECT profession_id FROM dir_profession WHERE profession = '$new'") or die(header("location:error.php?e=Error+Checking+Professions+Database+For+Exisiting+Profession"));
            if(mysql_num_rows($check) == 0) {
                // If it doesn't exist, insert the new profession into the profession table and update the user's info
                mysql_query("INSERT INTO dir_profession (profession) VALUES ('$new')") or die(header("location:error.php?e=Error+Adding+New+Profession"));
                mysql_query("UPDATE dir_users_data SET profession_id=" . mysql_insert_id() . " WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+With+New+Profession"));
            } else if(mysql_num_rows($check) == 1) {
                // If it does exist, then update the user's info with the existing id
                $data = mysql_fetch_array($check);
                $duplicate_id = $data["profession_id"];
                mysql_query("UPDATE dir_users_data SET profession_id=$duplicate_id WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+Profession"));
            }  
        }
    }

    /**
     * Add a new city
     */
    protected function add_city($id,$new,$uid)
    {
        if($id == "add") {
            // Check to see if the city already exists
            $check = mysql_query("SELECT city_id FROM dir_cities WHERE city = '$new'") or die(header("location:error.php?e=Error+Checking+Cities+Database+For+Exisiting+City"));
            if(mysql_num_rows($check) == 0) {
                // If it doesn't exist, insert the new city into the city table and update the user's info
                mysql_query("INSERT INTO dir_cities (city) VALUES ('$new')") or die(header("location:error.php?e=Error+Adding+New+City"));
                mysql_query("UPDATE dir_users_data SET city_id=" . mysql_insert_id() . " WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+With+New+City"));
            } else if(mysql_num_rows($check) == 1) {
                // If it does exist, then update the user's info with the existing id
                $data = mysql_fetch_array($check);
                $duplicate_id = $data["city_id"];
                mysql_query("UPDATE dir_users_data SET city_id=$duplicate_id WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+City"));
            }  
        }
    }
    
    /**
     * Add a new state
     */
    protected function add_state($id,$new,$uid)
    {
        if($id == "add") {
            // Check to see if the state already exists
            $check = mysql_query("SELECT state_id FROM dir_states WHERE state = '$new'") or die(header("location:error.php?e=Error+Checking+States+Database+For+Exisiting+State"));
            if(mysql_num_rows($check) == 0) {
                // If it doesn't exist, insert the new state into the state table and update the user's info
                mysql_query("INSERT INTO dir_states (state) VALUES ('$new')") or die(header("location:error.php?e=Error+Adding+New+State"));
                mysql_query("UPDATE dir_users_data SET state_id=" . mysql_insert_id() . " WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+With+New+State"));
            } else if(mysql_num_rows($check) == 1) {
                // If it does exist, then update the user's info with the existing id
                $data = mysql_fetch_array($check);
                $duplicate_id = $data["state_id"];
                mysql_query("UPDATE dir_users_data SET state_id=$duplicate_id WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+State"));
            }  
        }
    }
    
    /**
     * Add a new country
     */
    protected function add_country($id,$new,$uid)
    {
        if($id == "add") {
            // Check to see if the country already exists
            $check = mysql_query("SELECT country_id FROM dir_countries WHERE country = '$new'") or die(header("location:error.php?e=Error+Checking+Countries+Database+For+Exisiting+Country"));
            if(mysql_num_rows($check) == 0) {
                // If it doesn't exist, insert the new country into the country table and update the user's info
                mysql_query("INSERT INTO dir_countries (country) VALUES ('$new')") or die(header("location:error.php?e=Error+Adding+New+Country"));
                mysql_query("UPDATE dir_users_data SET country_id=" . mysql_insert_id() . " WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+With+New+Country"));
            } else if(mysql_num_rows($check) == 1) {
                // If it does exist, then update the user's info with the existing id
                $data = mysql_fetch_array($check);
                $duplicate_id = $data["country_id"];
                mysql_query("UPDATE dir_users_data SET country_id=$duplicate_id WHERE user_id=$uid") or die(header("location:error.php?e=Error+Updating+User+Country"));
            }  
        }
    }
    
    protected function create_user_name($first,$last)
    {
        // Look for usernames that are similar to the last name
        $query = mysql_query("SELECT user_name FROM dir_users_access WHERE user_name LIKE '%$last' ORDER BY user_name") or die(header("location:error.php?e=Error+Creating+User+Name"));
        
        // Create the standard username
        $uname = strtolower(substr($first,0,1) . $last);
    
        if(mysql_num_rows($query) == 0) {
            // If there are no usernames that end with the user's last name, return the standard username
            return $uname;
        } else {
            // If there are usernames that end with the user's last name, store them all in an array and create a new username
            while($data = mysql_fetch_array($query)) {
                $arr[] = $data["user_name"];
            }
            
            $char_count = 0; // Start count at 0 to account for the first letter incase there are multiple users with the same last name
            $unique_name = 0; // Loop flag
            
            // Loop through creating new user names as long as we dont have a unique match
            while($unique_name == 0) {
                $char_count++;
                $unique_name = 1;
                
                // add additional letter of first_name to create a new user_name
                $uname = strtolower(substr($first,0,$char_count) . $last);
                
                // iterate through each user_name and see if there is a match
                for($i = 0; $i < count($arr); $i++) {
                    if($uname == $arr[$i]) {
                        $unique_name = 0;
                        continue;
                    }
                }
    
                // user_name consisting of full first_name and full last_name is taken
                if($uname == $first.$last and $unique_name == 0) {
                    $temp = $first;
                    $first = $last;
                    $last = $temp;
                    $char_count = 0;
                }
            }
            
            return $uname;
        }
    }

    /**
     * Get an array of recently added/edited/update users
     */
    public function get_recent($order_by,$limit = 10)
    {
        // Query the activity logs table and arrange on the value of $order_by with a limit of $limit
        $sql = mysql_query("SELECT 
                            logs.user_id, user_name, last_name, preferred_name, gender, marital_status,
                            education, profession, city, state, country, show_picture 
                            FROM dir_logs_activity AS logs 
                            JOIN dir_users_access AS access
                            ON logs.user_id = access.user_id 
                            JOIN dir_users_data AS users 
                            ON logs.user_id = users.user_id 
                            JOIN dir_education AS education 
                            ON users.education_id = education.education_id 
                            JOIN dir_profession AS profession 
                            ON users.profession_id = profession.profession_id 
                            JOIN dir_cities AS cities 
                            ON users.city_id = cities.city_id 
                            JOIN dir_states AS states 
                            ON users.state_id = states.state_id 
                            JOIN dir_countries AS countries 
                            ON users.country_id = countries.country_id 
                            JOIN dir_marital_status AS marital_status 
                            ON users.marital_status_id = marital_status.marital_status_id 
                            ORDER BY $order_by DESC 
                            LIMIT $limit") or die(header("location:error.php?e=Error+Getting+Recent+User+Data"));
        
        // Loop through the data and store each users information
        $i = 0;
        while($data = mysql_fetch_assoc($sql)) {
            // Set the data array
            foreach($data as $key=>$value) {
                $recent[$i][$key] = $value;
            }
            
            // Set the uri of the profile picture
            if($data["show_picture"] && file_exists("images/faces/" . $data["user_name"] . ".jpg")) {
                $recent[$i]["picture_uri"] = "images/faces/" . $data["user_name"] . ".jpg";
            } else if($data["gender"]) {
                $recent[$i]["picture_uri"] = "images/faces/no_image_male.jpg";
            } else {
                $recent[$i]["picture_uri"] = "images/faces/no_image_female.jpg";
            }
            
            $i++;
        }
        
        return $recent;
    }
}
?>