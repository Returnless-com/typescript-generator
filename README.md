# Typescript Generator

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

#[Returnless\TypescriptGenerator\Attributes\Typescript(DummyClass)]
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
$typeCompiler = new Returnless\TypescriptGenerator\TypeCompiler();

$typeCompiler->compile('...class-string...');
```
