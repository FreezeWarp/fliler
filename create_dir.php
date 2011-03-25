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

echo documentStart('Create a Directory');

/* Document Content */
if ($perm['MkD']) {
  switch($stage) {
    case null:
    case 1:
    echo container('Create a Directory','<form action="create_dir.php?stage=2" method="post">
  <div class="left">
    <label for="dir">Directory Name:</label><br />
    <input type="text" name="dir" id="dir" /><br /><br />
    <label for="perm">What Should the Directory Permissions be?:</label><br />
    <select name="perm" id="perm">
      <option value="0777" title="Owner, Group, Global - Read, Write, Execute">777</option>
      <option value="0755" title="Owner - Read, Write, Execute; Group, Global - Read, Execute">755</option>
      <option value="0744" title="Owner - Read, Write, Execute; Group, Global - Read">744</option>
      <option value="0700" title="Owner - Read, Write, Execute; Group, Global - Nothing">700</option>
      <option value="0555" title="Owner, Group, Global - Read, Execute">555</option>
      <option value="0544" title="Owner - Read, Execute; Group, Global - Read">544</option>
      <option value="0700" title="Owner - Read, Execute; Group, Global - Nothing">500</option>
      <option value="0700" title="Owner - Read; Group, Global - Nothing">400</option>
    </select><br /><br />
    <img src="images/info.png" onClick="help(\'If a directory has the same name as the directory you are trying to create, it will be overwritten. This is not recommended because all files in the directory will also be deleted.\');" /><label for="ow">Overwrite an Existing Directory?:</label> <input name="ow" id="ow" type="checkbox" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can create a new directory.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    case 2:
    $dir = $uploadDirectory . $_POST['dir'];
    $dir2 = $accessDirectory . $_POST['dir'];
    $ow = ((($_POST['ow'] == 'on') && ($perm['RmD'])) ? true : false);

    $perm = intval(octdec($_POST['perm']));
    if (createDir($dir,$ow,$perm)) {
      echo container('The directory was successfully created. What would you like to do now?','<ol>
  <li><a href="create_dir.php">Create Another Directory</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="viewdir.php?d=' . $dir2 . '">View the Created Directory</a></li>
  <li><a href="javascript:window.close()">Close this Window</a></li>
</ol>',0);
    }
    else {
      echo container('The directory could not be created. What would you like to do now?','<ol id="main">
  <li><a href="create_dir.php">Create a Directory Elsewhere</a></li>
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