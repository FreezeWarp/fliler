<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Document Start */
require_once('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

echo documentStart('Upload a File');

/* Document Content */
if ($perm['MkF']) {
  $dirs = listDirs();
  switch($stage) {
    case null:
    case 1:
    $browser = getBrowser();
    $maxuploads = ini_get('max_file_uploads');
    $maxsize = ini_get('upload_max_filesize');
    $friendlySize = friendlySize(parseConfigNum($maxsize),0);
//    if (($browser['browser'] == 'firefox') && ((($browser['majorVersion'] == 3) && ($browser['minorVersion'] >= 6)) || ($browser['majorVersion'] >= 4))) { $firefoxUpload = true; }
    echo container('Upload a File:','<script src="upload_file.js"></script><form action="upload_file.php?stage=2" method="post" enctype="multipart/form-data">
  <div class="left">
    <label for="file[]">Choose a File:</label><br />
    <div id="filesBox" style="display: inline;"><input name="file[]" id="file[]" onChange="upFiles()" type="file" multiple="multiple" x-child="0" /></div><br /><img src="images/info.png" onClick="help(\'Add additional file streams. Modern browsers should be able to select multiple files anyway, though this allows more control.\');" /><input type="button" value="Add More Files" onclick="newField(0)" /><img src="images/info.png" onClick="help(\'If you are using a compatible browser (currently Chrome 7), you can upload an entire directory. Note that the directory structure will be lost.\');" /><input type="button" value="Add Directory" onclick="newField(2)" /><img src="images/info.png" onClick="help(\'Allows you to enter an HTTP:, FTP:, or DATA: URI, such as from other websites.\');" /><input type="button" value="Add URL" onclick="newField(1)" /><br /><br />
    <label for="dir">Directory to be Created in:</label><br />
    ' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'If a file has the same name as the file you are trying to create, it will be overwritten.\');" /><label for="ow">Overwrite an Existing File?:</label> <input name="ow" id="ow" type="checkbox" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can upload files. If you need to add more files, select "Add More Files". You can also upload files from URLs by choosing "Add URL".<br /><br />Max Uploads: ' . $maxuploads . '<br />Max Upload Size: ' . $friendlySize . '<br />Allowed File Types: ' . ($extwl ? implode(', ',$extwl) : 'All') . '</div>') . container('<img src="images/info.png" onClick="help(\'This is a dynamically updated list of files you have selected for upload. Note that it requires JQuery for it to work properly, and will list repetetive files among multiple upload boxes twice. On Firefox and other browsers that support FileReader(), you can also see file previews.\');" /> Files to be Uploaded','<table id="filesList" class="generic" style="overflow: auto;"><tr><th>Name</th><th>Size</th><th>Contents</th></tr></table>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;

    case 2:
    $dir = $_POST['dir'];
    $ow = ((($_POST['ow'] == 'on') && ($perm['RmF'])) ? $ow = 1 : $ow = 0);
    for($i = 0; $i <= count($_FILES['file']['name']); $i ++) {
      if ($_FILES['file']['error'][$i] || !$_FILES['file']['name'][$i]) {
        continue;
      }

      $file = $_FILES['file']['name'][$i];

      $uploadFile = new fileManager;
      $uploadFile->setUploadedFile($tmpfile = $_FILES['file']['tmp_name'][$i]);
      $uploadFile->setGoal($dir,$file,true);
      if ($uploadFile->moveFile($ow,true)) {
        $goodFiles[] = '<a href="viewfile.php?f=' . urlencode($accessDirectory . $dir . '/' . $file) . '">' . $file . '</a>';
      }
      else { echo 2;
        $badFiles[] = '<a href="viewfile.php?f=' . urlencode($accessDirectory . $dir . '/' . $file) . '">' . $file . '</a>';
      }
    }
    for($i = 0; $i <= count($_POST['urls']); $i ++) {
      if (!$_POST['urls'][$i]) { continue; }
      $file = $_POST['urls'][$i];
      if (copyFile($file,$dir . '/' . filePart($file),$ow,true)) {
        $goodFiles[] = '<a href="viewfile.php?f=' . urlencode($accessDirectory . $dir . '/' . filePart($file)) . '">' . filePart($file) . '</a>';
      }
      else {
        $badFiles[] = '<a href="viewfile.php?f=' . urlencode($accessDirectory . $dir . '/' . filePart($file)) . '">' . filePart($file) . '</a>';
      }
    }
    echo container('The file upload has finished. What would you like to do now.','<ol>' . ((count($goodFiles) > 0) ? '
  <li>The following files were uploaded successfully: ' . implode(', ',$goodFiles) . '</li>' : '<li>No files were uploaded successfully.</li>') . ((count($badFiles) > 0) ? '
  <li>The following files could not be uploaded: ' . implode(', ',$badFiles) . '</li>' : '<li>No files could not be uploaded.</li>') . '
  <li><a href="upload_file.php">Upload Another File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
/*    if (uploadFile($dir,$file,$ow)) {
      echo container('The file has been successfully uploaded. What would you like to do now?','<ol>
  <li><a href="create_file.php">Upload Another File</a></li>
  <li><a href="viewfile.php?f=' . $file2 . '">View the File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
    }
    else {
      echo container('The file upload failed. What would you like to do now?','<ol id="main">
  <li><a href="create_file.php">Upload a File Elsewhere</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    } */
    break;
  }
}

/* Document End */
echo documentEnd();
?>