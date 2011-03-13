<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

// Pre-processing
require_once('config.php');
if ((isset($_COOKIE['password'])) || (isset($_COOKIE['username']))) {
  $expire = time() - 60 * 30;
  setcookie('password', 'Removed', $expire);
  setcookie('username', 'Removed', $expire);
}
else {
  $nocookie = 1;
}

// Document Start
echo documentStart('Logout of' . $branding);

// Document Content
if ($nocookie == 1) {
  echo container('You Are Already Logged Out','You have already logged out, and no cookies currently exist for a login. Thank you for making sure you were logged out and keeping this server safe.<br /><br /><a href="index.php">Return to the Index</a>',0);
}
elseif (($_COOKIE['password'] === null) && ($_COOKIE['username'] === null)) {
  echo container('Logout Successful!','You have now logged out. You will need to login again before you can do any further tasks. Thank you for helping keeping this server secure by logging out instead of staying logged in.<br /><br /><a href="index.php">Return to the Index</a>',0);
}
else {
  echo container('Logout Successful!','You have almost logged out, though you will need to close your browser before the cookies will be fully removed. You will still need to login again before you can do any further tasks. Thank you for helping keeping this server secure by logging out instead of staying logged in.<br /><br /><a href="index.php">Return to the Index</a>',0);
}
echo '</div>';

echo documentEnd();
?>