<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: This file defines the controller of the HTML view to be included
 * into the ownCloud's User Panel view. It validates the form to pair/unpair a  
 * Latch account.
 */

// Library includes:
require_once 'lib/latchPairingLib.php';
require_once 'lib/db.php';

// Check if the user is logged in and get username:
OC_Util::checkLoggedIn();
$user = OCP\User::getUser();

// Constants:
$DEFAULT_STRING = '';

// Variables:
$msg = $DEFAULT_STRING;

// Form validation logic:
if(($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['action'])){
    $action = $_POST['action'];
    switch ($action) {
        case 'pair':
            $token = getLatchToken();
            if($token !== $DEFAULT_STRING){
                $msg = pairAccount($token, $user);
            }else{
                $msg = ['class' => 'msg error',
                'value' => 'Latch Pairing Token field is required'];
            }
            break;
        
        case 'unpair':
            unpairAccount($user);
            break;
        
        default:
            $msg = ['class' => 'msg error',
                'value' => 'Oops! The requested action could not be executed'];
            break;
    }
}


// Template object instantiation:
$tmpl = new OCP\Template('latch_plugin','latchPairingTemplate');

// Check if user has an account ID:
$accountID = OC_LATCH_PLUGIN_DB::retrieveAccountID($user);

$has_account = !empty($accountID);

// Assign variables to the template:
$tmpl->assign('msg',$msg);
$tmpl->assign('has_account',$has_account);

return $tmpl->fetchPage();