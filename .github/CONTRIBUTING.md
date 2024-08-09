# Contributing

## Bug fixes

If you've found a bug in the code that you'd like to fix,
[submit a pull request](https://github.com/Returnless-com/typescript-generator/pulls) with your changes. Include a
helpful description of the problem and how your changes address it, and provide tests so we can verify the fix works
as expected.

## New features

If there's a new feature you'd like to see added to the project,
[share your idea with use](https://github.com/Returnless-com/typescript-generator/discussions/new?category=ideas) in
our discussion forum to get it on our radar as something to consider for a future release.

**Please note that we don't often accept pull requests for new features.** Adding a new feature to the project
requires us to think through the entire problem ourselves to make sure we agree with the proposed change, which
means the feature needs to be high on our own priority list for us to be able to give it the attention it needs.

If you open a pull request for a new feature, we're likely to close it not because it's a bad idea, but because we
aren't ready to prioritize the feature and don't want the PR to sit open for months or even years.

## Coding standards

Our code formatting rules are defined in
[pint.json](https://github.com/Returnless-com/typescript-generator/blob/main/pint.json) and our static analysis are
defined in [phpstan.neon](https://github.com/Returnless-com/typescript-generator/blob/main/phpstan.neon). You can check
your code against these standards by running:

```sh
./vendor/bin/pint
./vendor/bin/phpstan
```

## Running tests

You can run the test suite using the following command:

```sh
./vendor/bin/phpunit
```

Please ensure that the tests are passing when submitting a pull request. If you're adding a new feature to the 
project, please include tests.
