# doc2html

doc2html is a tool that enables to convert PHP documentation in HTML files.

> [!WARNING]
> _For the moment, this project is in the development phase. It is not yet completely finished._

## Installation
To use doc2html, it must be installed in the user directory.

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

## Contributions
Any contributions are welcome.