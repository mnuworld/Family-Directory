{extends file="_layout.tpl"}
{block name="content"}
<div class="activity_container">
    <div class="section_header">Recently Added</div>
    <div class="section_container">
        {foreach $recently_added as $family_member}
            <div onclick="location.href='showuser.php?uid={$family_member.user_id}'">{include file="_user_tile.tpl"}</div>
        {/foreach}
    </div>
</div>
  
<div class="activity_container">
    <div class="section_header">Recently Viewed</div>
    <div class="section_container">
        {foreach $recently_viewed as $family_member}
            <div onclick="location.href='showuser.php?uid={$family_member.user_id}'">{include file="_user_tile.tpl"}</div>
        {/foreach}
    </div>
</div>
  
<div class="activity_container">
    <div class="section_header">Recently Edited</div>
    <div class="section_container">
        {foreach $recently_edited as $family_member}
            <div onclick="location.href='showuser.php?uid={$family_member.user_id}'">{include file="_user_tile.tpl"}</div>
        {/foreach}
    </div>
</div>
{/block}