<?php

/*
  Latch ownCloud 7 plugin - Integrates Latch into the ownCloud 7 authentication process.
  Copyright (C) 2013 Eleven Paths

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
 * DESCRIPTION: This library defines functions needed in the pairing/unpairing 
 * process (it depends on the Latch SDK).
 */

// Latch SDK includes:
require_once 'latch_plugin/latchSDK/Latch.php';
require_once 'latch_plugin/latchSDK/LatchResponse.php';

// Functions:
function getLatchToken(){
    $token = '';
    if(isset($_POST['latch_token'])){
        $token = $_POST['latch_token'];
    }
    
    return $token;
}

function pairAccount($token, $user){
    $msg = ''; // This is the default value of the returned variable
    $accountID = OCP\Config::getUserValue($user,'latch_plugin','accountID','');
    if(empty($accountID)){ 
        // Current user account not paired
        $appID = OCP\Config::getAppValue('latch_plugin','appID','');
        $appSecret = OCP\Config::getAppValue('latch_plugin','appSecret','');
        
        if(!empty($appID) && !empty($appSecret)){
            // Latch plugin properly configured
            $api = new Latch($appID, $appSecret);
            $pairResponse = $api->pair($token);
            $pairResponseData = $pairResponse->getData();
            $msg = processPairResponseData($pairResponseData,$user);
        }
    }
    
    return $msg;
}

function processPairResponseData($pairResponseData,$user){
    $msg = ''; // This is the default value of the returned variable
    if(!empty($pairResponseData) && !empty($pairResponseData->{'accountId'})){
        // An accountID has been received
        $accountID = $pairResponseData->{'accountId'};
        // Save accountID in database and set success message:
        OCP\Config::setUserValue($user,'latch_plugin','accountID',$accountID);
        $msg = ['class' => 'msg success', 'value' => 'Pairing success'];
    }else{
        // There has been no success in the pairing process:
        $msg = ['class' => 'msg error', 'value' => 'Pairing token not valid'];
    }
    
    return $msg;
}

function unpairAccount($user){
    // First, check if user has actually a paired Latch account:
    $accountID = OCP\Config::getUserValue($user,'latch_plugin','accountID','');
    if(!empty($accountID)){
        // The current user has a paired account. 
        // Let's proceed with the unpairing:
        $appID = OCP\Config::getAppValue('latch_plugin','appID','');
        $appSecret = OCP\Config::getAppValue('latch_plugin','appSecret','');
        
        if(!empty($appID) && !empty($appSecret)){
            // Latch plugin properly configured
            $api = new Latch($appID, $appSecret);
            $api->unpair($accountID);
            
            // As the ownCloud API does not allow to delete the data saved with
            // the OCP\Config::setUserValue method, an empty string will be 
            // assigned to the stored accountID:
            OCP\Config::setUserValue($user,'latch_plugin','accountID','');
        }
    }
}