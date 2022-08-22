<?php

namespace Flipjms\Scenarios;

use Illuminate\Console\Command;

class Scenario
{
    public function __construct(private Command $command)
    {
    }

    public function table($headers, $rows, $tableStyle = 'default', array $columnStyles = [])
    {
        $this->command->table($headers, $rows, $tableStyle, $columnStyles);
    }

    public function info($string, $verbosity = null)
    {
        $this->command->info($string, $verbosity);
    }

    public function line($string, $style = null, $verbosity = null)
    {
        $this->command->line($string, $style, $verbosity);
    }

    public function comment($string, $verbosity = null)
    {
        $this->command->comment($string, $verbosity);
    }

    public function question($string, $verbosity = null)
    {
        $this->command->question($string, $verbosity);
    }

    public function error($string, $verbosity = null)
    {
        $this->command->error($string, $verbosity);
    }

    public function warn($string, $verbosity = null)
    {
        $this->command->warn($string, $verbosity);
    }

    public function alert($string)
    {
        $this->command->alert($string);
    }

    public function newLine($count = 1)
    {
        $this->command->newLine($count);
    }
}
