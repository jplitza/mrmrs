{include file=aheader.tpl}
{if not $deleted}
<p>Möchten Sie die Umfrage wirklich zurücksetzen? Damit gehen alle Abstimmungsergebnisse verloren und alle Keys werden gelöscht!</p>
<p>Geben Sie als Bestätigung bitte das Passwort ein:</p>
<form method="post" action="{$smarty.server.PHP_SELF}?page=reset">
<input type="password" name="confirmation" />
<input type="submit" value="OK" />
</form>
{else}
<p>Die Abstimmung wurde zurückgesetzt.</p>
{/if}
{include file=afooter.tpl}
