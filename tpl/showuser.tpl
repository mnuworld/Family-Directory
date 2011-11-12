{extends file="_layout.tpl"}
{block name="content"}
<div id="subject_family_container">
    <div class="section_header">{$subject.preferred_name}'s Father</div>
    {foreach $subject.father as $family_member}
        <div class="section_container"
            {if $family_member.user_id > 0}
                onclick="location.href='showuser.php?uid={$family_member.user_id}'"
            {/if}
        >{include file="_user_tile.tpl"}</div>
    {/foreach}
    
    <div class="section_header">{$subject.preferred_name}'s Mother</div>
    {foreach $subject.mother as $family_member}
        <div class="section_container"
            {if $family_member.user_id > 0}
                onclick="location.href='showuser.php?uid={$family_member.user_id}'"
            {/if}
        >{include file="_user_tile.tpl"}</div>
    {/foreach}
    
    <div class="section_header">{$subject.preferred_name}'s
        {if $subject.gender == 0}
            Husband
        {else}
            Wife
        {/if}
    </div>
    {foreach $subject.spouse as $family_member}
        <div class="section_container"
            {if $family_member.user_id > 0}
                onclick="location.href='showuser.php?uid={$family_member.user_id}'"
            {/if}
        >{include file="_user_tile.tpl"}</div>
    {/foreach}

    <div class="section_header">{$subject.preferred_name}'s Children</div>
    <div class="section_container">
        {foreach $subject.children as $family_member}
            <div 
                {if $family_member.user_id > 0}
                    onclick="location.href='showuser.php?uid={$family_member.user_id}'"
                {/if}
            >{include file="_user_tile.tpl"}</div>
        {/foreach}
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
	            		<span class="edit_data text" id="edit_user_name">{$subject.user_name}</span>	
            		{else}
            			{$subject.user_name}
            		{/if}
        		</td>
                <td rowspan="40" width="40%" align="center"><img src="{$subject.picture_uri}" width="200" height="200" /></td>
            </tr>
            <tr>
                <td width="20%">Full Name:</td>
                <td>
                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
	            		<span class="edit_data text" id="edit_title">{$subject.title} </span>
	            		<span class="edit_data text" id="edit_first_name">{$subject.first_name} </span>
	            		<span class="edit_data text" id="edit_middle_name">{$subject.middle_name} </span>
	            		<span class="edit_data text" id="edit_last_name">{$subject.last_name}</span>	
            		{else}
            			{$subject.title} {$subject.first_name} {$subject.middle_name} {$subject.last_name}
            		{/if}
            	</td>
            </tr>
            <tr>
                <td width="20%">Preferred Name:</td>
                <td>
                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
	            		<span class="edit_data text" id="edit_preferred_name">{$subject.preferred_name}</span>	
            		{else}
            			{$subject.preferred_name}
            		{/if}
            	</td>
            </tr>
            <tr>
                <td width="20%">Gender:</td>
                <td>
                    {if $subject.gender == 0}
                        Female
                    {else}
                        Male
                    {/if}
                </td>
            </tr>
            {if $subject.dob or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.dob}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Date of Birth:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data date">
		            			{if $subject.dob}
		            				{$subject.dob}
	            				{else}
	            					click to edit
            					{/if}
	            			</span>	
	            		{else}
	            			{$subject.dob}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.pob or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.pob}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Place of Birth:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_pob">{if $subject.pob}{$subject.pob}{else}click to edit{/if}</span>	
	            		{else}
	            			{$subject.pob}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.dod or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.dod}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Date of Death:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data date">
		            			{if $subject.dod}
		            				{$subject.dod}
	            				{else}
	            					click to edit
            					{/if}
	            			</span>	
	            		{else}
	            			{$subject.dod}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.pod or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.pod}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Place of Death:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_pod">{if $subject.pod}{$subject.pod}{else}click to edit{/if}</span>	
	            		{else}
	            			{$subject.pod}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.dom or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.dom}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Date of Marriage:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data date">
		            			{if $subject.dom}
		            				{$subject.dom}
	            				{else}
	            					click to edit
            					{/if}
	            			</span>	
	            		{else}
	            			{$subject.dom}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.pom or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.pom}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Place of Marriage:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_pom">{if $subject.pom}{$subject.pom}{else}click to edit{/if}</span>	
	            		{else}
	            			{$subject.pom}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if ($subject.email && $subject.show_email) or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.email}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Email:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_email">{if $subject.email}{$subject.email}{else}click to edit{/if}</span>	
	            		{else}
	            			{$subject.email}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.number_home or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.number_home}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Home Number:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_number_home">
		            			{if $subject.number_home}
		            				{$subject.number_home}
	            				{else}
	            					click to edit
            					{/if}
	            			</span>	
	            		{else}
	            			{$subject.number_home}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.number_cell or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.number_cell}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Cell Number:</td>
                    <td>
	                	{if $admin_access or $edit_access or $user_id == $subject.user_id}
		            		<span class="edit_data text" id="edit_number_cell">
		            			{if $subject.number_cell}
		            				{$subject.number_cell}
	            				{else}
	            					click to edit
            					{/if}
	            			</span>	
	            		{else}
	            			{$subject.number_cell}
	            		{/if}
            		</td>
                </tr>
            {/if}
            {if $subject.education != "NA" or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if $subject.education == "NA"}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Education:</td>
                    <td>{$subject.education}</td>
                </tr>
            {/if}
            {if $subject.profession != "NA" or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if $subject.profession == "NA"}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Profession:</td>
                    <td>{$subject.profession}</td>
                </tr> 
            {/if}    
            {if $subject.street_1 or $subject.city != "NA" or $subject.state != "NA" or $subject.country != "NA" or $admin_access or $edit_access or $user_id == $subject.user_id}
                <tr
                	{if !$subject.street_1 and $subject.city == "NA" and $subject.state == "NA" and $subject.country == "NA"}
                		style="display:none" class="show_more"
            		{/if}
                >
                    <td width="20%">Address:</td>
                    <td>
                        {if $subject.street_1}
                            {$subject.street_1}<br />
                        {/if}
                        {if $subject.city and $subject.state != "NA"}
                            {$subject.city}, {$subject.state} {$subject.zipcode}<br />
                        {else if $subject.city != "NA"}
                        	{$subject.city} {$subject.zipcode}<br />
                    	{else if $subject.state != "NA"}
                    		{$subject.state} {$subject.zipcode}<br />
                		{/if}
                        {if $subject.country != "NA" or $admin_access or $edit_access or $user_id == $subject.user_id}
                            {$subject.country}
                        {/if}
                    </td>
                </tr>
            {/if}
            <tr>
                <td width="20%">Marital Status:</td>
                <td>{$subject.marital_status}</td>
            </tr>
            {*{if $admin_access or $edit_access or $user_id == $subject.user_id}
            	<tr>
            		<td>&nbsp;</td>
            		<td>
            			<span id="show_more">
            				<a href="#" onclick="showMore(1); return false">+ Show More</a>
        				</span>
    				</td>
            	</tr>
            {/if}*}
        </table>
    </div>
</div>

<div id="subject_option_container">
    {if $admin_access or $edit_access or $user_id == $subject.user_id}
        <div class="section_header">Edit User</div>
        <div class="section_container">
            <center>
                <a href="edituser.php?uid={$subject.user_id}">Edit {$subject.preferred_name}</a>
            </center>
        </div>
    {/if}
  
    <div class="section_header">Family Tree</div>
    <div class="section_container">
        <center>
            <a href="familytree.php?v=a&uid={$subject.user_id}">Ancestors</a>
        </center>
        <center>
            {*<a href="familytree.php?v=d&uid={$subject.user_id}">Descendants</a>*}
        </center>
    </div>
</div>
<input type="hidden" id="hidden_user_id" value="{$subject.user_id}">
{/block}