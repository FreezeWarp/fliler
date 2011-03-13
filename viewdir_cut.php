<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

$login = 1;
require_once('uac.php');
require_once('lib.php');
$f = $_GET['f'];
if (preg_match('/^;/',$f)) {
  $f = preg_replace('/^;(.+)/','$1',$f);
}
$f = explode(";",$f);
$max = count($f);
$d = $_GET['d'];
if (($valid === 1) && (($accessLevel == 2) || ($accessLevel == 3))) {
  $maxm1 = $max - 1;
  for($max = 0; $max <= $maxm1; $max += 1) {
    $maxp1 = $max + 1;
    if ($f[$max] == '') {
    }
    elseif (file_exists($uploadDirectory . $d . '/' . $f[$max])) {
      $con = 0;
    }
    elseif (!is_writable($uploadDirectory . parentDirectory($d) . $f[$max])) {
      $con = 0;
    }
    elseif (!is_writable($uploadDirectory . $d)) {
      $con = 0;
    }
    else {
      if (!rename($uploadDirectory . parentDirectory($d) . $f[$max],$uploadDirectory . $d . '/' . $f[$max])) {
        $con = 0;
      }
    }
  }
  if ($con == 0) {
    header('Location: viewdir.php?d=' . $d . '&status=0_cut');
  }
  else {
    header('Location: viewdir.php?d=' . $d . '&status=1_cut');
  }
}
?>