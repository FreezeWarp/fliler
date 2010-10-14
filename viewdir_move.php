<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */
echo 2;
$stopLogin = true;
require_once('uac.php');
$f = $_GET['f'];
$files = explode(',',$f);
$d = formatDir($_GET['d']);
if (($valid) && ($perm['MkD']) && ($perm['MvD'])) {
  for ($i = 0; $i < count($files); $i++) { echo 1;
    if (!$files[$i]) {
      continue;
    }
    else {
      $filePart = filePart($files[$i]);
      $newFile = $uploadDirectory . $d . $filePart;
      $oldFile = $uploadDirectory . $files[$i];
      if (moveFile(null,null,$oldFile,$newFile)) {
        $success += 1;
      }
      else {
        $fail += 1;
      }
    }
  }
  if (($success > 0) && ($fail == 0)) { $message = 'All files were copied successfully.'; $status = '1'; }
  elseif (($success == 0) && ($fail > 0)) { $message = 'No files were copied successfully.'; $status = '0'; }
  else { $message = 'Some files were copied successfully.'; $status = '.5'; }
  header('Message: ' . $message);
  header('Status: ' . $status);
}
else {
  header('Message: You are not authenticated.');
  header('Status: 0');
}
?>