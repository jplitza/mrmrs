{include file=aheader.tpl}
{if not $deleted}
<p>Möchten Sie die Umfrage wirklich zurücksetzen? Damit gehen alle Abstimmungsergebnisse verloren und alle Keys werden auf die für den Produktivbetrieb gedachten Keys zurückgesetzt!</p>
<p>Geben Sie als Bestätigung bitte das Passwort ein:</p>
<form method="post" action="{$smarty.server.PHP_SELF}?page=reset">
<input type="password" name="confirmation" style="font-size: 300%; text-align: center; width: 400px;" size="8"/><br />
<input type="submit" value="DO NOT CLICK!" style="font-size: 300%; width: 400px;"/>
</form>
{else}
<p>Die Abstimmung wurde zurückgesetzt.</p>
{/if}
{include file=afooter.tpl}
