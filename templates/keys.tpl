{include file=aheader.tpl}
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
    <td>{if $keys[k].used}<a href="{$smarty.server.PHP_SELF}?page=keys&amp;delete={$keys[k].key}">Löschen</a>{/if}</td>
  </tr>
{/section}
</table>
{include file=afooter.tpl}
