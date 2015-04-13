<?php

/*
  Latch ownCloud 8 plugin - Integrates Latch into the ownCloud 8 authentication process.
  Copyright (C) 2015 Eleven Paths.

  This library is free software; you can redistribute it and/or
  modify it under the terms of the GNU Lesser General Public
  License as published by the Free Software Foundation; either
  version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public
  License along with this library; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace OCA\Latch_Plugin\AppInfo;

use \OCP\AppFramework\App;

use \OCA\Latch_Plugin\Lib\DbService;
use \OCA\Latch_Plugin\Lib\LatchHooks;
use \OCA\Latch_Plugin\Lib\PairingService;

class Application extends App{
    /*
     *  This is the container where dependencies are registered
     */
    
    public function __construct(array $urlParams=array()){
    parent::__construct('latch_plugin', $urlParams);

    $container = $this->getContainer();

    
    /**
     * Services
     */
    
    $container->registerService('Config', function($c){
        
        return $c->query('ServerContainer')->getConfig();
    });
    
    $container->registerService('PluginConfig', function($c){
        
        return $c->query('ServerContainer')->getAppConfig();
    });
    
    $container->registerService('Session', function($c){
        
        return $c->query('ServerContainer')->getUserSession();
    });
    
    $container->registerService('L10N', function($c){
        
        return $c->query('ServerContainer')
                ->getL10N(
                    $c->query('AppName')
                );
    });
    
    $container->registerService('DbService', function($c){
        
        return new DbService(
                    $c->query('AppName'),
                    $c->query('Config'),
                    $c->query('PluginConfig')
                );
    });
    
    $container->registerService('PairingService', function($c){
        
        return new PairingService(
                    $c->query('L10N'),
                    $c->query('DbService')
                );
    });
    
    $container->registerService('LatchHooks', function($c){
        
        return new LatchHooks(
                    $c->query('AppName'),
                    $c->query('Session'),
                    $c->query('DbService')
                );
    });
  }
}