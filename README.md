# Pedreiro - Laravel CRUD Forms & Admin Panel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sierratecnologia/pedreiro.svg?style=flat-square)](https://packagist.org/packages/sierratecnologia/pedreiro)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sierratecnologia/pedreiro/run-tests?label=tests)](https://github.com/sierratecnologia/pedreiro/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/sierratecnologia/pedreiro.svg?style=flat-square)](https://packagist.org/packages/sierratecnologia/pedreiro)

**Pedreiro** is a comprehensive Laravel package (≥5.5) that provides a powerful CRUD (Create, Read, Update, Delete) form framework and admin panel infrastructure. It's designed as a quick, non-intrusive tool that dramatically reduces the time spent building admin interfaces while maintaining flexibility and extensibility.

## Features

- **Complete CRUD System**: Automatic generation of Create, Read, Update, Delete operations
- **20+ Form Field Types**: Text, Number, Date, Image, Select, RichText, Markdown, and many more
- **Relationship Handling**: Seamless management of BelongsTo and ManyToMany relationships
- **Data Export**: Built-in CSV and Excel export capabilities
- **AdminLTE Integration**: Professional admin theme with customizable layouts
- **DataTables Support**: Fast, searchable, sortable data tables out of the box
- **Multiple Sections**: Organize your admin panel into Painel, Master, Admin, and Rica sections
- **Validation System**: Model-level validation with automatic form error handling
- **Authorization**: Policy-based access control for secure admin operations
- **Localization**: Multi-language support with locale-specific data
- **Asset Management**: Integrated asset pipeline with minification support
- **Extensible Architecture**: Easy to customize through traits, service providers, and handlers

## Requirements

- PHP 7.2 or higher (PHP 8.0+ supported)
- Laravel 5.5 or higher
- Composer

## Installation

Install the package via Composer:

```bash
composer require sierratecnologia/pedreiro
```

The package will automatically register its service provider through Laravel's package discovery.

### Publish Configuration

Publish the package configuration and assets:

```bash
# Publish configuration files
php artisan vendor:publish --provider="Pedreiro\PedreiroServiceProvider" --tag=config

# Publish views (optional, for customization)
php artisan vendor:publish --provider="Pedreiro\PedreiroServiceProvider" --tag=views

# Publish assets
php artisan vendor:publish --provider="Pedreiro\PedreiroServiceProvider" --tag=assets

# Publish language files
php artisan vendor:publish --provider="Pedreiro\PedreiroServiceProvider" --tag=lang
```

### Run Migrations

Run the package migrations to create necessary database tables:

```bash
php artisan migrate
```

## Configuration

The main configuration file is located at `config/pedreiro.php`. Key options include:

```php
return [
    // Base layout for admin pages
    'blade_layout' => 'layouts.app',

    // Section name for content injection
    'blade_section' => 'content',

    // Use FontAwesome icons in buttons
    'button_icons' => true,
];
```

## Usage

### Basic CRUD Controller

Create a controller that uses the `CrudController` trait:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Pedreiro\CrudController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use CrudController;

    protected $model = Post::class;

    protected $views = [
        'index' => 'admin.posts.index',
        'create' => 'admin.posts.create',
        'edit' => 'admin.posts.edit',
    ];
}
```

### Model Configuration

Extend the Pedreiro base model for enhanced functionality:

```php
<?php

namespace App\Models;

use Pedreiro\Models\Base;

class Post extends Base
{
    protected $fillable = ['title', 'content', 'author_id', 'published_at'];

    // Define validation rules
    public $rules = [
        'title' => 'required|max:255',
        'content' => 'required',
    ];

    // Define admin-specific attributes
    public function getAdminTitleAttribute()
    {
        return $this->title;
    }

    public function getAdminThumbAttribute()
    {
        return $this->featured_image;
    }
}
```

### Form Fields

Define form fields for your model:

```php
// In your controller or form builder
$fields = [
    [
        'name' => 'title',
        'type' => 'text',
        'label' => 'Post Title',
    ],
    [
        'name' => 'content',
        'type' => 'richtext',
        'label' => 'Content',
    ],
    [
        'name' => 'author_id',
        'type' => 'select',
        'label' => 'Author',
        'relationship' => 'author',
    ],
    [
        'name' => 'published_at',
        'type' => 'datetime',
        'label' => 'Publish Date',
    ],
];
```

### Available Field Types

Pedreiro supports 20+ field types:

- **Text Inputs**: `text`, `textarea`, `password`, `email`, `hidden`
- **Numeric**: `number`
- **Selection**: `select`, `select_multiple`, `checkbox`, `multiple_checkbox`, `radio`
- **Date/Time**: `date`, `time`, `datetime`, `timestamp`
- **Files**: `file`, `image`, `multiple_images`, `media_picker`
- **Rich Content**: `richtext`, `markdown`, `code_editor`
- **Advanced**: `color`, `coordinates`, `key_value_json`

### Routes

Define your admin routes:

```php
Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function () {
    Route::resource('posts', 'Admin\PostController');
});
```

### Views

Pedreiro provides default views built with Bootstrap 3/4 and AdminLTE. You can customize them by publishing and modifying:

```bash
php artisan vendor:publish --provider="Pedreiro\PedreiroServiceProvider" --tag=views
```

The views include classes for popular JavaScript libraries:
- **select2**: Applied to select inputs
- **datepicker**: Applied to date inputs
- **data-table**: Applied to index view tables

## Quality Tools Configuration

Pedreiro includes comprehensive code quality tools to maintain high standards.

### PHPUnit

Run tests with:

```bash
composer test

