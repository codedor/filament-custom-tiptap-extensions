<?php

namespace Codedor\FilamentCustomTiptapExtensions\Providers;

use Codedor\FilamentCustomTiptapExtensions\Components\Linkpicker;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCustomTiptapExtensionsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-custom-tiptap-extensions')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('create_package_table')
            ->hasViews('filament-custom-tiptap-extensions');
    }

    public function packageBooted()
    {
        Blade::component(Linkpicker::class, 'filament-custom-tiptap-extensions::linkpicker');
    }
}
