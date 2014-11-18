<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
                <p style="margin-top: 16px;">One-time password</p>
            </div>
            <div class="twoFactorForm">
                <form method="post" name="latch_OTP" accept-charset="UTF-8">
                    <fieldset style="margin-left: -9px;">
                        <div>
                            <label for="edit-twofactor" style="margin-left: 32px; margin-right: 32px;">Type your one-time password </label>
                            <input type="text" id="edit-twofactor" name="twoFactor" value="" autofocus 
                                   autocomplete="off" size="20" maxlength="128" style="border: solid 1px rgb(184, 184, 184);
                                   margin-left: 15px; margin-right: 15px;">
                        </div>
                        <input type="hidden" name="user" value="<?php p($_['username']); ?>">
                        <input type="hidden" name="password" value="<?php p($_['password']); ?>">
                        <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" />
                        <input type="submit" value="Log in" class="login primary" 
                               style="float:none;background: #00b9be; color: 
                                      white; margin-top: 20px;margin-left: 100px;
                                      margin-right: 100px;">
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>