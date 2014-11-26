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


class OC_LATCH_PLUGIN_DB{
    public static function saveAppID($appID){
        OCP\Config::setAppValue('latch_plugin','appID',$appID);
    }
    
    public static function retrieveAppID(){
        return OCP\Config::getAppValue('latch_plugin','appID','');
    }
    
    public static function saveAppSecret($appSecret){
        OCP\Config::setAppValue('latch_plugin','appSecret',$appSecret);
    }
    
    public static function retrieveAppSecret(){
        return OCP\Config::getAppValue('latch_plugin','appSecret','');
    }
    
    public static function saveAccountID($user,$accountID){
        OCP\Config::setUserValue($user,'latch_plugin','accountID',$accountID);
    }
    
    public static function retrieveAccountID($user){
        return OCP\Config::getUserValue($user,'latch_plugin','accountID','');
    }
    
    public static function saveOTP($user,$otp){
        OCP\Config::setUserValue($user,'latch_plugin','OTP',$otp);
    }
    
    public static function retrieveOTP($user){
        return OCP\Config::getUserValue($user,'latch_plugin','OTP','');
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
