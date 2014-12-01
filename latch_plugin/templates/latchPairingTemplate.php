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

/*
 * DESCRIPTION: This is the HTML template that creates the Latch Pairing 
 * form. The information received is transferred to "latchPairing.php".
 */

?>

<form id="latch_pairing_form" class="section" method="POST" action="#latch_pairing_form">
    <h2>Latch Account</h2>
    
        <?php if(is_array($_['msg'])){ ?>
        <br>
        <span class="<?php p($_['msg']['class']); ?>">
            <?php p($_['msg']['value']); ?>
        </span>
        <br>    
        <?php } ?>
        
    <?php if($_['has_account']){ ?>
        <em>Click the button to unpair your Latch account</em>
        <br>
        <input type="hidden" name="action" value="unpair" >
        <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" >
        <input type='submit' value='<?php p($l->t('Unpair Account'));?>'>
    <?php }else{ ?>
        <br>
        <strong>Latch Pairing Token</strong>
        <br>
        <input type="text" name="latch_token" autocomplete="off" maxlength="10">
        <br>
        <br>  
        <input type="hidden" name="action" value="pair" >
        <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" >
        <input type='submit' value='<?php p($l->t('Pair Account'));?>'>
    <?php } ?>
    
</form>