<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Backend Procedure */
if ($_REQUEST['process'] == true) {
  switch ($_REQUEST['processStage']) {
    case 'mysql':

    break;

    case 'config':
    // Create Config File Structure
    if (file_exists('config.php')) {
      rename('config.php','config.php~' . time());
    }
    break;
  }
}

$stopLogin = true;
require('config.php');

echo documentStart('Fliler Beta 6 Installation');
?>

<?php if (file_exists('config.php')) echo '<b><span class="alert1">Warning</span>: A config.php file already exists. It will be automatically backed up if you choose to continue.</b><br /><br />'; ?>

<div id="install">

</div>

<script type="text/javascript">
var home = '<?php echo jsEscape(container('Welcome to Fliler. Fliler is a high-quality PHP file manager that can do everything like it\'s nothing. Here you will be guided through the installation. First, how shall we start?','<ul><li><a href="javascript:void(0);" onclick="$(\'#install\').fadeOut(300,function() { $(\'#install\').html(guided1).fadeIn(500) });"><b>Guided Installation</b></a></li><li><a href="help.php#no-install-php">Manual</a></li></ul><br /><br />Or, if you have no clue what you just downloaded, <a href="help.php">check out the help page</a>.')); ?>';

var guided1 = '<?php echo jsEscape(container('Guided Installation','Before we can do a guided installation, how would you prefer to handle logins?:<br /><br /><ul><li><a href="javascript:void(0);" onclick="$(\'#install\').fadeOut(300,function() { $(\'#install\').html(guided_norm1).fadeIn(500) });">Normal Login (Recommended)</a></li><li><a href="javascript:void(0);" onclick="$(\'#install\').fadeOut(300,function() { $(\'#install\').html(guided_nomysql1).fadeIn(500) });">No-MySQL Login (Not Recommended; Only One User Account Possible)</a></li></ul>')); ?>';

var guided_norm1 = '<?php echo jsEscape(container('Guided Installation: Normal Login','<b>MySQL Login</b><hr /><table><tr><td><label for="mysql_user">MySQL User:</label></td><td><input type="text" name="mysql_user" id="mysql_user" /></td></tr><tr><td><label for="mysql_password">MySQL Password:</label></td><td><input type="password" name="mysql_password" id="mysql_password" /><input type="button" onclick="document.getElementById(\'mysql_password\').type = \'text\';" value="Show" /></td></tr><tr><td><label for="mysql_host">MySQL Host:</label></td><td><input type="text" name="mysql_host" id="mysql_host" value="localhost" /></td></tr><tr><td><label for="mysql_db">MySQL Database:</label></td><td><input type="text" name="mysql_db" id="mysql_db" value="fliler" /></td></tr><tr><td><label for="mysql_prefix">MySQL Prefix:</label></td><td><input type="text" name="mysql_prefix" id="mysql_prefix" /></td></tr></table><br /><br /><b>Fliler Login</b><hr /><table><tr><td><label for="fliler_username">Fliler Username:</label></td><td><input type="text" name="fliler_username" id="fliler_username" /></td></tr><tr><td><label for="fliler_password">Fliler Password:</label></td><td><input type="password" name="fliler_password" id="fliler_password" /><input type="button" onclick="document.getElementById(\'fliler_password\').type = \'text\';" value="Show" /></td></tr></table><br /><br /><b>Server Paths</b><hr /><table><tr><td><label for="uploadDir">Upload Directory</label></td><td><input type="text" name="uploadDir" id="uploadDir" /></td></tr><tr><td><label for="uploadUrl">Upload URL</label></td><td><input type="text" name="uploadUrl" id="uploadUrl" /></td></tr><tr><td><label for="installLocation">Install Location</label></td><td><input type="text" name="installLocation" id="installLocation" value="' . getcwd() . '" /></td></tr><tr><td><label for="binPath">Binary Executable Path</label></td><td><input type="text" name="binPath" id="binPath" value="/usr/bin/" /></td></tr></table><br /><br /><b><a href="javascript:void();" onclick="$(\'#extra\').slideToggle();"><span id="arr"></span>Extra Details (Click)</a></b><hr /><div id="extra" style="display:none;"><table><tr><td><label for="blowfish">Blowfish Secret:</label> </td><td><input type="text" name="blowfish" id="blowfish" value="' . randomString(4) . '" /></td></tr><tr><td><label for="captcha_public">reCaptcha Public Key:</label> </td><td><input type="text" name="captcha_public" id="captcha_public" /></td></tr><tr><td><label for="captcha_private">reCaptcha Private Key:</label> </td><td><input type="text" name="captcha_private" id="captcha_private" /></td></tr><tr><td><label for="buffer_errors">Buffer Errors:</label></td><td><input type="checkbox" checked="checked" name="buffer_errors" id="buffer_errors" /></td></tr><tr><td><label for="directorySelect">Pre-Populate Directory Select:</label></td><td><input type="checkbox" checked="checked" name="directorySelect" id="directorySelect" /></td></tr><tr><td><label for="fileSelect">AJAX File Select:</label></td><td><input type="checkbox" checked="checked" name="fileSelect" id="fileSelect" /></td></tr><tr><td><label for="container">Container Type:</label></td><td><select name="container"><option value="fieldset" selected="selected">Fieldset</option><option value="table">Table</option></select></td></tr></table></div><br /><br /><b><a href="javascript:void(0);" onclick="">Create the Database and User &rarr;</a></b>')); ?>';

