<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Notice: This code is more than a little messy (and really buggy). Try to improve consistency, and do it soon. */

/* Document Start */
require_once('uac.php');
echo documentStart('Viewing a File');

/* Document Content */
/* File Extension Lookup Prepartion */
require('filter_type.php');
if ($perm['View']) {
  $fileTemp = ((($_GET['f']) ? $_GET['f'] : $_GET['file']));
  $dir = (($_GET['dir']) ? '/' . $_GET['dir'] . '/' : '');
  $file = str_replace('//','/',$dir . $fileTemp);
  $file2 = formatDir($dir,true) . $file;

  //if (substr($file,0,1) != '/') { preg_replace('/^\/(.*)/','$1',$file); }
  if (!$fileTemp) {
    trigger_error('No file was specified.',E_USER_ERROR);
  }
  elseif (!$fileData = fileData(null,$file2,array('content' => true,'ext' => true,'mime' => true,'name' => true,'size' => true,'lastMod' => true,'owner' => true))) {
    trigger_error('The file information could not be read.',E_USER_ERROR);
  }
  else {
    $base64 = base64_encode($fileData['content']);
    @mysql_connect($mysqlHost,$mysqlUser,$mysqlPassword);
    @mysql_select_db($mysqlDatabase);
    $extQuery = @mysql_fetch_assoc(@mysql_query('SELECT * FROM ' . $mysqlPrefix . 'filetypes WHERE ext = "' . $fileData['ext'] . '"'));

    $relativePath = $uploadUrl . str_replace($uploadDirectory,'',$fileData['dir']) . urlencode($fileData['file']);
  
    // The full path to the existing file.
    $permFile = $file2;
    // Where a temporary file will be stored if it needs to be created.
    $ut = time();
    $tempFile = $uploadDirectory . $tmpPathLocal . '.fliler-' . $ut . '-' . $fileData['name'] . '.htm';
    $tempFileUrl = $uploadUrl . $tmpPathLocal . '.fliler-' . $ut . '-' . urlencode($fileData['name']) . '.htm';
    createFile(null,$tempFile,null,1);

    // Override values.
    $modeIndex = array(
      'die' => 0,
      'text' => 1,
      'frame' => 2,
      'object' => 3,
      'embed' => 4,
      'code' => 5,
      'image' => 6,
      'abiword' => 7,
      'gnumeric' => 9,
      'csv' => 10,
      'video' => 11,
      'audio' => 12,
    );

    // Determine default value.
    if (!$modeIndex[$_GET['mode']]) {
      if ((in_array($fileData['ext'],$exts['dev'])) || (in_array($fileData['ext'],$exts['web'])) || (in_array($fileData['ext'],$exts['text']))) {
        $mode = 5;
      }
      elseif (in_array($fileData['ext'],$exts['image'])) {
        $mode = 6;
      }
      elseif (in_array($fileData['ext'],$exts['document'])) {
        if (is_executable('/usr/bin/abiword')) {
          $mode = 7;
        }
        else {
          $mode = 0;
        }
      }
      elseif (in_array($fileData['ext'],$exts['spreadsheet'])) {
        if (is_executable('/usr/bin/gnumeric')) {
          $mode = 9;
        }
        elseif ($fileData['ext'] == 'csv') {
          $mode = 10;
        }
        else {
          $mode = 0;
        }
      }
      elseif (in_array($fileData['ext'],$exts['presentation'])) {
        $mode = 0;
      }
      elseif (in_array($fileData['ext'],$exts['video'])) {
        $mode = 11;
      }
      elseif  (in_array($fileData['ext'],$exts['audio'])) {
        $mode = 12;
      }
      else {
        $mode = 0;
      }
    }
    // Use the override value if it is specified.
    else {
      $mode = $modeIndex[$_GET['mode']];
    }

    $parentdirectory = 'viewdir.php?d=' . parentDirectory($file);
    echo '<div class="toolbar" style="float: right; width: 75px;"><a href="download_file.php?stage=2&file=' . $file . '&convert=true"><img src="images/save.png" /></a><a href="edit_file.php?stage=2&file=' . urlencode($file) . '"><img src="images/edit.png" /></a></div><h3>File: ' . $file . '</h3><a href="' . $relativePath . '">View this directly in your browser</a> | <a href="' . $parentdirectory . '">See more files in this directory</a><hr />';

    // Generate the container based on the above.
    switch($mode) {
      case 0:
      echo 'The file type is unrecognized. Try viewing it directly in the browser or downloading it.';
      break;

      case 1:
      $content = $fileData['content'];
      $content = str_replace('<','&lt;',$content);
      $content = str_replace('>','&gt;',$content);
      $content = nl2br($content);
      echo '<div class="code_shell">' . $content . '</div><hr />Generated with generic PHP functions.';
      break;

      // Will be used if the overrride "frame" is used.
      case 2:
      echo '<iframe src="' . $uploadUrl . $file . '" style="width: 100%; height: 400px;"></iframe><hr />Displayed with native browser plugins in a seperate frame.';
      break;

      // Will be used if the overrided "object" is used.
      case 3:
      echo '<object data="' . $uploadUrl . $file . '" style="width: 100%; height: 400px;" type="' . $fileData['mime'] . '"></object><hr />Displayed with native browser plugins.';
      break;

      case 4:

      break;

      // Will be used if the file extension represents web, development, or text data, or if the override "code" is used.
      case 5:
      $code = highlightCode($uploadDirectory,$file,'.geshi/geshi.php');
      echo '<div class="code_shell">' . $code . '</div><hr />Generated with geSHi.';
      break;

      // Will be used if the file extension represents image data, or if the override "image" is used.
      case 6:
      echo '<script src="viewfile.js"></script><img src="' . $uploadUrl . $file . '" id="image" /><br />Zoom: <input onkeyup="resizeImage(\'image\',(this.value / 100));" type="text" value="100" style="width: 40px;">%<hr />Generated with native-browser HTML image.';
      break;

      // Will be used if the extension represents a document and AbiWord is installed, or if the override "abiword" is used.
      case 7:
      exec($binaryPath . 'abiword "' . escapeshellcmd($fileData['full']) . '" -t html -o "' . escapeshellcmd($tempFile) . '" --exp-props="embed-css: yes; embed-images: yes;"');
      echo '<iframe src="' . $tempFileUrl . '" style="height: 400px;" class="generic"></iframe><hr />Displayed with AbiWord.';
      break;

      // Will be used if the document is a spreadsheet and Gnumeric is installed, or if the override "gnumeric" is used.
      case 9:
      exec('/usr/bin/ssconvert "' . $permFile . '" "' . $tempFile . '" --export-type=Gnumeric_html:xhtml');
      echo '<iframe src="' . $uploadUrl . '.fliler-' . $fileData['name'] . '.htm" style="width: 100%; height: 400px;"></iframe><hr />Displayed with Gnumeric.';
      break;

      // Will be used if gnumeric is not installed and the file is a CSV file, or if the override "csv" is used.
      case 10:
      require('lib/csv.php');
      echo csvtoTable($permFile) . '<hr />Displayed with Fliler CVS to Table converter.';
      break;

      // Will be used if the file a video, or if the override "video" is used.
      case 11:
      echo '<video src="' . $uploadUrl . $file . '" style="width: 800px; height: 600px;" controls="controls"></video><hr />Displayed with native browser HTML5 video.';
      break;

      // Will be used if the file is a audio, or if the override "audio" is used.
      case 12:
      echo '<audio src="' . $uploadUrl . $file . '" style="width: 400px;" controls="controls"></audio><hr />Displayed with native browser HTML5 audio.';
      break;
    }

    // Generate alternate displays.
    echo '<hr /><b>Recommended Alternate Containers</b>: ';
    if ($fileData['ext'] == 'csv') {
      echo '<a href="viewfile.php?file=' . $file . '&mode=gnumeric">Gnumeric</a> | <a href="viewfile.php?file=' . $file . '&mode=csv">HTML Table</a>';
    }
    elseif ($fileData['ext'] == 'svg' || $fileData['ext'] == 'svgz') {
      echo '<a href="viewfile.php?file=' . $file . '&mode=code">Highlighted Code</a> | <a href="viewfile.php?file=' . $file . '&mode=image">HTML Image</a>';
    }
    else {
      echo 'None.';
    }

    echo '<br /><b>All Possible Containers</b>: <a href="viewfile.php?file=' . $file . '&mode=object">HTML Object</a> | <a href="viewfile.php?file=' . $file . '&mode=frame">HTML IFrame</a> | <a href="viewfile.php?file=' . $file . '&mode=text">Raw Text</a> | <a href="viewfile.php?file=' . $file . '&mode=code">Highlighted Code</a> | ' . (in_array($fileData['ext'],array('jpg','jpeg','gif','png','svg','svgz')) ? '<a href="viewfile.php?file=' . $file . '&mode=image">HTML Image</a> | ' : '') . (in_array($fileData['ext'],array('doc','docx','odt','sxw','abi')) ? ' | <a href="viewfile.php?file=' . $file . '&mode=abiword">AbiWord Documnt</a> | ' : '') . (in_array($fileData['ext'],array('csv','xls','xlsx','ods','sdc')) ? ' | <a href="viewfile.php?file=' . $file . '&mode=gnumeric">Gnumeric Document</a>' : '') . (in_array($fileData['ext'],array('mp2','mp3','mp4','wav','ogg','oga','ogv','mov')) ? ' | <a href="viewfile.php?file=' . $file . '&mode=video">HTML Video</a> | <a href="viewfile.php?file=' . $file . '&mode=audio">HTML Audio</a>' : '');

    echo '<hr />
<table class="generic">
  <tr>
    <th colspan="2">File Data</th>
  </tr>
  <tr>
    <td>Absolute Path</td>
    <td>' . $fileData['full'] . '</td>
  </tr>
  <tr>
    <td>Relative Path</td>
    <td>' . $relativePath . '</td>
  </tr>
  <tr>
    <td>File Name</td>
    <td>' . $fileData['file'] . '</td>
  </tr>
  <tr>
    <td>File Part</td>
    <td>' . $fileData['name'] . '</td>
  </tr>
  <tr>
    <td>File Extension</td>
    <td>' . $fileData['ext'] . '</td>
  </tr>
  <tr>
    <td>Mime Type</td>
    <td>' . $fileData['mime'] . '</td>
  </tr>
  <tr>
    <td>Owner</td>
    <td>' . $fileData['owner'] . '</td>
  </tr>
  <tr>
    <td>File Size</td>
    <td>' . $fileData['size'][1] . '</td>
  </tr>
  <tr>
    <td>Last Modification</td>
    <td>' . $fileData['lastMod'][1] . '</td>
  </tr>
  <tr>
    <td>Backup File?</td>
    <td>' . (($fileData['backup']) ? 'Yes' : 'No') . '</td>
  </tr>
  <tr>
    <td>Hidden/Dot File?</td>
    <td>' . (($fileData['dot']) ? 'Yes' : 'No') . '</td>
  </tr>
  <tr>
    <td>Writable?</td>
    <td>' . (is_writable($fileData['full']) ? 'Yes' : 'No') . '</td>
  </tr>
  <tr>
    <td>Readable?</td>
    <td>' . (is_readable($fileData['full']) ? 'Yes' : 'No') . '</td>
  </tr>
  <tr>
    <td>Base64</td>
    <td><textarea cols="60" rows="6">' . $base64 . '</textarea>';

    if (in_array($fileData['ext'],array('png','gif','jpg','jpeg','bmp','psd','tif','tiff'))) {
      list($imageWidth,$imageHeight) = getimagesize($fileData['full']);
      echo '  <tr>
    <th colspan="2">Image Data</th>
  </tr>
  <tr>
    <td>Width</td>
    <td>' . $imageWidth . '</td>
  </tr>
  <tr>
    <td>Height</td>
    <td>' . $imageHeight .  '</td>
  </tr>
  <tr>
    <td>HTML Embed</td>
    <td><textarea cols="60" rows="1"><img src="' . $relativePath . '" /></textarea></td>
  </tr>
  <tr>
    <td>HTML Embed<br />(with Link)</td>
    <td><textarea rows="3" cols="60"><a href="' . $relativePath . '">' . "\n" . '<img src="' . $relativePath . '" />' . "\n" . '</a></textarea></td>
  </tr>
  <tr>
    <td>HTML Embed<br />(as Data URI)</td>
    <td><textarea cols="60" rows="6">' . wordwrap('<img src="data:' . $extQuery['mime'] . ';base64,' . $base64 . '" />',60,"\n",true) . '</textarea></td>
  </tr>
  <tr>
    <td>BBCode Embed</td>
    <td><textarea rows="1" cols="60">[img]' . $relativePath . '[/img]</textarea></td>
  </tr>
  <tr>
    <td>BBCode Embed<br />(with Link)</td>
    <td><textarea rows="3" cols="60">[url=' . $relativePath . ']' . "\n" . '[img]' . $relativePath . '[/img]' . "\n" . '[/url]</textarea></td>
  </tr>';
    }

    echo '</table>';
  }
}
echo documentEnd();
?>
