<?php

namespace Codedor\FilamentCustomTiptapExtensions\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCustomTiptapExtensionsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-custom-tiptap-extensions')
            ->setBasePath(__DIR__ . '/../');
    }
}
