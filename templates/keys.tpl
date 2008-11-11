{include file=header.tpl}
<table id="questions">
{section name="k" loop=$keys}
  <tr>
    <td>{$keys[k].key}</td>
    <td>{$keys[k].used|date_format:'%d.%m.%y %H:%M'}</td>
  </tr>
{/section}
</table>
{include file=footer.tpl}
