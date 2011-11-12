{extends file="_layout.tpl"}
{block name="content"}
<div id="log_container">
    <div id="log_menu_container">
        <div class="log_menu_item {if $view=='visits'} log_menu_item_selected {/if}" onclick="location.href='showlogs.php?v=visits'">
            Visits
        </div>
        <div class="log_menu_item {if $view=='views'} log_menu_item_selected {/if}" onclick="location.href='showlogs.php?v=views'">
            Views
        </div>
        <div class="log_menu_item {if $view=='adds'} log_menu_item_selected {/if}" onclick="location.href='showlogs.php?v=adds'">
            Adds
        </div>
        <div class="log_menu_item log_menu_right {if $view=='edits'} log_menu_item_selected {/if}" onclick="location.href='showlogs.php?v=edits'">
            Edits
        </div>
    </div>
    <div id="log_user_container">
        {foreach $log_users as $family_member}
            <div onclick="showLogs('{$view}',{$family_member.user_id})">{include file="_user_tile.tpl"}</div>
        {/foreach}
    </div>
    <div id="log_records_container"">
        {if $view == "visits"}
            <div id="log_records_header_visits">{$log_users.0.first_name} {$log_users.0.last_name}</div>
            
            {foreach $log_records as $record}
                <div>on {$record.visit_date} at {$record.visit_time} - {$record.ip}</div>
                <div><a href="{$record.page_visited}">{$record.page_visited}</a></div>
                <div class="log_records_visits">{$record.browser}</div>
            {/foreach}
        {else}
            {if $view == "views"}
                <div id="log_records_header">{$log_users.0.first_name} {$log_users.0.last_name} was last viewed by</div>
            {elseif $view == "adds"}
                <div id="log_records_header">{$log_users.0.first_name} {$log_users.0.last_name} was added by</div>
            {elseif $view == "edits"}
                <div id="log_records_header">{$log_users.0.first_name} {$log_users.0.last_name} was last edited by</div>
            {/if}
            
            {foreach $log_records as $family_member}
                {include file="_user_tile.tpl"}
            {/foreach}
            
            <div>on {$log_records.0.activity_date} at {$log_records.0.activity_time}</div>
        {/if}
    </div>
</div>
{/block}