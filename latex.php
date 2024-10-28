<?php

/**
 * latex.php
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 * Year: 10/2024
 * Version: 3.1
 *
 * Popis:
 * Tato knihovna poskytuje třídu Latex pro převod LaTeXových výrazů do HTML pomocí PHP.
 * Navíc obsahuje globální funkce pro zpětnou kompatibilitu:
 * - latex_settings($options)
 * - latex_formula($input)
 * - latex_replace($text, $start='<$', $end='$>')
 * - latex_version()
 *
 * Tyto funkce interně využívají instanci třídy Latex.
 */

class Latex {
    private $version = '3.1';
    private $settings = array(
        'lang' => 'us',
        'output' => 'html', // 'html' nebo 'char'
    );

    public function version() {
        return 'LaTeX PHP Parser Version ' . $this->version;
    }

    public function settings($options) {
        $settings = explode(',', $options);
        foreach ($settings as $setting) {
            $setting = trim($setting);
            if ($setting == 'cz') {
                $this->settings['lang'] = 'cz';
            } elseif ($setting == 'us') {
                $this->settings['lang'] = 'us';
            } elseif ($setting == 'html') {
                $this->settings['output'] = 'html';
            } elseif ($setting == 'char') {
                $this->settings['output'] = 'char';
            }
        }
    }

    public function formula($input) {
        $lang = $this->settings['lang'];
        $output_format = $this->settings['output'];

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
        $output = $this->parse_latex($input, $lang);

        // Přidání nového řádku na konec
        $output .= "\n";

        return $output;
    }

    private function parse_latex($input, $lang) {
        $output_format = $this->settings['output'];

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
                    $braceContent = $this->extract_braces(substr($input, $i + 1));
                    $parsedContent = $this->parse_latex($braceContent['content'], $lang);
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
                    $braceContent = $this->extract_braces(substr($input, $i + 1));
                    $parsedContent = $this->parse_latex($braceContent['content'], $lang);
                    $output .= '&radic;<span style="text-decoration:overline;">' . $parsedContent . '</span>';
                    $i += 1 + $braceContent['length'];
                } else {
                    $output .= '&radic;';
                }
            } elseif (substr($input, $i, 4) === 'frac') {
                // Zlomek
                $i += 4;
                if (isset($input[$i]) && $input[$i] === '{') {
                    $numeratorContent = $this->extract_braces(substr($input, $i + 1));
                    $numerator = $this->parse_latex($numeratorContent['content'], $lang);
                    $i += 1 + $numeratorContent['length'];
                    if (isset($input[$i]) && $input[$i] === '{') {
                        $denominatorContent = $this->extract_braces(substr($input, $i + 1));
                        $denominator = $this->parse_latex($denominatorContent['content'], $lang);
                        $i += 1 + $denominatorContent['length'];

                        // Formátování čísel podle národnostních zvyklostí
                        if ($lang == 'cz') {
                            $numerator = $this->format_numbers_cz($numerator);
                            $denominator = $this->format_numbers_cz($denominator);
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
                    $braceContent = $this->extract_braces(substr($input, $i + 1));
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
                        $braceContent = $this->extract_braces(substr($input, $i + 1));
                        $subContent = $this->parse_latex($braceContent['content'], $lang);
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
                        $braceContent = $this->extract_braces(substr($input, $i + 1));
                        $supContent = $this->parse_latex($braceContent['content'], $lang);
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
            $output = $this->format_numbers_cz($output);
        }

        return $output;
    }

    private function extract_braces($input) {
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

    private function format_numbers_cz($input) {
        // Nahrazení teček za čárky u desetinných čísel
        return preg_replace_callback('/\d+(\.\d+)?/', function ($matches) {
            return str_replace('.', ',', $matches[0]);
        }, $input);
    }

    public function replace($text, $start='<$', $end='$>') {
        // Únik speciálních znaků pro regulární výrazy
        $start_escaped = preg_quote($start, '/');
        $end_escaped = preg_quote($end, '/');

        // Regulární výraz pro vyhledání vzorců
        $pattern = '/' . $start_escaped . '(.*?)' . $end_escaped . '/s';

        // Náhrada pomocí callback funkce
        $result = preg_replace_callback($pattern, function ($matches) {
            $formula = $matches[1];
            return $this->formula($formula);
        }, $text);

        return $result;
    }
}

// Globální instance třídy Latex pro použití ve funkcích
$latex_instance = new Latex();

/**
 * Globální funkce pro zpětnou kompatibilitu
 */

function latex_settings($options) {
    global $latex_instance;
    $latex_instance->settings($options);
}

function latex_formula($input) {
    global $latex_instance;
    return $latex_instance->formula($input);
}

function latex_replace($text, $start='<$', $end='$>') {
    global $latex_instance;
    return $latex_instance->replace($text, $start, $end);
}

function latex_version() {
    global $latex_instance;
    return $latex_instance->version();
}

?>
