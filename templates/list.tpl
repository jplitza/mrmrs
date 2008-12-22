{include file=header.tpl}
  <form action="{$smarty.server.PHP_SELF}" method="post" onsubmit="return check()">
  <table class="btable">
    <tr>
      <th class="question">Titel (Schüler)</th>
      <th class="mister">Mister</th>
      <th class="missis">Missis</th>
    </tr>
{section name='q' loop=$questions.0}
    <tr>
      <td class="question">{$questions.0[q].question}</td>
      <td class="mister">
        <select name="votes[{$questions.0[q].id}][m]">
<option value=""></option>
{html_options options=$persons.0.m}
        </select>
      </td>
      <td class="missis">
        <select name="votes[{$questions.0[q].id}][w]">
<option value=""></option>
{html_options options=$persons.0.w}
        </select>
      </td>
    </tr>
{/section}
  </table>
  <table class="btable">
    <tr>
      <th class="question">Titel (Lehrer)</th>
      <th class="mister">Mister</th>
      <th class="missis">Missis</th>
    </tr>
{section name='q' loop=$questions.1}
    <tr>
      <td class="question">{$questions.1[q].question}</td>
      <td class="mister">
        <select name="votes[{$questions.1[q].id}][m]">
<option value=""></option>
{html_options options=$persons.1.m}
        </select>
      </td>
      <td class="missis">
        <select name="votes[{$questions.1[q].id}][w]">
<option value=""></option>
{html_options options=$persons.1.w}
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
