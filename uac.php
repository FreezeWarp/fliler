<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* User Account Control
 * Validates users and manages their accounts.
 * Last modified 5/23/10 with improved cookie storage security. */

require_once('config.php');

// Define early variables and clear their existing values (if any).
static $username, $password, $nopassword, $error, $valid, $iv, $extwl, $accessLevel, $accessDirectory, $nomysqlLogin, $captcha;
if (isset($_POST['captcha'])) $captcha = $_POST['captcha'];

// Determine the username and password information based on either the $_POST or $_COOKIE data.
if ((isset($_POST['username'])) || (isset($_POST['password']))) {
  // No username was provided.
  if (!$_POST['username']) {
    $valid = false;
    $error = 'nouser';
  }
  // No password was provided.
  elseif (!$_POST['password']) {
    $valid = false;
    $error = 'nopass';
  }
  // We have both a username and password to work with.
  else {
    $username = $_POST['username'];
    $password = $_POST['password'];
  }
}

// No post data exists, but an existing login exists in the cookies.
elseif ((isset($_COOKIE['username'])) && (isset($_COOKIE['password']))) {
  // Have the cookies expired yet?
  if ($_COOKIE['expireDate'] > time()) {
    if ($blowfish) {
      $iv = $_COOKIE['iv'];
      $username = $_COOKIE['username'];
      $password = mcrypt_decrypt(MCRYPT_BLOWFISH, $blowfish, $_COOKIE['password'], MCRYPT_MODE_ECB,$iv);
    }
  }
  else {
    $valid = 0;
  }
}

// Login information exists, process it.
if ($username) {
  // This will appear if a catpcha was triggered before. We first check the Captcha before continuing.
  if ($captcha == 'set' && file_exists('.recaptcha/recaptchalib.php')) {
    require_once('.recaptcha/recaptchalib.php');
    $privatekey = $recaptchaPrivateKey;
    $resp = recaptcha_check_answer($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
    if (!$resp->is_valid) { $recaptchaValid = false; }
    else { $recaptchaValid = true; }
  }
  else { $recaptchaValid = true;  }

  // Only proceed if the captcha data was valid or not presented.
  if ($recaptchaValid) {
    // Try to establish a connection with MySQL.
    if (mysqlConnect($mysqlHost,$mysqlUser,$mysqlPassword,$mysqlDatabase)) {
      // Get the user information.
      $user = sqlArr('SELECT * FROM `' . $mysqlPrefix . 'users` WHERE `username` = "' . mysqlEscape($username) . '"');
      // If the password is valid.
      if (md5(md5($password) . $user['salt']) == $user['password']) {
        $valid = 1;
        $accessLevel = $user['accessLevel'];
        $accessDirectory = $user['accessDirectory'];
        $perm = sqlArr('SELECT * FROM `' . $mysqlPrefix . 'levels` WHERE `id` = "' . $accessLevel . '"');
        if ($perm['FileWhiteList']) {
          $extwl = explode(',',$perm['FileWhiteList']);
        }

        // Set the cookies to expire two days from now, to avoid possible localization bugs. We'll later check an expireDate cookie to see whether its still valid or not.
        $expire = time() + 30 * 60 * 24 * 2;

        // If we can, encrypt the password before storing it.
        if ($blowfish) {
          $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH,MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

          $password = mcrypt_encrypt(MCRYPT_BLOWFISH, $blowfish, $password, MCRYPT_MODE_ECB, $iv);
          setcookie('iv',$iv,$expire);
        }

        // Update the cookies with the added expiration time.
        setcookie('username',$username,$expire);
        setcookie('password',$password,$expire);
        setcookie('expireDate',time() + 30 * 60,$expire);
      }
      else { $valid = -1; }
      mysqlClose();
    }
    // If no connection could be establish, run through the nomysql login. Don't run through if $nomysqlUsername evaluates to false.
    else {
      if ($nomysqlUsername) {
        if (($username = $nomysqlUsername) && ($password = $nomysqlPassword)) {
          $valid = 1;
          $accessLevel = 3;
          $accessDirectory = '';
          $perm = array('MkF' => true,'MvF' => true,'EdF' => true,'ChmdF' => true,'MkD' => true,'MvD' => true,'ChmdD' => true,'View' => true);

          // Update the cookies.
          $expire = time() + 60 * 30 * 24 * 2;
          setcookie('username',$username,$expire);
          setcookie('password',$password,$expire);
          setcookie('expireDate',time() + 30 * 60,$expire);
        }
        else { $valid = -1; }
      }
      else { trigger_error('Could not connect to MySQL',E_USER_ERROR); }
    }
  }
  else {
    $valid = -1;
  }
}
else {
  $valid = 0;
}

if (($valid !== 1) && (!isset($stopLogin))) {
  require_once('uac2.php');
  ob_end_flush();
  die();
}
?>