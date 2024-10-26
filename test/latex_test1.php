<?php
include "latex.php";

// Nastavení formátu na český s HTML entitami
latex_settings('cz,html');

// Test základního převodu vzorce
print latex_formula('E = mc^2');

print '<hr>';

// Test zlomku s limitem a odmocninou
print latex_formula('x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}');

print '<hr>';

// Zobrazení aktuální verze knihovny
print latex_version();
?>
