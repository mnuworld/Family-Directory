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
 * User object class contains all objects and methods pertaining to the current logged in user.
 * Authentication checks, password resets, user info (e.g. name, email, location), etc.
 * 
 */
class User extends FamilyMember
{
    // Variables used to authenticate user
    protected $user_id;    
    protected $user_name;
    protected $admin_access;
    protected $add_access;
    protected $edit_access;
     
    /**
     * User construct
     */
    public function __construct()
    {
        // Get the cookie data related to the authenticated user. If they dont exists, set the value to null
        $this->user_id = empty($_COOKIE['user_id']) ? null : $_COOKIE['user_id'];
        $this->user_name = empty($_COOKIE['user_name']) ? null : $_COOKIE['user_name'];
        $this->admin_access = empty($_COOKIE['admin_access']) ? null : $_COOKIE['admin_access'];
        $this->add_access = empty($_COOKIE['add_access']) ? null : $_COOKIE['add_access'];
        $this->edit_access = empty($_COOKIE['edit_access']) ? null : $_COOKIE['edit_access'];
        parent::__construct($this->user_id);
    }
    
    /**
     * Redirect the user if not logged in
     */
    public function require_login()
    {
        // If the cookie for the user name is not set, redirect user to the login page
        if(empty($this->user_name)) {
            header("location:login.php");
            exit;
        }
    }
    
