# Schemator for Laravel

Schemator is a Laravel package designed to automate the process of generating Eloquent models and Filament resources based on your database schema. It simplifies the initial setup of models in Laravel projects by auto-generating them with properties and relationships.

## Features

- Automatically generates Eloquent models for each database table.
- Supports a wide array of relationships including `belongsTo`, `hasMany`, `hasOne`, `belongsToMany`, `morphOne`, and `morphMany`.
- Generates Filament resources if Filament is installed, with support for various options like `--simple`, `--generate`, `--soft-deletes`, and `--view`.
- Embeds a comment in each model indicating creation by Schemator for clarity and tracking.

## Requirements

- Laravel 8 or newer
- PHP 7.3 or newer
- FilamentPHP (optional for resource generation)

## Installation

To install Schemator, run the following command in your Laravel project:

```bash
composer require 0jkb/Schemator
```

After installation, you can use the Artisan command provided by Schemator.

## Usage :


- -f | --filament-options: Activate Filament resource generation with specific options. Accepts both shorthand (g, s, d, v, e) and full words (generate, simple, soft-deletes, view, empty). For example, -f gs or -f generate,simple.
- --skip= for specifying tables to skip.
- --skip-default as a flag to skip Laravel's default tables.
- --only= to generate models for specific tables.

Generate models only :
```bash
php artisan vendor:publish --tag=config
```


To generate models and empty Filament resources, run (e | empty option):
```bash
php artisan schemator:generate -f e

```
- This will create several files in the app/Filament/Resources directory with empty form and table .


To generate models and optionally Filament resources, run:
```bash
php artisan schemator:generate -f [options]

```
Generate Filament resources (s | simple option):
```bash
php artisan schemator:generate -f s
```
 - This command will generate Filament resources for each table, applying the simple option.


Generate Filament resources (g | generate option):

```bash
php artisan schemator:generate -f g
```

Generate models and Filament resources with all options:

```bash
php artisan schemator:generate -f sgdv

```

Skipping specific tables(--skip option):
```bash
php artisan schemator:generate -f sgdv --skip=users,logs
```

Generating models for specific tables(--only option):
```bash
php artisan schemator:generate -f sgdv --only=users,posts
```
 - This command will generate models only for the 'users' and 'posts' tables.

Generate models and Filament resources, skipping Laravel default tables (--skip-default option):
```bash
php artisan schemator:generate -f sgdv --skip-default
```





## Contributing
Contributions to Schemator are welcome. You can contribute in various ways:

- Submitting bug reports and feature requests.
- Writing code for new features or bug fixes.
- Improving documentation.

Please feel free to fork the repository and submit pull requests.

## License
Schemator is open-sourced software licensed under the MIT license.

