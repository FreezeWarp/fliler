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
  if (!$sqlConnect = @mysql_connect($mysqlHost,$mysqlUser,$mysqlPassword)) {
    echo container('Cannot Connect to MySQL','The MySQL database could not be connected to.',1);
  }
  elseif (!$sqlConnect = @mysql_select_db($mysqlDatabase)) {
    echo container('Cannot Connect to Database','The MySQL database "' . $mysqlDatabase. '" cannot be connected to. Please make sure it exists.',1);
  }
  else {
    switch($_GET['stage']) {
      case null:
      case 1:
        echo "<table border='1'>";
        echo '<tr><th>Username</th><th>Access Directory</th><th>Level</th><th>Password</th><th></th></tr>';
        $totalUsers = mysql_num_rows(mysql_query('SELECT * FROM `' . $mysqlPrefix . 'users`'));
        $query = mysql_query('SELECT `username`,`accessDirectory`,`accessLevel`,`password` FROM `' . $mysqlPrefix . 'users`');
        for ($count = 0; $count < $totalUsers; $count += 1) {
          $cells = mysql_fetch_assoc($query);
          echo '<tr><td><a href="javascript:var answer = prompt(\'Choose a New Username:\',\'' . $cells['username'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changename&user=' . $cells['username'] . '&newValue=\' + answer; }">' . (($cells['username']) ? $cells['username'] : '<i>null</i>') . '</a></td><td><a href="javascript:var answer = prompt(\'Choose an Access Directory:\',\'' . $cells['accessDirectory'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changeadir&user=$userID&newValue=\' + answer; }">' . (($cells['accessDirectory']) ? $cells['accessDirectory'] : '<i>Home</i>') . '</a></td></td><td><a href="javascript:var answer = prompt(\'What should the new access level be?\',\'' . $cells['accessLevel'] . '\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changealevel&user=' . $cells['username'] . '&newValue=\' + answer; }">' . (($cells['accessLevel']) ? $cells['accessLevel'] : '<i>null</i>') . '</a></td><td><a href="javascript:var answer = prompt(\'Choose a new password:\',\'\'); if(answer) { location.href=\'manage_users.php?stage=2&action=changepassword&user=' . $cells['username'] . '&newValue=\' + answer; }">Change Password</a></td><td><a href="javascript:var answer = confirm(\'Are you sure you want to delete this user?\'); if(answer) { location.href=\'manage_users.php?stage=2&action=delete&user=\'' . $cells['username'] . '\'; }"><img src="images/delete.png" /></a></td></tr>';
        }
        echo '</table><br /><a href="manage_users.php?stage=2&action=new"><img style="padding-right: 20px;" src="images/add-user.png" />Add a New User</a> | <a href="index.php">Return to Index</a>';
      break;
      case 2:
      $user = $_GET['user'];
      $newValue = $_GET['newValue'];
      switch($_GET['action']) {
        case 'changename':
          echo '<b>Change Username</b><br />';
          if (mysql_query('UPDATE `' . $mysqlPrefix . 'users` SET `username` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'changealevel':
          if (mysql_query('UPDATE `' . $mysqlPrefix . 'users` SET `accessLevel` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'changeadir':
          if (mysql_query('UPDATE `' . $mysqlPrefix . 'users` SET `accessDirectory` = "' . $newValue . '" WHERE `username` = "' . $user . '"')) {
            echo $user . ' was updated successfully.<br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
          }
          else {
            trigger_error('The query was unsuccessful; ' . mysql_error(),E_USER_ERROR);
          }
        break;
        case 'new':
          // Generate possible access levels.
          $levels = mysql_query('SELECT `id` FROM `' . $mysqlPrefix . 'levels`');
          while (false !== ($row = mysql_fetch_row($levels))) {
            $levelText .= '<option>' . $row[0] . '</option>';
          }
          echo '<form action="manage_users.php?stage=2&action=new2" method="post"><table><tr><td>Username</td><td><input type="text" name="username" /></td></tr><tr><td>Password</td><td><input type="password" name="password" /></td></tr><tr><td>Access Directory (Optional)</td><td><input type="text" name="accessDirectory" /></td></tr><tr><td>Access Level</td><td><select name="accessLevel">' . $levelText . '</select></td></tr></table><br /><br /><input type="submit" value="Add" /></form>';
        break;
        case 'new2':
          echo '<b>Add a New User</b><br />';
          // Generate a random string.
          $salt = randomString(5);
          if (mysql_query('INSERT INTO `' . $mysqlPrefix . 'users`
			   SET `username` = "' . $_POST['username'] . '",
			       `salt` = "' . $salt . '",
			       `password` = "' . md5(md5($_POST['password']) . $salt) . '",
			       `accessDirectory` = "' . $_POST['accessDirectory'] . '",
                               `accessLevel` = "' . $_POST['accessLevel'] . '"')) {
            echo 'The user has been successfully added with the following information:<br /><br /><table><tr><td>Username:</td><td>' . $_POST['username'] . '</td></tr><tr><td>Password:</td><td>' . $_POST['password'] . '</td></tr><tr><td>Access Directory</td><td>' . $_POST['accessDirectory'] . '</td></tr><tr><td>Access Level</td><td>' . $_POST['accessLevel'] . '</td></tr></table><br /><br /><a href="manage_users.php">Click here</a> to return to managing users.';
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