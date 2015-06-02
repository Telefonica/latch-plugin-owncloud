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

/*
 * DESCRIPTION: This script is the first to be executed by ownCloud. 
 * It includes some libraries and defines the app architechture.
 */

use \OCP\App;

use \OCA\Latch_Plugin\AppInfo\Application;

// Latch SDK files includes:
require_once 'latch_plugin/latchSDK/Error.php';
require_once 'latch_plugin/latchSDK/LatchResponse.php';
require_once 'latch_plugin/latchSDK/LatchAuth.php';
require_once 'latch_plugin/latchSDK/LatchApp.php';
    
// Hooks:
$app = new Application();
$app->getContainer()->query('LatchHooks')->register();

$appName = $app->getContainer()->query('AppName');
// Admin menu configuration:
App::registerAdmin($appName,'latchAdmin');

// Pairing menu configuration:
App::registerPersonal($appName,'latchPairing');