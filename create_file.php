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

echo documentStart('Create a New File');

/* Document Content */
if ($perm['MkF']) {
  switch($stage) {
    case null:
    case 1:
    $dirs = listDirs(null,null,3);
    echo container('Create a File','<form action="create_file.php?stage=2" method="post" enctype="multipart/form-data">
  <div class="left">
    <label for="contents">Contents:</label>
  </div>
  <div class="right">
    <label for="boxHeight">Change Textbox Height</label>: <input type="text" value="200" name="boxHeight" id="boxHeight" style="width: 50px;" tabindex="6" onChange="if ((this.value > 0) && (this.value.match(/^[0-9]+$/))) document.getElementById(\'contents\').style.height = this.value + \'px\';" />px<br />
  </div>
  <div class="full">
    <textarea name="contents" id="contents" style="height: 200px; width: 100%;" tabindex="1"></textarea><br />
  </div>
  <div class="left">
    <label for="file">Name:</label><br />
    <input name="file" id="file" type="text" tabindex="2" />
    <br /><br />
    <label for="dir">Directory to be Created in:</label><br />
    ' . $dirs . '<br /><br />
    <img src="images/info.png" onClick="help(\'If a file has the same name as the file you are trying to create, it will be overwritten.\');" /><label for="ow">Overwrite an Existing File?:</label> <input name="ow" id="ow" type="checkbox" tabindex="4" /><br /><br />
    <img src="images/info.png" onClick="help(\'By forcing a file extension, file names that do not contain file extensions will automatically have one appeneded to them depending on certain conditions, usually the file contents.\');" /><label for="force_ext">Force File Extension?</label>:
    <input name="force_ext" id="force_ext" type="checkbox" tabindex="5" /><br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">Here you can create files using plain text. If you want to create a document, enter nothing above and the appropriate file name, like "file.docx". You can then edit this using a full editor later on. Images, videos, audio files, and other binary files must be uploaded.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" tabindex="7" /><input name="reset" type="reset" value="Reset" tabindex="8" />
  </div>
</form>',0);
    break;

    case 2:
    $dir = $_POST['dir'];
    $file = $_POST['file'];
    $ow = ((($_POST['ow'] == 'on') && ($perm['RmF'])) ? true : false);
    $contents = $_POST['contents'];

    if ($_POST['force_ext']) {
      if (strstr($file,'.')) {
        // Do nothing
      }
      elseif (preg_match('/<\?php(.*?)\?>/is',$contents)) {
        $file .= '.php';
      }
      elseif (preg_match('/\#\!(.+?)\/perl/i',$contents)) {
        $file .= '.pl';
      }
      elseif (preg_match('/<html>(.*?)<\/html>/is',$contents)) {
        $file .= '.htm';
      }
      elseif (preg_match('/<svg(.*?)>(.*?)<\/svg>/is',$contents)) {
        $file .= '.svg';
      }
      else {
        $file .= '.txt';
      }
    }

    $fm = new fileManager;
    $fm->setFile($dir,$file,true);

    if ($fm->createFile($contents,$ow)) {
      echo container('The file has been successfully created. What would you like to do now?','<ol>
  <li><a href="create_file.php">Create Another File</a></li>
  <li><a href="viewfile.php?f=' . $file2 . '">View the File</a></li>
  <li><a href="edit_file.php?stage=2&dir=' . $dir . '&file=' . $file . '">Edit the File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',0);
    }
    else {
      echo container('The file creation failed. What would you like to do now?','<ol id="main">
  <li><a href="create_file.php">Create a File Elsewhere</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    if (preg_match('/\.(htm|html)/',$file)) {
      echo '<div style="display: block; width: 100%;">
  <div style="width: 49%; float: left;">File Contents:</div>
  <div style="width: 49%; float: right;">HTML Output:</div>
</div>
<textarea style="width: 49%; height: 200px; display: inline; float: left;" disabled="disabled">' . $contents . '</textarea>
<div style="width: 49%; height: 200px; display: inline; overflow: auto; border: 1px solid black; float: right;">' . $contents . '</div>';
    }
    else {
      echo container('File Contents','<blockquote>' . nl2br(htmlentities($contents)) . '</blockquote>');
    }
    break;
  }
}

/* Document End */
echo documentEnd();
?>