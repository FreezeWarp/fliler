<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

echo documentStart('Move a Directory');

/* Document Content */
if ($perm['MvD']) {
  switch($stage) {
    case null:
    case 1:
    $dirs = listDirs('oldDir');
    echo container('Move/Rename a Directory','<form action="move_dir.php?stage=2" method="post">
  <div class="left">
    <label for="oldDir">Start Directory</label><br />
    ' . $dirs . '<br /><br />
    <label for="newDir">End Directory</label><br />
    <input type="text" name="newDir" id="newDir" /><br /><br />
    <img src="images/info.png" onClick="help(\'If a directory has the same name as the directory you are trying to rename, it will be overwritten.\');" /><label for="ow">Overwrite an Existing Directory?:</label> <input name="ow" id="ow" type="checkbox" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can move and rename directories.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $oldDir = $uploadDirectory . $_POST['oldDir'];
    $newDir = $uploadDirectory . $_POST['newDir'];
    $dir2 = $accessDirectory . $_POST['newDir'];
    $ow = $_POST['ow'];
    if (($_POST['ow'] == 'on') && ($accessLevel >= 3)) { $ow = 1; } else { $ow = 0; }
    if (moveDir($oldDir,$newDir,$ow)) {
      echo container('The directory has been moved. What would you like to do now?','<ol>
  <li><a href="move_dir.php">Move Another Directory</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="viewdir.php?d=' . $dir2 . '">View the Created Directory</a></li>
  <li><a href="javascript:window.close()">Close this Window</a></li>
</ol>',0);
    }
    else {
      echo container('The directory movement failed. What would you like to do now?','<ol id="main">
  <li><a href="move_dir.php">Move a Directory Differently</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>