<!-- omit in toc -->
# Chore Manager
Chore manager is a simple chore management application for taking care of household tasks.

<!-- omit in toc -->
## Table of Contents
- [Key Features](#key-features)
- [Set Up Local Environment](#set-up-local-environment)
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

## Technologies
Chore Manager is built using a **TALL** stack.
* [Tailwind CSS](https://tailwindcss.com/)
* [Alpine.js](https://github.com/alpinejs/alpine)
* [Laravel](https://laravel.com/)
* [Laravel Livewire](https://laravel-livewire.com/)
