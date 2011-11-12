{extends file="_layout.tpl"}
{block name="content"}
<form enctype="multipart/form-data" method="post" name="user_form" id="user_form">
    <div id="subject_family_container">
        <div class="section_header">Change {$subject.preferred_name}'s Father
            <span class="x_box" id="father_x_box" onclick="hideUsers('father',1,{$subject.father.0.user_id})"><img src="" width="16" /></span>
        </div>
        {foreach $subject.father as $family_member}
            <div class="section_container">
                <div id="father" onclick="changeUserTile('father',1,{$family_member.user_id})">{include file="_user_tile.tpl"}</div>
                <div class="change_user" id="change_father"></div>
            </div>
        {/foreach}
        
        <div class="section_header">Change {$subject.preferred_name}'s Mother
            <span class="x_box" id="mother_x_box" onclick="hideUsers('mother',0,{$subject.mother.0.user_id})"><img src="" width="16" /></span>
        </div>
        {foreach $subject.mother as $family_member}
            <div class="section_container">
                <div id="mother" onclick="changeUserTile('mother',0,{$family_member.user_id})">{include file="_user_tile.tpl"}</div>
                <div class="change_user" id="change_mother"></div>
            </div>
        {/foreach}
        
        <div class="section_header">Change {$subject.preferred_name}'s
            {if $subject.gender == 0}
                Husband
            {else}
                Wife
            {/if}
            <span class="x_box" id="spouse_x_box" onclick="hideUsers('spouse',{if $subject.gender}0{else}1{/if},{$subject.spouse.0.user_id})"><img src="" width="16" /></span>
        </div>
        {foreach $subject.spouse as $family_member}
            <div class="section_container">
                <div id="spouse" onclick="changeUserTile('spouse',{if $subject.gender}0{else}1{/if},{$subject.spouse.0.user_id})">{include file="_user_tile.tpl"}</div>
                <div class="change_user" id="change_spouse"></div>
            </div>
        {/foreach}
    
        <div class="section_header">Change {$subject.preferred_name}'s Picture</div>
        <div class="section_container">
            <img src="{$subject.picture_uri}" width="100%" />
            <div>
                <input type="checkbox" name="delete_picture" value="1" /> Delete Picture
            </div>
            <input type="file" name="picture" name="Upload Picture" />
        </div>
    </div>

    <div id="subject_container">
        <div class="section_header">{$subject.first_name} {$subject.last_name}'s Info</div>
        <div class="section_container" style="cursor:default">
            <table width="100%" cellpadding="5" cellspacing="0" class="user_content" id="show_user_table">
                <tr>
                    <td width="20%">Username:</td>
                    <td>
                        {if $admin_access}
                            <input type="text" name="user_name" id="user_name" value="{$subject.user_name}" maxlength="50" size="25" class="input_field" />    
                        {else}
                            {$subject.user_name}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Title:</td>
                    <td><input type="text" name="title" id="title" value="{$subject.title}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">First Name:</td>
                    <td>
                        {if $admin_access}
                            <input type="text" name="first_name" id="first_name" value="{$subject.first_name}" maxlength="50" size="25" class="input_field" />
                        {else}
                            {$subject.first_name}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Middle Name:</td>
                    <td><input type="text" name="middle_name" id="middle_name" value="{$subject.middle_name}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Last Name:</td>
                    <td>
                        {if $admin_access}
                            <input type="text" name="last_name" id="last_name" value="{$subject.last_name}" maxlength="50" size="25" class="input_field" />
                        {else}
                            {$subject.last_name}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Preferred Name:</td>
                    <td><input type="text" name="preferred_name" id="preferred_name" value="{$subject.preferred_name}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Gender:</td>
                    <td>
                        <select name="gender">
                            <option value="0" {if $subject.gender == 0} selected {/if}>Female</option>
                            <option value="1" {if $subject.gender == 1} selected {/if}>Male</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Date of Birth:</td>
                    <td>
                        <select name="dob_month">
                            {foreach $months as $month}
                                <option value="{$month.id}" {if $subject.dob_month == $month.id} selected {/if}>{$month.month}</option>
                            {/foreach}
                        </select>
                        <select name="dob_date">
                            {foreach $dates as $date}
                                <option value="{$date.id}" {if $subject.dob_date == $date.id} selected {/if}>{$date.date}</option>
                            {/foreach}
                        </select>
                        <select name="dob_year">
                            {foreach $years as $year}
                                <option value="{$year.id}" {if $subject.dob_year == $year.id} selected {/if}>{$year.year}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Place of Birth:</td>
                    <td><input type="text" name="pob" id="pob" value="{$subject.pob}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Date of Death:</td>
                    <td>
                        <select name="dod_month">
                            {foreach $months as $month}
                                <option value="{$month.id}" {if $subject.dod_month == $month.id} selected {/if}>{$month.month}</option>
                            {/foreach}
                        </select>
                        <select name="dod_date">
                            {foreach $dates as $date}
                                <option value="{$date.id}" {if $subject.dod_date == $date.id} selected {/if}>{$date.date}</option>
                            {/foreach}
                        </select>
                        <select name="dod_year">
                            {foreach $years as $year}
                                <option value="{$year.id}" {if $subject.dod_year == $year.id} selected {/if}>{$year.year}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Place of Death:</td>
                    <td><input type="text" name="pod" id="pod" value="{$subject.pod}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Date of Marriage:</td>
                    <td>
                        <select name="dom_month">
                            {foreach $months as $month}
                                <option value="{$month.id}" {if $subject.dom_month == $month.id} selected {/if}>{$month.month}</option>
                            {/foreach}
                        </select>
                        <select name="dom_date">
                            {foreach $dates as $date}
                                <option value="{$date.id}" {if $subject.dom_date == $date.id} selected {/if}>{$date.date}</option>
                            {/foreach}
                        </select>
                        <select name="dom_year">
                            {foreach $years as $year}
                                <option value="{$year.id}" {if $subject.dom_year == $year.id} selected {/if}>{$year.year}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Place of Marriage:</td>
                    <td><input type="text" name="pom" id="pom" value="{$subject.pom}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Email:</td>
                    <td><input type="text" name="email" id="email" value="{$subject.email}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Show Email:</td>
                    <td>
                        <select name="show_email">
                            <option value="0" {if $subject.show_email == 0} selected {/if}>No</option>
                            <option value="1" {if $subject.show_email == 1} selected {/if}>Yes</option>
                        </select>                            
                    </td>
                </tr>
                <tr>
                    <td width="20%">Home Number:</td>
                    <td><input type="text" name="number_home" id="number_home" value="{$subject.number_home}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Cell Number:</td>
                    <td><input type="text" name="number_cell" id="number_cell" value="{$subject.number_cell}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">Education:</td>
                    <td>
                        <select name="education" onchange="checkAddField(form,'education')">
                            {foreach $search_educations as $education}
                                <option value="{$education.id}" {if $subject.education == $education.education}selected{/if}>{$education.education}</option>
                            {/foreach}
                            <option value="add">Add Education...</option>
                        </select>
                        <input type="text" name="education_add" id="education_add" style="display:none" />
                    </td>
                </tr>
                <tr>
                    <td width="20%">Profession:</td>
                    <td>
                        <select name="profession" onchange="checkAddField(form,'profession')">
                            {foreach $search_professions as $profession}
                                <option value="{$profession.id}" {if $subject.profession == $profession.profession}selected{/if}>{$profession.profession}</option>
                            {/foreach}
                            <option value="add">Add Profession...</option>
                        </select>
                        <input type="text" name="profession_add" id="profession_add" style="display:none" />
                    </td>
                </tr>
                <tr>
                    <td width="20%" rowspan="3">Street Address:</td>
                    <td><input type="text" name="street_1" id="street_1" value="{$subject.street_1}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td><input type="text" name="street_2" id="street_2" value="{$subject.street_2}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td><input type="text" name="street_3" id="street_3" value="{$subject.street_3}" maxlength="50" size="25" class="input_field" /></td>
                </tr>
                <tr>
                    <td width="20%">City:</td>
                    <td>
                        <select name="city" onchange="checkAddField(form,'city')">
                            {foreach $search_cities as $city}
                                <option value="{$city.id}" {if $subject.city == $city.city}selected{/if}>{$city.city}</option>
                            {/foreach}
                            <option value="add">Add City...</option>
                        </select>
                        <input type="text" name="city_add" id="city_add" style="display:none" />
                    </td>
                </tr>                   
                <tr>
                    <td width="20%">State:</td>
                    <td>
                        <select name="state" onchange="checkAddField(form,'state')">
                            {foreach $search_states as $state}
                                <option value="{$state.id}" {if $subject.state == $state.state}selected{/if}>{$state.state}</option>
                            {/foreach}
                            <option value="add">Add State...</option>
                        </select>     
                        <input type="text" name="state_add" id="state_add" style="display:none" />
                    </td>
                </tr>                     
                <tr>
                    <td width="20%">Country:</td>
                    <td>
                        <select name="country" onchange="checkAddField(form,'country')">
                            {foreach $search_countries as $country}
                                <option value="{$country.id}" {if $subject.country == $country.country}selected{/if}>{$country.country}</option>
                            {/foreach}
                            <option value="add">Add Country...</option>
                        </select>
                        <input type="text" name="country_add" id="country_add" style="display:none" />
                    </td>
                </tr>                     
                <tr>
                    <td width="20%">Zip Code:</td>
                    <td><input type="text" name="zipcode" id="zipcode" value="{$subject.zipcode}" maxlength="50" size="25" class="input_field" /></td>
                </tr>   
                <tr>
                    <td width="20%">Marital Status:</td>
                    <td>
                        <select name="marital_status" id="marital_status">
                            {foreach $search_marital_statuses as $marital_status}
                                <option value="{$marital_status.id}" {if $subject.marital_status == $marital_status.marital_status}selected{/if}>{$marital_status.marital_status}</option>
                            {/foreach}
                        </select>                         
                    </td>
                </tr> 
                {if $admin_access or $user_name == $subject.user_name}
                    <tr>
                        <td width="20%">Show Picture:</td>
                        <td>
                            <select name="show_picture">
                                <option value="0" {if $subject.show_picture == 0} selected {/if}>No</option>
                                <option value="1" {if $subject.show_picture == 1} selected {/if}>Yes</option>
                            </select>                            
                        </td>
                    </tr>
                {/if}
                {if $admin_access}
                    <tr>
                        <td width="20%">Admin Access:</td>
                        <td>
                            <select name="admin_access">
                                <option value="0" {if $subject.admin_access == 0} selected {/if}>No</option>
                                <option value="1" {if $subject.admin_access == 1} selected {/if}>Yes</option>
                            </select>                            
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">Add Access:</td>
                        <td>
                            <select name="add_access">
                                <option value="0" {if $subject.add_access == 0} selected {/if}>No</option>
                                <option value="1" {if $subject.add_access == 1} selected {/if}>Yes</option>
                            </select>                            
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">Edit Access:</td>
                        <td>
                            <select name="edit_access">
                                <option value="0" {if $subject.edit_access == 0} selected {/if}>No</option>
                                <option value="1" {if $subject.edit_access == 1} selected {/if}>Yes</option>
                            </select>                            
                        </td>
                    </tr>
                {/if}
            </table>
        </div>
    </div>
    <input type="hidden" name="hidden_user_id" id="hidden_user_id" value="{$subject.user_id}" />
    <input type="hidden" name="hidden_user_name" id="hidden_user_name" value="{$subject.user_name}" />
    <input type="hidden" name="hidden_user_admin_access" id="hidden_user_admin_access" value="{$admin_access}" />
    <input type="hidden" name="hidden_father_id" id="hidden_father_id" value="{$subject.father[0].user_id}" />
    <input type="hidden" name="hidden_mother_id" id="hidden_mother_id" value="{$subject.mother[0].user_id}" />
    <input type="hidden" name="hidden_spouse_id" id="hidden_spouse_id" value="{$subject.spouse[0].user_id}" />
    <input type="hidden" name="hidden_submit_form" id="hidden_submit_form" value="0" />
