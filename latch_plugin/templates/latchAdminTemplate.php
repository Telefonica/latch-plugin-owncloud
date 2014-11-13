<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * DESCRIPTION: This is the HTML template that creates the Latch Configuration 
 * form. The information received is transferred to "latchAdmin.php".
 */

?>

<form id="latch_admin_form" class="section" method="POST" action="#">
    <h2>Latch Configuration</h2>
    <em>This is the section to set the Latch plugin. In order to be registered  
        as a Latch application, please visit  
        <a target="blank" href="http://latch.elevenpaths.com">
            http://latch.elevenpaths.com
        </a>
    </em>
    <br>
    <br>
    <strong>Application ID</strong>
    <br>
    <input type="text" name="appID" value="<?php p($_['appID']); ?>" style="width:300px">
    <br>
    <strong>Application Secret</strong>
    <br>
    <input type="text" name="appSecret" value="<?php p($_['appSecret']); ?>" style="width:300px">
    <br>
    <br>    
    <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" >
    <input type='submit' value='<?php p($l->t('Save'));?>'>
</form>