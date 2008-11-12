{include file=header.tpl}
<div id="questions" style="width: 400px;">
{foreach from=$questions item=q key=qid name=questions}
<h3>{$q.question}</h3>
<ul>
{foreach from=$q.answers item=a name=answers}
{assign var=aid value=$a.key}
<li>{$persons.$aid} ({$a.count})</li>
{/foreach}
</ul>
{/foreach}
</div>
{include file=footer.tpl}
