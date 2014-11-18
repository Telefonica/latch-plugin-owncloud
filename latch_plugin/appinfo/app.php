<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: This script is the first to be exeuted by ownCloud. 
 * It includes some libraries and defines the app architechture.
 */

// Registration of some classes in the ownCloud's CLASSPATH variable:
OC::$CLASSPATH['OC_LATCH_PLUGIN_Hooks'] = 'latch_plugin/lib/hooks.php';

// Hooks:
OCP\Util::connectHook('OC_User','post_login','OC_LATCH_PLUGIN_Hooks','postLogin');

// Admin menu configuration:
OCP\App::registerAdmin('latch_plugin','latchAdmin');

// Pairing menu configuration:
OCP\App::registerPersonal('latch_plugin','latchPairing');