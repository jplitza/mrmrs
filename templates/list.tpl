{include file=header.tpl}
  <form action="{$smarty.server.PHP_SELF}" method="post" onsubmit="return check()">
  <div class="btable">
    <b>Willkommen bei der Mr. &amp; Mrs. Wahl 2009.</b><br />
    Wir wissen, dass das ne ganze Menge Titel sind und sich vielleicht in den Augen einiger einige Titel ähneln. Wir planen nicht, alle Titel in die Zeitung zu bringen, sondern werden nichtssagende Ergebnisse rausschmeißen und ähnliche Ergebnisse bei ähnlichen Titeln zusammenfassen. Wenn euch zu einem Titel einfach keiner/keine einfällt, könnt ihr das Feld auch leer lassen - bitte wählt nicht einfach irgendwen!<br />
    Bevor es los geht sei euch noch gesagt, dass <i>niemand</i>, auch das Gremium nicht, nachvollziehen kann, wer was gewählt hat.</div>
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
  <table class="btable">
    <tr>
      <th class="question">Statistiken</th>
      <th class="missis">Antwort</th>
    </tr>
{section name='q' loop=$questions.2}
    <tr>
      <td class="question">{$questions.2[q].question}</td>
      <td class="missis"><input type="text" name="votes[{$questions.2[q].id}][s]" /></td>
    </tr>
{/section}
  </table>
  <table class="btable">
    <tr>
      <th class="mister" style="border: 1px solid #000;">Fakten</th>
    </tr>
{section name='q' loop=$questions.3}
    <tr>
      <td class="mister" style="border: 1px solid #000;"><label><input type="checkbox" name="votes[{$questions.3[q].id}][b]" value="{$yesid}" /> {$questions.3[q].question}</label></td>
    </tr>
{/section}
  </table>
  <input type="hidden" name="key" value="{$smarty.post.key}" />
  <input type="submit" value="Absenden" />
  </form>
{include file=footer.tpl}
