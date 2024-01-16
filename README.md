<!-- omit in toc -->
# Chore Manager
Chore manager is a simple chore management application for taking care of household tasks.

<!-- omit in toc -->
## Table of Contents
- [Key Features](#key-features)
- [Set Up Local Environment](#set-up-local-environment)
- [Testing](#testing)
- [Linting/Static Analysis](#lintingstatic-analysis)
- [Related Projects](#related-projects)
- [Technologies](#technologies)

## Key Features
* Keep track of chores and other tasks
* Control frequency of chore occurance over intervals of days, weeks, months, quarters, or years.
* View completion history of past chores
* [Use Markdown](app/Providers/AppServiceProvider.php) to format chore description with rich information.
* [iCalendar format support](app/Http/Controllers/Api/ICalendarController.php) to integrate with calendar applications like Google Calendar

## Set Up Local Environment
These instructions assume you are using [Laravel Valet](https://laravel.com/docs/8.x/valet) and [Composer](https://getcomposer.org/) running on macOS.

1. Clone the repository to `~/Sites` or wherever is parked in Valet
2. `cd` into the directory
3. `sh .bin/init.sh`
4. Optionally make changes to `.env` and `.env.testing`.

## Testing

This application is built with a comprehensive test suite using [PHPUnit](https://phpunit.de/).

To Run tests
```sh
composer test:unit
```

## Linting/Static Analysis

This application is standardized and protected using [Laravel Pint](https://laravel.com/docs/10.x/pint) for linting/fixing and [PHPStan](https://phpstan.org/)/[Larastan](https://github.com/nunomaduro/larastan) for static code analysis.

To run linter
```sh
composer test:lint
```

To run linter & fix errors automatically
```sh
composer test:fix
```

To run static analysis
```sh
composer test:types
```

## Related Projects
* [Chore Manager Mobile - Flutter application](https://github.com/JHWelch/chore_manager_mobile)
* [Chore Manager Magic Mirror Module](https://github.com/JHWelch/MMM-Chore-Manager)

## Technologies
Chore Manager is built using a **TALL** stack.
* [Tailwind CSS](https://tailwindcss.com/)
* [Alpine.js](https://alpinejs.dev/)
* [Laravel](https://laravel.com/)
* [Laravel Livewire](https://livewire.laravel.com/)
