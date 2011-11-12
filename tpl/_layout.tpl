<!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
    <title>{$page_title}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta property="og:title" content="Khandan Directory" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.khandandirectory.com" />
    <meta property="og:image" content="http://www.khandandirectory.com/images/logo_short_small.png" />
    <meta property="og:site_name" content="Khandan Directory" />
    <meta property="og:description" content="Online directory and family tree" />
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="shortcut icon" href="images/favicon.ico" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
    
    {literal}
    <!-- Google Analytics Code Start -->
    <script>
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-23096643-1']);
        _gaq.push(['_setDomainName', '.khandandirectory.com']);
        _gaq.push(['_trackPageview']);
        
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <!-- Google Analytics Code End -->
    {/literal}
</head>

<body>
    <div id="header">
        <a href="/"><img src="images/logo_main.png"></a>
        {if $user_id}
            <div id="toolbox">
                {$user_first_name} {$user_last_name}
                <img src="images/arrow_down.png">
            </div>
            <ul id="toolbox_dropdown">
                {if $admin_access || $add_access}
                    <li><a href="adduser.php">Add User</a></li>
                {/if}
                <li><a href="showuser.php?uid={$user_id}">My Information</a></li>
                {if $admin_access}
                    <li><a href="showlogs.php">Show Logs</a></li>
                {/if}
                <li><a href="logout.php">Sign Out</a></li>
            </ul>
        {/if}
        
        {if $view == "a"}
            <div id="show_siblings">
                <form name="show_siblings">
                Show Siblings 
                <select name="siblings" id="siblings" onchange="changeGeneration(document.g_level.level.value)">
                    <option value="0">No</option>
                    <option value="1" selected>Yes</option>
                </select>
                </form>
            </div>
        {/if}
        
        {if $view == "a" || $view == "d"}
            <div id="select_level">
                <form name="g_level">
                    Generations 
                    <select id="level" name="level" onchange="changeGeneration(this.value)">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6" selected>6</option>
                    </select>
                </form>
            </div>
            <div id="left_button" onclick="moveFamilyTreeDivs(37)"><img src="images/arrow_left.png" class="arrow_button" /></div>
            <div id="right_button" onclick="moveFamilyTreeDivs(39)"><img src="images/arrow_right.png" class="arrow_button" /></div>
            <div id="up_button" onclick="moveFamilyTreeDivs(38)"><img src="images/arrow_up.png" class="arrow_button" /></div>
            <div id="down_button" onclick="moveFamilyTreeDivs(40)"><img src="images/arrow_down.png" class="arrow_button" /></div>
        {/if}
        
        {if $results}
            <div id="left_button" onclick="moveResults(37)"><img src="images/arrow_left.png" class="arrow_button" /></div>
            <div id="right_button" onclick="moveResults(39)"><img src="images/arrow_right.png" class="arrow_button" /></div>
        {/if}
    </div>
    <div id="container">
        {if $user_id && $view != "a" && $view != "d"}
            <div id="search_form">
                <form name="search" action="results.php" method="post">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td><label for="firstname">First Name</label></td>
                            <td><input type="text" name="firstname" id="firstname"></td>
                            <td><label for="lastname">Last Name</label></td>
                            <td><input type="text" name="lastname" id="lastname"></td>
                            <td><label for="gender">Gender</label></td>
                            <td>
                                <select name="gender" id="gender">
                                    <option value="blank"></option>
                                    <option value="1">Male</option>
                                    <option value="0">Female</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="city">City</label></td>
                            <td>
                                <select name="city" id="city">
                                    {foreach $search_cities as $city}
                                        <option value="{$city.id}">{$city.city}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="state">State</label></td>
                            <td>
                                <select name="state" id="state">
                                    {foreach $search_states as $state}
                                        <option value="{$state.id}">{$state.state}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="country">Country</label></td>
                            <td>
                                <select name="country" id="country">
                                    {foreach $search_countries as $country}
                                        <option value="{$country.id}">{$country.country}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="education">Education</label></td>
                            <td>
                                <select name="education" id="education">
                                    {foreach $search_educations as $education}
                                        <option value="{$education.id}">{$education.education}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="profession">Profession</label></td>
                            <td>
                                <select name="profession" id="profession">
                                    {foreach $search_professions as $profession}
                                        <option value="{$profession.id}">{$profession.profession}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="maritalstatus">Marital Status</label></td>
                            <td>
                                <select name="maritalstatus" id="maritalstatus">
                                    {foreach $search_marital_statuses as $marital_status}
                                        <option value="{$marital_status.id}">{$marital_status.marital_status}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><a href="#" onclick="document.search.submit(); return false;">Search</a></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div id="search_tab"><a href="#" onClick="toggleSearchForm(1); return false;">Open</a></div>
        {/if}
        <div id="content_container">
            {block name="content"}{/block}
        </div>
    </div>
    <div id="footer">&#169; 2011 khandandirectory.com. All Rights Reserved.</div>
</body>
</html>