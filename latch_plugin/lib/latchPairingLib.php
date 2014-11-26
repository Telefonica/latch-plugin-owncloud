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

// Library includes:
require_once 'latch_plugin/latchSDK/Latch.php';
require_once 'latch_plugin/latchSDK/LatchResponse.php';
require_once 'latch_plugin/lib/db.php';

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
    $accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);
    if(empty($accountID)){ 
        // Current user account not paired
        $appID = OC_LATCH_PLUGIN_DB::retrieveAppID();
        $appSecret = OC_LATCH_PLUGIN_DB::retrieveAppSecret();
        
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
        OC_LATCH_PLUGIN_DB::saveAccountID($user, $accountID);
        $msg = ['class' => 'msg success', 'value' => 'Pairing success'];
    }else{
        // There has been no success in the pairing process:
        $msg = ['class' => 'msg error', 'value' => 'Pairing token not valid'];
    }
    
    return $msg;
}

function unpairAccount($user){
    // First, check if user has actually a paired Latch account:
    $accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);
    if(!empty($accountID)){
        // The current user has a paired account. 
        // Let's proceed with the unpairing:
        $appID = OC_LATCH_PLUGIN_DB::retrieveAppID();
        $appSecret = OC_LATCH_PLUGIN_DB::retrieveAppSecret();
        
        if(!empty($appID) && !empty($appSecret)){
            // Latch plugin properly configured
            $api = new Latch($appID, $appSecret);
            $api->unpair($accountID);
            
            OC_LATCH_PLUGIN_DB::deleteAccountData($user);
        }
    }
}