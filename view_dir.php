<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');
echo documentStart('View a Directory');

/* Document Content */
if ($perm['View']) {
  $dirs = listDirs('d');
  echo container('View a Directory','<form name="filter1" method="get" action="viewdir.php">
  <div class="left" id="filter1">
    <label for="dir">Directory:</label><br />
    ' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'Using this search, the filename will be block searched, where the entire phrase must match anywhere in the filename for the file to be shown. When a file extension is entered, it must be an exact match for the file to be displayed.\');" /><label for="filename">File Name Filter (Block Search):</label><br />
    <input type="text" name="n" /> . <input type="text" name="e" style="width: 40px;" />
    <input type="hidden" name="f" value="1" />
    <button type="button" onclick="getElementById(\'filter2\').style.display=\'block\'; getElementById(\'filter1\').style.display=\'none\';" title="Switch Filter Method">&#x21ba;</button><br /><br />
  </div>
  <div class="left" style="display: none;" id="filter2">
    <label for="d">Directory:</label><br />
    ' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'Here only files of the specified type will be shown.\');" /><label for="type">File Type Filter:</label><br />
    <select name="t">
      <option selected="selected" value="">All File Types</option>
      <option value="audio">Music and Audio</option>
      <option value="compressed">Compressed Files</option>
      <option value="database">Databases</option>
      <option value="dev">Development Files</option>
      <option value="disk">Disk Images</option>
      <option value="document">Documents and Text Files</option>
      <option value="exec">Executable Files</option>
      <option value="font">Fonts</option>
      <option value="image">Pictures and Images</option>
      <option value="presentation">Presenations</option>
      <option value="settings">Setting Files</option>
      <option value="spreadsheet">Spreadsheets</option>
      <option value="sys">System Files</option>
      <option value="video">Movies and Video</option>
      <option value="web">Web Documents</option>
    </select>
    <input type="hidden" name="f" value="2" />
    <button type="button" onclick="getElementById(\'filter1\').style.display=\'block\'; getElementById(\'filter2\').style.display=\'none\';" title="Switch Filter Method">&#x21ba;</button><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can choose directories to view in the directory viewer. Note that the directory viewer is more intensive than other scripts.</div>') . '
  </div>
  <div class="full">
    <input type="submit" value="Submit" /><input type="reset" value="Reset" />
  </div>
</form>',0);
}

/* Document End */
echo documentEnd();
?>