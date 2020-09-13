<?php

namespace Pedreiro;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Pedreiro\Commands\PedreiroCommand;
use Pedreiro\Events\FormFieldsRegistered;

class PedreiroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/pedreiro.php' => config_path('pedreiro.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/pedreiro'),
            ], 'views');

            $migrationFileName = 'create_pedreiro_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                PedreiroCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pedreiro');
    }

    public function register()
    {
        $this->app->bind('pedreiro', Pedreiro::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('Pedreiro', PedreiroFacade::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/pedreiro.php', 'pedreiro');
        // ExtendedBreadFormFieldsServiceProvider

        // PedreiroFacade::FormField(MultipleImagesWithAttrsFormField::class);

        $this->registerFormFields();


        // $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'pedreiro');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'multiple_checkbox',
            'color',
            'date',
            'file',
            'image',
            'multiple_images',
            'media_picker',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'code_editor',
            'markdown_editor',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'time',
            'timestamp',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = Str::studly("{$formField}_handler");

            PedreiroFacade::addFormField("Pedreiro\\Elements\\FormFields\\{$class}");
        }

        // PedreiroFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }
}
