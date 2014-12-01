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
 * DESCRIPTION: This is the HTML template that creates the Latch Configuration 
 * form. The information received is transferred to "latchAdmin.php".
 */

?>

<form id="latch_admin_form" class="section" method="POST" action="#latch_admin_form">
    <h2>Latch Configuration</h2>
    <em>This is the section to set the Latch plugin. In order to be registered  
        as a Latch application, please visit  
        <a target="blank" href="http://latch.elevenpaths.com">
            http://latch.elevenpaths.com
        </a>
    </em>
    <br>
        <?php if(is_array($_['msg'])){ ?>
        <br>
        <span class="<?php p($_['msg']['class']); ?>">
            <?php p($_['msg']['value']); ?>
        </span>
        <br>    
        <?php } ?>    
    <br>
    <strong>Application ID</strong>
    <br>
    <input type="text" name="appID" value="<?php p($_['appID']); ?>" autocomplete="off" style="width:300px">
    <br>
    <strong>Application Secret</strong>
    <br>
    <input type="text" name="appSecret" value="<?php p($_['appSecret']); ?>" autocomplete="off" style="width:300px">
    <br>
    <br>    
    <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" >
    <input type='submit' value='<?php p($l->t('Save'));?>'>
    <a id="show_popup" style="margin-left:50px;text-decoration: underline;">Uninstall Plugin</a>
</form>