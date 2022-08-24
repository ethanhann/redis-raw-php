<?php

use Robo\Symfony\ConsoleIO;
use Robo\Collection\CollectionBuilder;
use Robo\Result;
use Robo\Tasks;

require_once 'vendor/autoload.php';

class RoboFile extends Tasks
{
    public function build(ConsoleIO $io): Result
    {
        return $this->collectionBuilder($io)
            ->addTask($this->taskFixCodeStyle())
            ->addTask($this->taskPhpUnit())
            ->run();
    }

    public function test(): Result
    {
        return $this->taskPhpUnit()->run();
    }

    public function taskFixCodeStyle(): CollectionBuilder
    {
        return $this->taskExec('./vendor/bin/php-cs-fixer fix src');
    }
}
