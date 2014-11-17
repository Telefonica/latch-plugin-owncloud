<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: This is the HTML template that creates the Latch Pairing 
 * form. The information received is transferred to "latchPairing.php".
 */

?>

<form id="latch_pairing_form" class="section" method="POST" action="#">
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