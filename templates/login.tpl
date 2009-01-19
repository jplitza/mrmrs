{include file='header.tpl'}
<form action="{$smarty.server.PHP_SELF}" method="post" id="login"{if $login_failed} class="false"{/if}>
<fieldset>
{if $login_failed}<legend>Ungültiger Key</legend>{/if}
<input type="text" name="key" size="8" value="{$smarty.post.key|escape}" />
<input type="submit" value="OK" />
</fieldset>
</form>
</body>
</html>
