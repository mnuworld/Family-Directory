<div id="{$family_member.user_id}" class="family_tree_tile" onMouseOver="highlightSelection(this,1)" onMouseOut="highlightSelection(this,0)">
    <img src="{$family_member.picture_uri}" />
    {if $family_member.phrase}
        <span class="family_tree_not_on_file">{$family_member.phrase}</span>
    {elseif $family_member.marital_status == "Deceased"}
        <span class="family_tree_deceased">{$family_member.preferred_name} {$family_member.last_name}</span>
    {else}
        <span>{$family_member.preferred_name} {$family_member.last_name}</span>
    {/if}
</div>