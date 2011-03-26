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

echo documentStart('Rename a File');

/* Document Content */
if ($perm['MvF']) {
  switch($stage) {
    case null:
    case 1:
    $oldDirs = listDirs();
    echo container('Rename a File','<form action="rename_file.php?stage=2" method="post">
  <div class="left">
    <label for="oldFile">File to be Renamed:</label><br />
    <span id="fileSelect">
    <input type="text" name="file" id="file" />
    </span><br /><br />
    <label for="dir" />Directory of the File:</label><br />
    ' . $oldDirs . '<br /><br />
    <label for="newFile">New Name of the File:</label><br />
    <input type="text" name="newFile" id="newFile" /><br /><br />
    <img src="images/info.png" onClick="help(\'If a file has the same name as the file you are trying to create, it will be overwritten.\');" /><label for="ow">Overwrite an Existing File?:</label> <input name="ow" id="ow" type="checkbox" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help"></div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    $oldFile = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    $newFile = (($_GET['newFile']) ? $_GET['newFile'] : $_POST['newFile']);
    $ow = ((($_POST['ow'] == 'on') && ($perm['RmF'])) ? true : false);

    $renameFile = new fileManager;
    $renameFile->setFile($dir,$oldFile,true);
    $renameFile->setGoal($dir,$newFile,true);

    if ($renameFile->moveFile($ow)) {
      echo container('The file has been successfully renamed. What would you like to do now?','<ol>
  <li><a href="rename_file.php">Rename Another File</a></li>
  <li><a href="viewfile.php?f=' . urlencode($accessDirectory . $_POST['dir'] . '/' . $_POST['newFile']) . '">View the File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
    }
    else {
      echo container('The file could not be renamed. What would you like to do now?','<ol id="main">  
  <li><a href="rename_file.php">Rename a File Differently</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li></ol>',1);
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>