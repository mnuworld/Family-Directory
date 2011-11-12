{extends file="_layout.tpl"}
{block name="content"}
<div id="family_tree_container">
    {if $view == "a"}
        {foreach $family_members as $family_member}
            <div id="anc_{$family_member.generation}_{$family_member.user_count}" class="anc family_tree_tile_container gen_{$family_member.generation}" {if $family_member.user_id} onclick='location.href="showuser.php?uid={$family_member.user_id}"' {/if}>
                {include file="_family_tree_tile.tpl"}
            </div>
        {/foreach}
        
        {for $gen=2 to 6}
            {for $item=1 to {math equation="pow(2,power-2)" power=$gen}}
                <div id="v_{$gen}_{$item}" class="family_tree_vertical_line gen_{$gen}"></div>
                <div id="h_{$gen}_{$item}" class="family_tree_horizontal_line gen_{$gen}"></div>
            {/for}
        {/for}
    {elseif $view == "d"}
        {foreach $family_members as $family_member}
            <div id="dec_{$family_member.user_id}_{$family_member.parent_id}" class="dec family_tree_tile_container gen_{$family_member.generation} parent_id_{$family_member.parent_id} spouse_id_{$family_member.spouse_id}" {if $family_member.user_id} onclick='location.href="showuser.php?uid={$family_member.user_id}"' {/if}>
                {include file="_family_tree_tile.tpl"}
            </div>
        {/foreach}
    {/if}
</div>
{/block}