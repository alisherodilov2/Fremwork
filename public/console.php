<?php

use App\Console\CommandDispatcher;
use App\Console\MigrationsCommad\MakeMigrationCommand;


$dispatcher = new CommandDispatcher();
$dispatcher->registerCommand("make:migration" , new MakeMigrationCommand('CreateUsertable'));