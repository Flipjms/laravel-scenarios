<?php

namespace Flipjms\Scenarios;

use Flipjms\Scenarios\Commands\ScenarioMakeCommand;
use Flipjms\Scenarios\Commands\ScenarioSetCommand;
use Illuminate\Support\ServiceProvider;

class ScenariosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/scenarios.php' => config_path('scenarios.php'),
            ], 'scenarios-config');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ScenarioMakeCommand::class,
                ScenarioSetCommand::class,
            ]);
        }
    }
}
