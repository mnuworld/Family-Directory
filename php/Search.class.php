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
 * Search object class contains all objects and methods pertaining to search.
 * Search form data, query, search results, etc.
 * 
 */
class Search
{
    // Arrays to hold the query data
    protected $cities;
    protected $states;
    protected $countries;
    protected $educations;
    protected $professions;
    protected $marital_statuses;
    
    /**
     * Search construct
     * 
     * Query the database to get data to populate the search form
     */
    public function __construct()
    {
        // Query the database to populate the search form
        $city_sql = mysql_query("SELECT city_id, city FROM dir_cities WHERE city_id > 0 ORDER BY city") or die(header("location:error.php?Error+Getting+City+Names"));
        $state_sql = mysql_query("SELECT state_id, state FROM dir_states WHERE state_id > 0 ORDER BY state") or die(header("location:error.php?Error+Getting+State+Names"));
        $country_sql = mysql_query("SELECT country_id, country FROM dir_countries WHERE country_id > 0 ORDER BY country") or die(header("location:error.php?Error+Getting+Country+Names"));
        $education_sql = mysql_query("SELECT education_id, education FROM dir_education WHERE education_id > 0 ORDER BY education") or die(header("location:error.php?Error+Getting+Education+Names"));
        $profession_sql = mysql_query("SELECT profession_id, profession FROM dir_profession WHERE profession_id > 0 ORDER BY profession") or die(header("location:error.php?Error+Getting+Profession+Names"));
        $marital_status_sql = mysql_query("SELECT marital_status_id, marital_status FROM dir_marital_status WHERE marital_status_id > 0 ORDER BY marital_status_id") or die(header("location:error.php?Error+Getting+Marital+Statues"));
    
        // Loop through the cities and store the data
        $i = 1;
        $this->cities[0]["id"] = "0";
        $this->cities[0]["city"] = "";
        while($data = mysql_fetch_array($city_sql)) {
            $this->cities[$i]["id"] = $data["city_id"];
            $this->cities[$i]["city"] = $data["city"];
            $i++;
        }
        
        // Loop through the states and store the data
        $i = 1;
        $this->states[0]["id"] = "0";
        $this->states[0]["state"] = "";
        while($data = mysql_fetch_array($state_sql)) {
            $this->states[$i]["id"] = $data["state_id"];
            $this->states[$i]["state"] = $data["state"];
            $i++;
        }
        
        // Loop through the countries and store the data
        $i = 1;
        $this->countries[0]["id"] = "0";
        $this->countries[0]["country"] = "";
        while($data = mysql_fetch_array($country_sql)) {
            $this->countries[$i]["id"] = $data["country_id"];
            $this->countries[$i]["country"] = $data["country"];
            $i++;
        }
        
        // Loop through the educations and store the data
        $i = 1;
        $this->educations[0]["id"] = "0";
        $this->educations[0]["education"] = "";
        while($data = mysql_fetch_array($education_sql)) {
            $this->educations[$i]["id"] = $data["education_id"];
            $this->educations[$i]["education"] = $data["education"];
            $i++;
        }
        
        // Loop through the professions and store the data
        $i = 1;
        $this->professions[0]["id"] = "0";
        $this->professions[0]["profession"] = "";
        while($data = mysql_fetch_array($profession_sql)) {
            $this->professions[$i]["id"] = $data["profession_id"];
            $this->professions[$i]["profession"] = $data["profession"];
            $i++;
        }
        
        // Loop through the marital statuses and store the data
        $i = 1;
        $this->marital_statuses[0]["id"] = "blank";
        $this->marital_statuses[0]["marital_status"] = "";
        while($data = mysql_fetch_array($marital_status_sql)) {
            $this->marital_statuses[$i]["id"] = $data["marital_status_id"];
            $this->marital_statuses[$i]["marital_status"] = $data["marital_status"];
            $i++;
        }
        
        return $this;
    }

    /**
     * Using the post data input, create the search query and return an array hold the data for all search results
     */
    public function get_search_results($post_data)
    {
        $i = 0;
        
        // Create the sql query
        $sql = $this->create_search_query($post_data);
        $query = mysql_query($sql) or die(header("location:error.php?e=Error+Searching+Database+For+Users"));
        
        // Loop through the results and create the results array by calling a new FamilyMember object for each result
        while($data = mysql_fetch_assoc($query)) {
            $family_member = new FamilyMember($data["user_id"]);
            $search_results[$i] = $family_member->get_user_info();
            $i++;
        }
        
        return $search_results;
    }

