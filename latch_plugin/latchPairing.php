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
 * into the ownCloud's User Panel view. It validates the form to pair/unpair a  
 * Latch account.
 */

// Library includes:
require_once 'lib/latchPairingLib.php';
require_once 'lib/db.php';

// Check if the user is logged in and get username:
OC_Util::checkLoggedIn();
OC_Util::checkAppEnabled('latch_plugin');


$user = OCP\User::getUser();

// Variables:
$msg = '';

// Form validation logic:
if(($_SERVER['REQUEST_METHOD'] === 'POST')){
    // A pairing or unpairing action is performed depending on the case when the
    // current user has (or not) an accountID:
    
    OCP\Util::callCheck(); // Prevents CRSF
    
    $accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);

    if(empty($accountID)){
        $token = getLatchToken();
        if(!empty($token)){
            $msg = pairAccount($token, $user);
        }else{
            $msg = ['class' => 'msg error',
            'value' => 'Latch Pairing Token field is required'];
        }
    } else {
        unpairAccount($user);
    }
}

// Template object instantiation:
$tmpl = new OCP\Template('latch_plugin','latchPairingTemplate');

// Check if user has an account ID:
$accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);

$has_account = !empty($accountID);

// Assign variables to the template:
$tmpl->assign('msg',$msg);
$tmpl->assign('has_account',$has_account);

return $tmpl->fetchPage();