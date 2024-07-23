# doc2html

doc2html is a tool that enables to convert PHP documentation in HTML files.

> [!WARNING]
> _For the moment, this project is in the development phase. It is not yet completely finished._

## Installation
```shell
composer require --dev tamdaz/doc2html
```

## Configuration

To generate a configuration, execute:
```shell
./vendor/bin/doc2html --gen-config
```

```php
return [
    // Path where the documentation will be saved.
    'output_dir' => __DIR__ . '/output',

    // For each action, indicate it to standard output (STDOUT).
    'verbose' => true,

    // When these namespaces are excluded, they will not be taken into account by the
    // tool (except when exceptions apply to the "include_classes" parameter).
    'exclude_namespaces' => [
        // ...
    ],

    // When these namespaces are included, they will be taken into account by the
    // tool (except when exceptions apply to the "exclude_classes" parameter).
    'include_namespaces' => [
        // ...
    ],

    // Exclude classes to avoid generate documentation, even though in the included namespace.
    'exclude_classes' => [
        // ...
    ],

    // Classes to include to generate documentation.
    // INFO: If the specified class is in the excluded namespace, it will still be taken
    // into account in the classmap.
    'include_classes' => [
        // ...
    ],
];
```

Before generating the documentation, it is necessary to target the different directories because
it allows to register your classes in the classmap.

```json
"autoload": {
    "classmap": [
        "examples/", "src/"
    ]
},
```

```shell
composer dump-autoload
```

For information, this library uses the classmap of Composer to allow you
to choose those you do not want to generate documentation.

After that, you can generate it by executing `./vendor/bin/doc2html`.

## Contributors
Any contributions are welcome.