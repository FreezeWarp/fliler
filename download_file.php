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
    $dir = formatDir(($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    $file = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    if ($_POST['convert'] == 'on' || $_GET['convert']) {
      $fileData = fileData(null,$uploadDirectory . $dir . $file);
      switch ($fileData['ext']) {
        case 'doc': case 'docx': case 'odt': case 'swx': case 'htm': case 'pdf': case 'rtf':
        if (is_executable($binaryPath . 'abiword')) {
          $options .= '<option value="doc">Microsoft Word Document</option><option value="docx">OfficeOpen XML Document</option><option value="odt">OpenDocument Text</option><option value="sxw">StarWriter Document</option><option value="pdf">Adobe Acrobat PDF</option><option value="rtf">Microsoft Rich Text Format</option><option value="htm">HTML 4.01 Text</option><option value="txt">Plain Text</option>';
        }
        if ($fileData['ext'] == 'pdf' && class_exists('imagick')) {
          $options .= '<option value="jpg">JPEG Image</option>';
        }
        break;

        case 'png': case 'gif': case 'jpg': case 'jpeg': case 'jpe':
        if (function_exists('imagecreatefrompng') || class_exists('imagick')) {
          $options .= '<option value="png">Portable Network Graphics (PNG)</option><option value="gif">Graphics Interchange Format (GIF)</option><option value="jpeg">JPEG Format</option>';
        }
        if (class_exists('imagick')) {
          $options .= '<option value="pdf">Portable Document Format (PDF)</option><option value="tiff">Tagged Image File Format (TIFF)</option>';
        }
        break;

        default:
        case 'svg':
        $options .= '<option value="png">Portable Network Graphics (PNG)</option><option value="jpg">JPEG Format</option><option value="tiff">Tagged Image File Format (TIFF)</option><option value="pdf">Adobe Acrobat PDF</option>';
        break;
      }

      if (!$options) {
        trigger_error('The neccessary dependencies are not installed for conversion.');
        $convert = false;
      }
      else $convert = true;
    }
    else $convert = true;

    if ($convert) echo '<form action="download_file.php?stage=3" method="post"><input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="dir" value="' . $dir . '" /><input type="hidden" name="convert" value="1" /><label for="format">Format:</label> <select name="format">' . $options . '</select><input type="submit" value="Download" /></form>';
    else echo '<a href="download_file.php?stage=3&file=' . $file . '&dir=' . $dir . '">Proceed to the download.</a>';
    break;

    case 3:
    $dir = formatDir((($_GET['dir']) ? $_GET['dir'] : $_POST['dir']));
    $file = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    if ($_POST['convert'] || $_GET['convert']) {
      $fileData = fileData(null,$uploadDirectory . $dir . $file);
      $format = (($_GET['format']) ? $_GET['format'] : $_POST['format']);
      $ut = time();
      $tempFile = $uploadDirectory . $tmpPathLocal . '.fliler-' . $ut . '-' . safeFile($fileData['name']) . '.' . $format;
      $tempFileOriginal = $uploadDirectory . $tmpPathLocal . '.flilersource-' . $ut . '-' . safeFile($fileData['name']) . '.' . $fileData['ext'];
      copyFile($fileData['full'],$tempFileOriginal);

      switch ($fileData['ext']) {
        case 'doc': case 'docx': case 'odt': case 'swx': case 'htm': case 'pdf': case 'rtf':
        if ($fileData['ext'] == 'pdf' && $format == 'jpg') {
          if (class_exists('imagick')) {
            $image = new Imagick();
            $image->readImage($tempFileOriginal);
            $image->writeImages($tempFile,false);
            $image->clear();
            $image->destroy();
          }
          else {
            trigger_error('Can not convert PDF to JPEG; Imagick not installed.',E_USER_ERROR);
          }
        }
        else {
          if (is_executable($binaryPath . 'abiword')) {
            exec($binaryPath . 'abiword "' . $tempFileOriginal . '" -t ' . $format . ' -o "' . $tempFile . '"');
            downloadFile(null,$tempFile,$fileData['name'] . '.' . $format);
          }
          else {
            trigger_error('Abiword not installed.',E_USER_ERROR);
          }
        }
        break;

        case 'png': case 'gif': case 'jpeg': case 'jpe': case 'jpg':
        if ($fileData['ext'] == 'jpe' || $fileData['ext'] == 'jpg') { $fileData['ext'] = 'jpeg'; }

        if (class_exists('imagick')) {
          $image = new Imagick();
          $image->readImage($tempFileOriginal);
          $image->writeImages($tempFile,false);
          $image->clear();
          $image->destroy();
          downloadFile(null,$tempFile,$fileData['name'] . '.' . $format);
        }
        elseif (function_exists('imagecreatefrompng') && in_array($format,array('jpg','png','gif'))) {
          $inputFunction = '$image = imagecreatefrom' . $fileData['ext'] . '(\'' . $fileData['full'] . '\');';
          $outputFunction = 'image' . $format . '($image,$tempFile);';
          eval($inputFunction);
          eval($outputFunction);
          downloadFile(null,$tempFile,$fileData['name'] . '.' . $format);
        }
        else {
          trigger_error('Imagick or GD not installed.',E_USER_ERROR);
        }
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