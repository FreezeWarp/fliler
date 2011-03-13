<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

if ($stage <= 2) {
  echo documentStart('Download a Directory');
}

function zipCreateString($files,$dir = '') {
  foreach ($files as $file) {
    if ($file['type'] == 'dir') {
      $string .= '$zip->addEmptyDir(\'' . addslashes($dir . $file['file']) . '\');';
      $string .= zipCreateString($file['contents'],$dir . $file['file'] . '/');
    }
    else {
      $string .= '$zip->addFile(\'' . addslashes($file['full']) . '\',\'' . addslashes($dir . $file['file']) . '\');';
    }
  }
  return $string;
}

/* Document Content */
if ($perm['View']) {
  switch($stage) {
    case false:
    case 1:
    $dirs = listDirs();
    echo container('Download File','<form action="download_dir.php?stage=2" method="post">
  <div class="left">
    <label for="dir">Directory</label>:<br />' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'By default, dot files are not included since they are used consistently by Fliler to make throw-away files. These are not wanted in the download, and can easily cause timeouts.\');" /><label for="dotfiles">Force Dot Files:</label> <input type="checkbox" name="dotfiles" id="dotfiles" />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can download entire directories as Zipped archives. Note that this is an incredibly server-intesive process, and may time out or encounter memory errors for larger directories.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>');
    break;

    case 2:
    $dir = $uploadDirectory . (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']) . '/';

    $tempFileBase = time() . '.zip';
    $tempFile = $uploadDirectory . $tmpPathLocal . $tempFileBase;

    // function listFiles($dir,$nameFilter = null,$extFilter = null,$hiddenFiles = null,$type = false,$recursive = false,$mode = false,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false))
    if ($_POST['dotfiles']) {
      $hideDotFiles = false;
    }

    $files = listFiles($dir,null,null,null,false,true,'structured');

    $zip = new ZipArchive();
    $zip->open($tempFile,ZIPARCHIVE::CREATE);

    $string = zipCreateString($files);

    unset($files);

    eval($string);
    unset($string);

    $zip->close();

    if ($uploadUrl) {
      $downloadLocation = $uploadUrl . $tmpPathLocal . $tempFileBase;
    }
    else {
      $downloadLocation = 'download_dir.php?stage=3&tempFile=' . $tempFileBase;
    }

    echo 'The directory download is now ready.<br /><br /><a href="' . $downloadLocation . '">Download</a>';
    break;

    case 3:
    $tempFile = $uploadDirectory . $tmpPathLocal . $_GET['tempFile'];
    downloadFile(null,$tempFile);
    break;
  }
}

/* Document End */
if ($stage <= 2) {
  echo documentEnd();
}
?>