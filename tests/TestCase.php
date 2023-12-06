<?php

namespace Codedor\FilamentCustomTiptapExtensions\Tests;

use Codedor\FilamentCustomTiptapExtensions\Providers\FilamentCustomTiptapExtensionsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Codedor\\FilamentCustomTiptapExtensions\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentCustomTiptapExtensionsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-custom-tiptap-extensions_table.php.stub';
        $migration->up();
        */
    }
}
