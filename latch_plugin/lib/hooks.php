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

// Library includes:
require_once 'latch_plugin/latchSDK/Latch.php';
require_once 'latch_plugin/latchSDK/LatchResponse.php';
require_once 'latch_plugin/lib/db.php';

class OC_LATCH_PLUGIN_Hooks{
    
    static public function postLogin($parameters) {
        $user     = $parameters['uid'];
        $password = $parameters['password'];
        
        // At this point, it is necessary to know whether this function has been
        // called due to a login, or it is because of an OTP sending:
        if(isset($_POST['twoFactor'])){
            self::compareOTP($user);
        }else{
            self::checkLatch($user,$password);
        }
    }
    
    private function compareOTP($user) {
        // Retrieve OTP from database:
        $otp = OC_LATCH_PLUGIN_DB::retrieveOTP($user);
        OC_LATCH_PLUGIN_DB::saveOTP($user, '');//No longer needed
        
        if($_POST['twoFactor'] !== $otp){
            // Wrong OTP. Redirect to login page (ACCESS DENIED)
            OCP\User::logout();
            $parameters = [
                'user_autofocus' => true,
                'rememberLoginAllowed' => true,
                'invalidpassword' => false
            ];
            OC_Template::printGuestPage('','login',$parameters);
        }
        
        // In case the sent OTP is correct, the current user has access to the 
        // platform (ACCESS GRANTED)
    }
    
    private function checkLatch($user,$password) {
        // Check if current user has an accountID:
        $accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);
        if(!empty($accountID)){
            // Retrieve Latch status from Latch server:
            $statusResponse = self::getLatchStatus($accountID);
            self::processStatusResponse($statusResponse,$user,$password);
        }
    }
    
    private function getLatchStatus($accountID) {
        // Retrieve appID and appSecret from database:
        $appID = OC_LATCH_PLUGIN_DB::retrieveAppID(); 
        $appSecret = OC_LATCH_PLUGIN_DB::retrieveAppSecret();
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
    
    private function processStatusResponse($statusResponse,$user,$password){
        // Retrieve appID from database:
        $appID = OC_LATCH_PLUGIN_DB::retrieveAppID();
        
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
                OC_LATCH_PLUGIN_DB::deleteAccountData($user);
            }
            if(!empty($responseData) && self::isLatchUnblocked($responseData,$appID)){
                // Current user properly logged in with unblocked Latch 
                
                // First, it is necessary to check if OTP functionality has been
                // enabled:
                self::checkOTP($responseData,$appID,$user,$password);
                // In the case it is not enabled, the current user has access to
                // the platform (ACCESS GRANTED)
            }else{
                // Current user properly logged in, but with blocked Latch 
                // (ACCESS DENIED)
                OCP\User::logout();
                $params = ['invalidpassword' => true,
                            'rememberLoginAllowed' => true,
                            'username' => $user];
                OC_Template::printGuestPage('','login',$params);
            }
        }
    }
    
    private function isLatchUnblocked($responseData, $appID) {
        return $responseData->{"operations"}->{$appID}->{"status"} === "on";
    }
    
    private function checkOTP($responseData,$appID,$user,$password) {
        if(self::isOTPenabled($responseData,$appID)){
            // Extract OTP from response object and store it in database:
            $otp = $responseData->{"operations"}->{$appID}->{"two_factor"}->{"token"};
            OC_LATCH_PLUGIN_DB::saveOTP($user, $otp);
            
            // End current user's session and redirect them to OTP template:
            OCP\User::logout();
            $vars = ['username' => $user, 'password' => $password];
            OC_Template::printGuestPage('latch_plugin','latchOTPTemplate',$vars);
        }
    }
    
    private function isOTPEnabled($responseData,$appID){
        return property_exists($responseData->{"operations"}->{$appID}, "two_factor");
    }
}