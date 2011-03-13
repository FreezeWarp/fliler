<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

if (!is_file('config.php')) {
  header('location: install.php');
}

require_once('uac.php');

// Define early variables and clear their existing values (if any).
static $options;
$options = array(
  'fileManagement' => false,
  'administrative' => false,
);

/* Document Start */
echo documentStart($branding . ' Index');

$browser = new getBrowser;
$browser->getBrowserBit();
$browser->getVersionBit();

/* Document Content */
if ($valid == 1) {
  if ($perm['MkF']) { $options['fileManagement'] .= '<li><a href="create_file.php">Create a File</a></li><ul><li><a href="upload_file.php">Upload a File</a></li></ul>'; }
  if ($perm['MvF']) { $options['fileManagement'] .= '<li><a href="move_file.php">Move a File</a></li><ul><li><a href="rename_file.php">Rename a File</a></li></ul>'; }
  if ($perm['EdF']) { $options['fileManagement'] .= '<li><a href="edit_file.php">Edit a File</a></li><ul><li><a href="restore_backup.php">Restore a File Backup</a></li><li><a href="remove_backups.php">Remove File Backups</a></li></ul>'; }
  if ($perm['View']) { $options['fileManagement'] .= '<li><a href="download_file.php">Download a File</a></li>'; }
  if ($perm['ChmdF']) { $options['fileManagement'] .= '<li><a href="chmod_file.php">Chmod a File</a></li>'; }
  if ($perm['RmF']) { $options['fileManagement'] .= '<li><a href="delete_file.php">Delete a File</a></li>'; }
  if ($perm['MkD']) { $options['fileManagement'] .= '<li><a href="create_dir.php">Create a Directory</a></li>'; }
  if ($perm['MvD']) { $options['fileManagement'] .= '<li><a href="move_dir.php">Move a Directory</a></li>'; }
  if ($perm['ChmdD']) { $options['fileManagement'] .= '<li><a href="chmod_dir.php">Chmod a Directory</a></li>'; }
  if ($perm['RmD']) { $options['fileManagement'] .= '<li><a href="delete_dir.php">Delete a Directory</a></li>'; }
  if ($perm['View']) { $options['fileManagement'] .= '<li><a href="view_dir.php">View a Directory</a></li><ul><li><a href="view_file.php">View a File</a></li></ul>'; }
  if ($perm['View']) { $options['fileManagement'] .= '<li><a href="download_dir.php">Download a Directory</a></li>'; }
  if ($perm['MngUsrs']) { $options['administrative'] .= '<li><a href="manage_users.php">Manage Users</a></li>'; }
  if ($perm['MngBckps']) { $options['administrative'] .= '<li><a href="manage_backups.php">Manage Backups</a></li>'; }
  if ($perm['MngLvls']) { $options['administrative'] .= '<li><a href="manage_levels.php">Manage Levels</a></li>'; }
  if ($nomysqlLogin == true) {
    trigger_error('Unfortunately, a connection to MySQL could not be detected. However, your login credentials matched that of the Non-MySQL Login, so you will be granted full permissions, excluding Administrative permissions.',E_USER_NOTICE);
  }
  if ($errorsInstall) {
    if (get_magic_quotes_gpc()) {
      trigger_error('Magic Quotes is enabled. Certain functions may not work with magic quotes, now depreciated, enabled. Please refer to <a href="http://www.php.net/manual/en/security.magicquotes.disabling.php">the php manual</a> on how to disable these.',E_USER_WARNING);
    }
    if (ini_get('register_globals') == true) {
      trigger_error('Register Globals is enabled. Several security risks may exist with the "register_globals" option, depreciated in PHP 5.3.0, enabled. Please refer to <a href="http://php.net/manual/en/security.globals.php">the php manual</a> on how to disable these.',E_USER_WARNING);
    }
    if (ini_get('file_uploads') == false) {
      trigger_error('File uploads are disabled.',E_USER_WARNING);
    }
    if (file_exists('install.php')) {
      trigger_error('The file "install.php" has been detected on the server. This file, which can be used to help install Fliler, poses a great security risk if it remains after installation. It is highly recommended that you delete the file through FTP. Alternatively, it may be possible to delete it with the built in delete function.',E_USER_WARNING);
    }
    if (is_writable('config.php')) {
      trigger_error('The file "config.php" is writable by the server. It is highly recommended that you chmod the file 400 to prevent Fliler from being used maliciously by a third party.',E_USER_WARNING);
    }
  }
  echo container('Developer Memo: Beta 6','<ol>
  <li>Remove E_NOTICE errors (good practice).</li>
  <li>Change all SQL queries to MySQLi (may break some functionality during conversion).</li>
  <li>Add logging of all actions (support both MySQLi and off-site Log Files).</li>
  <li>Add full IE8 compatibility.</li>
</ol>
See <a href="./todo">todo</a> for more.',0);
  echo '<div style="float: left; width: 49%;">';
  echo container('File Management Tasks','<ol>' . $options['fileManagement'] . '</ol>',0);
  echo '</div><div style="float: right; width: 49%;">';
  echo container('Administrative Tasks','<ol>' . $options['administrative'] . '</ol>',0);
  echo container('Miscellaneous Tasks','<ul>
  <li><a href="memo.php">Edit My Memo</a></li>
  <li><a href="help.php">Get Help</a></li>
  <li><a href="filetypes.php">File Type Database</a></li>
  <li><a href="browser_support.php">Browser Information</a></li>
</ul>',0);
  echo '</div>';
}

echo documentEnd();
?>