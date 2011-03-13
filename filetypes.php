<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

require_once('uac.php');
echo documentStart('File Type Database');
if ($valid) {
  if (!@mysql_connect($mysqlHost,$mysqlUser,$mysqlPassword)) {
    trigger_error('Could not connect to MySQL.',E_USER_ERROR);
  }
  elseif (!@mysql_select_db($mysqlDatabase)) {
    trigger_error('Could not select MySQL database.',E_USER_ERROR);
  }
  else {
    // Define the limit.
    $limit = (($_GET['limit']) ? $_GET['limit'] : 10000);

    // Define the sort.
    if (!in_array($_GET['sort'],array('ext','commonness','mime','description','type'))) {
      $sort = 'ext';
    } else { $sort = $_GET['sort']; }

    // Define the sort order.
    switch ($_GET['sortOrder']) {
      case 'asc':
      $sortOrder = 'ASC';
      break;
      case 'desc':
      $sortOrder = 'DESC';
      break;
      default:
      $sortOrder = 'ASC';
      break;
    }

    $contains = $_GET['contains'];

    if (!$filetypes = @mysql_query('SELECT * FROM ' . $mysqlPrefix . 'filetypes ' . (($contains) ? ' WHERE ext LIKE "%' . mysql_real_escape_string($contains) . '%" ' : '') . 'ORDER BY ' . $sort . ' ' . $sortOrder . ' LIMIT ' . $limit)) { echo mysql_error();
      trigger_error('The filetypes table does not exist.',E_USER_ERROR);
    }
    else {
      echo '<form method="get" action="filetypes.php"><table class="generic"><tr><th>Contains</th><td><input type="text" name="contains" value="' . $_GET['contains'] . '" /></td></tr><tr><th>Sort By</th><td><select name="sort"><option value="ext">File Extension</option><option value="type">File Class</option><option value="description">Description</option><option value="mime">Mime Type</option><option value="commonness">Commonness</option></select></td></tr><tr><th>Sort Order</th><td><select name="sortOrder"><option value="asc">Ascending</option><option value="desc">Descending</option></select></td></tr><tr><th>Limit</th><td><input type="text" name="limit" value="' . $_GET['limit'] . '" /></td></tr><tr><td colspan="2" align="center"><input type="submit" value="Look-Up" /></table></form><br /><table class="generic"><tr><th>File Extension</th><th>File Class</th><th>Description</th><th>Mime Type</th><th>Commonness</th></tr>';
      while (false !== ($type = @mysql_fetch_assoc($filetypes))) {
        echo '<tr><td>' . $type['ext'] . '</td><td>' . $type['type'] . '</td><td>' . $type['description'] . '</td><td>' . $type['mime'] . '</td><td>' . $type['commonness'] . '</td></tr>';
      }
      echo '</table>';
    }
  }
}
echo documentEnd();
?>