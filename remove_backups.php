<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');
echo documentStart('Remove File Backups');

/* Document Content */
if (($perm['RmF']) && ($perm['EdF'])) {
  switch($_GET['stage']) {
    case null:
    case 1:
    $dirs = listDirs();
    echo container('Remove File Backups','<form action="remove_backups.php?stage=2" method="post">
  <div class="left">
    <label for="file">File:</label><br />
    <span id="fileSelect">
    <input type="text" name="file" id="file" />
    </span><br /><br />
    <label for="dir">Please Select a Directory:</label><br />
    ' . $dirs . '<br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">This allows you to remove backups in bulk created by the file editor.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = $uploadDirectory . $_POST['dir'];
    $file = $_POST['file'];
    if(removeBackups($dir,$file)) {
      echo container('All file backups have been successfully deleted. What would you like to do now?','<ol>
  <li><a href="remove_backups.php">Remove Backups of Other Files</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
    }
    else {
      echo container('Some or all file backups could not be deleted. What would you like to do now?','<ol id="main">
  <li><a onclick="document.getElementById(\'main\').innerHTML=\'' . $dieErrors . '\'">View the Errors</a></li>
  <li><a href="remove_backups.php">Remove File Backups Differently</a></li>
  <li><a href="index.php">Go to the Index</a></li><li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>