# LaTeX PHP Parser

This PHP library provides functions to convert LaTeX expressions into HTML with various formatting options. It enables easy conversion and display of LaTeX formulas directly in your HTML output.

## Functions

### 1. `latex_settings($options)`
Sets global formatting options for the parser.
- **Parameters:**
  - `$options` (string): Comma-separated options like `'cz,html'` or `'us,char'`.
- **Usage examples:**
  ```php
  latex_settings('cz,html'); // Sets Czech formatting and HTML entities
  latex_settings('us,char'); // Sets English formatting and UTF-8 characters
  ```

### 2. `latex_formula($input)`
Converts a LaTeX expression into HTML output.
- **Parameters:**
  - `$input` (string): LaTeX expression.
- **Usage examples:**
  ```php
  echo latex_formula('E = mc^2');
  echo latex_formula('S = \sum_{n=1}^\infty \frac{1}{n^2}');
  ```

### 3. `latex_replace($text, $start='/*', $end='*/')`
Replaces LaTeX expressions in the specified text with their HTML equivalents and removes delimiters.
- **Parameters:**
  - `$text` (string): Text containing LaTeX expressions.
  - `$start` (string): Starting delimiter (default `'/*'`).
  - `$end` (string): Ending delimiter (default `'*/'`).
- **Usage examples:**
  ```php
  $text = 'Formula: /*E = mc^2*/.';
  echo latex_replace($text);
  ```

### 4. `latex_version()`
Returns the current version of the library.
- **Usage example:**
  ```php
  echo latex_version();
  ```

## Practical Examples
For practical usage examples, see `latex_test1.php`, `latex_test2.php`, and `latex_test3.php`.

## License
This project is licensed under the MIT License. For more information, see the `LICENSE` file.

## Composer Setup

1. **Updating Dependencies**: Run `composer update` to update all dependencies as per the project's `composer.json` file.
2. **Installing the New Package**: Run `composer require pavel852/latex` to install the `pavel852/latex` package. This package allows for handling HTML templates with ease.

## Usage Instructions

To utilize the package in your project, follow these steps:
- Add all dependencies by including `require 'vendor/autoload.php';` at the beginning of your PHP file.
- Import the package with `use pavel852/latex;` to access its functionalities.
- You can open a template using `print latex_formula('E = mc^2');`.
- https://packagist.org/packages/pavel852/latex
