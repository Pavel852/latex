<?php
namespace Pavel852\Latex;
$g_latex_version='2.1';

/**
 * latex.php
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 * Year: 10/2024
 * Version: 2.1
 *
 * Popis:
 * Tato knihovna poskytuje funkce pro převod LaTeXových výrazů do HTML pomocí PHP.
 *
 * Funkce:
 * 1. latex_settings($options)
 *    - Nastaví globální nastavení pro parser.
 *    - Parametry:
 *      - $options (řetězec): Nastavení oddělená čárkou, např. 'cz,html' nebo 'us,char'.
 *    - Příklady použití:
 *      ```php
 *      latex_settings('cz,html'); // Nastaví české formátování a výstup speciálních znaků jako HTML entity.
 *      latex_settings('us,char'); // Nastaví anglické formátování a výstup speciálních znaků jako UTF-8 znaky.
 *      ```
 *
 * 2. latex_formula($input)
 *    - Převádí LaTeXový výraz do HTML.
 *    - Parametry:
 *      - $input (řetězec): LaTeXový výraz.
 *    - Příklady použití:
 *      ```php
 *      echo latex_formula('E = mc^2');
 *      echo latex_formula('S = \sum_{n=1}^\infty \frac{1}{n^2}');
 *      ```
 *
 * 3. latex_replace($text, $start='<$', $end='$>')
 *    - Nahrazuje v textu LaTeXové výrazy jejich vizuální podobou.
 *    - Parametry:
 *      - $text (řetězec): Text obsahující LaTeXové výrazy.
 *      - $start (řetězec): Počáteční oddělovač (výchozí '<$').
 *      - $end (řetězec): Koncový oddělovač (výchozí '$>').
 *    - Příklady použití:
 *      ```php
 *      $text = 'Toto je vzorec: <$E = mc^2$>.';
 *      echo latex_replace($text);
 *      ```
 *
 * 4. latex_version()
 *    - Vrací verzi knihovny.
 *    - Příklad použití:
 *      ```php
 *      echo latex_version();
 *      ```
 */

$latex_version = '2.1';
$latex_global_settings = array(
    'lang' => 'us',
    'output' => 'html', // 'html' nebo 'char'
);

function latex_version() {
    global $latex_version;
    return 'LaTeX PHP Parser Version ' . $latex_version;
}

function latex_settings($options) {
    global $latex_global_settings;

    $settings = explode(',', $options);
    foreach ($settings as $setting) {
        $setting = trim($setting);
        if ($setting == 'cz') {
            $latex_global_settings['lang'] = 'cz';
        } elseif ($setting == 'us') {
            $latex_global_settings['lang'] = 'us';
        } elseif ($setting == 'html') {
            $latex_global_settings['output'] = 'html';
        } elseif ($setting == 'char') {
            $latex_global_settings['output'] = 'char';
        }
    }
}

function latex_formula($input) {
    global $latex_global_settings;

    $lang = $latex_global_settings['lang'];
    $output_format = $latex_global_settings['output'];

    // Odstranění vnějších mezer
    $input = trim($input);

    // Definice náhrad pro speciální symboly a řecká písmena
    if ($output_format == 'html') {
        $replacements = array(
            '\\infty' => '&infin;',
            '\\pm' => '&plusmn;',
            '\\cdot' => '&middot;',
            '\\times' => '&times;',
            '\\div' => '&divide;',
            '\\leq' => '&le;',
            '\\geq' => '&ge;',
            '\\neq' => '&ne;',
            '\\approx' => '&asymp;',
            '\\sim' => '&sim;',
            '\\rightarrow' => '&rarr;',
            '\\leftarrow' => '&larr;',
            '\\leftrightarrow' => '&harr;',
            '\\alpha' => '&alpha;',
            '\\beta' => '&beta;',
            '\\gamma' => '&gamma;',
            '\\delta' => '&delta;',
            '\\epsilon' => '&epsilon;',
            '\\theta' => '&theta;',
            '\\lambda' => '&lambda;',
            '\\mu' => '&mu;',
            '\\pi' => '&pi;',
            '\\sigma' => '&sigma;',
            '\\phi' => '&phi;',
            '\\omega' => '&omega;',
            '\\sum' => '&sum;',
            '\\sqrt' => 'sqrt',
            '\\frac' => 'frac',
            '\\text' => 'text',
        );
    } else {
        $replacements = array(
            '\\infty' => '∞',
            '\\pm' => '±',
            '\\cdot' => '·',
            '\\times' => '×',
            '\\div' => '÷',
            '\\leq' => '≤',
            '\\geq' => '≥',
            '\\neq' => '≠',
            '\\approx' => '≈',
            '\\sim' => '∼',
            '\\rightarrow' => '→',
            '\\leftarrow' => '←',
            '\\leftrightarrow' => '↔',
            '\\alpha' => 'α',
            '\\beta' => 'β',
            '\\gamma' => 'γ',
            '\\delta' => 'δ',
            '\\epsilon' => 'ε',
            '\\theta' => 'θ',
            '\\lambda' => 'λ',
            '\\mu' => 'μ',
            '\\pi' => 'π',
            '\\sigma' => 'σ',
            '\\phi' => 'φ',
            '\\omega' => 'ω',
            '\\sum' => '∑',
            '\\sqrt' => 'sqrt',
            '\\frac' => 'frac',
            '\\text' => 'text',
        );
    }

    // Nahrazení speciálních symbolů a řeckých písmen
    $input = str_replace(array_keys($replacements), array_values($replacements), $input);

    // Odstranění zbylých zpětných lomítek
    $input = str_replace('\\', '', $input);

    // Zavolání parseru
    $output = parse_latex($input, $lang);

    // Přidání nového řádku na konec
    $output .= "\n";

    return $output;
}

