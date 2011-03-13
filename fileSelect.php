<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* listFiles
 * Overall, not a perfect script, but it works. Should be improved later. */
$stopLogin = true;
require_once('uac.php');
$dir = formatDir($_GET['d']);
if ($perm['View']) {
  header('Content-type: text/plain');
  echo fileSelect($uploadDirectory . $dir);
}
?>