</form>

<div id="subject_option_container">
    <div class="section_header">
        Save Changes
        <span id="save_loader" class="x_box"><img src="images/loader_white_small.gif" width="16" /></span>
    </div>
    <div class="section_container">
        <div>
            <center>
                <input type="button" class="save_cancel_button" value="Save" onclick="checkForm()" />
                <input type="button" class="save_cancel_button" value="Cancel" onclick="location.href='showuser.php?uid={$subject.user_id}'" />
            </center>
        </div>
    </div>
    
    {if $admin_access || $user_id == $subject.user_id}
        <div class="section_header">Change Password</div>
        <div class="section_container">
            <form name="change_password" method="post">
                {if !$admin_access}
                    <div>
                        <label for="password_current">Current Password:</label>
                        <input type="password" name="password_current" id="password_current" />
                    </div>
                {/if}
                <div>
                    <label for="password_new_1" style="margin-right:16px">New Password:</label>
                    <input type="password" name="password_new_1" id="password_new_1" />
                </div>
                <div>
                    <label for="password_new_2">Confirm Password</label>
                    <input type="password" name="password_new_2" id="password_new_2" />
                </div>
                <div>
                    <div style="width:117px; float:left;">&nbsp;</div>
                    <input type="button" class="change_password_button" onclick="checkPasswordChangeForm(form)" value="Change Password" />
                </div>
                <input type="hidden" name="hidden_password_change_submit" id="hidden_password_change_submit" value="0" />
            </form>
        </div>
    {/if}
</div>
{/block}