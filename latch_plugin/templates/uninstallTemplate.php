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
<div id="uninstall_warning">

    <a id="closeWarning" class="close">
            <img class="svg" src="<?php print_unescaped(OCP\Util::imagePath('core', 'actions/close.svg')); ?>">
    </a>
    <div class="warningContainer" style="background-color: white;">
        <div class="warningHeader">
            <img src="<?php print_unescaped(OCP\Util::imagePath('latch_plugin', 'symbol.png')); ?>">
            <h1 style="display:inline;margin-left: -65px;"><?php p($l->t('Disable Plugin')); ?></h1>
        </div>
        <div class="warningContent">
            <p><?php p($l->t('If the process continues, all the data managed by this plugin
            will be removed from database.')); ?></p>
            <br>
            <p><strong><?php p($l->t('Warning: This action cannot be undone.')); ?></strong></p>
            <br> <br> <br>
            <form method="POST" action="<?php print_unescaped(\OCP\Util::linkToRoute('latch_uninstall')); ?>">
                <input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']);?>" />
                <input type="submit" value="<?php p($l->t('Accept')); ?>" class="button" style="background: #00b9be;color: #FFF;
                       font-family: inherit;vertical-align: baseline;width: 77px;height: 32px;padding-top: 3px">
            </form>
            <a id="cancel_uninstall" class="button" style="background: #00b9be;color: #FFF;"><?php p($l->t('Cancel')); ?></a>
        </div>
    </div>
    
</div>