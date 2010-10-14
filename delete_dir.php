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

echo documentStart('Delete a Directory');

/* Document Content */
if ($perm['RmD']) {
  switch($stage) {
    case null:
    case 1:
    $dirs = listDirs();
    echo container('Delete a Directory','<form action="delete_dir.php?stage=2" method="post">
  <div class="left">
    <label for="dir">Directory to be Deleted</label><br />
    ' . $dirs . '<br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can delete directories.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = $uploadDirectory . (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    if (deleteDir($dir)) {
      echo container('The directory has been successfully deleted. What would you like to do now?','<ol>
  <li><a href="delete_dir.php">Delete Another Directory</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close this window</a></li>
</ol>',0);
    }
    else {
      echo container('The directory could not be deleted. What would you like to do now?','<ol id="main">
  <li><a href="delete_dir.php">Delete a Different Directory</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close this Window</a></li>
</ol>',1);
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>