# :package_description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sierratecnologia/pedreiro.svg?style=flat-square)](https://packagist.org/packages/sierratecnologia/pedreiro)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sierratecnologia/pedreiro/run-tests?label=tests)](https://github.com/sierratecnologia/pedreiro/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/sierratecnologia/pedreiro.svg?style=flat-square)](https://packagist.org/packages/sierratecnologia/pedreiro)

**Note:** Run `./configure-pedreiro` to get started, or manually replace  ```:author_name``` ```:author_username``` ```:author_email``` ```sierratecnologia``` ```pedreiro``` ```:package_description``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](.github/CONTRIBUTING.md), [LICENSE.md](LICENSE.md) and [composer.json](composer.json) files, then delete this line. You can also run `configure-pedreiro.sh` to do this automatically.

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.


# Usando
[1. Instalando](docs/0.1/README.md)
[2. Configurando](docs/0.1/theme.md)
[3. Controllers](docs/0.1/controller.md)
[4. Modelos](docs/0.1/model.md)











## Support us
# Laravel CRUD Forms

This is a Laravel >=5.5 package to help easily create CRUD (Create, Read, Update, Delete) forms for eloquent models (as well as an index page).
It aims to be used as a quick tool which does not interfere with the other parts of the application that it's used in.

The package provides:
- A trait to use in resource controllers and
- A series of views for displaying the forms

The views are built using bootstrap (v3), but the styling can easily be overriden.




## Views

The views are built with bootstrap v.3 and also have css classes to support some common JavaScript libraries.
- select2 class is used in select inputs
- datepicker class is used in date inputs
- data-table class is used in the index view table

It is also possible to publish the views, so you can change them anyway you need. 
To publish them, use the following artisan command:

```
php artisan vendor:publish --provider=Pedreiro\CrudFormsServiceProvider --tag=views
``` 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 
