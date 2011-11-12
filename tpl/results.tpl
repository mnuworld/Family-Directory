{extends file="_layout.tpl"}
{block name="content"}
<div class="search_results">
    <div class="section_header">
        Results 
        {if $results_count > 1}
            1 -
            {if $results_count < 10}
                {$results_count}
            {else}
                10
            {/if}
        {/if}
    </div>
    <div class="section_container">
        {foreach $results as $family_member name="results"}
            <div onclick="location.href='showuser.php?uid={$family_member.user_id}'">{include file="_user_tile.tpl"}</div>
            {if $smarty.foreach.results.iteration % 10 == 0 && $smarty.foreach.results.iteration != $smarty.foreach.results.total}
                    </div>
                </div>
                <div class="search_results">
                    <div class="section_header">
                        Results {$smarty.foreach.results.iteration+1}-
                        {if $smarty.foreach.results.total > $smarty.foreach.results.iteration+10}
                            {$smarty.foreach.results.iteration+10}
                        {else}
                            {$smarty.foreach.results.total}
                        {/if}
                    </div>
                    <div class="section_container">   
            {/if}
        {/foreach}
    </div>
</div>
{/block}