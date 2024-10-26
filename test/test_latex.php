<meta charset="utf-8">
<?php


include "latex.php";

latex_settings('cz,html');

print latex_formula('x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}');

print "<hr>";

print latex_formula('S = \sum_{n=1}^\infty \frac{1}{n^2}');

print "<hr>";

print latex_formula('E = mc^2');

print "<hr>";

print latex_formula('[ frac{1}{2} + frac{1}{3} = frac{3}{6} + frac{2}{6} = frac{5}{6} ]');

print "<hr>";

print latex_formula('\text{CH}_4 + 2 \text{O}_2 \rightarrow \text{CO}_2 + 2 \text{H}_2 \text{O} ');

print "<hr>";

print latex_version();
?>