function parse_latex($input, $lang) {
    global $latex_global_settings;
    $output_format = $latex_global_settings['output'];

    $length = strlen($input);
    $output = '';
    $i = 0;

    while ($i < $length) {
        $char = $input[$i];

        if ($char === '^' || $char === '_') {
            // Exponent nebo index
            $i++;
            $nextChar = $input[$i] ?? '';
            if ($nextChar === '{') {
                // Najít obsah ve složených závorkách
                $braceContent = extract_braces(substr($input, $i + 1));
                $parsedContent = parse_latex($braceContent['content'], $lang);
                $tag = $char === '^' ? 'sup' : 'sub';
                $output .= '<' . $tag . '>' . $parsedContent . '</' . $tag . '>';
                $i += 1 + $braceContent['length'];
            } else {
                // Přečíst další entitu nebo znak
                if ($input[$i] === '&') {
                    // HTML entita
                    $semicolonPos = strpos($input, ';', $i);
                    if ($semicolonPos !== false) {
                        $parsedContent = substr($input, $i, $semicolonPos - $i + 1);
                        $i = $semicolonPos + 1;
                    } else {
                        // Není ukončena středníkem, považujeme za jeden znak
                        $parsedContent = $input[$i];
                        $i++;
                    }
                } else {
                    // Jediný znak
                    $parsedContent = $input[$i];
                    $i++;
                }
                $tag = $char === '^' ? 'sup' : 'sub';
                $output .= '<' . $tag . '>' . $parsedContent . '</' . $tag . '>';
            }
        } elseif (substr($input, $i, 4) === 'sqrt') {
            // Odmocnina
            $i += 4;
            if (isset($input[$i]) && $input[$i] === '{') {
                $braceContent = extract_braces(substr($input, $i + 1));
                $parsedContent = parse_latex($braceContent['content'], $lang);
                $output .= '&radic;<span style="text-decoration:overline;">' . $parsedContent . '</span>';
                $i += 1 + $braceContent['length'];
            } else {
                $output .= '&radic;';
            }
        } elseif (substr($input, $i, 4) === 'frac') {
            // Zlomek
            $i += 4;
            if (isset($input[$i]) && $input[$i] === '{') {
                $numeratorContent = extract_braces(substr($input, $i + 1));
                $numerator = parse_latex($numeratorContent['content'], $lang);
                $i += 1 + $numeratorContent['length'];
                if (isset($input[$i]) && $input[$i] === '{') {
                    $denominatorContent = extract_braces(substr($input, $i + 1));
                    $denominator = parse_latex($denominatorContent['content'], $lang);
                    $i += 1 + $denominatorContent['length'];

                    // Formátování čísel podle národnostních zvyklostí
                    if ($lang == 'cz') {
                        $numerator = format_numbers_cz($numerator);
                        $denominator = format_numbers_cz($denominator);
                    }

                    // Vytvoření zlomku pomocí divů
                    $output .= '<div style="display:inline-block; text-align:center; vertical-align:middle;">
                                    <div style="border-bottom:1px solid; padding:0 2px;">' . $numerator . '</div>
                                    <div style="padding:0 2px;">' . $denominator . '</div>
                                </div>';
                }
            }
        } elseif (substr($input, $i, 4) === 'text') {
            // Text
            $i += 4;
            if (isset($input[$i]) && $input[$i] === '{') {
                $braceContent = extract_braces(substr($input, $i + 1));
                // Nepoužíváme htmlspecialchars, aby se HTML entity nezměnily
                $textContent = $braceContent['content'];
                $output .= $textContent;
                $i += 1 + $braceContent['length'];
            }
        } elseif (substr($input, $i, 5) === ($output_format == 'html' ? '&sum;' : '∑')) {
            // Summa
            $i += ($output_format == 'html' ? 5 : 1);
            $sumSymbol = $output_format == 'html' ? '&sum;' : '∑';
            // Zpracování dolního indexu
            if ($i < $length && $input[$i] === '_') {
                $i++;
                if (isset($input[$i]) && $input[$i] === '{') {
                    $braceContent = extract_braces(substr($input, $i + 1));
                    $subContent = parse_latex($braceContent['content'], $lang);
                    $sumSymbol .= '<sub>' . $subContent . '</sub>';
                    $i += 1 + $braceContent['length'];
                } else {
                    // Přečíst další entitu nebo znak
                    if ($input[$i] === '&') {
                        $semicolonPos = strpos($input, ';', $i);
                        if ($semicolonPos !== false) {
                            $subContent = substr($input, $i, $semicolonPos - $i + 1);
                            $i = $semicolonPos + 1;
                        } else {
                            $subContent = $input[$i];
                            $i++;
                        }
                    } else {
                        $subContent = $input[$i];
                        $i++;
                    }
                    $sumSymbol .= '<sub>' . $subContent . '</sub>';
                }
            }
            // Zpracování horního indexu
            if ($i < $length && $input[$i] === '^') {
                $i++;
                if (isset($input[$i]) && $input[$i] === '{') {
                    $braceContent = extract_braces(substr($input, $i + 1));
                    $supContent = parse_latex($braceContent['content'], $lang);
                    $sumSymbol .= '<sup>' . $supContent . '</sup>';
                    $i += 1 + $braceContent['length'];
                } else {
                    // Přečíst další entitu nebo znak
                    if ($input[$i] === '&') {
                        $semicolonPos = strpos($input, ';', $i);
                        if ($semicolonPos !== false) {
                            $supContent = substr($input, $i, $semicolonPos - $i + 1);
                            $i = $semicolonPos + 1;
                        } else {
                            $supContent = $input[$i];
                            $i++;
                        }
                    } else {
                        $supContent = $input[$i];
                        $i++;
                    }
                    $sumSymbol .= '<sup>' . $supContent . '</sup>';
                }
            }
            $output .= $sumSymbol;
        } else {
            // Obyčejný znak nebo HTML entita
            // Kontrola, zda se jedná o HTML entitu
            if ($char === '&') {
                $semicolonPos = strpos($input, ';', $i);
                if ($semicolonPos !== false) {
                    $entity = substr($input, $i, $semicolonPos - $i + 1);
                    $output .= $entity;
                    $i = $semicolonPos + 1;
                } else {
                    $output .= '&';
                    $i++;
                }
            } else {
                $output .= htmlspecialchars($char, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
                $i++;
            }
        }
    }

    // Formátování čísel podle národnostních zvyklostí
    if ($lang == 'cz') {
        $output = format_numbers_cz($output);
    }

    return $output;
}

function extract_braces($input) {
    $length = strlen($input);
    $content = '';
    $depth = 1;
    $i = 0;

    while ($i < $length && $depth > 0) {
        $char = $input[$i];
        if ($char === '{') {
            $depth++;
            $content .= $char;
        } elseif ($char === '}') {
            $depth--;
            if ($depth > 0) {
                $content .= $char;
            }
        } else {
            $content .= $char;
        }
        $i++;
    }

    return ['content' => $content, 'length' => $i];
}

function format_numbers_cz($input) {
    // Nahrazení teček za čárky u desetinných čísel
    return preg_replace_callback('/\d+(\.\d+)?/', function ($matches) {
        return str_replace('.', ',', $matches[0]);
    }, $input);
}

/**
 * Funkce latex_replace
 * Hledá v textu vzorce uzavřené mezi $start a $end a nahradí je jejich vizuální podobou.
 *
 * @param string $text Text obsahující LaTeXové výrazy.
 * @param string $start Počáteční oddělovač (výchozí '<$').
 * @param string $end Koncový oddělovač (výchozí '$>').
 * @return string Text s nahrazenými LaTeXovými výrazy.
 *
 * Příklad použití:
 * ```php
 * $text = 'Toto je vzorec: <$E = mc^2$>.';
 * echo latex_replace($text);
 * ```
 */
function latex_replace($text, $start='/*', $end='*/') {
    // Únik speciálních znaků pro regulární výrazy
    $start_escaped = preg_quote($start, '/');
    $end_escaped = preg_quote($end, '/');

    // Regulární výraz pro vyhledání vzorců
    $pattern = '/' . $start_escaped . '(.*?)' . $end_escaped . '/s';

    // Náhrada pomocí callback funkce
    $result = preg_replace_callback($pattern, function ($matches) {
        $formula = $matches[1];
        return latex_formula($formula);
    }, $text);

    return $result;
}
?>
