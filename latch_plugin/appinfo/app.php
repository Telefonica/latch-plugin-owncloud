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
 * DESCRIPTION: This script is the first to be exeuted by ownCloud. 
 * It includes some libraries and defines the app architechture.
 */

// Registration of some classes in the ownCloud's CLASSPATH variable:
OC::$CLASSPATH['OC_LATCH_PLUGIN_Hooks'] = 'latch_plugin/lib/hooks.php';

// Hooks:
OCP\Util::connectHook('OC_User','post_login','OC_LATCH_PLUGIN_Hooks','postLogin');

// Admin menu configuration:
OCP\App::registerAdmin('latch_plugin','latchAdmin');

// Pairing menu configuration:
OCP\App::registerPersonal('latch_plugin','latchPairing');