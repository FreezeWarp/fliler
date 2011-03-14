<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
require_once('uac.php');

/* Document Start */
echo documentStart('Manage Users');

/* Document Content */
if ($perm['MngUsrs']) {
  if (!mysqlConnect($mysqlHost,$mysqlUser,$mysqlPassword,$mysqlDatabase)) {
    echo container('Cannot Connect to MySQL','The MySQL database could not be connected to.',1);
  }
  else {
    switch($_GET['stage']) {
      case null:
      case 1:
        echo "<table border='1'>";
        echo '<tr><th>Username</th><th>Access Directory</th><th>Level</th><th>Password</th><th></th></tr>';
        $totalUsers = sqlArr('SELECT COUNT(*) AS count FROM `' . $mysqlPrefix . 'users`');
        $users = sqlArr('SELECT `username`,`accessDirectory`,`accessLevel`,`password` FROM `' . $mysqlPrefix . 'users`','username');

        foreach ($users AS $username => $user) {
          echo '<tr><td><a href="javascript:var answer = prompt(\'Choose a New Username:\',\'' . $user['username'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changename&user=' . $user['username'] . '&newValue=\' + answer; }">' . (($user['username']) ? $user['username'] : '<i>null</i>') . '</a></td><td><a href="javascript:var answer = prompt(\'Choose an Access Directory:\',\'' . $user['accessDirectory'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changeadir&user=' . $user['username'] . '&newValue=\' + answer; }">' . (($user['accessDirectory']) ? $user['accessDirectory'] : '<i>Home</i>') . '</a></td></td><td><a href="javascript:var answer = prompt(\'What should the new access level be?\',\'' . $user['accessLevel'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changealevel&user=' . $user['username'] . '&newValue=\' + answer; }">' . (($user['accessLevel']) ? $user['accessLevel'] : '<i>null</i>') . '</a></td><td><a href="javascript:var answer = prompt(\'Choose a new password:\',\'\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changepassword&user=' . $user['username'] . '&newValue=\' + answer; }">Change Password</a></td><td><a href="javascript:var answer = confirm(\'Are you sure you want to delete this user?\'); if(answer) { location.href=\'manage_users.php?stage=2&action=delete&user=\'' . $user['username'] . '\'; }"><img src="images/delete.png" /></a></td></tr>';
        }
        echo '</table><br /><a href="manage_users.php?stage=2&action=new"><img style="padding-right: 20px;" src="images/add-user.png" />Add a New User</a> | <a href="index.php">Return to Index</a>';
      break;

      case 2:
      $user = mysqlEscape($_GET['user']);
      $newValue = mysqlEscape($_GET['newValue']);
      switch($_GET['action']) {
        case 'changename':
          echo '<b>Change Username</b><br />';
          if (mysqlQuery('UPDATE `' . $mysqlPrefix . 'users` SET `username` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'changealevel':
          if (mysqlQuery('UPDATE `' . $mysqlPrefix . 'users` SET `accessLevel` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'changeadir':
          if (mysqlQuery('UPDATE `' . $mysqlPrefix . 'users` SET `accessDirectory` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        case 'changepassword': //echo md5(md5('test') . 'MBn63');
          if (mysqlQuery('UPDATE `' . $mysqlPrefix . 'users` SET `password` = md5(concat(md5("' . trim($newValue) . '"),salt)) WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'new':
          // Generate possible access levels.
          $levelText = mysqlReadThrough(mysqlQuery('SELECT `id` FROM `' . $mysqlPrefix . 'levels`'),'<option>$id</option>');
          echo '<form action="manage_users.php?stage=2&action=new2" method="post"><table><tr><td>Username</td><td><input type="text" name="newusername" /></td></tr><tr><td>Password</td><td><input type="password" name="newpassword" /></td></tr><tr><td>Access Directory (Optional)</td><td><input type="text" name="newaccessDirectory" /></td></tr><tr><td>Access Level</td><td><select name="newaccessLevel">' . $levelText . '</select></td></tr></table><br /><br /><input type="submit" value="Add" /></form>';
        break;
        case 'new2':
          echo '<b>Add a New User</b><br />';
          // Generate a random string.
          $salt = randomString(5);
          if (mysqlQuery('INSERT INTO `' . $mysqlPrefix . 'users`
			   SET `username` = "' . mysqlEscape($_POST['newusername']) . '",
			       `salt` = "' . $salt . '",
			       `password` = "' . md5(md5($_POST['newnpassword']) . $salt) . '",
			       `accessDirectory` = "' . mysqlEscape($_POST['newaccessDirectory']) . '",
                               `accessLevel` = "' . intval($_POST['newaccessLevel']) . '"')) {
            echo 'The user has been successfully added with the following information:<br /><br /><table><tr><td>Username:</td><td>' . $_POST['newusername'] . '</td></tr><tr><td>Password:</td><td>' . $_POST['newpassword'] . '</td></tr><tr><td>Access Directory</td><td>' . $_POST['newaccessDirectory'] . '</td></tr><tr><td>Access Level</td><td>' . $_POST['newaccessLevel'] . '</td></tr></table><br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            echo 'The user could not be added. <a href="manage_users.php">Click here</a> to return to managing users.';
          }
      }
    break;
    }
  }
}

/* Document End */
echo documentEnd();
?>