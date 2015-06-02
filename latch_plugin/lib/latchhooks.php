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
 * DESCRIPTION: The class whose methods will handle the events of the ownCloud's
 * hooks is described in this file.
 */


namespace OCA\Latch_Plugin\Lib;

use \ElevenPaths\Latch\LatchApp as Latch;
use \ElevenPaths\Latch\LatchResponse;

use \OC\User\Session;

use \OCP\Template;
use \OCP\Util;

use \OCA\Latch_Plugin\Lib\DbService;

error_reporting(0);

class LatchHooks{
    
    private $appName;
    
    private $userSession;
    
    private $dbService;
    
    public function __construct($AppName, Session $userSession, DbService $dbService) {
        $this->appName     = $AppName;
        $this->userSession = $userSession;
        $this->dbService   = $dbService;
    }
    
    public function register(){
        // The method 'postLogin' is registered 
        // under the hook of the same name:
        $callback = function($user, $password){
            $this->postLogin($user->getUID(), $password);
        };
        
        $this->userSession->listen('\OC\User', 'postLogin', $callback);
    }


    public function postLogin($user, $password) {
        // At this point, it is necessary to know whether this function has been
        // called due to a login, or it is because of an OTP sending:
        if(isset($_POST['twoFactor'])){
            $this->compareOTP($user);
        }else{
            $this->checkLatch($user,$password);
        }
    }
    
    private function compareOTP($user) {
        // Retrieve OTP from database:
        $otp = $this->dbService->retrieveOTP($user);
        
        if(empty($_POST['twoFactor']) || ($_POST['twoFactor'] !== $otp)){
            // Wrong OTP. Redirect to login page (ACCESS DENIED)
            $this->dbService->saveOTP($user, NULL);//No longer needed

            $this->userSession->logout();
            $parameters = [
                'user_autofocus' => true,
                'rememberLoginAllowed' => true,
                'invalidpassword' => false
            ];
            OC_Template::printGuestPage('','login',$parameters);
            exit();
        }
        
        // In case the sent OTP is correct, the current user has access to the 
        // platform (ACCESS GRANTED)
    }
    
    private function checkLatch($user,$password) {
        // Check if current user has an accountID:
        $accountID = $this->dbService->retrieveAccountID($user);
        if(!empty($accountID)){
            // Retrieve Latch status from Latch server:
            $statusResponse = $this->getLatchStatus($accountID);
            $this->processStatusResponse($statusResponse,$user,$password);
        }
    }
    
    private function getLatchStatus($accountID) {
        // Retrieve appID and appSecret from database:
        $appID = $this->dbService->retrieveAppID(); 
        $appSecret = $this->dbService->retrieveAppSecret();
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
        $appID = $this->dbService->retrieveAppID();
        
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
                $this->dbService->deleteAccountData($user);
            }
            if(!empty($responseData) && $this->isLatchUnblocked($responseData,$appID)){
                // Current user properly logged in with unblocked Latch 
                
                // First, it is necessary to check if OTP functionality has been
                // enabled:
                $this->checkOTP($responseData,$appID,$user,$password);
                // In the case it is not enabled, the current user has access to
                // the platform (ACCESS GRANTED)
            }else{
                // Current user properly logged in, but with blocked Latch 
                // (ACCESS DENIED)
                $this->userSession->logout();
                $params = ['invalidpassword' => true,
                            'rememberLoginAllowed' => true,
                            'username' => $user];
                OC_Template::printGuestPage('','login',$params);
                exit();
            }
        }
    }
    
    private function isLatchUnblocked($responseData, $appID) {
        return $responseData->{"operations"}->{$appID}->{"status"} === "on";
    }
    
    private function checkOTP($responseData,$appID,$user,$password) {
        if($this->isOTPenabled($responseData,$appID)){
            // Extract OTP from response object and store it in database:
            $otp = $responseData->{"operations"}->{$appID}->{"two_factor"}->{"token"};
            $this->dbService->saveOTP($user, $otp);
            
            // End current user's session and redirect them to OTP template:
            $this->userSession->logout();
            $vars = ['username' => $user, 'password' => $password];
            OCP\Util::addStyle('latch_plugin', 'latchOTPTemplate');
            OC_Template::printGuestPage('latch_plugin','latchOTPTemplate',$vars);
            exit();
        }
    }
    
    private function isOTPEnabled($responseData,$appID){
        return property_exists($responseData->{"operations"}->{$appID}, "two_factor");
    }
}