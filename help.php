<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */
/* Pre-Processing */
require_once('config.php');

/* Document Start */
echo documentStart('Fliler Readme');

/* Document Content */
?>
<b>Yeah, the grammar is pretty bad, I know. It's being worked on.</b><br /><br />

<div id="content">
Fliler is a file manager designed to be light on browsers, while still being easy and powerful to use. It has many functions, and can be overwhelming at times. Use your browser's search function to run through this page, or check out the table of contents.
<h2>Table of Contents</h2>
<ol>
  <li><a href="#install">Installation</a></li>
  <ol>
    <li><a href="#req">Requirements</a></li>
    <li><a href="#install-php">Using Install.php</a></li>
    <li><a href="#no-install-php">Not Using Install.php</a></li>
  </ol>
  <li><a href="#php-config">PHP Config Options</a></li>
  <li><a href="#addons">Additional Software</a></li>
  <ol>
    <li><a href="#required">Required</a></li>
    <li><a href="#recommended">Recommended</a></li>
    <li><a href="#optional">Optional</a></li>
  </ol>
  <li><a href="#tips">Useful Knowledge</a></li>
  <ol>
    <li><a href="#hash">Hashes</a></li>
    <li><a href="#perm">User Permissions</a></li>
    <li><a href="#nomysql">No-MySQL Fallback</a></li>
    <li><a href="#libs">Libraries</a></li>
    <li><a href="#themes">Themes</a></li>
    <li><a href="#secure">Security</a></li>
    <li><a href="#view">View Interfaces</a></li>
    <li><a href="#edit">Edit Interfaces</a></li>
    <li><a href="#tables">Table Structure</a></li>
  </ol>
  <li><a href="#browser">Improving Your Browsing Experience</a></li>
  <ol>
    <li><a href="#support">Browser Support Notes</a></li>
    <li><a href="#priority">Priority</a></li>
  </ol>
  <li><a href="#api">API (WIP)</a></li>
  <li><a href="#copyright">Copyright</a></li>
  <ol>
    <li><a href="#copyright_fliler">Fliler</a></li>
    <li><a href="#copyright_flilerlib">Fliler Library</a></li>
    <li><a href="#copyright_jquery">jQuery</a></li>
    <li><a href="#copyright_geshi">geSHi</a></li>
    <li><a href="#copyright_recaptcha">reCAPTCHA</a></li>
  </ol>
</ol><br />

