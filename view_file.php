<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');
echo documentStart('View a File');

/* Document Content */
if ($perm['View']) {
  $dirs = listDirs();
  echo container('View a File','<form action="viewfile.php" method="get">
  <div class="left">
    <label for="file">File (Include the Directory):</label><br />
    <span id="fileSelect"><input type="text" name="f" id="file" /></span><br /><br />
    <label for="dir">Directory:</label><br />
    ' . $dirs . '<br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can select a file that you will be able to view in an ideal cotnaienr.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
}

/* Document End */
echo documentEnd();
?>