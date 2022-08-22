<?php

namespace Flipjms\Scenarios\Commands;

use Flipjms\Scenarios\InteractiveScenariosPrompt;
use Flipjms\Scenarios\Scenario;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use function Termwind\{render};

class ScenarioSetCommand extends Command
{
    protected $signature = 'scenarios:set {scenario?}
                                {--fresh : Cleans database before setting up scenario}';

    protected $description = 'It sets the given scenario';

    protected Collection $availableScenarios;

    public function __construct()
    {
        parent::__construct();

        $this->availableScenarios = collect();
    }

    public function handle(): int
    {
        $this->load($this->getScenariosPath());

        $scenarioAlias = $this->argument('scenario');

        if (! $scenarioAlias) {
            $scenarioAlias = (new InteractiveScenariosPrompt($this->availableScenarios))();
        }

        $scenario = $this->resolveAlias($scenarioAlias);

        if (! $scenario) {
            $this->error("Scenario {$scenarioAlias} does not exist!");

            return -1;
        }

        if ($this->option('fresh')) {
            $this->displayCleaningDatabaseMessage();
            $this->fresh();
        }

        $this->displayRunningMessage($scenarioAlias);

        $scenario->execute()->output();

        return 0;
    }

    protected function load(string $path)
    {
        collect((new Finder)->in($path)->files())
            ->each(function ($scenario) {
                $scenarioClass = Str::of($scenario->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', ''])
                    ->prepend('\\Database\\Scenarios\\')
                    ->toString();

                $this->availableScenarios->add(new $scenarioClass($this));
            });
    }

    protected function getScenariosPath(): string
    {
        return database_path('scenarios');
    }

    protected function resolveAlias(string $alias): ?Scenario
    {
        return $this->availableScenarios->first(fn ($scenario) => $scenario->alias == $alias);
    }

    protected function displayCleaningDatabaseMessage()
    {
        render(<<<'HTML'
            <div class="my-1 bg-orange text-black">Refreshing database...</div>
        HTML);
    }

    protected function displayRunningMessage(string $scenario)
    {
        render(<<<HTML
            <div class="my-1 bg-green text-black">Running $scenario...</div>
        HTML);
    }

    protected function fresh()
    {
        Artisan::call('migrate:fresh');
    }
}