<h2 id="install">Installation</h2>
<div style="padding-left: 50px;">
  <h3 id="req">Requirements</h3>
  Fliler should work in both IIS and Unix environments with PHP 5.0 or greater, with at the very least the <a href="http://php.net/manual/en/book.mcrypt.php">mcrypt</a>, <a href="http://php.net/manual/en/book.fileinfo.php">FileInfo, and <a href="http://www.php.net/manual/en/book.zip.php">Zip</a> extensions. MySQL 5.0 or greater (and the MySQLi extension) is also strongly recommended, though basic tasks should still work if you use the <a href="#nomysql">No-MySQL Fallback</a>. See the <a href="#addons">add-ons</a> section for additional optional software that plays nice with Fliler.<br /><br />

  Clients wishing to use Fliler will need a <a href="#browser">Javascript and CSS-capable browser</a>. Firefox and Google Chrome are most recommended, though Internet Explorer, Safari, Opera, and Konqueror should also work.<br /><br />

  More detailed numbers are:<br />
  <ol>
    <li>2MB Memory Minimum, 16MB Recommended</li>
    <ol>
      <li>Directory list population and the directory viewer together take the most memory. Only 1MB usually is needed if you don't plan on using these extensions.</li>
      <li>Additionally, for backups to work best they should run with 16~32MB of RAM, however this is only required for larger files.</li>
    </ol>
    <li>5MB of Free Space</li>
    <ol>
      <li>Without reCAPTCHA, CkEditor, and GeSHi only 2MB are required. These extensions aren't needed for the basic tasks, but are still very useful.</li>
      <li>With Batik and AbiWord, tools that are used for editing and viewing different types of documents, an additional 20~50MB are required.</li>
      <li>For actual file management, this number is obviously much higher.</li>
    </ol>
  </ol>

  <h3 id="install-php">Using Install.php</h3>
  <a href="install.php">Install.php</a> is a quick and easy way to install Fliler, and takes only a matter of minutes. There are four approaches you can choose with install.php: manually create the config.php and install the databases with the script, generate the config.php for a simple no-mysql installation, generate the config.php for a standard mysql installation, or generate a full config.php. Generally, the first method is recommended as it gives you all the neccessary controls.<br /><br />

  <ul>
    <li><b>Manual Config.php Generation</b> - First, open up config.php.inc. If you don't plan on a MySQL installation, modify $nomysqlUsername with your preferred username and $nomysqlPassword with your password. If you want a MySQL installation, enter your MySQL login information at $mysqlUser, $mysqlPassword, and $mysqlHost. You may also want to provide a $mysqlPrefix if you use the table for other things. Upload the file to Fliler's root as "config.php", then proceed to the installation. You can now proceed to install the databases and your main user.</li>
    <li><b>Basic Installation</b> - The basic installation is quick and easy. Enter you username and password, then upload the generated config file to the root as "config.php". Skip ahead to the last step and you are done.</li>
    <li><b>Standard Installation</b> - The standard installation is for MySQL. It will ask you to first provide your MySQL login information, a database (which will be generated later if it doesn't already exist), and a table prefix if you are using the database for other things. You can also enter the root directory for file management tasks (no files can be modified above this). Then proceed to install everything else by entering your username and password.</li>
    <li><b>Install.php Removal</b> - Install.php should be removed once you are done. There is a major security risk as the file allows the reinstallation and takeover of the software as a whole.</li>
  </ul>

  <h3 id="no-install-php">Not Using Install.php</h3>
  To install without install.php, execute the code found in "fileTypes.sql" and "generic.sql". Then modify the "users" table with your preferred login credentials, done by specifying a username, a random salt, an access level equal to 1, and an empty access directory, and a password equal to md5(md5(password) . salt).<br /><br />
</div>

<h2 id="php-config">PHP Configuration</h2>
The configuration options in php.ini can sometimes cause problems for Fliler. In general, here are key variables that may cause problems with Fliler:<br />

<ul>
  <li><b>Magic Quotes</b> - Magic Quotes can cause many of Fliler's function to become glitchy and in some cases fail completely. This function was deprecated in PHP 5.3.0, and will likely be removed in the future. If enabled, Fliler will try to disable them, but you are advised to disable them yourself.</li>
  <li><b>Register Globals</b> - The Register Globals option is known to cause several security risks. Though Fliler should still function, it is recommend you turn them off. It is also depreciated as of PHP 5.3.0.</li>
  <li><b>Safe Mode</b> - Safe mode by itself is not known to cause any problems. Environment variables are not used at any point in Fliler, but there can be some problems with limit_basedir. These should still not exist, and will ideally be fixed.</li>
  <li><b>Short Open Tag and ASP Tags</b> - Neither short open tags or ASP tags are required, as Fliler always functions without them and uses the proper &lt;?php ?&gt; code.</li>
  <li><b>Memory Limit</b> - Should be at least 2MB, and is recommended at 16MB. This can be lower if, among other things, output buffering is disabled, directory select is disabled, and the directory viewer and backups are unimportant.</li>
</ul><br />

<h2 id="addons">Additional Software</h2>
<div style="padding-left: 50px;">
  <h3 id="required">Required Software</h3>
  <ul>
    <li><b>PHP</b> - <a href="http://www.php.net/">PHP version 5.0</a> is the very minimum that Fliler will work on. PHP 5.3.0 is recommended, and PHP 6.0 is not test (but should work).</li>
    <ul>
      <li><b>MCrypt Extension (Critical)</b> - MCrypt is required for password hashing. It would be easy to hack Fliler to not use it, but this is not recommended.</li>
      <li><b>MySQLi (Critical)</b> - The MySQLi extension is currently used for advanced MySQL features, namely user and backup management, and will in the future be used throughout.</li>
      <li><b>PCRE (Critical)</b> - PCRE Regular Expressions is used throughout the library files.</li>
      <li><b>ZIP Compression (Recommended)</b> - The zip file archiver extension is required to download backups from MySQL and download directories.</li>
      <li><b>GD (Recommended)</b> - Used for file thumbnails and converting images for download.</li>
      <li><b>Imagick (Helpful)</b> - Replacement for GD when not found and used for PDF image previews.</li>
      <li><b>Date (Helpful)</b> - The date extension is required for resolving file modification dates and other time-related events.</li>
      <li><b>EXIF (Helpful)</b> - EXIF is required for image property viewing.</li>
      <li><b>FileInfo (Helpful)</b> - Used for resolving some file properties and mime types.</li>
    </ul>
    <li><b>MySQL</b> - <a href="http://dev.mysql.com/downloads/mysql/5.4.html">MySQL version 5.4</a> is used for most of Fliler's development, however any version of MySQL 5.0 or newer should work.</li>
  </ul><br />

  <h3 id="recommended">Recommended Software</h3>
  <ul>
    <li><b>AbiWord</b> - <a href="http://www.abisource.com/download/">AbiWord</a> is used for viewing, editing, and converting word-processed documents (.doc, .docx, .sxw, .odt, .rtf, .pdf) in HTML. The command line plugin, "AbiCommand" is used for this.</li>
    <li><b>Gnumeric</b> - <a href="http://projects.gnome.org/gnumeric/downloads.shtml">Gnumeric</a> is used for viewing and converting spreadsheets (.xls, .xlsx, .sxc, .ods, .csv) in HTML. The command line executable, "ssconvert" is used for this.</li>
    <li><b>Batik</b> - <a href="http://xmlgraphics.apache.org/batik/">Batik</a> is a useful SVG conversion library. Due to size constraints, it is not included by default, but should be placed in "{INSTALL PATH}/.batik/". It requires Java version 1.4 or greater.</li>
  </ul>
</div><br />

<h2 id="tips">Useful Knowledge</h2>
<div style="padding-left: 50px;">
  <h3 id="hash">Hashes</h3>
  In Fliler, user passwords are stored in a secure hash-salt method that prevents password discovery. This is done by using a plain text salt (usually 5 characters, though it can be variable) and your own password, like so: md5(md5(password) + salt). The non-MySQL login is not protected in this manner, and instead is a simple raw-text comparison (because of this, systems with working MySQL should not declare the variables).<br /><br />

  Additionally, cookies are hashed using the blue fish secret key stored in the config.php file.<br /><br />

  <h3 id="perm">User Permissions</h3>
  User permissions are defined by levels, which restrict and grant access to each function of the file manager. To change permissions of a user, you must either create a level, or modify the existing one. Remember that when changing a level, all users with that level will be affected. The levels are as follows: Create File (MkF), Move/Rename File (MvF), Edit File (EdF), Delete File (RmF), Create Directory (MkD), Move/Rename Directory (MvD), Delete Directory (RmD), View File/Directory (View), Manage Users (MngUsrs), Manage Levels (MngLvls), and Manage Backups (MngBckps). File whitelists and max upload restraints are also being programmed but have not yet landed.<br /><br />

  <h3 id="nomysql">No-MySQL Fallback</h3>
  In config.php, you can specify a non-MySQL login. This login will only be used when the system cannot connect to MySQL. It has full permissions for everything not involving MySQL. However, since passwords are stored in plain text, this should not be declared if possible.<br /><br />

  <h3 id="libs">Libraries</h3>
  <ul>
    <li><b>jQuery</b> - <a href="http://jquery.com/">jQuery</a> is a cross-platform Javascript effects library that has been developed by John Resig and the jQuery Team. jQuery is dual licensed under the MIT and GPL licenses. Version 1.4.2 is included, and can be disabled by the config.php file, but this has been found to cause serious issues and so is not recommended (this configuration directive will likely be removed in the future).</li>
    <li><b>GeSHi</b> - <a href="http://qbnz.com/highlighter/">GeSHi</a> is a PHP syntax highlighter currently being developed by Milian Wolf. It allows you to view syntax highlighted code for PHP and several other languages. Version 1.0.8.6 is included, and version 1.0.8.8 is available.</li>
    <li><b>reCAPTCHA</b> - <a href="#">reCAPTCHA</a> is a captcha implementation shown to be highly effective based on using "actual books". The project is currently owned by Google and is highly effective at detering spam bots. It can however be removed without issue.</li>
  </ul><br />

  <h3 id="themes">Themes</h3>
  <ul>
    <li><b>Aqua</b> - Aqua is the main theme used, which renders page using warm-cool colors. Form fields are transparent and text is black.
  </ul><br />

  <h3 id="secure">Security</h3>
  Fliler is designed to be reasonably secure, but is not designed as a product that should be accessible by anyone - only trusted parties. As such, little focus on security exists once the login is successful.<br />

  <ul>
    <li><b>config.php</b> - The config file is a common vector for attacks. As such, it is highly recommended you prevent the file from being read by anyone other than the server. To do so, chmod the file 400 (read permission only for the owner).</li>
    <li><b>install.php</b> - The install file is helpful for getting things going initially, but, as such, it completely ignores user login data, or whether existing installation data is present. Because of this, it is wise to delete the file immediately after installing it.</li>
    <li><b>Locked Files</b> - The <u>$lockedFiles</u> array defines files that should not be read or written to using Fliler. It is currently only partially implemented, and generally it is better to only trust Fliler with people who will be allowed to access the entire server.</li>
    <li><b>User Permissions</b> - Individual user permissions prevent existing GUI functions from being executed if a user does not have the credentials. However, it is important to note that it would be possible for anyone to create a file that could be executed with user permissions ignored. As such, the "ExtWhtLst" configuration directive that is planned for Beta 6 will allow you to restrict files to certain types.</li>
  </ul><br />

  <h3 id="view">View Modes</h3>
  <ul>
    <li><b>Plain Text</b> - A raw text dump of the file contents. This will appear jumbled for binary files, and will encode for HTML viewing purposes.</li>
    <li><b>Image</b> - The file is loaded through a plain HTML &lt;img/&gt; tag. This won't work for non-image files. In IE6, PNG images do not display properly.</li>
    <li><b>Video and Audio</b> - The file is loaded through a plain HTML5 &lt;video/&gt; or &lt;audio&gt; tag. This will fail in IE browsers, where any unsupported codecs will not work either (Firefox only can display OGG, Chrome OGG and MP4).</li>
    <li><b>HTML Object</b> - An HTML object loaded with a mime time looked up through a database. This should work with most files, and will usually trigger a download for those that don't work.</li>
    <li><b>HTML IFrame</b> - An HTML IFrame object. This automatically assumes a mime type, but works largely like an HTML Object.</li>
    <li><b>AbiWord</b> - The document is converted to HTML from .doc, .docx, .odt, and others.</li>
    <li><b>Gnumeric</b> - The spreadsheet is converted to HTML from .ods, .xls, and others.</li>
    <li><b>Code</b> - The document is displayed as plain text with GeSHi syntax highlighting.</li>
    <li><b>CSV Table</b> - The file is outputted as an HTML table.</li>
  </ul><br />

  <h3 id="edit">Edit Modes</h3>
  <ul>
    <li><b>Plain Text</b> - A textarea is displayed with a raw dump of the file contents.</li>
    <li><b>Standard Editor</b> - A built-in basic Javascript editor that requires first hand knowledge of HTML. It will display what you type alongside the actual code, updated live.</li>
    <li><b>Koivi Editor</b> - A relatively old editor that is very basic, was never popular, but uses a basic and simpler interface that may be more accessible.</li>
    <li><b>CKEditor</b> - The successor to FCKEditor, a very popular and well suppported editor that has been used by several enterprises.</li>
  </ul><br />

  <h3 id="tables">Table Structure</h3>
  <ul>
    <li><b>Users</b> - The users table is used to define different users.</li>
    <li><b>Access Levels</b> - The access levels table is used to define what different users can do. Its structure is:<br /></li>
    <li><b>Backups, BackupFiles, and BackupContents</b> - Stores data on backups. These tables can usually be safely truncated, but backups are important to have.</li>
  </ul>
</div><br />

<h2 id="browser">Improving Your Browsing Experience</h2>
Although any browser, including text-based browser (like Lynx) should work with the majority of Flilr, it is generally recommended that you use <a href="http://www.mozilla.com/en-US/firefox/firefox.html">Mozilla Firefox 3.5</a>, <a href="http://www.google.com/chrome">Google Chrome 3</a>, or <a href="http://www.apple.com/safari/download/">Apple Safari 4</a> for this software. Additionally, if you use Opera, please make sure you are using version 10 or newer, and if you are using Internet Explorer, please make sure you are using version 8 or newer.<br /><br />

<div style="padding-left: 50px;">
  <h3 id="support">Browser Support Notes</h3>
  Fliler tries to support all browsers as much as possible, however some browsers are not well tested. Google Chrome and Firefox are tested most frequently, while Opera and Konqueror also receive some attention (thus covering the lesser 4 layout engines). Internet Explorer is not well tested due to its lackluster support on Linux, but most things stil work.<br />

  <ul>
    <li><b>Internet Explorer 6:</b> HTML-5 Video Unsupported, XML HTTP Requests Unsupported, Table Row Hover Unsupported</li>
    <li><b>Internet Explorer 7:</b> HTML-5 Video Unsupported, Table Row Hover Untested</li>
    <li><b>Internet Explorer 8:</b> HTML-5 Video Unsupported</li>
    <li><b>Chrome 4.0</b> Webkit rendering engine may crash when rendering :hover CSS gradient, so are unused on this platform.</li>
    <li><b>Firefox 3.0:</b> HTML-5 Video Unsupported</li>
    <li><b>Firefox 3.6:</b> Only browser where CSS gradient table hover is used.</li>
  </ul><br />

  <h3 id="priority">Priority</h3>
  As developed, Fliler focuses on supporting the most recent (and sometimes developmental) technologies, especially those employed in Firefox and Google Chrome. For instance, technologies present in HTML5 and CSS3 are used where they can be helpful, including the FileReader() API, which was first added when Firefox gained experimental support for it. Now both Firefox and Google Chrome can use this technology.<br /><br />

  Older browser versions, especially Internet Explorer 6, are often intentionally developed to not work if it means increasing the speed elsewhere. Otherwise, support hacks will be added, but usually much later in the development cycle.
</div><br />

<h2 id="api">API (WIP)</h2>
The Fliler file management API is early in the works and not yet used through most of Fliler even. It is composed of several classes that are incredibly useful outside of Fliler itself. Most of these are general-purpose File utils, but others are encompassed as well.<br /><br />

<div style="padding-left: 50px;">
  <h3 id="fileManagerClass">FileManager Class</h3>
  <br />

  <h3 id="browserInfoClass">browserInfo Class</h3>

  <h4>Variables</h4>
  <ul>
    <li><b>userAgent</b> - The active user agent being tested. Set with <u>$this->setUserAgent($ua)</u>.</li>
    <li><b>length</b> - String length of <u>$this->userAgent</u>.</li>
    <li><b>browsers</b> - Array of browsers able to be tested.</li>
    <li><b>osfam</b> - Array of operating systems to be tested.</li>
    <li><b>osversion</b> - Array of operating system versions (mostly Windows) to be tested.</li>
    <li><b>architectures</b> - Array of architectures to be tested.</li>
    <li><b>browser</b> - The detected browser of the useragent. Obtained with <u>$this->getBrowserbit</u>.</li>
    <li><b>browserClass</b> - The detected browser of the useragent, including codewords of identical browsers. This method is preferrable to <u>$this->browser</u>. Obtained with <u>$this->getBrowserbit</u>.</li>
    <li><b>arcitecture</b> - The architecture of the system (i386, ppc, or amd64).</li>
  </ul><br />

  <h4>Methods</h4>
  <ul>
    <li><b>setUserAgent($ua)</b> - Sets the useragent to $ua. If not called assumes session useragent.</li>
    <li><b>getBrowserBit()</b> - Adds <u>$this->browser</u> and <u>$this->browserClass</u>.</li>
    <li><b>getVersionBit()</b> - Adds all version variables for the browser.</li>
    <li><b>getPlatformBit()</b> - Adds os variables.</li>
    <li><b>getArcBit()</b> - Adds <u>$this->arcitecture</u>.</li>
  </ul><br />

  <h3 id="htmlFormClass">htmlForm Class</h3>
</div>

<h2 id="copyright">Copyright</h2>
Fliler is copyright Joseph T. Parsons, 2010, except where otherwise noted. Fliler is licensed as GPLv3 - this means you are free to use any portion of Fliler for your own projects, be it commercial or non-profit, as long as you follow the terms of the GPLv3 (which usually means making all source available), and the Fliler Library (see above) is licensed under the LGPLv3 (which is more permissive than normal GPLv3).<br /><br />

Several included libraries also use a version of GPL or other compatible license, including jQuery (the standard Javascript animation library used throughout), geSHi, the code highlighting library used for viewing code files, and CKEditor, the advanced WYSIWYG editor that can be used to edit documents. reCAPTCHA is licensed under the MIT license (which is even less restrictive than the LGPL, and compatible with the GPL)<br /><br />

Licenses are listed below, but are specially formatted for readability and understanding. The actual license documents, which you agree to by using this software, are listed in their respective files.<br /><br />
<div style="padding-left: 50px;">
<h3 id="copyright_fliler">Fliler</h3>
<?php
echo container('GNU GENERAL PUBLIC LICENSE VERSION 3','<small>Version 3, 29 June 2007<br /><br />

Copyright © 2007 Free Software Foundation, Inc. <http://fsf.org/><br /><br />

Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.</small><br /><br />

<h4 onclick="$(\'#gpl01\').slideToggle();">Preamble</h4>
<div id="gpl01" style="display: none;">
The GNU General Public License is a free, copyleft license for software and other kinds of works.<br /><br />

The licenses for most software and other practical works are designed to take away your freedom to share and change the works. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change all versions of a program--to make sure it remains free software for all its users. We, the Free Software Foundation, use the GNU General Public License for most of our software; it applies also to any other work released this way by its authors. You can apply it to your programs, too.<br /><br />

When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for them if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs, and that you know you can do these things.<br /><br />

To protect your rights, we need to prevent others from denying you these rights or asking you to surrender the rights. Therefore, you have certain responsibilities if you distribute copies of the software, or if you modify it: responsibilities to respect the freedom of others.<br /><br />

For example, if you distribute copies of such a program, whether gratis or for a fee, you must pass on to the recipients the same freedoms that you received. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.<br /><br />

Developers that use the GNU GPL protect your rights with two steps: (1) assert copyright on the software, and (2) offer you this License giving you legal permission to copy, distribute and/or modify it.<br /><br />

For the developers\' and authors\' protection, the GPL clearly explains that there is no warranty for this free software. For both users\' and authors\' sake, the GPL requires that modified versions be marked as changed, so that their problems will not be attributed erroneously to authors of previous versions.<br /><br />

Some devices are designed to deny users access to install or run modified versions of the software inside them, although the manufacturer can do so. This is fundamentally incompatible with the aim of protecting users\' freedom to change the software. The systematic pattern of such abuse occurs in the area of products for individuals to use, which is precisely where it is most unacceptable. Therefore, we have designed this version of the GPL to prohibit the practice for those products. If such problems arise substantially in other domains, we stand ready to extend this provision to those domains in future versions of the GPL, as needed to protect the freedom of users.<br /><br />

Finally, every program is threatened constantly by software patents. States should not allow patents to restrict development and use of software on general-purpose computers, but in those that do, we wish to avoid the special danger that patents applied to a free program could make it effectively proprietary. To prevent this, the GPL assures that patents cannot be used to render the program non-free.<br /><br />

The precise terms and conditions for copying, distribution and modification follow.
</div><br />

<h4 onclick="$(\'#gpl02\').slideToggle();">TERMS AND CONDITIONS</h4>
<div id="gpl02">
<strong onclick="$(\'#gpl10\').slideToggle();">0. Definitions.</strong><br />
<div id="gpl10" style="display: none;">
“This License” refers to version 3 of the GNU General Public License.<br /><br />

“Copyright” also means copyright-like laws that apply to other kinds of works, such as semiconductor masks.<br /><br />

“The Program” refers to any copyrightable work licensed under this License. Each licensee is addressed as “you”. “Licensees” and “recipients” may be individuals or organizations.<br /><br />

To “modify” a work means to copy from or adapt all or part of the work in a fashion requiring copyright permission, other than the making of an exact copy. The resulting work is called a “modified version” of the earlier work or a work “based on” the earlier work.<br /><br />

A “covered work” means either the unmodified Program or a work based on the Program.<br /><br />

To “propagate” a work means to do anything with it that, without permission, would make you directly or secondarily liable for infringement under applicable copyright law, except executing it on a computer or modifying a private copy. Propagation includes copying, distribution (with or without modification), making available to the public, and in some countries other activities as well.<br /><br />

To “convey” a work means any kind of propagation that enables other parties to make or receive copies. Mere interaction with a user through a computer network, with no transfer of a copy, is not conveying.<br /><br />

An interactive user interface displays “Appropriate Legal Notices” to the extent that it includes a convenient and prominently visible feature that (1) displays an appropriate copyright notice, and (2) tells the user that there is no warranty for the work (except to the extent that warranties are provided), that licensees may convey the work under this License, and how to view a copy of this License. If the interface presents a list of user commands or options, such as a menu, a prominent item in the list meets this criterion.
</div><br />

<strong onclick="$(\'#gpl11\').slideToggle();">1. Source Code</strong><br />
<div id="gpl11" style="display: none;">
The “source code” for a work means the preferred form of the work for making modifications to it. “Object code” means any non-source form of a work.<br /><br />

A “Standard Interface” means an interface that either is an official standard defined by a recognized standards body, or, in the case of interfaces specified for a particular programming language, one that is widely used among developers working in that language.<br /><br />

The “System Libraries” of an executable work include anything, other than the work as a whole, that (a) is included in the normal form of packaging a Major Component, but which is not part of that Major Component, and (b) serves only to enable use of the work with that Major Component, or to implement a Standard Interface for which an implementation is available to the public in source code form. A “Major Component”, in this context, means a major essential component (kernel, window system, and so on) of the specific operating system (if any) on which the executable work runs, or a compiler used to produce the work, or an object code interpreter used to run it.<br /><br />

The “Corresponding Source” for a work in object code form means all the source code needed to generate, install, and (for an executable work) run the object code and to modify the work, including scripts to control those activities. However, it does not include the work\'s System Libraries, or general-purpose tools or generally available free programs which are used unmodified in performing those activities but which are not part of the work. For example, Corresponding Source includes interface definition files associated with source files for the work, and the source code for shared libraries and dynamically linked subprograms that the work is specifically designed to require, such as by intimate data communication or control flow between those subprograms and other parts of the work.<br /><br />

The Corresponding Source need not include anything that users can regenerate automatically from other parts of the Corresponding Source.<br /><br />

The Corresponding Source for a work in source code form is that same work.
</div><br />

<strong onclick="$(\'#gpl12\').slideToggle();">2. Basic Permissions.</strong><br />
<div id="gpl12" style="display: none;">All rights granted under this License are granted for the term of copyright on the Program, and are irrevocable provided the stated conditions are met. This License explicitly affirms your unlimited permission to run the unmodified Program. The output from running a covered work is covered by this License only if the output, given its content, constitutes a covered work. This License acknowledges your rights of fair use or other equivalent, as provided by copyright law.<br /><br />

You may make, run and propagate covered works that you do not convey, without conditions so long as your license otherwise remains in force. You may convey covered works to others for the sole purpose of having them make modifications exclusively for you, or provide you with facilities for running those works, provided that you comply with the terms of this License in conveying all material for which you do not control copyright. Those thus making or running the covered works for you must do so exclusively on your behalf, under your direction and control, on terms that prohibit them from making any copies of your copyrighted material outside their relationship with you.<br /><br />

Conveying under any other circumstances is permitted solely under the conditions stated below. Sublicensing is not allowed; section 10 makes it unnecessary.
</div><br />

<strong onclick="$(\'#gpl13\').slideToggle();">3. Protecting Users\' Legal Rights From Anti-Circumvention Law.</strong><br />
<div id="gpl13" style="display: none;">
No covered work shall be deemed part of an effective technological measure under any applicable law fulfilling obligations under article 11 of the WIPO copyright treaty adopted on 20 December 1996, or similar laws prohibiting or restricting circumvention of such measures.<br /><br />

When you convey a covered work, you waive any legal power to forbid circumvention of technological measures to the extent such circumvention is effected by exercising rights under this License with respect to the covered work, and you disclaim any intention to limit operation or modification of the work as a means of enforcing, against the work\'s users, your or third parties\' legal rights to forbid circumvention of technological measures.
</div><br />

<strong onclick="$(\'#gpl14\').slideToggle();">4. Conveying Verbatim Copies.</strong><br />
<div id="gpl14" style="display: none;">
You may convey verbatim copies of the Program\'s source code as you receive it, in any medium, provided that you conspicuously and appropriately publish on each copy an appropriate copyright notice; keep intact all notices stating that this License and any non-permissive terms added in accord with section 7 apply to the code; keep intact all notices of the absence of any warranty; and give all recipients a copy of this License along with the Program.

You may charge any price or no price for each copy that you convey, and you may offer support or warranty protection for a fee.
</div><br />

<strong onclick="$(\'#gpl15\').slideToggle();">5. Conveying Modified Source Versions.</strong><br />
<div id="gpl15" style="display: none;">
You may convey a work based on the Program, or the modifications to produce it from the Program, in the form of source code under the terms of section 4, provided that you also meet all of these conditions:<br />

<ul>
  <li>The work must carry prominent notices stating that you modified it, and giving a relevant date.</li>
  <li>The work must carry prominent notices stating that it is released under this License and any conditions added under section 7. This requirement modifies the requirement in section 4 to “keep intact all notices”.</li>
  <li>You must license the entire work, as a whole, under this License to anyone who comes into possession of a copy. This License will therefore apply, along with any applicable section 7 additional terms, to the whole of the work, and all its parts, regardless of how they are packaged. This License gives no permission to license the work in any other way, but it does not invalidate such permission if you have separately received it.</li>
  <li>If the work has interactive user interfaces, each must display Appropriate Legal Notices; however, if the Program has interactive interfaces that do not display Appropriate Legal Notices, your work need not make them do so.</li>
</ul><br /><br />

A compilation of a covered work with other separate and independent works, which are not by their nature extensions of the covered work, and which are not combined with it such as to form a larger program, in or on a volume of a storage or distribution medium, is called an “aggregate” if the compilation and its resulting copyright are not used to limit the access or legal rights of the compilation\'s users beyond what the individual works permit. Inclusion of a covered work in an aggregate does not cause this License to apply to the other parts of the aggregate.
</div><br />

<strong onclick="$(\'#gpl16\').slideToggle();">6. Conveying Non-Source Forms.</strong><br />
<div id="gpl16" style="display: none;">
You may convey a covered work in object code form under the terms of sections 4 and 5, provided that you also convey the machine-readable Corresponding Source under the terms of this License, in one of these ways:<br />

<ul>
  <li>Convey the object code in, or embodied in, a physical product (including a physical distribution medium), accompanied by the Corresponding Source fixed on a durable physical medium customarily used for software interchange.</li>
  <li>Convey the object code in, or embodied in, a physical product (including a physical distribution medium), accompanied by a written offer, valid for at least three years and valid for as long as you offer spare parts or customer support for that product model, to give anyone who possesses the object code either (1) a copy of the Corresponding Source for all the software in the product that is covered by this License, on a durable physical medium customarily used for software interchange, for a price no more than your reasonable cost of physically performing this conveying of source, or (2) access to copy the Corresponding Source from a network server at no charge.</li>
  <li>Convey individual copies of the object code with a copy of the written offer to provide the Corresponding Source. This alternative is allowed only occasionally and noncommercially, and only if you received the object code with such an offer, in accord with subsection 6b.</li>
  <li>Convey the object code by offering access from a designated place (gratis or for a charge), and offer equivalent access to the Corresponding Source in the same way through the same place at no further charge. You need not require recipients to copy the Corresponding Source along with the object code. If the place to copy the object code is a network server, the Corresponding Source may be on a different server (operated by you or a third party) that supports equivalent copying facilities, provided you maintain clear directions next to the object code saying where to find the Corresponding Source. Regardless of what server hosts the Corresponding Source, you remain obligated to ensure that it is available for as long as needed to satisfy these requirements.</li>
  <li>Convey the object code using peer-to-peer transmission, provided you inform other peers where the object code and Corresponding Source of the work are being offered to the general public at no charge under subsection 6d.</li>
</ul><br /><br />

A separable portion of the object code, whose source code is excluded from the Corresponding Source as a System Library, need not be included in conveying the object code work.<br /><br />

A “User Product” is either (1) a “consumer product”, which means any tangible personal property which is normally used for personal, family, or household purposes, or (2) anything designed or sold for incorporation into a dwelling. In determining whether a product is a consumer product, doubtful cases shall be resolved in favor of coverage. For a particular product received by a particular user, “normally used” refers to a typical or common use of that class of product, regardless of the status of the particular user or of the way in which the particular user actually uses, or expects or is expected to use, the product. A product is a consumer product regardless of whether the product has substantial commercial, industrial or non-consumer uses, unless such uses represent the only significant mode of use of the product.<br /><br />

“Installation Information” for a User Product means any methods, procedures, authorization keys, or other information required to install and execute modified versions of a covered work in that User Product from a modified version of its Corresponding Source. The information must suffice to ensure that the continued functioning of the modified object code is in no case prevented or interfered with solely because modification has been made.<br /><br />

If you convey an object code work under this section in, or with, or specifically for use in, a User Product, and the conveying occurs as part of a transaction in which the right of possession and use of the User Product is transferred to the recipient in perpetuity or for a fixed term (regardless of how the transaction is characterized), the Corresponding Source conveyed under this section must be accompanied by the Installation Information. But this requirement does not apply if neither you nor any third party retains the ability to install modified object code on the User Product (for example, the work has been installed in ROM).<br /><br />

The requirement to provide Installation Information does not include a requirement to continue to provide support service, warranty, or updates for a work that has been modified or installed by the recipient, or for the User Product in which it has been modified or installed. Access to a network may be denied when the modification itself materially and adversely affects the operation of the network or violates the rules and protocols for communication across the network.<br /><br />

Corresponding Source conveyed, and Installation Information provided, in accord with this section must be in a format that is publicly documented (and with an implementation available to the public in source code form), and must require no special password or key for unpacking, reading or copying.
</div><br />

<strong onclick="$(\'#gpl17\').slideToggle();">7. Additional Terms.</strong><br />
<div id="gpl17" style="display: none;">
“Additional permissions” are terms that supplement the terms of this License by making exceptions from one or more of its conditions. Additional permissions that are applicable to the entire Program shall be treated as though they were included in this License, to the extent that they are valid under applicable law. If additional permissions apply only to part of the Program, that part may be used separately under those permissions, but the entire Program remains governed by this License without regard to the additional permissions.<br /><br />

When you convey a copy of a covered work, you may at your option remove any additional permissions from that copy, or from any part of it. (Additional permissions may be written to require their own removal in certain cases when you modify the work.) You may place additional permissions on material, added by you to a covered work, for which you have or can give appropriate copyright permission.<br /><br />

Notwithstanding any other provision of this License, for material you add to a covered work, you may (if authorized by the copyright holders of that material) supplement the terms of this License with terms:<br /><br />

<ul>
  <li>Disclaiming warranty or limiting liability differently from the terms of sections 15 and 16 of this License; or</li>
  <li>Requiring preservation of specified reasonable legal notices or author attributions in that material or in the Appropriate Legal Notices displayed by works containing it; or</li>
  <li>Prohibiting misrepresentation of the origin of that material, or requiring that modified versions of such material be marked in reasonable ways as different from the original version; or</li>
  <li>Limiting the use for publicity purposes of names of licensors or authors of the material; or</li>
  <li>Declining to grant rights under trademark law for use of some trade names, trademarks, or service marks; or</li>
  <li>Requiring indemnification of licensors and authors of that material by anyone who conveys the material (or modified versions of it) with contractual assumptions of liability to the recipient, for any liability that these contractual assumptions directly impose on those licensors and authors.</li>
</ul><br /><br />

All other non-permissive additional terms are considered “further restrictions” within the meaning of section 10. If the Program as you received it, or any part of it, contains a notice stating that it is governed by this License along with a term that is a further restriction, you may remove that term. If a license document contains a further restriction but permits relicensing or conveying under this License, you may add to a covered work material governed by the terms of that license document, provided that the further restriction does not survive such relicensing or conveying.<br /><br />

If you add terms to a covered work in accord with this section, you must place, in the relevant source files, a statement of the additional terms that apply to those files, or a notice indicating where to find the applicable terms.<br /><br />

Additional terms, permissive or non-permissive, may be stated in the form of a separately written license, or stated as exceptions; the above requirements apply either way.
</div><br />

<strong onclick="$(\'#gpl18\').slideToggle();">8. Termination.</strong><br />
<div id="gpl18" style="display: none;">
You may not propagate or modify a covered work except as expressly provided under this License. Any attempt otherwise to propagate or modify it is void, and will automatically terminate your rights under this License (including any patent licenses granted under the third paragraph of section 11).<br /><br />

However, if you cease all violation of this License, then your license from a particular copyright holder is reinstated (a) provisionally, unless and until the copyright holder explicitly and finally terminates your license, and (b) permanently, if the copyright holder fails to notify you of the violation by some reasonable means prior to 60 days after the cessation.<br /><br />

Moreover, your license from a particular copyright holder is reinstated permanently if the copyright holder notifies you of the violation by some reasonable means, this is the first time you have received notice of violation of this License (for any work) from that copyright holder, and you cure the violation prior to 30 days after your receipt of the notice.<br /><br />

Termination of your rights under this section does not terminate the licenses of parties who have received copies or rights from you under this License. If your rights have been terminated and not permanently reinstated, you do not qualify to receive new licenses for the same material under section 10.
</div><br />

<strong onclick="$(\'#gpl19\').slideToggle();">9. Acceptance Not Required for Having Copies.</strong><br />
<div id="gpl19" style="display: none;">
You are not required to accept this License in order to receive or run a copy of the Program. Ancillary propagation of a covered work occurring solely as a consequence of using peer-to-peer transmission to receive a copy likewise does not require acceptance. However, nothing other than this License grants you permission to propagate or modify any covered work. These actions infringe copyright if you do not accept this License. Therefore, by modifying or propagating a covered work, you indicate your acceptance of this License to do so.
</div><br />

<strong onclick="$(\'#gpl110\').slideToggle();">10. Automatic Licensing of Downstream Recipients.</strong><br />
<div id="gpl110" style="display: none;">
Each time you convey a covered work, the recipient automatically receives a license from the original licensors, to run, modify and propagate that work, subject to this License. You are not responsible for enforcing compliance by third parties with this License.<br /><br />

An “entity transaction” is a transaction transferring control of an organization, or substantially all assets of one, or subdividing an organization, or merging organizations. If propagation of a covered work results from an entity transaction, each party to that transaction who receives a copy of the work also receives whatever licenses to the work the party\'s predecessor in interest had or could give under the previous paragraph, plus a right to possession of the Corresponding Source of the work from the predecessor in interest, if the predecessor has it or can get it with reasonable efforts.<br /><br />

You may not impose any further restrictions on the exercise of the rights granted or affirmed under this License. For example, you may not impose a license fee, royalty, or other charge for exercise of rights granted under this License, and you may not initiate litigation (including a cross-claim or counterclaim in a lawsuit) alleging that any patent claim is infringed by making, using, selling, offering for sale, or importing the Program or any portion of it.
</div><br />

<strong onclick="$(\'#gpl111\').slideToggle();">11. Patents.</strong><br />
<div id="gpl111" style="display: none;">
A “contributor” is a copyright holder who authorizes use under this License of the Program or a work on which the Program is based. The work thus licensed is called the contributor\'s “contributor version”.<br /><br />

A contributor\'s “essential patent claims” are all patent claims owned or controlled by the contributor, whether already acquired or hereafter acquired, that would be infringed by some manner, permitted by this License, of making, using, or selling its contributor version, but do not include claims that would be infringed only as a consequence of further modification of the contributor version. For purposes of this definition, “control” includes the right to grant patent sublicenses in a manner consistent with the requirements of this License.<br /><br />

Each contributor grants you a non-exclusive, worldwide, royalty-free patent license under the contributor\'s essential patent claims, to make, use, sell, offer for sale, import and otherwise run, modify and propagate the contents of its contributor version.<br /><br />

In the following three paragraphs, a “patent license” is any express agreement or commitment, however denominated, not to enforce a patent (such as an express permission to practice a patent or covenant not to sue for patent infringement). To “grant” such a patent license to a party means to make such an agreement or commitment not to enforce a patent against the party.<br /><br />

If you convey a covered work, knowingly relying on a patent license, and the Corresponding Source of the work is not available for anyone to copy, free of charge and under the terms of this License, through a publicly available network server or other readily accessible means, then you must either (1) cause the Corresponding Source to be so available, or (2) arrange to deprive yourself of the benefit of the patent license for this particular work, or (3) arrange, in a manner consistent with the requirements of this License, to extend the patent license to downstream recipients. “Knowingly relying” means you have actual knowledge that, but for the patent license, your conveying the covered work in a country, or your recipient\'s use of the covered work in a country, would infringe one or more identifiable patents in that country that you have reason to believe are valid.<br /><br />

If, pursuant to or in connection with a single transaction or arrangement, you convey, or propagate by procuring conveyance of, a covered work, and grant a patent license to some of the parties receiving the covered work authorizing them to use, propagate, modify or convey a specific copy of the covered work, then the patent license you grant is automatically extended to all recipients of the covered work and works based on it.<br /><br />

A patent license is “discriminatory” if it does not include within the scope of its coverage, prohibits the exercise of, or is conditioned on the non-exercise of one or more of the rights that are specifically granted under this License. You may not convey a covered work if you are a party to an arrangement with a third party that is in the business of distributing software, under which you make payment to the third party based on the extent of your activity of conveying the work, and under which the third party grants, to any of the parties who would receive the covered work from you, a discriminatory patent license (a) in connection with copies of the covered work conveyed by you (or copies made from those copies), or (b) primarily for and in connection with specific products or compilations that contain the covered work, unless you entered into that arrangement, or that patent license was granted, prior to 28 March 2007.<br /><br />

Nothing in this License shall be construed as excluding or limiting any implied license or other defenses to infringement that may otherwise be available to you under applicable patent law.
</div><br />

<strong onclick="$(\'#gpl112\').slideToggle();">12. No Surrender of Others\' Freedom.</strong><br />
<div id="gpl112" style="display: none;">
If conditions are imposed on you (whether by court order, agreement or otherwise) that contradict the conditions of this License, they do not excuse you from the conditions of this License. If you cannot convey a covered work so as to satisfy simultaneously your obligations under this License and any other pertinent obligations, then as a consequence you may not convey it at all. For example, if you agree to terms that obligate you to collect a royalty for further conveying from those to whom you convey the Program, the only way you could satisfy both those terms and this License would be to refrain entirely from conveying the Program.
</div><br />

<strong onclick="$(\'#gpl113\').slideToggle();">13. Use with the GNU Affero General Public License.</strong><br />
<div id="gpl113" style="display: none;">
Notwithstanding any other provision of this License, you have permission to link or combine any covered work with a work licensed under version 3 of the GNU Affero General Public License into a single combined work, and to convey the resulting work. The terms of this License will continue to apply to the part which is the covered work, but the special requirements of the GNU Affero General Public License, section 13, concerning interaction through a network will apply to the combination as such.
</div><br />

<strong onclick="$(\'#gpl114\').slideToggle();">14. Revised Versions of this License.</strong><br />
<div id="gpl114" style="display: none;">
The Free Software Foundation may publish revised and/or new versions of the GNU General Public License from time to time. Such new versions will be similar in spirit to the present version, but may differ in detail to address new problems or concerns.<br /><br />

Each version is given a distinguishing version number. If the Program specifies that a certain numbered version of the GNU General Public License “or any later version” applies to it, you have the option of following the terms and conditions either of that numbered version or of any later version published by the Free Software Foundation. If the Program does not specify a version number of the GNU General Public License, you may choose any version ever published by the Free Software Foundation.<br /><br />

If the Program specifies that a proxy can decide which future versions of the GNU General Public License can be used, that proxy\'s public statement of acceptance of a version permanently authorizes you to choose that version for the Program.<br /><br />

Later license versions may give you additional or different permissions. However, no additional obligations are imposed on any author or copyright holder as a result of your choosing to follow a later version.<br /><br />
</div><br />

<strong onclick="$(\'#gpl115\').slideToggle();">15. Disclaimer of Warranty.</strong><br />
<div id="gpl115" style="display: none;">
THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM “AS IS” WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.
</div><br />

<strong onclick="$(\'#gpl116\').slideToggle();">16. Limitation of Liability.</strong><br />
<div id="gpl116" style="display: none;">
IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MODIFIES AND/OR CONVEYS THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
</div><br />

<strong onclick="$(\'#gpl117\').slideToggle();">17. Interpretation of Sections 15 and 16.</strong><br />
<div id="gpl117" style="display: none;">
If the disclaimer of warranty and limitation of liability provided above cannot be given local legal effect according to their terms, reviewing courts shall apply local law that most closely approximates an absolute waiver of all civil liability in connection with the Program, unless a warranty or assumption of liability accompanies a copy of the Program in return for a fee.
</div>',0,1);
?>

<h3 id="copyright_flilerlib">Fliler Library</h3>
<?php
echo container('GNU LESSER GENERAL PUBLIC LICENSE VERSION 3','<small>Version 3, 29 June 2007<br /><br />

Copyright © 2007 Free Software Foundation, Inc. <http://fsf.org/><br /><br />

Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.<br /><br />

This version of the GNU Lesser General Public License incorporates the terms and conditions of version 3 of the GNU General Public License, supplemented by the additional permissions listed below.</small><br /><br />

<strong onclick="$(\'#lgpl0\').slideToggle();">0. Additional Definitions.</strong><br />
<div id="lgpl0" style="display: none;">
As used herein, “this License” refers to version 3 of the GNU Lesser General Public License, and the “GNU GPL” refers to version 3 of the GNU General Public License.<br /><br />

“The Library” refers to a covered work governed by this License, other than an Application or a Combined Work as defined below.<br /><br />

An “Application” is any work that makes use of an interface provided by the Library, but which is not otherwise based on the Library. Defining a subclass of a class defined by the Library is deemed a mode of using an interface provided by the Library.<br /><br />

A “Combined Work” is a work produced by combining or linking an Application with the Library. The particular version of the Library with which the Combined Work was made is also called the “Linked Version”.<br /><br />

The “Minimal Corresponding Source” for a Combined Work means the Corresponding Source for the Combined Work, excluding any source code for portions of the Combined Work that, considered in isolation, are based on the Application, and not on the Linked Version.<br /><br />

The “Corresponding Application Code” for a Combined Work means the object code and/or source code for the Application, including any data and utility programs needed for reproducing the Combined Work from the Application, but excluding the System Libraries of the Combined Work.
</div><br />

<strong onclick="$(\'#lgpl1\').slideToggle();">1. Exception to Section 3 of the GNU GPL.</strong><br />
<div id="lgpl1" style="display: none;">
You may convey a covered work under sections 3 and 4 of this License without being bound by section 3 of the GNU GPL.
</div><br />

<strong onclick="$(\'#lgpl2\').slideToggle();">2. Conveying Modified Versions.</strong><br />
<div id="lgpl2" style="display: none;">
If you modify a copy of the Library, and, in your modifications, a facility refers to a function or data to be supplied by an Application that uses the facility (other than as an argument passed when the facility is invoked), then you may convey a copy of the modified version:<br />
<ul>
  <li>under this License, provided that you make a good faith effort to ensure that, in the event an Application does not supply the function or data, the facility still operates, and performs whatever part of its purpose remains meaningful, or</li>
  <li>under the GNU GPL, with none of the additional permissions of this License applicable to that copy.</li>
</ul>
</div><br />

<strong onclick="$(\'#lgpl3\').slideToggle();">3. Object Code Incorporating Material from Library Header Files.</strong><br />
<div id="lgpl3" style="display: none;">
The object code form of an Application may incorporate material from a header file that is part of the Library. You may convey such object code under terms of your choice, provided that, if the incorporated material is not limited to numerical parameters, data structure layouts and accessors, or small macros, inline functions and templates (ten or fewer lines in length), you do both of the following:<br /><br />

<ul>
  <li>Give prominent notice with each copy of the object code that the Library is used in it and that the Library and its use are covered by this License.</li>
  <li>Accompany the object code with a copy of the GNU GPL and this license document.</li>
</ul>
</div><br />

<strong onclick="$(\'#lgpl4\').slideToggle();">4. Combined Works.</strong><br />
<div id="lgpl4" style="display: none;">
You may convey a Combined Work under terms of your choice that, taken together, effectively do not restrict modification of the portions of the Library contained in the Combined Work and reverse engineering for debugging such modifications, if you also do each of the following:<br />

<ul>
<li>Give prominent notice with each copy of the Combined Work that the Library is used in it and that the Library and its use are covered by this License.
<li>Accompany the Combined Work with a copy of the GNU GPL and this license document.</li>
<li>For a Combined Work that displays copyright notices during execution, include the copyright notice for the Library among these notices, as well as a reference directing the user to the copies of the GNU GPL and this license document.</li>
<li>Do one of the following:</li>
<ul>
<li>Convey the Minimal Corresponding Source under the terms of this License, and the Corresponding Application Code in a form suitable for, and under terms that permit, the user to recombine or relink the Application with a modified version of the Linked Version to produce a modified Combined Work, in the manner specified by section 6 of the GNU GPL for conveying Corresponding Source.</li>
<li>Use a suitable shared library mechanism for linking with the Library. A suitable mechanism is one that (a) uses at run time a copy of the Library already present on the user\'s computer system, and (b) will operate properly with a modified version of the Library that is interface-compatible with the Linked Version.</li>
</ul>
<li>Provide Installation Information, but only if you would otherwise be required to provide such information under section 6 of the GNU GPL, and only to the extent that such information is necessary to install and execute a modified version of the Combined Work produced by recombining or relinking the Application with a modified version of the Linked Version. (If you use option 4d0, the Installation Information must accompany the Minimal Corresponding Source and Corresponding Application Code. If you use option 4d1, you must provide the Installation Information in the manner specified by section 6 of the GNU GPL for conveying Corresponding Source.)</li>
</ul>
</div><br />

<strong onclick="$(\'#lgpl5\').slideToggle();">5. Combined Libraries.</strong><br />
<div id="lgpl5" style="display: none;">
You may place library facilities that are a work based on the Library side by side in a single library together with other library facilities that are not Applications and are not covered by this License, and convey such a combined library under terms of your choice, if you do both of the following:<br />

<ul>
<li>Accompany the combined library with a copy of the same work based on the Library, uncombined with any other library facilities, conveyed under the terms of this License.</li>
<li>Give prominent notice with the combined library that part of it is a work based on the Library, and explaining where to find the accompanying uncombined form of the same work.</li>
</ul>
</div><br />

<strong onclick="$(\'#lgpl6\').slideToggle();">6. Revised Versions of the GNU Lesser General Public License.</strong><br />
<div id="lgpl6" style="display: none;">
The Free Software Foundation may publish revised and/or new versions of the GNU Lesser General Public License from time to time. Such new versions will be similar in spirit to the present version, but may differ in detail to address new problems or concerns.<br /><br />

Each version is given a distinguishing version number. If the Library as you received it specifies that a certain numbered version of the GNU Lesser General Public License “or any later version” applies to it, you have the option of following the terms and conditions either of that published version or of any later version published by the Free Software Foundation. If the Library as you received it does not specify a version number of the GNU Lesser General Public License, you may choose any version of the GNU Lesser General Public License ever published by the Free Software Foundation.<br /><br />

If the Library as you received it specifies that a proxy can decide whether future versions of the GNU Lesser General Public License shall apply, that proxy\'s public statement of acceptance of any version is permanent authorization for you to choose that version for the Library.
</div>',0,1); ?>
<h3 id="copyright_jquery">jQuery</h3>
<?php
echo container('GNU GENERAL PUBLIC LICENSE VERSION 2','<small>Version 2, June 1991<br /><br /></small>

<tt>Copyright (C) 1989, 1991 Free Software Foundation, Inc.  
51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA<br />

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.</tt><br /><br />

<h4 onclick="$(\'#gplv2-preamble\').slideToggle();">Preamble</h4>
<div id="gplv2-preamble" style="display: none;">
The licenses for most software are designed to take away your freedom to share and change it. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free software--to make sure the software is free for all its users. This General Public License applies to most of the Free Software Foundation\'s software and to any other program whose authors commit to using it. (Some other Free Software Foundation software is covered by the GNU Lesser General Public License instead.) You can apply it to your programs, too.<br /><br />

When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.<br /><br />

To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights. These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.<br /><br />

For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.<br /><br />

We protect your rights with two steps: (1) copyright the software, and (2) offer you this license which gives you legal permission to copy, distribute and/or modify the software.<br /><br />

Also, for each author\'s protection and ours, we want to make certain that everyone understands that there is no warranty for this free software. If the software is modified by someone else and passed on, we want its recipients to know that what they have is not the original, so that any problems introduced by others will not reflect on the original authors\' reputations.<br /><br />

Finally, any free program is threatened constantly by software patents. We wish to avoid the danger that redistributors of a free program will individually obtain patent licenses, in effect making the program proprietary. To prevent this, we have made it clear that any patent must be licensed for everyone\'s free use or not licensed at all.<br /><br />

The precise terms and conditions for copying, distribution and modification follow.
</div><br />

<h4 onclick="$(\'#gplv2-terms\').slideToggle();">TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION</h4>
<div id="gplv2-terms" style="display: none;">
<ol>
<li>This License applies to any program or other work which contains a notice placed by the copyright holder saying it may be distributed under the terms of this General Public License. The "Program", below, refers to any such program or work, and a "work based on the Program" means either the Program or any derivative work under copyright law: that is to say, a work containing the Program or a portion of it, either verbatim or with modifications and/or translated into another language. (Hereinafter, translation is included without limitation in the term "modification".) Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not covered by this License; they are outside its scope. The act of running the Program is not restricted, and the output from the Program is covered only if its contents constitute a work based on the Program (independent of having been made by running the Program). Whether that is true depends on what the Program does.</li>

<li>You may copy and distribute verbatim copies of the Program\'s source code as you receive it, in any medium, provided that you conspicuously and appropriately publish on each copy an appropriate copyright notice and disclaimer of warranty; keep intact all the notices that refer to this License and to the absence of any warranty; and give any other recipients of the Program a copy of this License along with the Program.

You may charge a fee for the physical act of transferring a copy, and you may at your option offer warranty protection in exchange for a fee.</li>

<li>You may modify your copy or copies of the Program or any portion of it, thus forming a work based on the Program, and copy and distribute such modifications or work under the terms of Section 1 above, provided that you also meet all of these conditions:

<ul>
<li>You must cause the modified files to carry prominent notices stating that you changed the files and the date of any change.</li>
<li>You must cause any work that you distribute or publish, that in whole or in part contains or is derived from the Program or any part thereof, to be licensed as a whole at no charge to all third parties under the terms of this License.</li>
<li>If the modified program normally reads commands interactively when run, you must cause it, when started running for such interactive use in the most ordinary way, to print or display an announcement including an appropriate copyright notice and a notice that there is no warranty (or else, saying that you provide a warranty) and that users may redistribute the program under these conditions, and telling the user how to view a copy of this License. (Exception: if the Program itself is interactive but does not normally print such an announcement, your work based on the Program is not required to print an announcement.)</li>
</ul>

These requirements apply to the modified work as a whole. If identifiable sections of that work are not derived from the Program, and can be reasonably considered independent and separate works in themselves, then this License, and its terms, do not apply to those sections when you distribute them as separate works. But when you distribute the same sections as part of a whole which is a work based on the Program, the distribution of the whole must be on the terms of this License, whose permissions for other licensees extend to the entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest your rights to work written entirely by you; rather, the intent is to exercise the right to control the distribution of derivative or collective works based on the Program.

In addition, mere aggregation of another work not based on the Program with the Program (or with a work based on the Program) on a volume of a storage or distribution medium does not bring the other work under the scope of this License.</li>

<li>You may copy and distribute the Program (or a work based on it, under Section 2) in object code or executable form under the terms of Sections 1 and 2 above provided that you also do one of the following:

<ul>
<li>Accompany it with the complete corresponding machine-readable source code, which must be distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,</li>
<li>Accompany it with a written offer, valid for at least three years, to give any third party, for a charge no more than your cost of physically performing source distribution, a complete machine-readable copy of the corresponding source code, to be distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,</li>
<li>Accompany it with the information you received as to the offer to distribute corresponding source code. (This alternative is allowed only for noncommercial distribution and only if you received the program in object code or executable form with such an offer, in accord with Subsection b above.)
The source code for a work means the preferred form of the work for making modifications to it. For an executable work, complete source code means all the source code for all modules it contains, plus any associated interface definition files, plus the scripts used to control compilation and installation of the executable. However, as a special exception, the source code distributed need not include anything that is normally distributed (in either source or binary form) with the major components (compiler, kernel, and so on) of the operating system on which the executable runs, unless that component itself accompanies the executable.</li>

If distribution of executable or object code is made by offering access to copy from a designated place, then offering equivalent access to copy the source code from the same place counts as distribution of the source code, even though third parties are not compelled to copy the source along with the object code.</li>

<li>You may not copy, modify, sublicense, or distribute the Program except as expressly provided under this License. Any attempt otherwise to copy, modify, sublicense or distribute the Program is void, and will automatically terminate your rights under this License. However, parties who have received copies, or rights, from you under this License will not have their licenses terminated so long as such parties remain in full compliance.</li>

<li>You are not required to accept this License, since you have not signed it. However, nothing else grants you permission to modify or distribute the Program or its derivative works. These actions are prohibited by law if you do not accept this License. Therefore, by modifying or distributing the Program (or any work based on the Program), you indicate your acceptance of this License to do so, and all its terms and conditions for copying, distributing or modifying the Program or works based on it.</li>

<li>Each time you redistribute the Program (or any work based on the Program), the recipient automatically receives a license from the original licensor to copy, distribute or modify the Program subject to these terms and conditions. You may not impose any further restrictions on the recipients\' exercise of the rights granted herein. You are not responsible for enforcing compliance by third parties to this License.</li>

<li>If, as a consequence of a court judgment or allegation of patent infringement or for any other reason (not limited to patent issues), conditions are imposed on you (whether by court order, agreement or otherwise) that contradict the conditions of this License, they do not excuse you from the conditions of this License. If you cannot distribute so as to satisfy simultaneously your obligations under this License and any other pertinent obligations, then as a consequence you may not distribute the Program at all. For example, if a patent license would not permit royalty-free redistribution of the Program by all those who receive copies directly or indirectly through you, then the only way you could satisfy both it and this License would be to refrain entirely from distribution of the Program.

If any portion of this section is held invalid or unenforceable under any particular circumstance, the balance of the section is intended to apply and the section as a whole is intended to apply in other circumstances.

It is not the purpose of this section to induce you to infringe any patents or other property right claims or to contest validity of any such claims; this section has the sole purpose of protecting the integrity of the free software distribution system, which is implemented by public license practices. Many people have made generous contributions to the wide range of software distributed through that system in reliance on consistent application of that system; it is up to the author/donor to decide if he or she is willing to distribute software through any other system and a licensee cannot impose that choice.

This section is intended to make thoroughly clear what is believed to be a consequence of the rest of this License.</li>

<li>If the distribution and/or use of the Program is restricted in certain countries either by patents or by copyrighted interfaces, the original copyright holder who places the Program under this License may add an explicit geographical distribution limitation excluding those countries, so that distribution is permitted only in or among countries not thus excluded. In such case, this License incorporates the limitation as if written in the body of this License.</li>

<li>The Free Software Foundation may publish revised and/or new versions of the General Public License from time to time. Such new versions will be similar in spirit to the present version, but may differ in detail to address new problems or concerns.

Each version is given a distinguishing version number. If the Program specifies a version number of this License which applies to it and "any later version", you have the option of following the terms and conditions either of that version or of any later version published by the Free Software Foundation. If the Program does not specify a version number of this License, you may choose any version ever published by the Free Software Foundation.</li>

<li>If you wish to incorporate parts of the Program into other free programs whose distribution conditions are different, write to the author to ask for permission. For software which is copyrighted by the Free Software Foundation, write to the Free Software Foundation; we sometimes make exceptions for this. Our decision will be guided by the two goals of preserving the free status of all derivatives of our free software and of promoting the sharing and reuse of software generally.</li><br />

<b>NO WARRANTY</b><br />

<li>BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.</li>

<li>IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</li>
</ol>
</div>',0,1);

echo container('MIT License','Copyright (c) 2010 John Resig, http://jquery.com/<br /><br />

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:<br /><br />

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.<br /><br />

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.',0,1);
?>
<h3 id="copyright_geshi">geSHi</h3>
<?php
echo container('GNU GENERAL PUBLIC LICENSE VERSION 2','<small>Version 2, June 1991<br /><br /></small>

<tt>Copyright (C) 1989, 1991 Free Software Foundation, Inc.  
51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA<br />

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.</tt><br /><br />

<h4 onclick="$(\'#gplv2-1-preamble\').slideToggle();">Preamble</h4>
<div id="gplv2-1-preamble" style="display: none;">
The licenses for most software are designed to take away your freedom to share and change it. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free software--to make sure the software is free for all its users. This General Public License applies to most of the Free Software Foundation\'s software and to any other program whose authors commit to using it. (Some other Free Software Foundation software is covered by the GNU Lesser General Public License instead.) You can apply it to your programs, too.<br /><br />

When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.<br /><br />

To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights. These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.<br /><br />

For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.<br /><br />

We protect your rights with two steps: (1) copyright the software, and (2) offer you this license which gives you legal permission to copy, distribute and/or modify the software.<br /><br />

Also, for each author\'s protection and ours, we want to make certain that everyone understands that there is no warranty for this free software. If the software is modified by someone else and passed on, we want its recipients to know that what they have is not the original, so that any problems introduced by others will not reflect on the original authors\' reputations.<br /><br />

Finally, any free program is threatened constantly by software patents. We wish to avoid the danger that redistributors of a free program will individually obtain patent licenses, in effect making the program proprietary. To prevent this, we have made it clear that any patent must be licensed for everyone\'s free use or not licensed at all.<br /><br />

The precise terms and conditions for copying, distribution and modification follow.
</div><br />

<h4 onclick="$(\'#gplv2-1-terms\').slideToggle();">TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION</h4>
<div id="gplv2-1-terms" style="display: none;">
<ol>
<li>This License applies to any program or other work which contains a notice placed by the copyright holder saying it may be distributed under the terms of this General Public License. The "Program", below, refers to any such program or work, and a "work based on the Program" means either the Program or any derivative work under copyright law: that is to say, a work containing the Program or a portion of it, either verbatim or with modifications and/or translated into another language. (Hereinafter, translation is included without limitation in the term "modification".) Each licensee is addressed as "you".

Activities other than copying, distribution and modification are not covered by this License; they are outside its scope. The act of running the Program is not restricted, and the output from the Program is covered only if its contents constitute a work based on the Program (independent of having been made by running the Program). Whether that is true depends on what the Program does.</li>

<li>You may copy and distribute verbatim copies of the Program\'s source code as you receive it, in any medium, provided that you conspicuously and appropriately publish on each copy an appropriate copyright notice and disclaimer of warranty; keep intact all the notices that refer to this License and to the absence of any warranty; and give any other recipients of the Program a copy of this License along with the Program.

You may charge a fee for the physical act of transferring a copy, and you may at your option offer warranty protection in exchange for a fee.</li>

<li>You may modify your copy or copies of the Program or any portion of it, thus forming a work based on the Program, and copy and distribute such modifications or work under the terms of Section 1 above, provided that you also meet all of these conditions:

<ul>
<li>You must cause the modified files to carry prominent notices stating that you changed the files and the date of any change.</li>
<li>You must cause any work that you distribute or publish, that in whole or in part contains or is derived from the Program or any part thereof, to be licensed as a whole at no charge to all third parties under the terms of this License.</li>
<li>If the modified program normally reads commands interactively when run, you must cause it, when started running for such interactive use in the most ordinary way, to print or display an announcement including an appropriate copyright notice and a notice that there is no warranty (or else, saying that you provide a warranty) and that users may redistribute the program under these conditions, and telling the user how to view a copy of this License. (Exception: if the Program itself is interactive but does not normally print such an announcement, your work based on the Program is not required to print an announcement.)</li>
</ul>

These requirements apply to the modified work as a whole. If identifiable sections of that work are not derived from the Program, and can be reasonably considered independent and separate works in themselves, then this License, and its terms, do not apply to those sections when you distribute them as separate works. But when you distribute the same sections as part of a whole which is a work based on the Program, the distribution of the whole must be on the terms of this License, whose permissions for other licensees extend to the entire whole, and thus to each and every part regardless of who wrote it.

Thus, it is not the intent of this section to claim rights or contest your rights to work written entirely by you; rather, the intent is to exercise the right to control the distribution of derivative or collective works based on the Program.

In addition, mere aggregation of another work not based on the Program with the Program (or with a work based on the Program) on a volume of a storage or distribution medium does not bring the other work under the scope of this License.</li>

<li>You may copy and distribute the Program (or a work based on it, under Section 2) in object code or executable form under the terms of Sections 1 and 2 above provided that you also do one of the following:

<ul>
<li>Accompany it with the complete corresponding machine-readable source code, which must be distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,</li>
<li>Accompany it with a written offer, valid for at least three years, to give any third party, for a charge no more than your cost of physically performing source distribution, a complete machine-readable copy of the corresponding source code, to be distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,</li>
<li>Accompany it with the information you received as to the offer to distribute corresponding source code. (This alternative is allowed only for noncommercial distribution and only if you received the program in object code or executable form with such an offer, in accord with Subsection b above.)
The source code for a work means the preferred form of the work for making modifications to it. For an executable work, complete source code means all the source code for all modules it contains, plus any associated interface definition files, plus the scripts used to control compilation and installation of the executable. However, as a special exception, the source code distributed need not include anything that is normally distributed (in either source or binary form) with the major components (compiler, kernel, and so on) of the operating system on which the executable runs, unless that component itself accompanies the executable.</li>

If distribution of executable or object code is made by offering access to copy from a designated place, then offering equivalent access to copy the source code from the same place counts as distribution of the source code, even though third parties are not compelled to copy the source along with the object code.</li>

<li>You may not copy, modify, sublicense, or distribute the Program except as expressly provided under this License. Any attempt otherwise to copy, modify, sublicense or distribute the Program is void, and will automatically terminate your rights under this License. However, parties who have received copies, or rights, from you under this License will not have their licenses terminated so long as such parties remain in full compliance.</li>

<li>You are not required to accept this License, since you have not signed it. However, nothing else grants you permission to modify or distribute the Program or its derivative works. These actions are prohibited by law if you do not accept this License. Therefore, by modifying or distributing the Program (or any work based on the Program), you indicate your acceptance of this License to do so, and all its terms and conditions for copying, distributing or modifying the Program or works based on it.</li>

<li>Each time you redistribute the Program (or any work based on the Program), the recipient automatically receives a license from the original licensor to copy, distribute or modify the Program subject to these terms and conditions. You may not impose any further restrictions on the recipients\' exercise of the rights granted herein. You are not responsible for enforcing compliance by third parties to this License.</li>

<li>If, as a consequence of a court judgment or allegation of patent infringement or for any other reason (not limited to patent issues), conditions are imposed on you (whether by court order, agreement or otherwise) that contradict the conditions of this License, they do not excuse you from the conditions of this License. If you cannot distribute so as to satisfy simultaneously your obligations under this License and any other pertinent obligations, then as a consequence you may not distribute the Program at all. For example, if a patent license would not permit royalty-free redistribution of the Program by all those who receive copies directly or indirectly through you, then the only way you could satisfy both it and this License would be to refrain entirely from distribution of the Program.

If any portion of this section is held invalid or unenforceable under any particular circumstance, the balance of the section is intended to apply and the section as a whole is intended to apply in other circumstances.

It is not the purpose of this section to induce you to infringe any patents or other property right claims or to contest validity of any such claims; this section has the sole purpose of protecting the integrity of the free software distribution system, which is implemented by public license practices. Many people have made generous contributions to the wide range of software distributed through that system in reliance on consistent application of that system; it is up to the author/donor to decide if he or she is willing to distribute software through any other system and a licensee cannot impose that choice.

This section is intended to make thoroughly clear what is believed to be a consequence of the rest of this License.</li>

<li>If the distribution and/or use of the Program is restricted in certain countries either by patents or by copyrighted interfaces, the original copyright holder who places the Program under this License may add an explicit geographical distribution limitation excluding those countries, so that distribution is permitted only in or among countries not thus excluded. In such case, this License incorporates the limitation as if written in the body of this License.</li>

<li>The Free Software Foundation may publish revised and/or new versions of the General Public License from time to time. Such new versions will be similar in spirit to the present version, but may differ in detail to address new problems or concerns.

Each version is given a distinguishing version number. If the Program specifies a version number of this License which applies to it and "any later version", you have the option of following the terms and conditions either of that version or of any later version published by the Free Software Foundation. If the Program does not specify a version number of this License, you may choose any version ever published by the Free Software Foundation.</li>

<li>If you wish to incorporate parts of the Program into other free programs whose distribution conditions are different, write to the author to ask for permission. For software which is copyrighted by the Free Software Foundation, write to the Free Software Foundation; we sometimes make exceptions for this. Our decision will be guided by the two goals of preserving the free status of all derivatives of our free software and of promoting the sharing and reuse of software generally.</li><br />

<b>NO WARRANTY</b><br />

<li>BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.</li>

<li>IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</li>
</ol>
</div>',0,1);
?>
<h3 id="copyright_recaptcha">reCAPTCHA</h3>
<?php
echo container('MIT License','Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net<br /><br />
AUTHORS:<br />
<ul>
<li>Mike Crawford</li>
<li>Ben Maurer</li>
</ul><br />

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:<br /><br />

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.<br /><br />

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.',0,1);
?>
</div>
<?php
/* Document End */
echo documentEnd();
?>