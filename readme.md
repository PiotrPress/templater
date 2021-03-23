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

$templater = new Templater( '/templates' );

// print
$templater->display( 'template', [
    'var1' => $var1,
    'var2' => $var2
] );

// return
echo $templater->render( 'template', [
    'var1' => $var1,
    'var2' => $var2
] );
```

**Note:** All Errors in templates will be converted to Exceptions.

## License

[GPL3.0](license.txt)