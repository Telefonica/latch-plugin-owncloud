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

?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="apps/latch_plugin/css/latchOTPTemplate.css">
    </head>
    <body>
        <div class="twoFactorContainer" style="background-color: white;">
            <div class="twoFactorHeader">
                <img src="apps/latch_plugin/img/symbol.png">
                <p style="margin-top: 16px;"><?php p($l->t('One-time password'));?></p>
            </div>
            <div class="twoFactorForm">
                <form method="post" name="latch_OTP" accept-charset="UTF-8">
                    <fieldset style="margin-left: -9px;">
                        <div>
                            <label for="edit-twofactor" style="margin-left: 32px; margin-right: 32px;"><?php p($l->t('Type your one-time password'));?></label>
                            <input type="text" id="edit-twofactor" name="twoFactor" value="" autofocus 
                                   autocomplete="off" size="20" maxlength="128" style="border: solid 1px rgb(184, 184, 184);
                                   margin-left: 15px; margin-right: 15px;">
                        </div>
                        <input type="hidden" name="user" value="<?php p($_['username']); ?>">
                        <input type="hidden" name="password" value="<?php p($_['password']); ?>">
                        <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" />
                        <input type="submit" value="<?php p($l->t('Log in'));?>" class="login primary" 
                               style="float:none;background: #00b9be; color: 
                                      white; margin-top: 20px;margin-left: 100px;
                                      margin-right: 100px;">
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>