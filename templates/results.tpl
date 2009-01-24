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
    <th class="question">Titel (Schüler)</th>
    <th class="mister">Mister</th>
    <th class="missis">Missis</th>
  </tr>
{foreach from=$questions.0 item=q key=qid name=questions}
  <tr>
    <td class="question">{$q.question}</td>
    <td class="mister">
      <ul>
{foreach from=$q.answers.m item=ag name=agroups key=place}
{if $place le 3 or $all}
{foreach from=$ag item=a name=answers key=count}
{assign var=aid value=$a.key}
<li>{$place}. {$persons.$aid} ({$a.count})</li>
{/foreach}
{elseif $place eq 4}
<li>...</li>
{/if}
{/foreach}
<li style="list-style-type: none; font-style: italic;">Gesamt: {$counts.$qid.m}</li>
      </ul>
    </td>
    <td class="missis">
      <ul>
{foreach from=$q.answers.w item=ag name=agroups key=place}
{if $place le 3 or $all}
{foreach from=$ag item=a name=answers key=count}
{assign var=aid value=$a.key}
<li>{$place}. {$persons.$aid} ({$a.count})</li>
{/foreach}
{elseif $place eq 4}
<li>...</li>
{/if}
{/foreach}
<li style="list-style-type: none; font-style: italic;">Gesamt: {$counts.$qid.w}</li>
      </ul>
    </td>
  </tr>
{/foreach}
</table>
<table class="btable">
  <tr>
    <th class="question">Titel (Lehrer)</th>
    <th class="mister">Mister</th>
    <th class="missis">Missis</th>
  </tr>
{foreach from=$questions.1 item=q key=qid name=questions}
  <tr>
    <td class="question">{$q.question}</td>
    <td class="mister">
      <ul>
{foreach from=$q.answers.m item=ag name=agroups key=place}
{if $place le 3 or $all}
{foreach from=$ag item=a name=answers key=count}
{assign var=aid value=$a.key}
<li>{$place}. {$persons.$aid} ({$a.count})</li>
{/foreach}
{elseif $place eq 4}
<li>...</li>
{/if}
{/foreach}
<li style="list-style-type: none; font-style: italic;">Gesamt: {$counts.$qid.m}</li>
      </ul>
    </td>
    <td class="missis">
      <ul>
{foreach from=$q.answers.w item=ag name=agroups key=place}
{if $place le 3 or $all}
{foreach from=$ag item=a name=answers key=count}
{assign var=aid value=$a.key}
<li>{$place}. {$persons.$aid} ({$a.count})</li>
{/foreach}
{elseif $place eq 4}
<li>...</li>
{/if}
{/foreach}
<li style="list-style-type: none; font-style: italic;">Gesamt: {$counts.$qid.w}</li>
      </ul>
    </td>
  </tr>
{/foreach}
</table>
<table class="btable">
  <tr>
    <th class="question">Statistik</th>
    <th class="missis">Werte</th>
  </tr>
{foreach from=$questions.2 item=q key=qid name=questions}
  <tr>
    <td class="question">{$q.question}</td>
    <td class="missis">
      <ul>
{foreach from=$q.answers item=a name=answers key=count}
{if $count lt 3 or $all}
{assign var=aid value=$a.key}
<li>{$persons.$aid} ({$a.count})</li>
{elseif $count eq 4}
<li>...</li>
{/if}
{/foreach}
      </ul>
    </td>
  </tr>
{/foreach}
</table>
<table class="btable">
  <tr>
    <th class="question">Fakten</th>
    <th class="missis">Werte</th>
  </tr>
{foreach from=$questions.3 item=q key=qid name=questions}
  <tr>
    <td class="question">{$q.question}</td>
    <td class="missis">
      <ul>
<li>ja ({$q.answers.0.count})</li>
<li>nein ({math equation="y-x" x=$q.answers.0.count y=$votes})</li>
      </ul>
    </td>
  </tr>
{/foreach}
</table>
{if $all}
<a href="{$smarty.server.PHP_SELF}?page=results">Nicht alle anzeigen</a>
{else}
<a href="{$smarty.server.PHP_SELF}?page=results&amp;display=all">Alle anzeigen</a>
{/if}
<a href="{$smarty.server.PHP_SELF}?page=results&amp;export=csv">CSV Export</a>
{include file=afooter.tpl}
{/if}
