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

define(PLUGIN_NAME, 'latch_plugin');
define(APPID_KEY, 'appID');
define(APPSECRET_KEY, 'appSecret');
define(ACCOUNTID_KEY, 'accountID');
define(OTP_KEY, 'OTP');

class OC_LATCH_PLUGIN_DB{
    public static function saveAppID($appID){
        OCP\Config::setAppValue(PLUGIN_NAME,APPID_KEY,$appID);
    }
    
    public static function retrieveAppID(){
        return OCP\Config::getAppValue(PLUGIN_NAME,APPID_KEY,'');
    }
    
    public static function saveAppSecret($appSecret){
        OCP\Config::setAppValue(PLUGIN_NAME,APPSECRET_KEY,$appSecret);
    }
    
    public static function retrieveAppSecret(){
        return OCP\Config::getAppValue(PLUGIN_NAME,APPSECRET_KEY,'');
    }
    
    public static function saveAccountID($user,$accountID){
        OCP\Config::setUserValue($user,PLUGIN_NAME,ACCOUNTID_KEY,$accountID);
    }
    
    public static function retrieveAccountID($user){
        return OCP\Config::getUserValue($user,PLUGIN_NAME,ACCOUNTID_KEY,'');
    }
    
    public static function saveOTP($user,$otp){
        OCP\Config::setUserValue($user,PLUGIN_NAME,OTP_KEY,$otp);
    }
    
    public static function retrieveOTP($user){
        return OCP\Config::getUserValue($user,PLUGIN_NAME,OTP_KEY,'');
    }
    
    public static function deleteAccountData($user){
        if(OC_DB::connect()){
            $query = OC_DB::prepare("DELETE FROM `oc_preferences` WHERE `userid`=? AND `appid`='latch_plugin'");
            OC_DB::executeAudited($query,[$user]);
        }
    }
    
    public static function deletePluginData(){
        
    }
}
