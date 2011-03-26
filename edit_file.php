<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Pragma: no-cache");
require_once('uac.php');

static $stage;
if (isset($_GET['stage'])) $stage = $_GET['stage'];

/* Document Start */
echo documentStart('Edit File');

/* Document Content */
if ($perm['EdF']) {
  switch($stage) {
    // Select a file to edit.
    case null:
    case 1:
    $dirs = listDirs('dir');
    echo container('Edit a File','<form action="edit_file.php?stage=2" method="post">
  <div class="left">
    <label for="file">File to Edit:</label><br />
    <span id="fileSelect"><input type="text" id="file" name="file" /></span><br /><br />
    <label for="dir">Directory of the File:</label><br />' . $dirs . '<br /><br />
  </div>
  <div class="right">
  ' . container('Help','<div id="help">Choose a file to edit. Documents can be edited using WYSIWYG editors, while other files will be edited as plain text. Images and non-document binary formats are not supported.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  </div>
</form>',0);
    break;
    // Show the file and present it to edit.
    case 2:
    $file = (($_GET['file']) ? $_GET['file'] : $_POST['file']);
    $dir = (($_GET['dir']) ? $_GET['dir'] : $_POST['dir']);
    $file2 = formatDir($dir,true) . $file;
    if (!$fileData = fileData(null,$file2,array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => true,'content' => true))) {
      echo container('The file cannot be read. What would you like to do now?','<ol id="main">
  <li><a href="edit_file.php">Edit a Different File</a></li>
  <li><a href="index.php">Go to the Index</a></li>
  <li><a href="javascript:window.close();">Close This Window</a></li>
</ol>',1);
    }
    else {
      if (in_array($fileData['ext'],array('html','htm'))) {
        $advanced = true;
      }
      elseif ((in_array($fileData['ext'],array('doc','docx','rtf','pdf','odt','sxw'))) && (is_executable('/usr/bin/abiword'))) {
        $tempFile = $uploadDirectory . $tmpPathLocal . '.fliler-' . time() . '-' . safeFile($fileData['name']) . '.htm';
        $tempFileOriginal = $uploadDirectory . $tmpPathLocal . '.flilersource-' . $ut . '-' . safeFile($fileData['name']) . '.htm';
        copyFile($fileData['full'],$tempFileOriginal);

        // Create the file. This allows us to overwrite any existing files and determine any errors before they happen.
        if (createFile(null,$tempFile,null,true)) {
          exec($binaryPath . 'abiword "' . $tempFileOriginal . '" -t html -o "' . $tempFile . '"');
          $content = readFileIntoString(null,$tempFile);
          if (false !== ($content = readFileIntoString(null,$tempFile))) {
            $fileData['content'] = $content;
            $advanced = true;
          }
          else {
            trigger_error('The temporary file could not be read - the basic text viewer will be loaded instead.',E_USER_WARNING);
          }
        }
        else {
          trigger_error('The temporary file could not be created - the basic text viewer will be loaded instead.',E_USER_WARNING);
        }
      }
      if ($advanced) {
        switch ($_GET['editor']) {
          // Built-in Editor
          case 1:
          echo '<script src="edit_file.js" type="text/javascript"></script>';
          echo container('Standard HTML Editor' . ($advanced ? '<select style="width: 100px;" onChange="addPTag2(\'span\',\'font-size\',options[selectedIndex].value)">
  <option value="100%">Font Size</option>
  <option value="6px">6px</option>
  <option value="8px">8px</option>
  <option value="10px">10px</option>
  <option value="12px">12px</option>
  <option value="14px">14px</option>
  <option value="20px">20px</option>
  <option value="24px">24px</option>
  <option value="28px">28px</option>
  <option value="32px">32px</option>
  <option value="36px">36px</option>
  <option value="40px">40px</option>
  <option value="46px">46px</option>
  <option value="52px">52px</option>
  <option value="58px">58px</option>
  <option value="66px">66px</option>
  <option value="74px">74px</option>
</select>
<select style="width: 150px;" onChange="addPTag2(\'span\',\'font-family\',options[selectedIndex].value)">
  <option value="Default">Font Families</option>
  <optgroup label="Serif">
    <option value="serif" style="font-family: serif">Generic</option>
    <option value="\'"Times New Roman\'" style="font-family: \'Times New Roman\'">Times New Roman</option>
    <option value="Garmond" style="font-family: Garmond">Garmond</option>
    <option value="Georgia" style="font-family: Georgia">Georgia</option>
  </optgroup>
  <optgroup label="Sans-Serif">
    <option value="sans-serif" style="font-family: sans-serif">Generic</option>
    <option value="Trebuchet" style="font-family: Trebuchet">Trebuchet</option>
    <option value="Arial" style="font-family: Arial">Arial</option>
    <option value="Verdana" style="font-family: Verdana">Verdana</option>
  </optgroup>
  <optgroup label="Cursive">
    <option value="cursive" style="font-family: cursive">Generic</option>
  </optgroup>
  <optgroup label="Fantasy">
    <option value="fantasy" style="font-family: fantasy">Generic</option>
  </optgroup>
  <optgroup label="Monospace">
    <option value="monospace" style="font-family: monospace">Generic</option>
    <option value="Courier" style="font-family: courier">Courier</option>
    <option value="\'MS Courier New\'" style="font-family: \'MS Courier New\'">MS Courier New</option>
  </optgorup>
</select>
<select style="width: 100px;" onChange="addPTag2(\'span\',\'color\',options[selectedIndex].value)">
  <option value="#000000">Font Color</option>
  <option value="#ffff00" style="background-color: #ffffff; color: #ffff00;">Yellow</option>
  <option value="#ff7f00" style="background-color: #ffffff; color: #ff7f00;">Orange</option>
  <option value="#ff0000" style="background-color: #ffffff; color: #ff0000;">Red</option>
  <option value="#ff00ff" style="background-color: #ffffff; color: #ff00ff;">Pink</option>
  <option value="#0000ff" style="background-color: #ffffff; color: #0000ff;">Blue</option>
  <option value="#00ffff" style="background-color: #ffffff; color: #00ffff;">Cyan</option>
  <option value="#00ff00" style="background-color: #ffffff; color: #00ff00; border-bottom: 1px solid black;">Bright Green</option>
  <option value="#7f0000" style="background-color: #ffffff; color: #7f0000;">Dark Red</option>
  <option value="#7f007f" style="background-color: #ffffff; color: #7f007f;">Violet</option>
  <option value="#00007f" style="background-color: #ffffff; color: #00007f;">Dark Blue</option>
  <option value="#007f7f" style="background-color: #ffffff; color: #00f7f7;">Teal</option>
  <option value="#007f00" style="background-color: #ffffff; color: #007f00; border-bottom: 1px solid black;">Green</option>
  <option value="#000000" style="background-color: #ffffff; color: #000000;">Black</option>
  <option value="#3f3f3f" style="background-color: #ffffff; color: #3f3f3f;">75% Gray</option>
  <option value="#7f7f7f" style="background-color: #ffffff; color: #7f7f7f;">50% Gray</option>
  <option value="#bfbfbf" style="background-color: #000000; color: #bfbfbf;">25% Gray</option>
  <option value="#ffffff" style="background-color: #000000; color: #ffffff;">White</option>
</select>
<select style="width: 100px;" onChange="addPTag2(\'span\',\'background-color\',options[selectedIndex].value)">
  <option value="Default">BG Color</option>
  <option value="#ff0000" style="background-color: #ff0000; color: black;">Red</option>
  <option value="#ff00ff" style="background-color: #ff00ff; color: black;">Pink</option>
  <option value="#7f007f" style="background-color: #7f007f; color: white;">Purple</option>
  <option value="#0000ff" style="background-color: #0000ff; color: white;">Blue</option>
  <option value="#007f7f" style="background-color: #00f7f7; color: white;">Teal</option>
  <option value="#00ffff" style="background-color: #00ffff; color: black;">Cyan</option>
  <option value="#00ff00" style="background-color: #00ff00; color: black;">Bright Green</option>
  <option value="#ffff00" style="background-color: #ffff00; color: black;">Yellow</option>
  <option value="#ff7f00" style="background-color: #ff7f00; color: black;">Orange</option>
  <option value="#7f0000" style="background-color: #7f0000; color: white;">Dark Red</option>
  <option value="#00007f" style="background-color: #00007f; color: white;">Dark Blue</option>
  <option value="#007f00" style="background-color: #007f00; color: white;">Green</option>
  <option value="#000000" style="background-color: #000000; color: white;">Black</option>
  <option value="#3f3f3f" style="background-color: #3f3f3f; color: white;">75% Gray</option>
  <option value="#7f7f7f" style="background-color: #7f7f7f; color: white;">50% Gray</option>
  <option value="#bfbfbf" style="background-color: #bfbfbf; color: black;">25% Gray</option>
  <option value="#ffffff" style="background-color: #ffffff; color: black;">White</option>
</select>
<select style="width: 100px;" onChange="addPTag2(\'span\',\'text-align\',options[selectedIndex].value)">
  <option>Text Align</option>
  <option value="left" style="text-align: left;">Left</option>
  <option value="center" style="text-align: center;">Center</option>
  <option value="right" style="text-align: right;">Right</option>
  <option value="justified" style="text-align: justified;">Justified</option>
</select>
<button type="button" onclick="addPTag(\'<span style=\\\'font-weight: bold;\\\'>\',\'</span>\')" class="wysiwyg"><span style="font-weight: bold;">B</span></button>
<button type="button" onclick="addPTag(\'<span style=\\\'text-decoration: underline;\\\'>\',\'</span>\')" class="wysiwyg"><span style="text-decoration: underline;">U</span></button>
<button type="button" onclick="addPTag(\'<i>\',\'</i>\')" class="wysiwyg"><i>I</i></button>
<button type="button" onclick="addPTag(\'<span style=\\\'text-decoration: line-through;\\\'>\',\'</span>\')" class="wysiwyg"><span style="text-decoration: line-through">S</span></button>
<button type="button" onclick="var linktext = prompt(\'Where should the link point?\',\'\'); addPTag(\'<a href=\\\'\' + linktext + \'\\\'>\',\'</a>\')" class="wysiwyg"><span style="color: blue; text-decoration: underline">L</span></button>
<button type="button" onclick="var imgUrl = prompt(\'What should the image\\\'s URL be?\',\'\'); var imgAlt = prompt(\'What should the alternate text be?\',\'\'); insertAtCursor(\'<img src=\\\'\' + imgUrl + \'\\\' alt=\\\'\' + imgAlt + \'\\\' />\');" class="wysiwyg"><span style="color: red;">IMG</span></button>
<button type="button" onclick="collapse();" id="toolbar2button" class="wysiwyg">&darr;</button>
<div id="toolbar2" style="display: none;">
  <button style="width: 90px;" type="button" onclick="var css = prompt(\'What should the CSS be?\',\'\'); if(validateCSS(css)); addPTag(\'<div style=\\\'\' + css + \\\'\'>\',\'</div>\');\">CSS Div</button>
  <button style="width: 90px;" type="button" onclick="var css = prompt(\'What should the CSS be?\',\'\'); if(validateCSS(css)); addPTag(\'<span style=\\\'\' + css + \'\\\'>\',\'</span>\');">CSS Span</button>
  <button style="width: 90px;" type="button" onclick="var reason = prompt(\'What should the reason for the spoiler be?\',\'\'); addPTag(\'<fieldset><legend><strong>Spoiler Alert:</strong><br /><span style=\\\'margin-left: 20px; text-decoration: underline;\\\'>\' + reason + \'</span></legend><br /><div id=\\\'content\\\' style=\\\'display: none;\\\'>\',\'</div><input type=\\\'button\\\' value=\\\'Show\\\' id=\\\'lever\\\' onClick=\\\'if (document.getElementById(\\\\\\\\\'content\\\\\\\\\').style.display == \\\\\\\\\'none\\\\\\\\\') { document.getElementById(\\\\\\\\\'content\\\\\\\\\').style.display = \\\\\\\\\'block\\\\\\\\\'; document.getElementById(\\\\\\\\\'lever\\\\\\\\\').value = \\\\\\\\\'Hide\\\\\\\\\'; } else {   document.getElementById(\\\\\\\\\'content\\\\\\\\\').style.display = \\\\\\\\\'none\\\\\\\\\'; document.getElementById(\\\\\\\\\'lever\\\\\\\\\').value = \\\\\\\\\'Show\\\\\\\\\'; }\' style=\\\'float: right;\\\' /></fieldset>\'); ">Spoiler</button>
</div>' : ''),'<form name="workarea" action="edit_file.php?stage=3" method="post" onkeyup="showOutput();"><input type="hidden" name="dir" value="' . $dir . '" /><input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="file2" value="' . $file2 . '" /><div style="display: inline; width: 100%; display: block; clear: both;"><div style="width: 49%; float: left;">File Contents:</div><div style="width: 49%; float: right; max-width: 49%;">HTML Output:</div></div><div><textarea id="fileContent" name="content" style="overflow: auto; max-width: 49%; width: 49%; height: 200px; display: inline; float: left;">' . htmlspecialchars($fileData['content']) . '</textarea><div id="htmlOutput" style="width: 49%; height: 200px; display: inline; float: right; overflow: auto; border: 1px solid black; float: right;">' . $fileData['content'] . '</div></div><div style="float: left;"><br /><input type="submit" value="Submit" /><input type="reset" value="Reset" /></div><div style="float: right; display: block;">
    <label for="boxHeight">Change Textbox Height</label>: <input type="text" value="200" name="boxHeight" id="boxHeight" onChange="if ((this.value > 0) && (this.value.match(/^[0-9]+$/))) { document.getElementById(\'fileContent\').style.height = this.value + \'px\'; document.getElementById(\'htmlOutput\').style.height = this.value + \'px\'; }" style="width: 50px !important;" />px<br /><u>Change Layout To:</u> <span onclick="verticalLayout();">Vertical</span> | <span onclick="horizontalLayout();">Horizontal</span></div></form>',false,false,true);
          break;

          // Koivi Editor
          case 2:
          echo '<script src="edit_file_2.js" type="text/javascript"></script><form onsubmit="editor.prepareSubmit();" name="workarea" action="edit_file.php?stage=3" method="post"><script type="text/javascript">var editor = new WYSIWYG_Editor(\'editor\',\'' . addslashes(str_replace("\n",'',$fileData['content'])) . '\'); editor.display();</script><input type="hidden" name="dir" value="' . $dir . '" /><input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="file2" value="' . $file2 . '" /><br /><input type="submit" value="Submit" /></form>';
          break;

          // Ck Editor
          case 3:
          case false:
          require_once('edit_file_3.php');
          echo '<form onclick="window.editor_content.setUiColor(\'#FFAD69\')" name="workarea" action="edit_file.php?stage=3" method="post">';
          $editor = new CKEditor();
          $editor->basePath = '.ckeditor/';
          $editor->setUiColor = '#ffad69';
          $editor->editor('editor_content',$fileData['content'],array('uiColor' => '#87bde3',));
          echo '<input type="hidden" name="dir" value="' . $dir . '" /><input type="hidden" name="file" value="' . $file . '" /><input type="hidden" name="file2" value="' . $file2 . '" /><br /><input type="submit" value="Submit" /><input type="reset" value="Reset" /></form>';
          break;
        }
        echo '<hr />Editor: <a href="edit_file.php?stage=2&file=' . urlencode($file) . '&dir=' . urlencode($dir) . '&editor=1">Fliler Standard Editor</a> | <a href="edit_file.php?stage=2&file=' . urlencode($file) . '&dir=' . urlencode($dir) . '&editor=2">Koivi Enhanced Editor</a> | <a href="edit_file.php?stage=2&file=' . urlencode($file) . '&dir=' . urlencode($dir) . '&editor=3">CK Professional Editor</a>';
      }
      else {
        echo container('Edit a File','<form action="edit_file.php?stage=3" method="post">
  <input type="hidden" name="dir" value="' . $dir . '" />
  <input type="hidden" name="file" value="' . $file . '" />
  <input type="hidden" name="file2" value="' . $file2 . '" />
  <textarea id="fileContent" name="content" style="width: 100%; height: 200px; display: block;">' . htmlspecialchars($fileData['content']) . '</textarea>
  <div style="float: right;">
    <label for="boxHeight">Change Textbox Height</label>: <input type="text" value="200" name="boxHeight" id="boxHeight" onChange="if ((this.value > 0) && (this.value.match(/^[0-9]+$/))) document.getElementById(\'fileContent\').style.height = this.value + \'px\';" />px
  </div><br /><br />
  <input type="submit" value="Submit" /><input type="reset" value="Reset" />
</form>',0);
      }
    }
    break;
    case 3:
    $ut = date('U');
    $dir = $_POST['dir'];
    $file = $_POST['file'];
    $file2 = $_POST['file2'];
    $fileData = fileData(null,$file2);
    $content = (($_POST['editor_content']) ? $_POST['editor_content'] : $_POST['content']);
    if ($createBackups >= 1) {
      if (!copyFile($file2,$file2 . '~' . $ut)) {
        if ($createBackups == 2) {
          trigger_error('The script will not continue because a backup could not be made.',E_USER_ERROR);
          $stop = true;
        }
        else {
          trigger_error('A backup could not be made.',E_USER_WARNING);
        }
      }
    }
    if (!$stop) {
      if (in_array($fileData['ext'],array('doc','docx','rtf','pdf','odt','sxw'))) {
        //$extensionsTo = array('doc' => 'doc';
        $tempFile = $uploadDirectory . $tmpPathLocal . '.fliler-' . time() . '-' . $fileData['name'] . '.htm';
        $tempFile2 = $uploadDirectory . $tmpPathLocal . '.fliler-' . time() . '-2-' . $fileData['name'] . '.' . $fileData['ext'];
        // Create the file. This allows us to overwrite any existing files and determine any errors before they happen.
        if (createFile(null,$tempFile,$content,true)) {
          exec($binaryPath . 'abiword "' . escapeshellcmd($tempFile) . '" --import-extension html -t doc -o "' . escapeshellcmd($tempFile2) . '"');
           $content = readFileIntoString(null,$tempFile2);
          if (writeFileFromString(null,$fileData['full'],$content)) {
            echo container('The file has been successfully modified. What would you like to do now?','<ol><li><a href="edit_file.php">Edit Another File</a></li><li><a href="viewfile.php?f=' . urlencode($file) . '">View the File</a></li><li><a href="index.php">Go to the Index</a></li><li><a href="javascript:window.close();">Close This Window</a></li></ol>',0);
          }
          else {
            echo container('Editing the file failed. What would you like to do now?','<ol id="main"><li><a href="edit_file.php">Create a File Elsewhere</a></li><li><a href="restore_backup.php">Restore a File Backup</a></li><li><a href="index.php">Go to the Index</a></li><li><a href="javascript:window.close();">Close This Window</a></li></ol>',1);
          }
        }
      }
      else {
        if (writeFileFromString(null,$file2,$content)) {
          echo container('The file has been successfully modified. What would you like to do now?','<ol><li><a href="edit_file.php">Edit Another File</a></li><li><a href="viewfile.php?f=' . urlencode($file) . '">View the File</a></li><li><a href="index.php">Go to the Index</a></li><li><a href="javascript:window.close();">Close This Window</a></li></ol>',0);
        }
        else {
          echo container('Editing the file failed. What would you like to do now?','<ol id="main"><li><a href="edit_file.php">Create a File Elsewhere</a></li><li><a href="restore_backup.php">Restore a File Backup</a></li><li><a href="index.php">Go to the Index</a></li><li><a href="javascript:window.close();">Close This Window</a></li></ol>',1);
        }
      }
      break;
    }
  }
}
/* Document End */
echo documentEnd();
?>