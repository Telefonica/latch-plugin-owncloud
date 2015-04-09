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

namespace OCA\Latch_Plugin\Lib;

use \OCP\IConfig;

define('PLUGIN_NAME', 'latch_plugin');
define('APPID_KEY', 'appID');
define('APPSECRET_KEY', 'appSecret');
define('ACCOUNTID_KEY', 'accountID');
define('OTP_KEY', 'OTP');

class DbService{
    
    // Attribute with access to ownCloud's config database
    /**
     * @var \OCP\IConfig $config 
     */
    private $config;
    
    private $appName;
    
    private $dbKeys = [
        'appIdKey'     => 'appID',
        'appSecretKey' => 'appSecret',
        'accountIdKey' => 'accountID',
        'otpKey'       => 'OTP'
    ];
    
    public function __construct(IConfig $config, $AppName){
        $this->config = $config;
        $this->appName = $AppName;
    }
    
    public function saveAppID($appID){
        $this->config->setAppValue(
                $this->appName,
                $this->dbKeys['appIdKey'],
                $appID);
    }
    
    public function retrieveAppID(){
        return $this->config->getAppValue(
                    $this->appName,
                    $this->dbKeys['appIdKey']
                );
    }
    
    public function saveAppSecret($appSecret){
        $this->config->setAppValue(
                $this->appName,
                $this->dbKeys['appSecretKey'],
                $appSecret);
    }
    
    public function retrieveAppSecret(){
        return $this->config->getAppValue(
                    $this->appName,
                    $this->dbKeys['appSecretKey']
                );
    }
    
    public function saveAccountID($user,$accountID){
        $this->config->setUserValue(
                    $user,
                    $this->appName,
                    $this->dbKeys['accountIdKey'],
                    $accountID
                );
    }
    
    public function retrieveAccountID($user){
        return $this->config->getUserValue(
                    $user,
                    $this->appName,
                    $this->dbKeys['accountIdKey']
                );
    }
    
    public function saveOTP($user,$otp){
        $this->config->setUserValue(
                    $user,
                    $this->appName,
                    $this->dbKeys['otpKey'],
                    $otp
                );
    }
    
    public function retrieveOTP($user){
        return $this->config->getUserValue(
                    $user,
                    $this->appName,
                    $this->dbKeys['otpKey']
                );
    }
    
    public function deleteAccountData($user){
        // Get all the config keys of the current 
        // user associated with the plugin
        $keys = $this->config->getUserKeys($user, $this->appName);
        
        foreach ($keys as $key) {
            $this->config->deleteUserValue($user, $this->appName, $key);
        }
    }
    
    public static function deletePluginData(){
        \OC_Preferences::deleteAppFromAllUsers(PLUGIN_NAME);
        \OC_AppConfig::deleteApp(PLUGIN_NAME);
    }
}