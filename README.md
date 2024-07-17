# doc2html

doc2html is a tool that enables to convert PHP documentation in HTML files.

> [!WARNING]
> _For the moment, this project is in the development phase. It is not yet completely finished._

## Installation
To use doc2html, it must be installed in the user directory.

## Configuration
```php
<?php

return [
    // Path where the documentation will be saved.
    'output_dir' => __DIR__ . '/output',

    // For each action, indicate it to standard output.
    'verbose' => true,

    // Selected namespaces to generate documentations.
    'target_namespaces' => [
        'Examples'
    ]
];
```

## Contributions
Any contributions are welcome.