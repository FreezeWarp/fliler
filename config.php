<?php
/* Copyright (c) 2010 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Configuration File
 * This is where you can customize how Fliler acts as well as different other mechanisms.
 * There are several passages and variables, each which will hopefully be easy to figure out. When first installing, you must define $uploadDirectory, $installLocation, $uploadUrl, $mysqlHost, $mysqlUser, $mysqlPassword, $mysqlDatabase, and $mysqlPrefix.
 * If you do not have MySQL available, a single login can be supplied using $nomysqlUsername and $nomysqlPassword. These can pose a security risk, so should ideally be set to false if possible.
 * This script does contain sensitive information. Make sure that the core server can read and only read this file, preferrably by change its permissions to 400, or read for the server and nothing for everybody else.
 * Starting with Beta 5, the default Captcha was replaced with Recaptcha. It is not required that you provide these details, but it will increase security. Registration for a key is free and easy at recaptcha.net.
 * The last entries of this file will include everything else that is needed. Do not change these. */

/*** INSTALLATION INFORMATION ***/

/* Base Directory for Uploads
 * Users will not be able to access files below this directory. For full security (since this is never perfect), define a basedir in PHP.ini that matches this.
 * Example: '/home/$user/public_html/'
 * Example: '/var/www/' */
$uploadDirectory = '/var/www/';

/* The Location of the Fliler Installation
 * This is where Fliler is installed, such as the index.php file and lib/ directory.
 * Example: '/home/$user/public_html/'
 * Example: '/var/www/' */
$installLocation = '/var/www/uploadb6/';

/* Upload Client URL
 * The URL from which Fliler will be accessed; the clientside location of $installLocation. Must include http://.
 * Example: "http://localhost/"
echo $mode;
 * Leave blank if managing files that are not web accessible. */
$uploadUrl = 'http://localhost/';

/* Binary Path
 * This defines where binaries exist for use in Fliler. Abiword is most important, and is recommended to be installed. 
 * '/usr/bin/' means abiword is located at '/usr/bin/abiword'. */
$binaryPath = '/usr/bin/';

/* Temporary Path
 * This defines where temporary files will be stored.
 * '/tmp/' is best if it is available. The directory must also already exist. */
$tmpPath = '/tmp/';

/* Temporary Path Local
 * This defines where temporary files will be stored if they must be accessed by the browser.
 * The directory should not contain a start slash and will be referenced from both $uploadUrl and $uploadDirectory. */
$tmpPathLocal = '.temp/';

/* Blowfish
 * This is required for encrypted cookies. */
$blowfish = 'Love';



/*** MYSQL INFORMATION ***/

/* MySQL Host
 * The host for MySQL; if local then it should be "localhost", otherwise it is usually an IP address. */
$mysqlHost = 'localhost';

/* MySQL User
 * A MySQL user to be used for Fliler. It will need table creation privilidges if you are going to use the install.php script. */
$mysqlUser = 'root';

/* MySQL Password
 * The password for the above user. */
$mysqlPassword = 'W1nt3rDr3@m*';

/* MySQL Database
 * The database that will be used for Fliler. */
$mysqlDatabase = 'fliler';

/* MySQL Table Prefix
 * The prefix to all tables. */
$mysqlPrefix = '';

/* Non-MySQL Login
 * If MySQL is broken, this user can still login. They will have full permissions. Keep in mind, though, that this user can not login if MySql is working. */
$nomysqlUsername = 'Administrator';
$nomysqlPassword = 'Fr33d0m*';



/*** ReCAPTCHA ***/

/* The public key and the private key can be obtained at recaptcha.net. */
$recaptchaPublicKey = '6Ldy-AsAAAAAAAuW-194L_wwQdIE5zBo1sOxhqF8';
$recaptchaPrivateKey = '6Ldy-AsAAAAAAHqgIodtOCHFeQVmka2QGOTSd5Wx';



/*** Error Display ***/
/* Show Sensitive Information
 * When true, errors will include more advanced information, such as the file location and line numbers
 * Default: false.  */
$errorsDetailed = true;

/* Display Common Notices
 * Fliler has several PHP "notices", generally due to less than perfect coding (like using if ($var) on an undefined variable to see whether or not its undefined). This are best hidden, but if you want to display these common notices, set this to true. Note that these notices display so frequently that file downloads may not work.
 * Default: false. */
$errorsCommon = false;

/* Display Implementation Warnings
 * This are warnings that display when something is wrong with your Fliler installation. For development, it can make more sense to display these outright.
 * Default: true. */
$errorsInstall = true;

/* Buffer Errors
 * The error function by default will echo errors using the container when they occur. By using the output buffer, they can instead be joined categorically be the type of error, and displaying them at a certain point in the document.
 * Default: true. */
$errorsBuffered = true;

/* Buffer Errors Placement
 * Where errors for both of the above should be placed.
 * 'docstart' defines immediately beginning any other output. This is not XHTML compliant, but is used anyway if certain conditions can not be met (like no body tag).
 * 'bodyopen' defines after the HTML <body>.
 * 'bodyclose' defines before the HTML </body>.
 * 'header' defines after the H1 heading tag used throughout.
 * 'contentstart' defines after the content div. This means that it will collapse with the rest of the content. This is generally recommended. Default.
 * 'contentend' defines before the end content div. */
