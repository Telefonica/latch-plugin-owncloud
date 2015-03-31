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

use OCA\Latch_Plugin\AppInfo\Application;

// Get an instance of the Application class for dependency injection purposes:
$application = new Application();

$appName = $application->getContainer()->query('AppName');

// Check if admin user:
OC_Util::checkAdminUser();
OC_Util::checkAppEnabled($appName);

$dbService = $application->getContainer()->query('DbService');

// Needed for multi language support:
$l = OC_L10N::get($appName);

// Template object instantiation:
OCP\Util::addStyle('firstrunwizard', 'colorbox');
OCP\Util::addStyle($appName, 'uninstallStyle');
OCP\Util::addStyle($appName, 'uninstallLatchStyle');
OCP\Util::addScript($appName, 'uninstallPopup');

$tmpl = new OCP\Template($appName, 'latchAdminTemplate');

$msg = '';

// Save input values:
if (($_SERVER['REQUEST_METHOD'] === 'POST') && 
    isset($_POST['appID']) && isset($_POST['appSecret'])){
    
    OCP\Util::callCheck(); // Prevents CRSF
    
    if (preg_match("/^[a-zA-Z0-9]{20}$/",$_POST['appID']) && 
        preg_match("/^[a-zA-Z0-9]{40}$/",$_POST['appSecret'])){

        $dbService->saveAppID($_POST['appID']);
        $dbService->saveAppSecret($_POST['appSecret']);
    }else{
        
        $msg = [
            'class' => 'msg error',
            'value' => $l->t('Wrong Application ID or Application Secret string format')
        ];
    }
}

// Set placeholders to the input fields:
$appID = $dbService->retrieveAppID();
$tmpl->assign('appID',$appID);

$appSecret = $dbService->retrieveAppSecret();
$tmpl->assign('appSecret',$appSecret);

$tmpl->assign('msg',$msg);

return $tmpl->fetchPage();

