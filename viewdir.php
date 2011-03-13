<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* View Directory
 ** GET Params **
 * 'd' = Directory to show.
 * 'f' = Filter type.
 * 'e' = Extension filter.
 * 'n' = File name filter.
 * 't' = File type filter.
 ** TODO **
 * Rewrite this entire annoying sucker. The engines have been rewritten, now to bring this baby up to par with my skills.
 * Restore feature parity with Beta 1; specifically restore cutting, copying, and pasting. Deleting and saving has already been done for files.
/* Document Start */
require('uac.php');
require('filter_type.php');

static $home, $dirid, $fileid, $n, $e, $t, $f, $d, $s;
if (isset($_GET['n'])) $n = $_GET['n'];
if (isset($_GET['e'])) $e = $_GET['e'];
if (isset($_GET['t'])) $t = $_GET['t'];
if (isset($_GET['f'])) $f = $_GET['f'];
if (isset($_GET['d'])) $d = $_GET['d'];
if (isset($_GET['s'])) $s = $_GET['s'];
if (isset($_GET['r'])) $r = $_GET['r'];

if ($f != 2) {
  $f = 1;
  if ($e) $e = explode(',',$e);
}
else {
  if ($e) $e = $exts[$t];
}
$dirPath = formatDir(urldecode($d));
$cleanUrl = cleanDirUrl($f,@implode(',',$e),$n,$t,$s,$r);
//else $e = array([0] = false);

echo documentStart('View a Directory');

/* Document Content */

$dirPathPieces = explode('/',$dirPath);
foreach ($dirPathPieces as $piece) {
  if ($piece) {
    $dirPathPieces2[] = '<a href="viewdir.php?d=' . $string . $piece . '/' . $cleanUrl . '">' . $piece . ' &#8594; </a>';
  }
}

$dirPath2 = 'You are currently in: <u><a href="viewdir.php?d=' . $cleanUrl . '">Home &#8594;</a> ' . @implode($dirPathPieces2) . '</u>';

