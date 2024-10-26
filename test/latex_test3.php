<?php
include "latex.php";

// Nastavení na český formát s HTML entitami
latex_settings('cz,html');

// Test funkce latex_replace na text obsahující LaTeX výrazy
$text = 'Zde je vzorec: /*E = mc^2*/ a další vzorec: /*S = \sum_{n=1}^\infty \frac{1}{n^2}*/.';

print latex_replace($text);

?>
