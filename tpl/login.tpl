{extends file="_layout.tpl"}
{block name="content"}
<div id="login_form">
    <form name="login" method="post" action="login.php">
        <table width="250" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
            <tr>
                <td colspan="2" align="center">
                    {if $message == "bl"}
                        <span class="form_error">Invalid login</span>
                    {/if}
                </td>
            </tr>
            <tr>
                <td width="70">Username:</td>
                <td><input name="user_name" id="user_name" type="text" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input name="password" id="password" type="password" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox" name="remember_me" id="remember_me" /> Remember Me</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="login" id="login_form_submit" value="Login" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><a href="reset.php">Forgot Password?</a></td>
            </tr>
        </table>
    </form>
</div>
{/block}