var guided_nomysql1 = '<?php echo jsEscape(container('Guided Installation: MySQLless Installation','<b>Login</b><hr /><table><tr><td><label for="fliler_username">Username:</label></td><td><input type="text" name="fliler_username" id="fliler_username" /></td></tr><tr><td><label for="fliler_password">Password:</label></td><td><input type="password" name="fliler_password" id="fliler_password" /><input type="button" onclick="document.getElementById(\'fliler_password\').type = \'text\';" value="Show" /></td></tr></table><br /><br /><b>Server Paths</b><hr /><table><tr><td><label for="uploadDir">Upload Directory</label></td><td><input type="text" name="uploadDir" id="uploadDir" /></td></tr><tr><td><label for="uploadUrl">Upload URL</label></td><td><input type="text" name="uploadUrl" id="uploadUrl" /></td></tr><tr><td><label for="installLocation">Install Location</label></td><td><input type="text" name="installLocation" id="installLocation" value="' . getcwd() . '" /></td></tr><tr><td><label for="binPath">Binary Executable Path</label></td><td><input type="text" name="binPath" id="binPath" value="/usr/bin/" /></td></tr></table><br /><br /><b><a href="javascript:void();" onclick="$(\'#extra\').slideToggle();"><span id="arr"></span>Extra Details (Click)</a></b><hr /><div id="extra" style="display:none;"><table><tr><td><label for="blowfish">Blowfish Secret:</label> </td><td><input type="text" name="blowfish" id="blowfish" value="' . randomString(4) . '" /></td></tr><tr><td><label for="captcha_public">reCaptcha Public Key:</label> </td><td><input type="text" name="captcha_public" id="captcha_public" /></td></tr><tr><td><label for="captcha_private">reCaptcha Private Key:</label> </td><td><input type="text" name="captcha_private" id="captcha_private" /></td></tr><tr><td><label for="buffer_errors">Buffer Errors:</label></td><td><input type="checkbox" checked="checked" name="buffer_errors" id="buffer_errors" /></td></tr><tr><td><label for="directorySelect">Pre-Populate Directory Select:</label></td><td><input type="checkbox" checked="checked" name="directorySelect" id="directorySelect" /></td></tr><tr><td><label for="fileSelect">AJAX File Select:</label></td><td><input type="checkbox" checked="checked" name="fileSelect" id="fileSelect" /></td></tr><tr><td><label for="container">Container Type:</label></td><td><select name="container"><option value="fieldset" selected="selected">Fieldset</option><option value="table">Table</option></select></td></tr></table></div><br /><br /><b><a href="javascript:void(0);" onclick="">Create the Database and User &rarr;</a></b>')); ?>';

$('#install').hide().html(home).fadeIn(500);
</script>

<noscript>
  <b>The installation can not proceed unless you have JavaScript enabled.</b>
</noscript>

<?php
echo documentEnd();
?>