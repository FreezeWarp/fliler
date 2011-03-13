<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

$stopLogin = true;
require_once('uac.php');

if (($valid == 1)  && ($perm['RmF'])) {
  if (isset($_GET['f'])) {
    $file = $_GET['f'];
    if (!@deleteFile(null,$uploadDirectory . $file)) {
      header('Message: The file could not be deleted.');
      header('Status: 0');
    }
    else {
      header('Message: The file was deleted successfully.');
      header('Status: 1');
    }
  }
  elseif (isset($_GET['d'])) {
    $dir = $_GET['d'];
    if (!@deleteDir($uploadDirectory . $dir . '/')) {
      header('Message: The directory could not be deleted.');
      header('Status: 0');
    }
    else {
      header('Message: The directory was deleted successfully.');
      header('Status: 1');
    }
  }
  else {
    header('Message: No file or directory specified.');
    header('Status: 0');
  }
}
else {
  header('Message: You are not authenticated.');
  header('Status: 0');
}
?>
