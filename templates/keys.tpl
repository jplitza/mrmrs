{include file=aheader.tpl}
<div style="text-align: center;">Benutzt: {$counts.used} von {$counts.total}</div>
<table class="btable">
  <tr>
    <th>Key</th>
    <th>Benutzung</th>
    <th>Löschlink</th>
  </tr>
{section name="k" loop=$keys}
  <tr>
    <td>{$keys[k].key}</td>
    <td>{$keys[k].used|date_format:'%d.%m.%y %H:%M'}</td>
    <td>{if $keys[k].used}<a href="{$smarty.server.PHP_SELF}?page=keys&amp;delete={$keys[k].key}">Löschen</a>{else}<a href="{$smarty.server.PHP_SELF}?page=keys&amp;invalidate={$keys[k].key}">Ungültig</a>{/if}</td>
  </tr>
{/section}
</table>
<form action="{$smarty.server.PHP_SELF}" method="get">
<input type="hidden" name="page" value="keys" />
<input type="text" name="new" value="10" size="2" /> neue Keys erzeugen. <input type="submit" value="OK" />
</form>
{include file=afooter.tpl}
