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
 * DESCRIPTION: This file defines the controller of the HTML view to be included
 * into the ownCloud's User Panel view. It validates the form to pair/unpair a  
 * Latch account.
 */

use \OCP\JSON;
use \OCP\Template;
use \OCP\User;

use \OCA\Latch_Plugin\AppInfo\Application;

// Get an instance of the Application class for dependency injection purposes:
$application = new Application();

$appName = $application->getContainer()->query('AppName');

// Check if the user is logged in and get username:
JSON::checkLoggedIn();
JSON::checkAdminUser();
JSON::checkAppEnabled($appName);

$dbService = $application->getContainer()->query('DbService');

$user = User::getUser();

// Variables:
$msg = '';

// Needed for multi language support:
$l = $application->getContainer()->query('L10N');

// Form validation logic:
if(($_SERVER['REQUEST_METHOD'] === 'POST')){
    // A pairing or unpairing action is performed depending on the case when the
    // current user has (or not) an accountID:
    
    JSON::callCheck(); // Prevents CSRF
    
    $pairingService = $application->getContainer()->query('PairingService');
    
    $accountID = $dbService->retrieveAccountID($user);
    
    if(empty($accountID)){
        $token = $pairingService->getLatchToken();
        if(!empty($token)){
            $msg = $pairingService->pairAccount($token, $user);
        }else{
            $msg = ['class' => 'msg error',
            'value' => $l->t('Latch Pairing Token field is required')];
        }
    } else {
        $pairingService->unpairAccount($user);
    }
}

// Template object instantiation:
$tmpl = new Template($appName,'latchPairingTemplate');

// Check if user has an account ID:
$accountID = $dbService->retrieveAccountID($user);

$has_account = !empty($accountID);

// Assign variables to the template:
$tmpl->assign('msg',$msg);
$tmpl->assign('has_account',$has_account);

return $tmpl->fetchPage();