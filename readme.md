# LaTeX PHP Parser

Tato PHP knihovna poskytuje funkce pro převod LaTeXových výrazů do HTML za použití různých možností formátování. Funkce jsou navrženy tak, aby umožňovaly snadný převod a zobrazení LaTeXových vzorců přímo ve vašem HTML výstupu.

## Funkce

### 1. `latex_settings($options)`
Nastaví globální formátování parseru.
- **Parametry:**
  - `$options` (řetězec): Oddělený seznam voleb jako `'cz,html'` nebo `'us,char'`.
- **Příklady použití:**
  ```php
  latex_settings('cz,html'); // Nastaví české formátování a HTML entity
  latex_settings('us,char'); // Nastaví anglické formátování a UTF-8 znaky
  ```

### 2. `latex_formula($input)`
Převede daný LaTeXový výraz na HTML výstup.
- **Parametry:**
  - `$input` (řetězec): LaTeXový výraz.
- **Příklady použití:**
  ```php
  echo latex_formula('E = mc^2');
  echo latex_formula('S = \sum_{n=1}^\infty \frac{1}{n^2}');
  ```

### 3. `latex_replace($text, $start='/*', $end='*/')`
Nahrazuje v zadaném textu LaTeXové výrazy jejich HTML ekvivalenty, včetně formátování, odstraní oddělovače.
- **Parametry:**
  - `$text` (řetězec): Text obsahující LaTeX výrazy.
  - `$start` (řetězec): Počáteční oddělovač (výchozí `'/*'`).
  - `$end` (řetězec): Koncový oddělovač (výchozí `'*/'`).
- **Příklady použití:**
  ```php
  $text = 'Vzorec: /*E = mc^2*/.';
  echo latex_replace($text);
  ```

### 4. `latex_version()`
Vrací aktuální verzi knihovny.
- **Příklad použití:**
  ```php
  echo latex_version();
  ```

## Praktické příklady
Příklady použití naleznete v souborech `latex_test1.php`, `latex_test2.php` a `latex_test3.php`.

## Licence
Tento projekt je licencován pod licencí MIT. Více informací naleznete v souboru `LICENSE`.

## Composer Setup

1. **Updating Dependencies**: Run `composer update` to update all dependencies as per the project's `composer.json` file.
2. **Installing the New Package**: Run `composer require pavel852/latex` to install the `pavel852/latex` package. This package allows for handling HTML templates with ease.

## Usage Instructions

To utilize the package in your project, follow these steps:
- Add all dependencies by including `require 'vendor/autoload.php';` at the beginning of your PHP file.
- Import the package with `use pavel852/latex;` to access its functionalities.
- You can open a template using `print latex_formula('E = mc^2');`.
- https://packagist.org/packages/pavel852/latex
