
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/knutle/pest-mock-recorder.svg?style=flat-square)](https://packagist.org/packages/knutle/pest-mock-recorder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/knutle/pest-mock-recorder/run-tests?label=tests)](https://github.com/knutle/pest-mock-recorder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/knutle/pest-mock-recorder/Check%20&%20fix%20styling?label=code%20style)](https://github.com/knutle/pest-mock-recorder/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/knutle/pest-mock-recorder.svg?style=flat-square)](https://packagist.org/packages/knutle/pest-mock-recorder)

Lets you easily bind a mock to the service container that can flexibly record details every time it is used. Especially helpful for generating a list of specific actions and their parameters throughout a job, then using that to match a snapshot or ensure that a certain sequence of actions happened in the right order. 

## Installation

You can install the package via composer:

```bash
composer require knutle/pest-mock-recorder
```

## Usage

```php
# Automatically bind the mock to the service container while instantiating it
$mock = MockRecorder::bind(MyClass::class);

# Setup expectations for only recording
$mock->expect(
    write: fn (string $name) => $name
);

# Resolve your mock from the service container and watch the history fill up
app(MyClass::class)->write('Write 1'); // returns null
app(MyClass::class)->write('Write 2'); // returns null

# Both entries can now be found in the history variable
$mock->history == [
    'Write 1',
    'Write 2'
]

# You can also return an array to also return data from your mock
$mock->expect(
    write: fn (string $name) => [ $name, "Hello $name!" ]
);

app(MyClass::class)->write('Bob'); // returns "Hello Bob!"

# Still it records to history
$mock->history == [
    'Write 1',
    'Write 2',
    'Bob',
]
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Knut Leborg](https://github.com/knutle)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
