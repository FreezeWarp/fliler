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
  echo documentStart('Download a File');
}

/* Document Content */
if ($perm['View']) {
  switch($stage) {
    case false:
    case 1:
    $dirs = listDirs();
    echo container('Download File','<form action="download_file.php?stage=2" method="post">
  <div class="left">
    <label for="file">File: </label><br />
    <span id="fileSelect"><input type="text" name="file" id="file" /></span><br /><br />
    <label for="dir">Directory</label>:<br />' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'By checking this, you will be taken to a screen where you can specify a specific format for download. Otherwise, you will be taken directly to the download.\');" /><label for="convert">Convert the File?: </label>
    <input type="checkbox" name="convert" id="convert" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can download files for offline viewing.</div>') . '
  </div>
  <div class="full">
    <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>');
    break;

    case 2:
    $dir = (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    $file = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    if ($_POST['convert'] == 'on' || $_GET['convert']) {
      $fileData = fileData(null,$uploadDirectory . $dir . $file);
      echo '<form action="download_file.php?stage=3" method="post"><input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="dir" value="' . $dir . '" /><input type="hidden" name="convert" value="1" /><label for="format">Format:</label> <select name="format">';
      switch ($fileData['ext']) {
        case 'doc': case 'docx': case 'odt': case 'swx': case 'htm': case 'pdf': case 'rtf':
        echo '<option value="doc">Microsoft Word Document</option><option value="docx">OfficeOpen XML Document</option><option value="odt">OpenDocument Text</option><option value="sxw">StarWriter Document</option><option value="pdf">Adobe Acrobat PDF</option><option value="rtf">Microsoft Rich Text Format</option><option value="htm">HTML 4.01 Text</option>';
        break;
        case 'png': case 'gif': case 'jpg': case 'jpeg': case 'jpe':
        echo '<option value="png">Portable Network Graphics (PNG)</option><option value="gif">Graphics Interchange Format (GIF)</option><option value="jpeg">JPEG Format</option>';
        break;
        default:
        case 'svg':
        echo '<option value="png">Portable Network Graphics (PNG)</option><option value="jpg">JPEG Format</option><option value="tiff">Tagged Image File Format (TIFF)</option><option value="pdf">Adobe Acrobat PDF</option>';
        break;
      }
      echo '</select><input type="submit" value="Download" /></form>';
    }
    else {
      echo '<a href="download_file.php?stage=3&file=' . $file . '&dir=' . $dir . '">Proceed to the download.</a>';
    }
    break;

    case 3:
    $dir = (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    $file = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    if ($_POST['convert'] || $_GET['convert']) {
      $fileData = fileData(null,$uploadDirectory . $dir . $file);
      $format = (($_GET['format']) ? $_GET['format'] : $_POST['format']);
      $tempFile = $uploadDirectory . $tmpPathLocal . '.fliler-' . time() . '-' . $fileData['name'] . '.' . $format;
      switch ($fileData['ext']) {
        case 'doc': case 'docx': case 'odt': case 'swx': case 'htm': case 'pdf': case 'rtf':
        exec($binaryPath . 'abiword "' . escapeshellcmd($fileData['full']) . '" -t ' . $format . ' -o "' . escapeshellcmd($tempFile) . '" --exp-props="embed-css: yes; embed-images: yes;"');
        downloadFile(null,$tempFile,$fileData['name'] . '.' . $format);
        break;
        case 'png': case 'gif': case 'jpeg': case 'jpe': case 'jpg':
        if ($fileData['ext'] == 'jpe' || $fileData['ext'] == 'jpg') { $fileData['ext'] = 'jpeg'; }
        $inputFunction = '$image = imagecreatefrom' . $fileData['ext'] . '(\'' . $fileData['full'] . '\');';
        $outputFunction = 'image' . $format . '($image,$tempFile);';
        eval($inputFunction);
        eval($outputFunction);
        downloadFile(null,$tempFile,$fileData['name'] . '.' . $format);
        break;
        case 'svg':
        switch ($format) {
          case 'pdf': $mime = 'application/pdf'; break;
          case 'png': $mime = 'image/png'; break;
          case 'jpg': $mime = 'image/jpg'; break;
          case 'tiff': $mime = 'image/tiff'; break;
        }
        exec('java -jar ' . $installLocation . '.batik/batik-rasterizer.jar ' . $fileData['full'] . ' -d ' . escapeshellcmd($tempFile) . ' -m ' . $mime);
        downloadFile(null,$tempFile,$fileData['name'] . '.' . $format,$mime);
        break;
      }
    }
    else {
      downloadFile($uploadDirectory . $dir,$file);
    }
    break;
  }
}

/* Document End */
if ($stage <= 2) {
  echo documentEnd();
}
?>