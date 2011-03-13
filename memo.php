<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
require_once('uac.php');

/* Document Start */
echo documentStart('Personal Memo');

/* Document Content */
if ($valid == 1) {
  switch($_POST['stage']) {
    case 1:
    case 'view':
    case null:
      echo '<form method="post" action="memo.php"><textarea name="contents" style="height: 400px; width: 100%;">' . $user['memo'] . '</textarea><br /><br /><input type="submit" value="Update" /><input type="reset" value="Reset" /><input type="hidden" name="stage" value="2" /></form>';
    break;
    case 2:
    case 'save':
      $contents = $_POST['contents'];
      mysql_connect($mysqlHost,$mysqlUser,$mysqlPassword); mysql_select_db($mysqlDatabase);
      if (mysql_query('UPDATE `' . $mysqlPrefix . 'users` SET `memo` = "' . mysql_real_escape_string($contents) . '" WHERE `username` = "' . $user['username'] . '"')) {
        echo container('Memo Updated','The memo has been successfully updated. <a href="memo.php?stage=1">Click here</a> to return to it.',0);
      }
    break;
  }
}

/* Document End */
echo documentEnd();
?>