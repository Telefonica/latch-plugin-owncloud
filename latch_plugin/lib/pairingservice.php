<?php

/*
  Latch ownCloud 8 plugin - Integrates Latch into the ownCloud 8 authentication process.
  Copyright (C) 2015 Eleven Paths..

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
 * DESCRIPTION: This library defines functions needed in the pairing/unpairing 
 * process (it depends on the Latch SDK).
 */

namespace OCA\Latch_Plugin\Lib{

    use \OCP\IL10N;
    
    use \Latch;

    // Class definition:
    class PairingService{

        private $l10n;

        private $dbService;

        public function __construct(IL10N $l10n, DbService $dbService) {
            $this->l10n      = $l10n;
            $this->dbService = $dbService;
        }

        public function getLatchToken(){
            $token = '';
            if(isset($_POST['latch_token'])){
                $token = $_POST['latch_token'];
            }

            return $token;
        }

        public function pairAccount($token, $user){
            $msg = ''; // This is the default value of the returned variable
            $accountID = $this->dbService->retrieveAccountID($user);
            if(empty($accountID)){ 
                // Current user account not paired
                $appID = $this->dbService->retrieveAppID();
                $appSecret = $this->dbService->retrieveAppSecret();

                if(!empty($appID) && !empty($appSecret)){
                    // Latch plugin properly configured
                    $api = new Latch($appID, $appSecret);
                    $pairResponse = $api->pair($token);
                    $pairResponseData = $pairResponse->getData();
                    $msg = $this->processPairResponseData($pairResponseData,$user);
                }
            }

            return $msg;
        }

        private function processPairResponseData($pairResponseData,$user){
            $msg = ''; // This is the default value of the returned variable
            if(!empty($pairResponseData) && !empty($pairResponseData->{'accountId'})){
                // An accountID has been received
                $accountID = $pairResponseData->{'accountId'};
                // Save accountID in database and set success message:
                $this->dbService->saveAccountID($user, $accountID);
                $msg = ['class' => 'msg success', 'value' => $this->l10n->t('Pairing success')];
            }else{
                // There has been no success in the pairing process:
                $msg = ['class' => 'msg error', 'value' => $this->l10n->t('Pairing token not valid')];
            }

            return $msg;
        }

        public function unpairAccount($user){
            // First, check if user has actually a paired Latch account:
            $accountID = $this->dbService->retrieveAccountID($user);
            if(!empty($accountID)){
                // The current user has a paired account. 
                // Let's proceed with the unpairing:
                $appID = $this->dbService->retrieveAppID();
                $appSecret = $this->dbService->retrieveAppSecret();

                if(!empty($appID) && !empty($appSecret)){
                    // Latch plugin properly configured
                    $api = new Latch($appID, $appSecret);
                    $api->unpair($accountID);
                    $this->dbService->deleteAccountData($user);
                }
            }
        }


    }
}