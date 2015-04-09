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
use OCA\Latch_Plugin\Lib\DbService;

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
    
    $container->registerService('DbService', function($c){
        
        return new DbService(
                    $c->query('Config'),
                    $c->query('AppName')
                );
    });

  }
}