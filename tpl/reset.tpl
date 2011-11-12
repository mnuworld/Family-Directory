{extends file="_layout.tpl"}
{block name="content"}
{if $reset_request}
    <div id="reset_form">
        <form name="reset" method="post">
            <table width="465" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
                <tr>
                    <td colspan="2" align="center">
                        <span class="form_error">
                            {if $message == "bun"}
                                Username or Email does not exists
                            {elseif $message == "pe"}
                                Reset instructions emailed to you
                            {/if}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="300">Username or Email:</td>
                    <td><input name="user_name" id="user_name" type="text" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit_form" id="reset_form_submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
{elseif $reset_password}
    <div id="reset_form">
        <form name="reset" method="post">
            <table width="475" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
                <tr>
                    <td colspan="2" align="center">
                        <span class="form_error">
                            {if $message == "pne"}
                                Passwords do not match
                            {/if}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="125">Username:</td>
                    <td><input name="user_name" id="user_name" type="text" /></td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td><input name="password_1" id="password_1" type="password" /></td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td><input name="password_2" id="password_2" type="password" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit_form" id="reset_form_submit" value="Submit Form" /></td>
                </tr>
            </table>
        </form>
    </div>
{/if}
{/block}