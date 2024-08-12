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

### Running the compiler directly

```php
$typescriptGenerator = new \Returnless\TypescriptGenerator\TypescriptGenerator();

$output = $typescriptGenerator->generate('...class-string...');
```
