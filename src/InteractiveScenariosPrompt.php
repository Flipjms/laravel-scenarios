<?php

namespace Flipjms\Scenarios;

use Illuminate\Support\Collection;
use function Termwind\ask;
use function Termwind\render;

class InteractiveScenariosPrompt
{
    public function __construct(protected Collection $availableScenarios)
    {
    }

    public function __invoke(): string
    {
        $this->displayHeader();
        $this->displayOptions();

        do {
            $option = (int) $this->ask();
        } while ($this->isInvalidOption($option));

        return $this->availableScenarios->get($option)->alias;
    }

    protected function displayHeader(): void
    {
        render('<em class="mb-1">These are the available scenarios:</em>');
    }

    protected function displayOptions(): void
    {
        $counter = 0;

        $this->availableScenarios->each(function ($scenario) use (&$counter) {
            render(<<<HTML
                <div>
                    <p>
                        <strong class="bg-gray">($counter) $scenario->alias</strong><br>
                        <em>$scenario->description</em>
                    </p>
                </div>
            HTML);
            $counter++;
        });
    }

    protected function ask()
    {
        return ask(<<<'HTML'
            <div class="mt-1 mr-1 bg-green px-1 text-black">
                Which one do you want to set?
            </div>
        HTML);
    }

    protected function isInvalidOption(int $option): bool
    {
        $isInvalid = $option < 0 || $option >= $this->availableScenarios->count();

        if ($isInvalid) {
            render(<<<'HTML'
                <div class="bg-red my-2 p-2">The given option is invalid!</div>
            HTML);
        }

        return $isInvalid;
    }
}
