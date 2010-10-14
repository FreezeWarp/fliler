<?php
/* Copyright (c) 2010 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Note: This script is not very compatible. */

/* Pre-Processing */
require_once('uac.php');

static $action, $onclick, $i, $query;
if (isset($_GET['action'])) $action = $_GET['action'];

/* Document Start */
if (!in_array($action,array('new3','view2','delete'))) {
  echo documentStart('Manage Backups');
}

/* Document Content */
if ($perm['MngBckps']) {
  $mysqli = new mysqli($mysqlHost,$mysqlUser,$mysqlPassword,$mysqlDatabase);
  if ($mysqli->connect_error) {
    trigger_error('MySQL Connect Error','MySQLi could not connect to the database: Err No. ' . $mysqli->connect_errno . ', Error ' . $mysqli->connect_error,E_USER_ERROR);
  }
  else {
    switch($action) {
      case null:
      case 'view':
      if ($mysqli->real_query('SELECT `' . $mysqlPrefix . 'backups`.*, count(`' . $mysqlPrefix . 'backupfiles`.backupid) AS backupid FROM `' . $mysqlPrefix . 'backups`, `' . $mysqlPrefix . 'backupfiles` WHERE `' . $mysqlPrefix . 'backupfiles`.backupid = `' . $mysqlPrefix . 'backups`.id GROUP BY `' . $mysqlPrefix . 'backupfiles`.backupid')) {
        if ($result = $mysqli->store_result()) {
          echo '<table border="1" class="generic"><tr><th>ID</th><th>Directory</th><th>Timestamp</th><th>Entries</th><th>Actions</th></tr>';
          while ($row = $result->fetch_assoc()) {
            echo '<tr><td>' . $row['id'] . '</td><td>' . $row['location'] . '</td><td>' . $row['timestamp'] .'</td><td>' . $row['backupid'] . '</td><td><a onclick="if(confirm(\'Warning: This will remove any existing files. You may want to backup the new files first. Are you sure you want to continue?\')) { location.href=\'manage_backups.php?&action=restore&id=' . $row['id'] . '\'; }"><img src="images/reload.png" /></a><a onclick="if(confirm(\'Are you sure you want to delete this backup?\')) { http(\'manage_backups.php?action=delete&id=' . $row['id'] . '\',[\'status\']); }"><img src="images/delete.png" /></a></td></tr>';
          }
          echo '</table><br /><a href="manage_backups.php?action=new"><img style="padding-right: 20px;" src="images/add.png" />Add a New Backup</a>';
        }
        else {
          trigger_error('Could not read the result set.<br />' . $mysqli->error,E_USER_ERROR);
        }
      }
      else {
        trigger_error('Could not connect to the ' . $myqslPrefix . 'backups table.<br />' . $mysqli->error,E_USER_ERROR);
      }
      break;

      case 'new':
      $dirs = listDirs();
      echo container('Create a New Backup','
<form action="manage_backups.php?action=new2" method="post">
  <div class="left">
    <label for="dir">Directory to be Backed Up:</label><br />
    ' . $dirs . '<br /><br />
  </div>
  <div class="right">
    ' . container('Help','<div id="help">This allows you to backup an entire directory to the database for easy download or restoration later.</div>') . '
  </div>
  <div class="full">
    <input name="submit" type="submit" value="Submit" tabindex="2" /><input name="reset" type="reset" value="Reset" tabindex="3" />
  </div>
</form>',0);
      break;

      case 'new2':
      $backupDir = formatDir($uploadDirectory . $_POST['dir']);
      if ($mysqli->real_query('INSERT INTO `' . $mysqlPrefix . 'backups` SET location = "' . $backupDir . '"')) {
        $id = $mysqli->insert_id;
        session_start();
        $_SESSION['id'] = $id;

        echo '<div class="right">' . container('Help','<div id="help">Backuping files is an incredibly server intensive process. When you are ready, press the button to run through the entire process. This will be done in sets of approx. 10 files at once. Note that you will need JavaScript enabled for this to work.</div>') . '</div>';
        // function listFiles($dir,$nameFilter = null,$extFilter = null,$hiddenFiles = null,$type = false,$recursive = false,$mode = false,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false)) {
        $files = listFiles($backupDir,null,null,null,false,true,false);
        $count = count($files);
        foreach ($files as $file) {
          if ($file['type'] == 'dir') continue;
          $i += 1;
          $onclick .= 'processFile("' . str_replace(array("'",'"'),array("&#39;",'\"'),$file['full']) . '",' . $i . ');';
        }
        echo '<script src="manage_backups.js"></script><button onclick=\'' . $onclick . '\'>Start Backup</button><br />Total Files Completed: <span id="processed"></span> of ' . $count . '<br /><br />Failed Files:<div id="bad"></div>';
      }
      break;

      case 'new3':
      $mysqli->query('SET GLOBAL max_allowed_packet = 17777216');
      $mysqli->autocommit(true);
      ini_set('memory_limit','50M');
      ini_set('max_execution_time','90');
      session_start();
      $id = $_SESSION['id'];

      $file = $_GET['file'];
      $fileData = @fileData(null,$file,array('mime' => true,'size' => true));
      if (is_file($fileData['full'])) {
        $mysqli->real_query('INSERT INTO `' . $mysqlPrefix . 'backupfiles` (backupid, name, mime, size) VALUES (' . $id . ', "' . $mysqli->real_escape_string($fileData['full']) . '", "' . $mysqli->real_escape_string($fileData['mime']) . '", ' . $fileData['size'][0] . ')');
        $fid = $mysqli->insert_id;

        $fp = fopen($fileData['full'], 'r');
        while (!feof($fp)) {
          $mysqli->query('INSERT INTO ' . $mysqlPrefix . 'backupcontents (contents, fileid) VALUES ("' . $mysqli->real_escape_string(fread($fp, 16777216)) . '", ' . $fid . ')');
        }
        header('Status: 1');
      }
      break;

      case 'restore':
      $backupid = $_GET['id'];
      echo container('WARNING','This will delete all existing data.',1);
      $data = @mysql_query('SELECT * FROM `' . $mysqlPrefix . 'backupcontents` WHERE backupid = ' . $backupid);
      while ($entry = @mysql_fetch_assoc($data)) {
        echo 1;
      }
      break;

      case 'delete':
      $backupid = $_GET['id'];
      $mysqli->autocommit(true);
      $mysqli->real_query('SELECT * FROM `' . $mysqlPrefix . 'backupfiles` WHERE backupid = ' . $backupid);
      if ($result = $mysqli->store_result()) {
        while ($row = $result->fetch_assoc()) {
          $mysqli->query('DELETE FROM `' . $mysqlPrefix . 'backupcontents` WHERE fileid = ' . $row['fileid']);
          $mysqli->query('DELETE FROM `' . $mysqlPrefix . 'backupfiles` WHERE fileid = ' . $row['fileid']);
        }
      }
      $mysqli->query('DELETE FROM `' . $mysqlPrefix . 'backups` WHERE backupid = ' . $backupid);
      if (!$mysqli->error) {
        header('Status: 1');
      }
      else {
        header('Status: 0'); echo $mysqli->error;
      }
      break;

      case 'view':
      $fileData = @fileData(null,$file,true,$get = array('backup' => false,'dot' => false,'size' => false,'lastMod' => false,'ext' => true,'name' => true,'owner' => false,'extFull' => true));
      break;
    }
  }
}

/* Document End */
if (!in_array($action,array('new3','view2','delete'))) {
  echo documentEnd();
}
?>