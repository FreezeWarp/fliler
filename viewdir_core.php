<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */
die('Unsupported.');
require_once('uac.php');
function fixDirectory($dir) {
  while (preg_match('/\/\//',$dir)) {
    $dir = preg_replace('/\/\//','/',$dir);
  }
  while (preg_match('/\.\./',$dir)) {
    $dir = preg_replace('/\.\./','.',$dir);
  }
  if (preg_match ('/^([^\.][^\/])(.*)$/', $dir)) {
    $dir = './' . $dir;
  }
  if (preg_match ('/^(.*)[^\/]$/', $dir)) {
    $dir = $dir . '/';
  }
  return $dir;
}
$d = fixDirectory($_GET['d']);
?>
<html>
<?php
echo headercode("Viewing Directory $d");
?>
<body>
<?php
if ($valid == 1) {
  $prefix = './';
  if (isset ($d)) {
    $dir_path = str_replace ($prefix, '', urldecode ($d));
    $dir_path = preg_replace('/\/{2,}/', '/', $dir_path);
    $dir_path_2 = preg_replace('/\//', ' &#8594; ', $dir_path);
    $dir_path_2 = "You are currently in: <u>Home &#8594; $dir_path_2</u>";
    if ((substr ($dir_path, -1) != '/') && ($dir_path != '')) {
      $dir_path = '/';
    }
  }
  else {
    $dir_path = '';
    $dir_path_2 = "You are in the home directory.";
    $home = 1;
  }
  require_once ('dir_viewer.php');
  require_once ('hidden_files.php');
  $prefix_dir_path  = $prefix . $dir_path;
  $read_dir = new DirectoryViewer (&$prefix_dir_path);
  $read_dir->set_hidden_files ($hidden_files);
  $read_dir->hide_the_stuff ();
  $dirs = $read_dir->output ('dirs');
  $files = $read_dir->output ('files');
  echo "<h1>$dir_path_2</h1><table border=\"1\" cellpadding=\"3\" width=\"100%\"><tr><td width=\"50%\"><b>Filename:</b></td><td width=\"30%\"><b>Last modified:</b></td><td width=\"20%\"><b>File size:</b></td></tr>";
  foreach ($dirs as $dir) {
    $filesize = $dir->filesize ();
    echo "<tr><td><img src=\"folder.png\" />&nbsp; <a href=\"viewdir_core.php?d={$prefix}{$dir_path}{$dir}\">$dir</a></td><td>{$dir->lastmod ()}</td><td>{$filesize [0]} {$filesize [1]}</td></tr>";
  }
  foreach ($files as $file) {
    $filesize = $file->filesize ();
    echo "<tr><td><img src=\"file.png\" />&nbsp; <a href=\"viewfile.php?f={$prefix}{$dir_path}{$file}\">$file</a></td><td>{$file->lastmod ()}</td><td>{$filesize [0]} {$filesize [1]}</td></tr>";
  }
  echo "</table></div>";
}
if (($accessLevel == 0) || ($accessLevel == 1)) {
  echo '<fieldset><legend>Lack of Permissions</legend>Your account does not have sufficient permissions to view files or folders.</fieldset>';
}
?>
</body>
</html>