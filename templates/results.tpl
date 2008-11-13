{if $export == 'csv'}
{foreach from=$questions item=q key=qid name=questions}
"{$q.question|replace:'"':'""'}"
{foreach from=$q.answers.m item=a name=answers}
{assign var=aid value=$a.key}
"{$persons.$aid|replace:'"':'""'}","{$a.count}"
{/foreach}

"{$q.question|replace:'"':'""'}"
{foreach from=$q.answers.w item=a name=answers}
{assign var=aid value=$a.key}
"{$persons.$aid|replace:'"':'""'}","{$a.count}"
{/foreach}

{/foreach}
{else}
{include file=aheader.tpl}
<table class="btable">
  <tr>
    <th class="question">Titel</th>
    <th class="mister">Mister</th>
    <th class="missis">Missis</th>
  </tr>
{foreach from=$questions item=q key=qid name=questions}
  <tr>
    <td class="question">{$q.question}</td>
    <td class="mister">
      <ul>
{foreach from=$q.answers.m item=a name=answers key=count}
{if $count lt 3 or $all}
{assign var=aid value=$a.key}
<li>{$persons.$aid} ({$a.count})</li>
{/if}
{/foreach}
      </ul>
    </td>
    <td class="missis">
      <ul>
{foreach from=$q.answers.w item=a name=answers key=count}
{if $count lt 3 or $all}
{assign var=aid value=$a.key}
<li>{$persons.$aid} ({$a.count})</li>
{/if}
{/foreach}
      </ul>
    </td>
  </tr>
{/foreach}
</table>
<a href="{$smarty.server.PHP_SELF}?page=results&amp;display=all">Alle anzeigen</a> <a href="{$smarty.server.PHP_SELF}?page=results&amp;export=csv">CSV Export</a>
{include file=afooter.tpl}
{/if}