    /**
     * Authenticate the user or redirect to login page on invalid login
     */
    public function authenticate_user($post_data)
    {
        $user_name = $post_data["user_name"]; 
        $password = $post_data["password"];
        $remember_me = $post_data["remember_me"];
        
        // Clean the post data of sql injections
        $user_name = stripslashes($user_name);
        $password = stripslashes($password);
        $user_name = mysql_real_escape_string($user_name);
        $password = mysql_real_escape_string($password);
        
        // Query the database
        $query = mysql_query("SELECT
                              user_id, user_name, admin_access, add_access, edit_access
                              FROM dir_users_access
                              WHERE user_name = '$user_name' and password = SHA1('$password')");
        
        // If only one match, set the login cookies and rediret user to home page
        if(mysql_num_rows($query) === 1) {
            $data = mysql_fetch_assoc($query);
            
            // Get the remember me settings and set the expire time for the cookie
            if($remember_me == "on") {
                $time = time()+60*60*24*180;
            } else {
                $time = 0;
            }
            
            // Set the cookies
            setcookie("user_id",$data["user_id"],$time);
            setcookie("user_name",$data["user_name"],$time);
            setcookie("admin_access",$data["admin_access"],$time);
            setcookie("add_access",$data["add_access"],$time);
            setcookie("edit_access",$data["edit_access"],$time);
            
            // Redirect to home page
            header("location:/");
            exit;
        } else {
            // Redirect back to login page with an error message
            header("location:login.php?m=bl");
            exit;
        }
    }
    
    /**
     * Get the user id of the current user
     */
    public function get_user_id($credential = null)
    {
        // If a credential is specified, query the database for the user id, otherwise return whats stored
        if($credential) {
            // Query the database checking either the user name or the email for a match
            $query = mysql_query("SELECT user_id FROM dir_users_access WHERE user_name = '$credential' OR email = '$credential'");
            $data = mysql_fetch_array($query);
            
            // Return the user id if there is a single match, otherwise return false
            if(!mysql_error() and mysql_num_rows($query) == 1) {
                return $data["user_id"];
            } else {
                return false;
            }
        } else {
            return $this->user_id;
        }
    }
    
    /**
     * Get the user name of the current user
     */
    public function get_user_name($credential = null)
    {
        // If a credential is specified, query the database for the user name, otherwise return whats stored
        if($credential) {
            // Query the database for the user name
            $query = mysql_query("SELECT user_name FROM dir_users_access WHERE user_id = '$credential'");
            $data = mysql_fetch_array($query);
            
            // Return the user name if only one record is found
            if(!mysql_error() and mysql_num_rows($query) == 1) {
                return $data["user_name"];
            } else {
                return false;
            }
        } else {
            return $this->user_name;
        }
    }
    
    /**
     * Get the email of the current user
     */
    public function get_user_email($credential = null)
    {
        // If a credential is specified, query the database for the email
        if($credential) {
            // Query the database for the email
            $query = mysql_query("SELECT email FROM dir_users_access WHERE user_id = $credential");
            $data = mysql_fetch_array($query);
            
            // Return the email if only one record is found
            if(!mysql_error() and mysql_num_rows($query) == 1) {
                return $data["email"];
            } else {
                return false;
            }
        }
    }
    
    /**
     * Get the admin access of the current user
     */
    public function get_admin_access() { return $this->admin_access; }
    
    /**
     * Get the add access of the current user
     */
    public function get_add_access() { return $this->add_access; }
    
    /**
     * Get the edit access of the current user
     */
    public function get_edit_access() { return $this->edit_access; }
    
    /**
     * Create the password reset token
     */
    public function create_forgot_password_token($uid)
    {
        // Set the default time zone
        date_default_timezone_set('UTC');
        
        // Get all known tokens to check the new token against and sort the token
        $data = mysql_fetch_assoc(mysql_query("SELECT token FROM dir_users_forgot_password WHERE active_token = 1"));
        if($data["token"]) {
            sort($data);
        }
        $continue = true;
    
        // Loop through each token as long as the new randomly generated token matches one currently in the database
        while($continue) {
            // Create the new token and encrypt
            $key = $uid;
            for($i = 0; $i < 40; $i++) {
                $key .= chr(rand(97,122));
            }
            $token = hash('sha1',$key);
    
            // If a token already exists in the databae, check the new token and make sure it doesnt match one already in the database
            if($data["token"]) {
                if(array_search($token,$data) == false) {
                    // Insert the new token in the database, set the continue flag
                    mysql_query("INSERT INTO dir_users_forgot_password (user_id,token,request_date) VALUES ($uid,'$token','" . date("Y-m-d\TH:i:sO") . "')");
                    $continue = false;
                }
            } else {
                // If no token exists in the database, insert the first one, set the continue flag
                mysql_query("INSERT INTO dir_users_forgot_password (user_id,token,request_date) VALUES ($uid,'$token','" . date("Y-m-d\TH:i:sO") . "')");
                $continue = false;
            }
        }

        return $token;
    }
    
    /**
     * Check the password reset token against the password reset database and return a token id
     */
    public function check_forgot_password_token($uid,$token)
    {
        // Query the forgot password database and get the token id if it exists
        $query = mysql_query("SELECT token_id FROM dir_users_forgot_password WHERE user_id = $uid and token = '$token' and active_token = 1");
        $data = mysql_fetch_array($query);
        
        // Return the token id if there is only one match, otherwise return false
        if(!mysql_error() and mysql_num_rows($query) == 1) {
            return $data["token_id"];
        } else {
            return false;
        }
    }

    /**
     * Disable the password reset token so that it is not used again
     */
    public function disable_forgot_password_token($uid)
    {
        // Set the default timezone
        date_default_timezone_set('UTC');
        
        // Update the database and deactivate all tokens for the given user
        mysql_query("UPDATE dir_users_forgot_password SET active_token = 0, deactivate_date = '" . date("Y-m-d\TH:i:sO") . "' WHERE user_id = $uid");
        
        // Return a flag on the mysql error
        if(!mysql_error()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reset password
     */
    public function reset_password($uid,$password_old,$password_new)
    {
        // If an old password was specified, check if the old password matches whats in the database
        if($password_old) {
            // Query the database for a user id with the supplied password
            $password_check = mysql_query("SELECT user_id FROM dir_users_access WHERE user_id=$uid and password=SHA('$password_old')");
            
            // If there is a match, reset the password, otherwise redirect to an error page
            if(mysql_num_rows($password_check) == 1) {
                mysql_query("UPDATE dir_users_access SET password=SHA('$password_new') WHERE user_id=$uid") or die(header('location:error.php'));
            } else {
                header("location:error.php?e=Password+Reset+Failed");
                exit;
            }
        } else {
            // If no old password was specified, reset is from an admin
            mysql_query("UPDATE dir_users_access SET password=SHA('$password_new') WHERE user_id=$uid") or die(header('location:error.php'));
        }
    }

    /**
     * Get the user list for a specific log view
     */
    public function logs_get_users($view)
    {
        if($view == "visits") {
            $sql = "SELECT DISTINCT user_id FROM dir_logs_visits GROUP BY user_id ORDER BY MIN(visit_date) LIMIT 10";
        } else if($view == "views") {
            $sql = "SELECT DISTINCT user_id FROM dir_logs_activity WHERE !ISNULL(view_by_id) ORDER BY view_date DESC LIMIT 10";
        } else if($view == "adds") {
            $sql = "SELECT DISTINCT user_id FROM dir_logs_activity WHERE !ISNULL(add_by_id) ORDER BY add_date DESC LIMIT 10";
        } else if($view == "edits") {
            $sql = "SELECT DISTINCT user_id FROM dir_logs_activity WHERE !ISNULL(edit_by_id) ORDER BY edit_date DESC LIMIT 10";
        }
        
        $i = 0;
        $query = mysql_query($sql) or die(header("location:error.php?e=Error+Getting+Log+Data"));
        
        if(mysql_num_rows($query) == 0) {
            $log_users[$i]["user_id"] = 0;
            $log_users[$i]["picture_uri"] = "images/faces/no_image_male.jpg";
            $log_users[$i]["phrase"] = "No Users Found";
        } else {
            while($data = mysql_fetch_assoc($query)) {
                $family_member = new FamilyMember($data["user_id"]);
                
                if($data["user_id"] == 0) {
                    $log_users[$i]["user_id"] = 0;
                    $log_users[$i]["picture_uri"] = "images/faces/no_image_male.jpg";
                    $log_users[$i]["phrase"] = "Anonymous Users";
                } else {
                    $log_users[$i] = $family_member->get_user_info();
                }
                
                $i++;
            }
        }

        return $log_users;
    }

    public function logs_get_records($view,$uid)
    {
        // Set the timezone to CST
        date_default_timezone_set('America/Chicago');
        
        // Query the database
        $i = 0;
        if($view == "visits") {
            $query = mysql_query("SELECT * FROM dir_logs_visits WHERE user_id = $uid ORDER BY visit_date DESC LIMIT 20") or die(header("location:error.php?e=Error+Getting+Log+Data+For+User+Visits"));
            
            // Loop through the data and output the display
            while($data = mysql_fetch_assoc($query)) {
                $log_records[$i] = $data;
                $log_records[$i]["visit_date"] = date("M j, Y",strtotime($data["visit_date"]));
                $log_records[$i]["visit_time"] = date("g:i a",strtotime($data["visit_date"]));
                $i++;
            }
        } else {
            // Get the log activity for the user
            $query = mysql_query("SELECT * FROM dir_logs_activity WHERE user_id = $uid") or die(header("location:error.php?e=Error+Getting+Log+Activity+For+User"));
            $data = mysql_fetch_assoc($query);
        
            // Get the data on the user based on the view
            if($view == "views") {
                $activity_user = new FamilyMember($data["view_by_id"]);
                $log_records[0] = $activity_user->get_user_info();
                $log_records[0]["activity_date"] = date("M j, Y",strtotime($data["view_date"]));
                $log_records[0]["activity_time"] = date("g:i a",strtotime($data["view_date"]));
            } else if($view == "adds") {
                $activity_user = new FamilyMember($data["add_by_id"]);
                $log_records[0] = $activity_user->get_user_info();
                $log_records[0]["activity_date"] = date("M j, Y",strtotime($data["add_date"]));
                $log_records[0]["activity_time"] = date("g:i a",strtotime($data["add_date"]));
            } else if($view == "edits") {
                $activity_user = new FamilyMember($data["edit_by_id"]);
                $log_records[0] = $activity_user->get_user_info();
                $log_records[0]["activity_date"] = date("M j, Y",strtotime($data["edit_date"]));
                $log_records[0]["activity_time"] = date("g:i a",strtotime($data["edit_date"]));
            }
        }

        return $log_records;
    }

    /**
     * Log user activity
     */
    public function log_activity($activity,$user_id,$viewer_id)
    {
        date_default_timezone_set('UTC');
        
        if($activity == "visit") {
            mysql_query("INSERT INTO dir_logs_visits (user_id, visit_date, ip, browser, page_visited) VALUES ($viewer_id, '" . date('Y-m-d\TH:i:sO') . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["HTTP_USER_AGENT"] . "', '" . $_SERVER["REQUEST_URI"] . "')");    
        } else if($activity == "view") {
            mysql_query("UPDATE dir_logs_activity SET view_date='" . date('Y-m-d\TH:i:sO') . "', view_by_id=$viewer_id WHERE user_id=$user_id");
        } else if($activity == "edit") {
            mysql_query("UPDATE dir_logs_activity SET edit_date='" . date('Y-m-d\TH:i:sO') . "', edit_by_id=$viewer_id WHERE user_id=$user_id");
        } else if($activity == "add") {
            mysql_query("UPDATE dir_logs_activity SET add_date='" . date('Y-m-d\TH:i:sO') . "', add_by_id=$viewer_id WHERE user_id=$user_id");
        } 
    }

    /**
     * Log all emails sent to users
     */
    public function log_emails($from,$to,$subject,$body)
    {
        date_default_timezone_set('UTC');
        
        mysql_query("INSERT INTO dir_logs_emails (from_addr,to_addr,subject,body,sent_date) VALUES ('$from','$to','$subject','$body','" . date('Y-m-d\TH:i:sO') . "')");
    }
    
    /**
     * Log error code
     */
    public function log_error($uid,$referer,$error)
    {
        date_default_timezone_set('UTC');
        
        if($referer) {
            mysql_query("INSERT INTO dir_logs_errors (user_id,referer_page,error_code,error_date) VALUES($uid,'$referer','$error','" . date('Y-m-d\TH:i:sO') . "')");
            echo mysql_error();
        } else {
            mysql_query("INSERT INTO dir_logs_errors (user_id,error_code,error_date) VALUES($uid,'$error','" . date('Y-m-d\TH:i:sO') . "')");
            echo mysql_error();
        }
    }

    /**
     * Send user email
     */
    public function send_email($to,$subject,$body)
    {
        // Use phpmailer class if it exists, otherwise use the default mail() method
        if(file_exists("C:\php\includes\class.phpmailer.php")) {
            $host = "host";
            $from = "email@server.com";
            $password = "password";
            $from_name = "from";
            
            for($i = 0; $i < count($to); $i++) {
                require("c:\php\includes\class.phpmailer.php");
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $from;
                $mail->Password = $password;
                $mail->From = $from;
                $mail->FromName = $from_name;
                $mail->AddAddress($to[$i]); 
                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->Send();
                
                // Log email
                $this->log_emails($from,$to[$i],$subject,$body);
            }
        } else {
            // Create the headers to send email using php's email() method
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: from <email@server.com>\r\n";
            
            for($i = 0; $i < count($to); $i++) {
                // Send email
                mail($to[$i],$subject,$body,$headers);
                
                // Log email
                $this->log_emails("email@server.com",$to[$i],$subject,$body);
            }
        }
    }
}
?>