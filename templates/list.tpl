{include file=header.tpl}
  <form action="{$smarty.server.PHP_SELF}" method="post" onsubmit="return check()">
  <table class="btable">
    <tr>
      <th class="question">Titel</th>
      <th class="mister">Mister</th>
      <th class="missis">Missis</th>
    </tr>
{section name='q' loop=$questions}
    <tr>
      <td class="question">{$questions[q].question}</td>
      <td class="mister">
        <select name="votes[{$questions[q].id}][m]">
<option value=""></option>
{html_options options=$persons.m}
        </select>
      </td>
      <td class="missis">
        <select name="votes[{$questions[q].id}][w]">
<option value=""></option>
{html_options options=$persons.w}
        </select>
      </td>
    </tr>
{/section}
  </table>
  <input type="hidden" name="key" value="{$smarty.post.key}" />
  <input type="submit" value="Absenden" />
  <input type="reset" value="Formular leeren" />
  </form>
{include file=footer.tpl}
