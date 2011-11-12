<div id="{$family_member.user_id}" class="user_tile" onMouseOver="highlightSelection(this,1)" onMouseOut="highlightSelection(this,0)">
    <img src="{$family_member.picture_uri}" />
    {if $family_member.user_id == 0}
        <div class="user_tile_phrase">{$family_member.phrase}</div>
    {else}
        <div>{$family_member.preferred_name} {$family_member.last_name}</div>
        {if $family_member.marital_status == "Deceased"}
            <div>Deceased</div>
        {elseif $family_member.profession != "NA" || $family_member.education != "NA"}
            {if $family_member.profession != "NA" && $family_member.education != "NA"}
                <div>{$family_member.profession} ({$family_member.education})</div>
            {elseif $family_member.profession != "NA"}
                <div>{$family_member.profession}</div>
            {elseif $family_member.education != "NA"}
                <div>{$family_member.education}</div>
            {/if}
        {/if}
        
        <div>
            {if $family_member.city != "NA" && $family_member.state != "NA" && $family_member.country != "NA"}
                {$family_member.city}, {$family_member.state}, {$family_member.country}
            {elseif $family_member.city != "NA" && $family_member.state != "NA"}
                {$family_member.city}, {$family_member.state}
            {elseif $family_member.city != "NA" && $family_member.country != "NA"}
                {$family_member.city}, {$family_member.country}            
            {elseif $family_member.state != "NA" && $family_member.country != "NA"}
                {$family_member.state}, {$family_member.country}            
            {elseif $family_member.city != "NA"}
                {$family_member.city}            
            {elseif $family_member.state != "NA"}
                {$family_member.state}            
            {elseif $family_member.country != "NA"}
                {$family_member.country}
            {/if}
        </div>
    {/if}
</div>