# With coverage
composer test-coverage
```

Configuration: `phpunit.xml.dist`

### PHP CS Fixer

Format code automatically:

```bash
composer format
```

Configuration: `.php_cs.dist`

### Psalm

Static analysis with Psalm:

```bash
composer psalm
```

Configuration: `psalm.xml`

### PHPStan

Static analysis with PHPStan:

```bash
./vendor/bin/phpstan analyse
```

Configuration: `phpstan.neon`

### GrumPHP

Git hooks for automated quality checks are configured in `grumphp.yml`. GrumPHP runs automatically on:
- Pre-commit: Checks file size, commit message format, blacklisted keywords
- Pre-push: Runs Psalm analysis

To skip hooks (not recommended):
```bash
git commit --no-verify
```

## GitHub Actions Workflows

The package includes GitHub Actions workflows for continuous integration:

- **Tests**: Runs PHPUnit tests on PHP 7.4, 8.0, 8.1 with Laravel 8.x and 9.x
- **PHP CS Fixer**: Automatically fixes code style issues
- **Psalm**: Runs static analysis on PHP code changes
- **PHPStan**: Runs additional static analysis

## Advanced Features

### Data Export

Export data to CSV or Excel:

```php
use Pedreiro\Http\Requests\ExportModelRequest;

public function export(ExportModelRequest $request)
{
    return $this->exportData($request);
}
```

### Relationship Management

Handle relationships in forms:

```php
// BelongsTo relationship
[
    'name' => 'category_id',
    'type' => 'select',
    'relationship' => 'category',
    'relationship_field' => 'name',
]

// ManyToMany relationship
[
    'name' => 'tags',
    'type' => 'select_multiple',
    'relationship' => 'tags',
    'relationship_field' => 'name',
]
```

### Custom Form Field Handlers

Create custom field handlers by implementing `HandlerInterface`:

```php
<?php

namespace App\FormFields;

use Pedreiro\Elements\FormFields\HandlerInterface;

class CustomFieldHandler implements HandlerInterface
{
    public function createInput($name, $label, $attributes = [])
    {
        // Return HTML for the input field
    }

    public function getValue($model, $name)
    {
        // Return the field value from the model
    }
}
```

### Menu System

Add custom menu items in your service provider:

```php
use Pedreiro\Facades\Pedreiro;

Pedreiro::menu()->add([
    'text' => 'My Custom Section',
    'url' => '/admin/custom',
    'icon' => 'fas fa-cog',
]);
```

## Testing

Run the test suite:

```bash
composer test
```

The package includes comprehensive tests covering:
- CRUD operations
- Form generation
- Relationship handling
- Data export (CSV/Excel)
- Localization
- File uploads
- Validation

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`
4. Check code style: `composer format`
5. Run static analysis: `composer psalm`

## Documentation

Detailed documentation is available:

- [Installation Guide](docs/0.1/README.md)
- [Theme Configuration](docs/0.1/theme.md)
- [Controllers](docs/0.1/controller.md)
- [Models](docs/0.1/model.md)

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for release notes and version history.

## License

The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for more information.

## About SierraTecnologia

**Pedreiro** is maintained by [SierraTecnologia](https://github.com/SierraTecnologia) and created by [Ricardo Sierra](https://ricasolucoes.com.br). It's part of a comprehensive ecosystem of Laravel packages designed to accelerate development:

- **Muleta**: Utility library for common Laravel operations
- **CrudMaker**: Code generator for CRUD applications
- **Former**: Advanced form builder
- **Changelog**: Version management and release notes

For more information, visit:
- Website: [https://ricasolucoes.com.br](https://ricasolucoes.com.br)
- GitHub: [https://github.com/SierraTecnologia](https://github.com/SierraTecnologia)