if ($perm['View']) {
  $data = listFiles($uploadDirectory . $dirPath,$n,$e,$lockedFiles,false,false,'seperate',array('lastMod' => true,'owner' => true,'size' => true,'ext' => true,'mime' => true,'content' => 100));

  if ($data['files']) {
    foreach ($data['files'] AS $file) {
      $i++;

      switch ($s) {
        case 'size':
        $files[$file['size'][0] . $i] = $file;
        break;

        case 'lastMod':
        $files[$file['lastMod'][0] . $i] = $file;
        break;

        case 'owner':
        $files[$file['owner'] . $i] = $file;
        break;

        case 'file':
        default:
        $files[$file['file'] . $i] = $file;
        break;
      }
    }
  }

  if ($files) {
    if ($r) {
      krsort($files);
    }
    else {
      ksort($files);
    }
  }

  if ($data) {
    echo '<script src="viewdir.js"></script><div style="clear: both;"><div style="float: left; width: 49%;"><h3>' . $dirPath2 . '</h3><div class="toolbar" style="width: 240px;">';
    if (!$home) {
      $parentPath = parentDirectory($d);
      echo '<a href="viewdir.php?d=' . $parentPath . $cleanUrl . '"><img src="images/up.png" /></a>';
    }
    echo '<a onclick="history.go(-1)"><img src="images/back.png" /></a><a onclick="history.go(+1)"><img src="images/forward.png" /></a><a href="viewdir.php"><img src="images/home.png" /></a><a onclick="window.location.reload()"><img src="images/reload.png" /></a>';
    if ($perm['MkD']) {
      echo '<a onclick="var answer = prompt(\'What should the new folder be named?\',\'\'); if(answer) { createDir(\'' . $dirPath . '\' + answer); }"><img style="padding-left: 20px;" src="images/folder-new.png" /></a>';
    }
    if ($perm['MkF']) {
      echo '<a onclick="var answer = prompt(\'What should the new file be named?\',\'\'); if(answer) { createFile(\'' . $dirPath . '\' + answer); }"><img src="images/document-new.png" /></a>';
    }
    echo '<a href="download_dir.php?stage=2&dir=' . $dirPath . '"><img src="images/save.png" /></a></div></div><div style="float: right; width: 49%; text-align: right;"><noscript><div style="font-weight: bold; color: red; vertical-align: top;">Please Enable Scripts</div><br /><br /></noscript><form name="form1" id="filter1" method="get" style="display: ' . (($f === 1) ? 'inline' : 'none') . ';"><label for="n">File Name Filter (Block Search): </label><input type="text" name="n" id="filename" value="' . $n . '" /> . <input type="text" name="e" value="' . $e[0] . '" style="width: 40px;" /><input type="hidden" name="f" value="1" /><input type="hidden" name="d" value="' . $d . '" /><input style="display: none;" type="submit" /></form><form name="form2" id="filter2" style="display: ' . (($f == 1) ? 'none' : 'inline') . ';" title="Here you should select a data type that best matches what you are looking for" method="get"><label for="extension">Extension Group</label>: <select name="t"><option value="audio">Music and Audio</option><option value="compressed">Compressed Files</option><option value="database">Databases</option><option value="dev">Development Files</option><option value="disk">Disk Images</option><option value="document">Documents and Text Files</option><option value="exec">Executable Files</option><option value="fontcur">Fonts and Cursors</option><option value="image">Pictures and Images</option><option value="presentation">Presenations</option><option value="settings">Setting Files</option><option value="spreadsheet">Spreadsheets</option><option value="sys">System Files</option><option value="video">Movies and Video</option><option value="web">Web Documents</option></select><input type="hidden" name="f" value="2" /><input type="hidden" name="d" value="' . $d . '" /></form><br /><button onclick="if (getElementById(\'filter1\').style.display == \'none\') { getElementById(\'filter1\').style.display=\'inline\'; getElementById(\'filter2\').style.display = \'none\'; } else { getElementById(\'filter2\').style.display=\'inline\'; getElementById(\'filter1\').style.display = \'none \'; }" title="Switch Filter Method">&#x21ba;</button><button onclick="if (getElementById(\'filter1\').style.display == \'none\') { document.form2.submit() } else { document.form1.submit() }">Filter Results</button></div></div><table class="generic" id="files" style="word-wrap: break-word; white-space: normal;"><tr><th width="15%">Actions:</th><th width="30%"><a href="viewdir.php?d=' . urlencode($d) . cleanDirUrl($f,@implode(',',$e),$n,$t,'file',($s == 'file' && !$r ? 'true' : '')) . '">Filename:</a></th><th width="20%"><a href="viewdir.php?d=' . urlencode($d) . cleanDirUrl($f,@implode(',',$e),$n,$t,'lastMod',($s == 'lastMod' && !$r ? 'true' : '')) . '">Last modified:</a></th><th width="8%"><a href="viewdir.php?d=' . urlencode($d) . cleanDirUrl($f,@implode(',',$e),$n,$t,'owner',($s == 'owner' && !$r ? 'true' : '')) . '">Owner</a></th><th width="8%"><a href="viewdir.php?d=' . urlencode($d) . cleanDirUrl($f,@implode(',',$e),$n,$t,'size',($s == 'size' && !$r ? 'true' : '')) . '">File size:</a></th><th>Mime Type</th><th width="19%">Preview <a href="javascript:void(0);" onclick="if ($(this).html() == \'-\') { $(\'.contentExcerpt\').hide(); $(this).html(\'+\'); } else { $(\'.contentExcerpt\').show(); $(this).html(\'-\'); }">-</a></th></tr>' . "\n";
    foreach ($data['dirs'] as $dir) {
      $dirid += 1;
      echo '<tr id="dir' . $dirid . '"><td>';
      if ($perm['RmD']) {
        echo '<a onclick="if(confirm(\'Warning: This will also delete all files inside the directory. Are you sure you want to continue?\')) { deleteDir(\'' . $dirPath . $dir['file'] . '\',\'dir' . $dirid . '\'); }"><img src="images/delete.png" /></a>';
      }
      if ($perm['MvF'] && $perm['MkF']) {
        echo '<a onclick="pasteFiles(\'' . $dirPath . $dir['file'] . '\');"><img src="images/paste.png" /></a>';
      }
      echo '<a href="download_dir.php?stage=2&dir=' . urlencode($dirPath . $dir['file']) . '"><img src="images/save.png" /></a></td><td><img src="images/folder.png" />&nbsp; <a href="viewdir.php?d=' . urlencode($dirPath . $dir['file']) . $cleanUrl . '">' . wordwrap($dir['file'],25,'<br />',true) . '</a></td><td>' . $dir['lastMod'][1] . '</td><td>' . $dir['owner'] . '</td><td>' . $dir['size'][1] . '</td><td></td><td></td></tr>' . "\n";
    }
    foreach ($files as $file) {
      $fileid += 1;
      echo '<tr id="file' . $fileid . '"><td>';
      if ($perm['RmF']) {
        echo '<a onclick="deleteFile(\'' . $dirPath . $file['file'] . '\',\'file' . $fileid . '\');"><img src="images/delete.png" /></a>';
      }
      if ($perm['EdF']) {
        echo '<a href="edit_file.php?stage=2&file=' . $dirPath . $file['file'] . '"><img src="images/edit.png" /></a>';
      }
      if ($perm['MkF'] && $perm['MvF']) {
        echo '<a onclick="copyFiles(\'' . $dirPath . $file['file']. '\',\'file' . $fileid . '\');"><img src="images/copy.png" /></a><a onclick="cutFiles(\'' . $dirPath . $file['file']. '\',\'file' . $fileid . '\');"><img src="images/cut.png" /></a>';
      }
      echo '<a href="download_file.php?stage=3&file=' . urlencode($dirPath . $file['file']) . '"><img src="images/save.png" /></a></td><td><img src="images/file.png" />&nbsp;<a href="viewfile.php?file=' . urlencode($dirPath . $file['file']) . '">' . wordwrap($file['file'],25,'<br />',true) . '</a>' . ($file['ext'] == 'zip' | $file['ext'] == 'cbz' ? ' <a href="viewdir.php?d=zip:' . urlencode($dirPath . $file['file']) . $cleanUrl . '">(View as Dir)</a>' : '') . '</td><td>' . $file['lastMod'][1] . '</td><td>' . $file['owner'] . '</td><td>' . $file['size'][1] . '</td><td>' . $file['mime'] . '</td><td class="contentExcerpt" align="center">';
      switch($file['ext']) {
        case 'png': case 'gif': case 'jpg': case 'jpeg': case 'svg':
        echo '<img src="' . $uploadUrl . $dirPath . $file['file'] . '" style="max-width: 40px; max-height: 40px;" />';
        break;
        case 'htm': case 'html': case 'php': case 'js': case 'css': case 'txt': case 'pl': case 'py': case 'text': case 'txt': case 'xml':
        echo '<div style="font-size: 8px; white-space: pre; width: 60px; height: 100px; overflow: hidden; background-color: white; padding: 2px; border: 1px solid black; text-align: left;">' . wordwrap(htmlentities($file['content']),30,'<br />',true) . '</div>';
        break;
      }
      echo '</td></tr>' . "\n";
    }
    echo '</table>';
  }
  else {
    echo 'The directory you are trying to load does not exist.';
  }
}

/* Document End */
echo documentEnd();
?>