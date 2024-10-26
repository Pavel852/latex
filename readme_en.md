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
