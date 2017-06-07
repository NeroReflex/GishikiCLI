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

use Gishiki\Algorithms\Collections\SerializableCollection;
use Gishiki\Security\Encryption\Asymmetric\PrivateKey;
use Gishiki\Security\Encryption\Symmetric\SecretKey;
use Gishiki\Exception;

abstract class Application
{
  public static function generate()
  {
    if ((file_exists('index.php')) || (file_exists('settings.json'))) {
          throw new \Exception('An application already exists in the current directory');
      }

      if ((!mkdir('Controllers'))) {
          throw new \Exception('The Controllers directory cannot be created');
      }

      if ((!mkdir('Models'))) {
          throw new \Exception('The Models directory cannot be created');
      }

      if (file_exists('composer.json')) {
          //composer have to autoload Controllers and Models
          $deserComposer = SerializableCollection::deserialize(file_get_contents('composer.json'), SerializableCollection::JSON);
          if (!$deserComposer->has('autoload')) {
              $deserComposer->set('autoload', ['classmap' => ['Controllers', 'Models']]);
              file_put_contents('composer.json', $deserComposer->serialize(SerializableCollection::JSON));
          }
      }

      //generate a new private key
      try {
          if (file_put_contents('private_key.pem', PrivateKey::generate(PrivateKey::RSA4096)) === false) {
              throw new \Exception('The application private key cannot be written');
          }
      } catch (Exception $ex) {
          throw new \Exception('The private key cannot be generated');
      }

      //generate and write out a new config file
      if (file_put_contents('settings.json', self::generateConfig()) === false) {
        throw new \Exception('The application configuration cannot be written');
      }

      $routerFile = null;
      if (file_exists(__DIR__.'/../neroreflex/gishikicli/GishikiCLI/Templates/index.php')) {
        $routerFile = file_get_contents(__DIR__.'/../neroreflex/gishikicli/GishikiCLI/Templates/index.php');
      }

      if ((is_null($routerFile)) || (file_put_contents('index.php', $routerFile) === false)) {
          throw new \Exception('The application router file cannot be written');
      }
  }

  private static function generateConfig()
  {
    //generate a new configuration file
    try {
        $settings = new SerializableCollection([
            'general' => [
                'development' => true,
                'autolog' => 'default',
            ],
            'loggers' => [
                'default' => [
                    [
                        'class' => 'StreamHandler',
                        'connection' => ['customLog.log', \Monolog\Logger::ERROR]
                    ],
                ]
            ],
            'security' => [
                'serverKey' => 'file://private_key.pem',
                'serverPassword' => SecretKey::generate(openssl_random_pseudo_bytes(32), 32),
            ],
            'connections' => [
                [
                    'name' => 'default',
                    'query' => 'sqlite://default.sqlite',
                ],
            ],
        ]);

        return $settings->serialize(SerializableCollection::JSON);
    } catch (Exception $ex) {
        throw new \Exception('The application configuration cannot be generated');
    }
  }
}
