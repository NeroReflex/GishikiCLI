<?php
/**************************************************************************
Copyright 2017 Benato Denis

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
 *****************************************************************************/


namespace GishikiCLI;

use Gishiki\Core\Environment;
use Gishiki\CLI\Console;
use Gishiki\CLI\ConsoleColor;

abstract class CLIActions
{
    public static function createController($controllerName)
    {
        try {
            if (!Environment::applicationExists()) {
                throw new \Exception('A valid Gishiki application was not found on the current directory.');
            }

            if (strlen($controllerName) <= 0) {
                throw new \Exception('Invalid name for a new controller.');
            }

            Controller::generate($controllerName);
            Console::setForegroundColor(ConsoleColor::TEXT_GREEN);
            Console::setBackgroundColor(ConsoleColor::BACKGROUND_WHITE);
            Console::writeLine("The new controller has been created, remember to run 'composer update --no-dev' when you're done adding controllers");
        } catch (\Exception $ex) {
            Console::setForegroundColor(ConsoleColor::TEXT_RED);
            Console::setBackgroundColor(ConsoleColor::BACKGROUND_WHITE);
            Console::writeLine($ex->getMessage());
        }
    }

    public static function initialize()
    {
        try {
            Application::generate();
            Console::setForegroundColor(ConsoleColor::TEXT_GREEN);
            Console::setBackgroundColor(ConsoleColor::BACKGROUND_WHITE);
            Console::writeLine("The new application has been created");
        } catch (\Exception $ex) {
            Console::setForegroundColor(ConsoleColor::TEXT_RED);
            Console::setBackgroundColor(ConsoleColor::BACKGROUND_WHITE);
            Console::writeLine($ex->getMessage());
        }
    }

    public static function help($cmd)
    {
        Console::writeLine("The following is a list of arguments that can be passed to the Gishiki CLI");
        Console::writeLine("");
        Console::writeLine($cmd." new application: generates an empty application");
        Console::writeLine($cmd." new controller <name>: generates an empty controller with the given name");
    }

    public static function unspecified($cmd)
    {
        Console::setForegroundColor(ConsoleColor::TEXT_RED);
        Console::setBackgroundColor(ConsoleColor::BACKGROUND_WHITE);
        Console::writeLine("Invalid command: use '$cmd help' for a list of available commands");
    }
}