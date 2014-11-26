<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: This file defines the controller of the HTML view to be included
 * into the ownCloud's Administrator Panel view. It validates the form to 
 * configure the Latch Application.
 */

// Library includes:
require_once 'lib/db.php';

// Check if admin user:
OC_Util::checkAdminUser();

// Template object instantiation:
$tmpl = new OCP\Template('latch_plugin', 'latchAdminTemplate');

// VAriables:
$msg = '';

// Save input values:
if (($_SERVER['REQUEST_METHOD'] === 'POST') && 
    isset($_POST['appID']) && isset($_POST['appSecret'])){
    
    if (preg_match("/^[a-zA-Z0-9]{20}$/",$_POST['appID']) && 
        preg_match("/^[a-zA-Z0-9]{40}$/",$_POST['appSecret'])){

        OC_LATCH_PLUGIN_DB::saveAppID($_POST['appID']);
        OC_LATCH_PLUGIN_DB::saveAppSecret($_POST['appSecret']);
    }else{
        
        $msg = [
            'class' => 'msg error',
            'value' => 'Wrong Application ID or Application Secret string format'
        ];
    }
}

// Set placeholders to the input fields:
$appID = OC_LATCH_PLUGIN_DB::retrieveAppID();
$tmpl->assign('appID',$appID);

$appSecret = OC_LATCH_PLUGIN_DB::retrieveAppSecret();
$tmpl->assign('appSecret',$appSecret);

$tmpl->assign('msg',$msg);

return $tmpl->fetchPage();

