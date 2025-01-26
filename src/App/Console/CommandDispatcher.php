<?php

namespace App\Console;

use CommandInterface;

class CommandDispatcher {
    protected $commands = [];

    public function registerCommand(string $name, CommandInterface $command) {
        $this->commands[$name] = $command;
    }

    public function run(string $commandName) {
        if (isset($this->commands[$commandName])) {
            $this->commands[$commandName]->handle();
        } else {
            echo "Command not found: {$commandName}\n";
        }
    }
}
