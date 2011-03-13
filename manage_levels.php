<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
require_once('uac.php');

/* Document Start */
echo documentStart('Manage Levels');

/* Document Content */
if ($perm['MngLvls']) {
  if (!mysql_connect($mysqlHost,$mysqlUser,$mysqlPassword)) {
    trigger_error('MySQL could not be conncted to.',E_USER_ERROR);
  }
  elseif (!mysql_select_db($mysqlDatabase)) {
    trigger_error('The MySQL database "' . $mysqlDatabase . '" could not be accessed.',E_USER_ERROR);
  }
  else {
    switch($_GET['do']) {
      // Show Levels
      case null:
      case 'view':
      $totalLevels = mysql_num_rows(mysql_query('SELECT * FROM `' . $mysqlPrefix . 'levels`'));
      echo '<b>View Levels</b><br /><table class="generic"><tr><th>Level</th><th>Create Files</th><th>Move Files</th><th>Edit Files</th><th>Chmod Files</th><th>Delete Files</th><th>Create Dirs</th><th>Move Dirs</th><th>Chmod Dirs</th><th>Delete Dirs</th><th>View</th><th>Manage Users</th><th>Manage Bckps</th><th>Manage Levels</th></tr>';
      $query = mysql_query('SELECT * FROM `' . $mysqlPrefix . 'levels');
      for ($count = 0; $count < $totalLevels; $count += 1) {
        $cells = mysql_fetch_assoc($query);
        echo '<tr><td><b>' . $cells['id'] . '</b></td><td><a href="manage_levels.php?do=edit&value=MkF&row=' . $cells['id'] . '">' . boolOutput($cells['MkF']) . '</a></td><td><a href="manage_levels.php?do=edit&value=MvF&row=' . $cells['id'] . '">' . boolOutput($cells['MvF']) . '</a></td><td><a href="manage_levels.php?do=edit&value=EdF&row=' . $cells['id'] . '">' . boolOutput($cells['EdF']) . '</a></td><td><a href="manage_levels.php?do=edit&value=ChmdF&row=' . $cells['id'] . '">' . boolOutput($cells['ChmdF']) . '</a></td><td><a href="manage_levels.php?do=edit&value=RmF&row=' . $cells['id'] . '">' . boolOutput($cells['RmF']) . '</td><td><a href="manage_levels.php?do=edit&value=MkD&row=' . $cells['id'] . '">' . boolOutput($cells['MkD']) . '</td><td><a href="manage_levels.php?do=edit&value=MvD&row=' . $cells['id'] . '">' . boolOutput($cells['MvD']) . '</td><td><a href="manage_levels.php?do=edit&value=ChmdD&row=' . $cells['id'] . '">' . boolOutput($cells['ChmdD']) . '</td><td><a href="manage_levels.php?do=edit&value=RmD&row=' . $cells['id'] . '">' . boolOutput($cells['RmD']) . '</td><td><a href="manage_levels.php?do=edit&value=View&row=' . $cells['id'] . '">' . boolOutput($cells['View']) . '</td><td><a href="manage_levels.php?do=edit&value=MngUsrs&row=' . $cells['id'] . '">' . boolOutput($cells['MngUsrs']) . '</td><td><a href="manage_levels.php?do=edit&value=MngBckps&row=' . $cells['id'] . '">' . boolOutput($cells['MngBckps']) . '</td><td><a href="manage_levels.php?do=edit&value=MngLvls&row=' . $cells['id'] . '">' . boolOutput($cells['MngLvls']) . '</td></tr>';
      }
      echo '</table><br /><br /><a href="manage_levels.php?do=add"><img src="images/add.png" style="padding-right: 20px;" />Add New Access Level</a> | <a href="index.php">Return to the Index</a>';
      break;
      // Edit Value
      case 'edit':
      echo '<b>Edit Value</b><br /><form action="manage_levels.php?do=edit2&value=' . $_GET['value'] . '&row=' . $_GET['row'] . '" method="post"><table><tr><td>Cell:</td><td>' . $_GET['value'] . '</td></tr><tr><td>Row:</td><td>' . $_GET['row'] . '</td></tr><tr><td>New Value:</td><td><select name="newValue"><option value="0">False</option><option value="1">True</option</select></td></tr></table><br /><input type="submit" value="Change" /></form>';
      break;
      // Process Editted Value
      case 'edit2':
      if (mysql_query('UPDATE `' . $mysqlPrefix . 'levels`
		       SET `' . $_GET['value'] . '` = "' . $_POST['newValue'] . '"
		       WHERE `id` = "' . $_GET['row'] . '"')) {
        echo 'The value was updated successfully.<br /><br /><a href="manage_levels.php">Return.</a>';
      }
      break;
      // Add New Value
      case 'add':
        echo '<form action="manage_levels.php?do=add2" method="post"><table><tr><td>Create Files (MkF): </td><td><select name="MkF"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Move Files (MvF): </td><td><select name="MvF"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Edit Files (EdF): </td><td><select name="EdF"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Change Permissions of Files (ChmdF): </td><td><select name="ChmdF"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Remove Files (RmF): </td><td><select name="RmF"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Create Directories (MkD): </td><td><select name="MkD"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Move Directories (MvD): </td><td><select name="MvD"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Change Permissions of Directories (ChmdD): </td><td><select name="ChmdD"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Remove Directories (RmD): </td><td><select name="RmD"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>View Files and Directories (View): </td><td><select name="View"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Manager Users (MngUsrs): </td><td><select name="MngUsrs"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Manage Backups (MngBckps): </td><td><select name="MngBckps"><option value="0">False</option><option value="1">True</option></select></td></tr><tr><td>Manage Levels (MngLvls): </td><td><select name="MngLvls"><option value="0">False</option><option value="1">True</option></select></td></tr></table><br /><input type="submit" value="Add" /></form>';
      break;
      case 'add2':
      if (mysql_query('INSERT INTO `' . $mysqlPrefix . 'levels`
		       SET `MkF` = "' . ($_POST['MkF'] ? 1 : 0) . '",
			   `MvF` = "' . ($_POST['MvF'] ? 1 : 0) . '",
			   `EdF` = "' . ($_POST['EdF'] ? 1 : 0) . '",
			   `RmF` = "' . ($_POST['RmF'] ? 1 : 0) . '",
			   `ChmdF` = "' . ($_POST['ChmdF'] ? 1 : 0) . '",
			   `MkD` = "' . ($_POST['MkD'] ? 1 : 0) . '",
			   `MvD` = "' . ($_POST['MvD'] ? 1 : 0) . '",
			   `RmD` = "' . ($_POST['RmD'] ? 1 : 0) . '",
			   `ChmdD` = "' . ($_POST['ChmdD'] ? 1 : 0) . '",
			   `View` = "' . ($_POST['View'] ? 1 : 0) . '",
			   `MngUsrs` = "' . ($_POST['MngUsrs'] ? 1 : 0) . '",
			   `MngLvls` = "' . ($_POST['MngLvls'] ? 1 : 0) . '",
			   `MngBckps` = "' . ($_POST['MngBckps'] ? 1 : 0) . '"')) {
        echo 'The access level has been successfully added.<br /><br /><a href="manage_levels.php">Return.</a>';
      }
      else {
        echo 'The MySQL quey was not successful.<br /><br /><a href="manage_levels.php">Return</a>';
      }
      break;
    }
  }
}

/* Document End */
echo documentEnd();
?>