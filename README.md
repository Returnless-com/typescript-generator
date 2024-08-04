# Typescript Generator

This package allows you to generate typescript types from your PHP classes.

### Installation

```json
{
  "require": {
    "returnless/typescript-generator": "dev-main"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/Returnless-com/typescript-generator.git"
    }
  ]
}
```

## Usage

### Using attributes

```php
class DummyClass
{
    public function myMethod(): string
    {
        // ...
    }
}

#[Returnless\TypescriptGenerator\Attributes\Typescript(DummyClass::class)]
class MyController
{
    // ....
}
```

```shell
php artisan typescript:generate
```

### Running the compiler directly

```php
$classCompiler = new \Returnless\TypescriptGenerator\ClassCompiler();

$output = $classCompiler->compile('...class-string...');
```
