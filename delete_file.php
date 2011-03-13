<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

echo documentStart('Delete a File');

/* Document Content */
if ($perm['RmF']) {
  switch($stage) {
    case null:
    case 1:
    $dirs = listDirs();
    echo container('Delete a File','<form action="delete_file.php?stage=2" method="post">
  <div class="left">
    <label for="file">File to be Deleted:</label><br />
    <span id="fileSelect">
    <input type="text" name="file" id="file" />
    </span><br /><br />
    <label for="dir">Directory of the File:</label><br />
    ' . $dirs . '<br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can delete files.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = (($_GET['file']) ? '' : $uploadDirectory . $_POST['dir']);
    $file = (($_GET['file']) ? $uploadDirectory . $_GET['file'] : $_POST['file']);
    if(deleteFile($dir,$file)) {
      echo container('The file has been deleted. What would you like to do now?','<ol>
  <li><a href="delete_file.php">Delete Another File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close()">Close this Window</a></li>
</ol>',0);
    }
    else {
      echo container('The file could not be deleted. What would you like to do now?','<ol id="main">
  <li><a href="delete_file.php">Delete a File Differently</a></li>
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