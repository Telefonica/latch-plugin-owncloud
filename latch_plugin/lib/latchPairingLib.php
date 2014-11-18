<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    $DEFAULT_STRING = '';
    $token = $DEFAULT_STRING;
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