    /**
     * Create search query using criteria sent from the search form
     */
    protected function create_search_query($post_data)
    {
        $firstname = $post_data["firstname"];
        $lastname = $post_data["lastname"];
        $city = $post_data["city"];
        $state = $post_data["state"];
        $country = $post_data["country"];
        $education = $post_data["education"];
        $profession = $post_data["profession"];
        $maritalstatus = $post_data["maritalstatus"];
        $gender = $post_data["gender"];
        
        // Clean the post values of sql injections
        $firstname = mysql_real_escape_string(stripslashes($firstname));
        $lastname = mysql_real_escape_string(stripslashes($lastname));
        $city = mysql_real_escape_string(stripslashes($city));
        $state = mysql_real_escape_string(stripslashes($state));
        $country = mysql_real_escape_string(stripslashes($country));
        $education = mysql_real_escape_string(stripslashes($education));
        $profession = mysql_real_escape_string(stripslashes($profession));
        $maritalstatus = mysql_real_escape_string(stripslashes($maritalstatus));
        $gender = mysql_real_escape_string(stripslashes($gender));
        
        // Formulate the search query
        $sql = "SELECT 
                users.user_id 
                FROM dir_users_data users 
                JOIN dir_education education 
                ON users.education_id = education.education_id 
                JOIN dir_profession profession 
                ON users.profession_id = profession.profession_id 
                JOIN dir_cities cities 
                ON users.city_id = cities.city_id 
                JOIN dir_states states 
                ON users.state_id = states.state_id 
                JOIN dir_countries countries 
                ON users.country_id = countries.country_id 
                JOIN dir_marital_status marital_status 
                ON users.marital_status_id = marital_status.marital_status_id ";
        
        if ($firstname || $lastname || ($education != "0") || 
            ($profession != "0") || ($city != "0") || ($state != "0") || 
            ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
            $sql .= " WHERE ";
        } else {
            $sql .= " ";
        }
        
        if ($firstname) {
            $sql .= "(users.first_name LIKE '%" . $firstname . "%' OR users.preferred_name LIKE '%" . $firstname;
            if ($lastname || ($education != "0") || ($profession != "0") || ($city != "0") || ($state != "0") || ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "%') AND ";
            } else {
                $sql .= "%') ";
            }
        }
                
        if ($lastname) {
            $sql .= "users.last_name LIKE '%" . $lastname;
            if (($education != "0") || ($profession != "0") || ($city != "0") || ($state != "0") || ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "%' AND ";
            } else {
                $sql .= "%' ";
            }
        }
                
        if ($education != "0") {
            $sql .= "education.education_id = '" . $education;
            if (($profession != "0") || ($city != "0") || ($state != "0") || ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
                
        if ($profession != "0") {
            $sql .= "profession.profession_id = '" . $profession;
            if (($city != "0") || ($state != "0") || ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
        
        if ($city != "0") {
            $sql .= "cities.city_id = '" . $city;
            if (($state != "0") || ($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
        
        if ($state != "0") {
            $sql .= "states.state_id = '" . $state;
            if (($country != "0") || ($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
                
        if ($country != "0") {
            $sql .= "countries.country_id = '" . $country;
            if (($maritalstatus != "blank") || ($gender != "blank")) {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
                
        if ($maritalstatus != "blank") {
            $sql .= "marital_status.marital_status_id = '" . $maritalstatus;
            if ($gender != "blank" && $gender != "") {
                $sql .= "' AND ";
            } else {
                $sql .= "' ";
            }
        }
    
        if ($gender != "blank" && $gender != "") {
            $sql .= "users.gender = $gender ";
        } else {
            $sql .= " ";
        }
        
        $sql .= "ORDER BY users.last_name, users.first_name";
        
        return $sql;
    }

    /**
     * Get an array of city ids and city names
     */
    public function get_cities() { return $this->cities; }
    
    /**
     * Get an array of state ids and state names
     */
    public function get_states() { return $this->states; }
    
    /**
     * Get an array of country ids and country names
     */
    public function get_countries() { return $this->countries; }
    
    /**
     * Get an array of education ids and education names
     */
    public function get_educations() { return $this->educations; }
    
    /**
     * Get an array of profession ids and profession names
     */
    public function get_professions() { return $this->professions; }
    
    /**
     * Get an array of marital status ids and marital statuses
     */
    public function get_marital_statuses() { return $this->marital_statuses; }
}
?>