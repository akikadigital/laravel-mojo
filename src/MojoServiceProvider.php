<?php

namespace Akika\Mojo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Akika\Mojo\Commands\MojoCommand;

class MojoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mojo')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_mojo_table')
            ->hasCommand(MojoCommand::class);
    }
}
