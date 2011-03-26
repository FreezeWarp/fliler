<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

echo documentStart('Change File Permissions');

/* Document Content */
if ($perm['ChmdF']) {
  switch($stage) {
    case null:
    case 1:
    $dirs = listDirs('dir');
    echo container('Change Permissions of a File','<form action="chmod_file.php?stage=2" method="post">
  <div class="left">
    <label for="file">File:</label><br />
    <span id="fileSelect">
    <input type="text" name="file" id="file" />
    </span><br /><br />
    <label for="dir">Directory:</label><br />
    ' . $dirs . '<br /><br />
    <label for="perm">Permissions:</label><br />
    <select name="perm" id="perm">
      <option value="777" title="Owner, Group, Global - Read, Write, Execute">777</option>
      <option value="755" title="Owner - Read, Write, Execute; Group, Global - Read, Execute">755</option>
      <option value="744" title="Owner - Read, Write, Execute; Group, Global - Read">744</option>
      <option value="700" title="Owner - Read, Write, Execute; Group, Global - Nothing">700</option>
      <option value="555" title="Owner, Group, Global - Read, Execute">555</option>
      <option value="544" title="Owner - Read, Execute; Group, Global - Read">544</option>
      <option value="500" title="Owner - Read, Execute; Group, Global - Nothing">500</option>
      <option value="400" title="Owner - Read; Group, Global - Nothing">400</option>
    </select><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can change a file\'s permissions.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = $uploadDirectory . $_POST['dir'];
    $file = $_POST['file'];
    $file2 = $accessDirectory . $_POST['dir'] . '/' . $_POST['file'];
    switch($_POST['perm']) {
      case '777': $perm = 0777; break;
      case '755': $perm = 0755; break;
      case '744': $perm = 0744; break;
      case '700': $perm = 0700; break;
      case '555': $perm = 0555; break;
      case '544': $perm = 0544; break;
      case '500': $perm = 0500; break;
      case '400': $perm = 0400; break;
      default: $perm = 0777; break;
    }
    if (chmodFile($dir,$file,$perm)) {
      echo container('The file permissions have been changed. What would you like to do now?','<ol>
  <li><a href="chmod_file.php">Change Another File\'s Permissions</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="viewfile.php?f=' . $file2 . '">View the Directory</a></li>
  <li><a href="javascript:window.close()">Close this Window</a></li>
</ol>',0);
    }
    else {
      echo container('The file permission change failed. What would you like to do now?','<ol id="main">
  <li><a onclick="document.getElementById(\'main\').innerHTML=\'' . dieErrors($dieErrors) . '\'">View the Errors</a></li>
  <li><a href="chmod_file.php">Change a File\'s Permissions Differently</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    break;
  }
}

/* Document End */
documentEnd();
?>