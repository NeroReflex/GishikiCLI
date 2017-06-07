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

abstract class Controller
{
    public static function generate($controllerName)
    {
        if (!file_exists('Controllers')) {
            throw new \Exception('The Controllers directory doesn\'t exists');
        }

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $controllerName)) {
            throw new \Exception('The controller name is not valid');
        }

        if (file_exists('Controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php')) {
            throw new \Exception('A controller with the same name already exists');
        }

        $controllerText = null;
        if (file_exists(__DIR__.'/../neroreflex/gishikicli/GishikiCLI/Templates/controller.php')) {
            $routerFile = str_replace('ctrlName', $controllerName,
                file_get_contents(__DIR__.'/../neroreflex/gishikicli/GishikiCLI/Templates/controller.php')
            );
        }

        if (file_put_contents('Controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php', $controllerText) === false) {
            throw new \Exception('The new controller file cannot be written');
        }

        if (file_exists('index.php')) {
            $routeSettings = file_get_contents('index.php');
            $exampleCall = PHP_EOL.'Route::get("/'.$controllerName.'", "'.$controllerName.'->getTime");';
            $newRouteSettings = str_replace('//this triggers the framework execution',$exampleCall.PHP_EOL.'//this triggers the framework execution', $routeSettings);
            file_put_contents('index.php', $newRouteSettings);
        }
    }


}
