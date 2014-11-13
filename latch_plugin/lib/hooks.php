<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: The class whose methods will handle the events of the ownCloud's
 * hooks is described in this file.
 */

// Latch SDK includes:
require_once 'latch_plugin/latchSDK/Latch.php';
require_once 'latch_plugin/latchSDK/LatchResponse.php';

class OC_LATCH_PLUGIN_Hooks{
    
    static function postLogin($parameters) {
        $user = $parameters['uid'];
        self::checkLatch($user);
    }
    
    private function checkLatch($user) {
        // Check if current user has an accountID:
        $accountID = OCP\Config::getUserValue($user,'latch_plugin','accountID','');
        if(!empty($accountID)){
            // Retrieve Latch status from Latch server:
            $statusResponse = self::getLatchStatus($accountID);
            self::processStatusResponse($statusResponse,$user);
        }
    }
    
    private function getLatchStatus($accountID) {
        // Retrieve appID and appSecret from database:
        $appID = OCP\Config::getAppValue('latch_plugin','appID','');
        $appSecret = OCP\Config::getAppValue('latch_plugin','appSecret','');
        if(!empty($appID) && !empty($appSecret)){
            // Latch plugin properly configured
            $api = new Latch($appID, $appSecret);
            return $api->status($accountID);
        }else{
            // Latch plugin not configured properly
            // Return an empty response
            return new LatchResponse("");
        }
    }
    
    private function processStatusResponse($statusResponse,$user){
        // Retrieve appID from database:
        $appID = OCP\Config::getAppValue('latch_plugin','appID','');
        
        // Extract data and possible errors from the status response:
        $responseData = $statusResponse->getData();
        $responseError = $statusResponse->getError();   
        
        if(empty($statusResponse) || (empty($responseData) && empty($responseError))){
            // This is the case when something goes wrong, e.g. problems 
            // with Latch service or with plugin configuration (ACCESS GRANTED)
        }else{
            if(!empty($responseError) && $responseError->getCode() == 201){
                // This error could happen because the user may have unpaired 
                // their Lacth account externally. Therefore, their accountID 
                // must be deleted from database (ACCESS GRANTED)
                OCP\Config::setUserValue($user,'latch_plugin','accountID','');
            }
            if(!empty($responseData) && self::isLatchUnblocked($responseData,$appID)){
                // Current user properly logged in with unblocked Latch 
                // (ACCESS GRANTED)
            }else{
                // Current user properly logged in, but with blocked Latch 
                // (ACCESS DENIED)
                OCP\User::logout();
            }
        }
    }
    
    private function isLatchUnblocked($responseData, $appID) {
        return $responseData->{"operations"}->{$appID}->{"status"} === "on";
    }
}