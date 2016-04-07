<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Конвертер регистров");
?>

<script language=javascript1.3><!--
// * Copyright (c) Art. Lebedev Studio | http://www.design.ru/
// * Author: serge@design.ru | http://serge.design.ru/
function isLower(c) { return toLower(c) == c }
function isUpper(c) { return toUpper(c) == c }
function toUpper(c) { return c.toUpperCase() }
function toLower(c) { return c.toLowerCase() }
function formatTitle(s) {
var t = new String()
var sc = s.length
var c
var doUpperFlag = true
for (i = 0; i < sc; i++) {
c = s.charAt(i)
if ( doUpperFlag ) { c = toUpper(c) } else { c = toLower(c) }
if ( c == ' ' ) { doUpperFlag = true } else { doUpperFlag = false }
t = t + c
}
return t
}
function formatToggle(s) {
var t = new String()
var sc = s.length
var c
for (i = 0; i < sc; i++) {
c = s.charAt(i)
if ( isLower(c) ) { c = toUpper(c) } else { if ( isUpper(c) ) c = toLower(c) }
t = t + c
}
return t
}
function manager() {
// method values:
// -1 - copy
// 0 - uppercase
// 1 - lowercase
// 2 - title case
// 3 - toggle case
if ( document.formater.method[0].checked ) document.formater.target.value = toUpper(document.formater.source.value)
else if ( document.formater.method[1].checked ) document.formater.target.value = toLower(document.formater.source.value)
else if ( document.formater.method[2].checked ) document.formater.target.value = formatTitle(document.formater.source.value)
else if ( document.formater.method[3].checked ) document.formater.target.value = formatToggle(document.formater.source.value)
else document.formater.target.value = document.formater.source.value
}
//-->
</script>

<form name=formater>
Вклейте текст в&nbsp;окно (или напечатайте):
<table>
  <td align=right>Сброс: <input type=reset value=" X "></td>
<tr><td colspan=3>
<textarea name=source cols=65 rows=8 onKeyUp="manager()"></textarea><br>
<textarea name=target cols=65 rows=8></textarea>
<font size=-1>
<p></td></tr>
  </font>
<tr><td>
<input type=radio name=method value=0 onClick="manager()"> ВЕРХНИЙ РЕГИСТР<br>
<input type=radio name=method value=1 onClick="manager()"> нижний регистр<br>
<input type=radio name=method value=2 onClick="manager()"> Заглавные Буквы<br>
<input type=radio name=method value=3 onClick="manager()"> иНВЕРСИЯ рЕГИСТРА<br>
</td></tr>
<font size=-1>
</form>
</table>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>