$errorsBufferedPlacement = 'contentstart';


/*** CUSTOMIZATION ***/

/* File Manager Branding
 * Because many people don't want the "Fliler" branding, you can change it to your own preferred name. Will not be used on installation scripts, copyright, ect. May be removed. */
$branding = 'Fliler';

/* Directory Select
 * This is used to define whether a directory select function should be used. This can be incredibly useful, but does have some drawbacks. 
 * True, 'auto_generate' - Generate a drop-down each time. This will eliminate the need for memorizing directory names.
 * 'ajax' - Will use Ajax to list known directories when typing.
 * 'cache' - Generates the directory tree from an existing cache. This is not as slow, but does have some drawbacks.
 * False - Use plain input. */
$directorySelect = true;

/* File Select
 * Used to specify whether HTTP requests should be used for fileselects as well. Unlike earlier implementations, this is relatively fast and stable in modern browsers.
 * True, 'auto_generate' - Use text drop-down (requires Javascript, HTTP request support.) Default.
 * 'ajax' - Will use Ajax to list known files when typing.
 * False - Use plain input. */
$fileSelect = true;

/* Select Max Depth
 * This is the deepest directory to be listed when displaying files recursively.
 * Use zero to disable recursion.
 * Default: 5. */
$selectMaxDepth = 10;

/* Container Type
 * This is used to define the container type to give basic functionality without editing the container() function.
 * 'fieldset' defines the default container, which uses HTML <fieldset>s. It is better supported when JQuery is used, as well. Default.
 * 'table' defines an HTML table. It suffers from some Jquery problems, but otherwise works well. */
$containerType = 'table';

/* Enable Browser Detection
 * Determines whether to check for specific versions of possibly incompatible browsers.
 * If on, the below setting for Jquery will be forced on if the user is detected using either IE6 or IE7. */
$enableBrowserDetection = true;

/* Enable JQuery
 * JQuery is an amazing Javascript library and has many effective uses. However, Fliler is built to be light on the browser, and JQuery is anything but. As such, all features should work without JQuery, but won't be as pretty, and there will be a few more bugs. By default, it is recommended that you turn this on.
 * Default: true.
 * Note: Consider for removal. */
$enableJquery = true;

/* Static Expires
 * Some static pages are those that are run by PHP but never really change (especially between browsers). This is the time in seconds that a static page should expire in to prevent loading it too much.
 * Note: this is not used currently. Consider for removal. */
$staticExpires = 60 * 60 * 24 * 30;

/* Create Backups
 * Certain actions will make a backup incase something went wrong. These can then be cleared out or restored later.
 * false or 0 will not create backups.
 * true or 1 will create backups. Default.
 * 2 will force backups; that is, if a backup could not be made, the script will not continue.
 * Note: Will most likely be replaced with database. Consider for removal/replacement. */
$createBackups = 2;


/*** SECURITY ***/

/* Locked Files
 * Here are the files that can not be edited in any way by any user.
 * Files should be stored in an array relative of the upload directory.
 * By default, config.php is the only one included.
 * Note: this is considerably untested right now. */
$lockedFiles = array(
  $installLocation . 'config.php',
  $installLocation . 'index.php',
  $installLocation . 'lib.php',
  $uploadDirectory . $tmpPathLocal,
);

/* Alter SQL Packet Size on Backups
 * If for security reasons, safe mode restrictions, or otherwise you want to disable the c*/
 
$backupsSqlPacketSize = true;



/*** VIEW DIRECTORY ***/

/* Hide Dot Files (These Files Start w/ a '.') */
$hideDotFiles = true;

/* Memory Safe Directory Lookup
 * If true, the directory search will attempt to end if you are near an end to PHP memory. */
$memorySafety = true;

/* Ignore Backup Syntax
 * Many Unix environments use "~" to denote backup files. For instance config.php~ or config.php~32 may mean a certain backup revision of config.php. As such, the file name should be recognized as "config" and the extension as "php". However, many environments, most notably Windows, do not use this syntax, and it may be wise to disable it in case it causes the user unexpected results. */
$ignoreBackupSyntax = true;



/*** INCLUDES ***/

/* Content Containers
 * These function will be used universally for content rendering. DO NOT change them unless you know what you're doing. */

// Note: PHP 5.3 introduced many key concepts that make a lot of sense. While the use of MySQLi among other things requires PHP 5.0, 5.3 may be required soon (actually, 5.0 isn't guarenteed to work now, either).
if (floatval(PHP_VERSION) < 5) {
  die('Wrong PHP version installed. Please upgrade to PHP version 5.0 or higher.');
}

// Initialize pre-error handler stuff. This will be re-written in OOP soon so its not quite so ugly.
$errors = array(
  E_USER_ERROR => '',
  E_USER_WARNING => '',
  E_USER_NOTICE => '',
  E_ERROR => '',
  E_WARNING => '',
  E_NOTICE => '',
  E_STRICT => '',
  E_DEPRECATED => '',
);

// Require all key functions of the script.
require('lib.php');

// Set the error handler.
set_error_handler("errorHandler");

// Start output buffering.
ob_start('callback');

// Output headers.
header('X-UA-Compatible: IE=8');
header('Content-Type: text/html;charset=UTF-8');
header('Content-Script-Type: text/javascript');
header('Content-Style-Type: text/css');
?>
