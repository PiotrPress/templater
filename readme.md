# Templater

This library is a simple template engine file loader with variable support.

## Installation

```console
composer require piotrpress/templater
```

## Usage

```php
require __DIR__ . '/vendor/autoload.php';

use PiotrPress\Templater;
use PiotrPress\Templater\Template;

$templater = new Templater( __DIR__ . '/templates' );

// Example #1
$templater->display( 'template', [
    'var1' => 'value1',
    'var2' => 'value2'
] );

// Example #2
echo $templater->render( 'template', [
    'var1' => 'value1',
    'var2' => 'value2'
] );

// Example #3
echo new Template( __DIR__ . '/templates/template.php', [
    'var1' => 'value1',
    'var2' => 'value2'
] );
```

**Note:** All Errors in templates will be converted to Exceptions.

## License

[GPL3.0](license.txt)