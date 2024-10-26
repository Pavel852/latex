<?php
include "latex.php";

// Nastavení na anglický formát s UTF-8 znaky
latex_settings('us,char');

// Test různých výrazů s LaTeX syntaxí
print latex_formula('S = \sum_{n=1}^\infty \frac{1}{n^2}');
print '<hr>';
print latex_formula('[ frac{1}{2} + frac{1}{3} = frac{3}{6} + frac{2}{6} = frac{5}{6} ]');
print '<hr>';

// Zobrazení chemické rovnice
print latex_formula('\text{CH}_4 + 2 \text{O}_2 \rightarrow \text{CO}_2 + 2 \text{H}_2 \text{O}');

?>
