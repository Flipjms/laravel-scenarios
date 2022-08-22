<?php

namespace Flipjms\Scenarios\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class ScenarioMakeCommand extends GeneratorCommand
{
    public $signature = 'make:scenario {name}';

    public $description = 'Create a new scenario';

    protected $type = 'Scenario';

    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/scenario.stub';
    }

    protected function buildClass($name): string
    {
        $scenario = class_basename(Str::ucfirst($name));

        return str_replace(
            ['{{ scenarioName }}'], [$scenario], parent::buildClass($name)
        );
    }

    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->databasePath().'/scenarios/'.str_replace('\\', '/', $name).'.php';
    }
}
