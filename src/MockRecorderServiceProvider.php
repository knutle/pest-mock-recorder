<?php

namespace Knutle\MockRecorder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Knutle\MockRecorder\Commands\MockRecorderCommand;

class MockRecorderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('pest-mock-recorder')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_pest-mock-recorder_table')
            ->hasCommand(MockRecorderCommand::class);
    }
}
