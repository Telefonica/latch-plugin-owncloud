<?php

/*
  Latch ownCloud 7 plugin - Integrates Latch into the ownCloud 7 authentication process.
  Copyright (C) 2014 Eleven Paths.

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
 * DESCRIPTION: This file defines the controller of the HTML view to be included
 * into the ownCloud's Administrator Panel view. It validates the form to 
 * configure the Latch Application.
 */

// Check if admin user:
OC_Util::checkAdminUser();

// Constants:
$DEFAULT_STRING = '';

// Template object instantiation:
$tmpl = new OCP\Template('latch_plugin', 'latchAdminTemplate');

// Save input values:
if ($_POST && isset($_POST['appID']) ){
    OCP\Config::setAppValue('latch_plugin','appID',$_POST['appID']);
}

if ($_POST && isset($_POST['appSecret']) ){
    OCP\Config::setAppValue('latch_plugin','appSecret',$_POST['appSecret']);
}

// Set placeholders to the input fields:
$appID = OCP\Config::getAppValue('latch_plugin','appID',$DEFAULT_STRING);
$tmpl->assign('appID',$appID);

$appSecret = OCP\Config::getAppValue('latch_plugin','appSecret',$DEFAULT_STRING);
$tmpl->assign('appSecret',$appSecret);


return $tmpl->fetchPage();

