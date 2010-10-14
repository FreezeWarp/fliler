<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

require_once('config.php');
require_once('uac.php');
if (file_exists('.recaptcha/recaptchalib.php')) {
  require_once('.recaptcha/recaptchalib.php');
  $publickey = $recaptchaPublicKey;
  $captcha = recaptcha_get_html($publickey);
  $recaptchaValid = true;
}

/* Document Start */
echo documentStart($branding . ' Login');

/* Document Content */
echo container((($valid === 0) ? 'Login Required!' : 'Login Required - Access Denied!'),(($nopassword) ? '<b>You did not enter a password.</b>' : '') . '<form method="post" name="login" id="login" action="' . $_SERVER['PHP_SELF'] . '">
  <label for="username">Please enter your username:</label><br />
  <input type="text" name="username" id="username" /><br /><br />
  <label for="password">Please enter your password:</label><br />
  <input type="password" name="password" id="password" /><button type="button"
onClick="getElementById(\'password\').type=\'text\';">Show Password</button><br
/><br />' . (($valid === 0) ? '' : '<hr />' . ((!$recaptchaValid) ? '<b>You entered the captcha incorrectly.</b><br />' : '') . '<script type="text/javascript">
var RecaptchaOptions = {
   theme : \'clean\'
};
</script>' . $captcha . '
  <input type="hidden" name="captcha" value="set" />
  <input type="hidden" name="setcookie" value="1" />') . '
  <input type="submit" name="submit" value="Submit" /><input name="reset" type="reset" value="Reset" />
  <input type="hidden" name="setcookie" value="1" />
</form>
<hr />
<small>To clear incorrect cookies, <a href="logout.php">click here</a>.</small>',1);

/* Document End */
echo documentEnd();
?>