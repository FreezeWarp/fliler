<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');
echo documentStart('Restore a File Backup');

/* Document Content */
if ($perm['EdF']) {
  switch($_GET['stage']) {
    case null:
    case 1:
    $dirs = listDirs();
    echo container('Choose a File:','<form action="restore_backup.php?stage=2" method="post">
  <div class="left">
    <label for="file">File:</label><br />
    <span id="fileSelect">
    <input type="text" name="file" id="file" />
    </span><br /><br />
    <label for="dir">Directory:</label><br />
    ' . $dirs . '</label><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can restore backups that were created by file edits in case something went wrong. Note that the previous file will be deleted.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = $uploadDirectory . $_POST['dir'];
    $file = $_POST['file'];
    $file2 = './' . $accessDirectory . $_POST['dir'] . '/' . $_POST['file'];
    if ($string = listBackups($dir,$file)) {
      echo container('Choose a Time Stamp','<form method="post" action="restore_backup.php?stage=3">
  <label for="ut">Which change of the file would you like to recover?:</label><br />
  <select name="ut">' . $string . '</select><br /><br />
  <label for="preview">Compare Files (Uncheck to Overwrite)?:</label> <input type="checkbox" name="preview" /><br /><br />
  <input type="hidden" name="dirFile" value="' . $dir . '/' . $file . '" /><input type="hidden" name="file2" value="' . $file2 . '" />
  <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
</form>',0);
    }
    else {
      echo container('It will not be possible to revert a backup of this file. What would you like to do now?','<ol id="main">
  <li><a href="restore_backup.php">Revert Backups of a Different File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    break;
    case 3:
    $ut = $_POST['ut'];
    $file2 = $_POST['file2'];
    $dirFile = $_POST['dirFile'];
    $preview = (($_POST['preview'] == 'on') ? 1 : 0);
    $oldFile = file_get_contents($dirFile);
    $newFile = file_get_contents($dirFile . '~' . $ut);
    echo '<div style="width: 100%;"><div style="float: left; width: 49%;"><b>Old File:</b><br /><textarea style="width: 100%; height: 400px;">' . $oldFile . '</textarea></div><div style="float: right; width: 49%;"><b>New File:</b><br /><textarea style="width: 100%; height: 400px;">' . $newFile . '</textarea></div></div><br /><form method="post" action="restore_backup.php?stage=3"><input type="hidden" name="dirFile" value="' . $dirFile . '" /><input type="hidden" name="ut" value="' . $ut . '" /><input type="hidden" name="preview" value="1" /><input type="hidden" name="file2" value="' . $file2 . '" /><input type="submit" value="Restore" /><input type="button" value="Go Back" onclick="history.go(-1)" /></form>';
    if (!$preview) {
      if (revertBackup($dirFile,$ut)) {
        echo container('The file backup was succesfully reverted. What would you like to do now?','<ol>
  <li><a href="restore_backup.php">Revert Another Backup</a></li>
  <li><a href="viewfile.php?f=' . $file2 . '">View the File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
      }
      else {
        echo container('The file was unable to be backed up. What would you like to do now?','<ol id="main">
  <li><a href="restore_backup.php">Revert a Backup Differrently</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
      }
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>