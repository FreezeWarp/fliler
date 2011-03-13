<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
require_once('config.php');

/* Document Start */
echo documentStart('Fliler Changelog');

/* Document Content */
?>
<div id="content">
<h2>Changelog</h2>
<ol>
  <li><a href="#dev9">Dev 9</a></li>
  <li><a href="#dev10">Dev 10</a></li>
  <li><a href="#dev11">Dev 11</a></li>
  <li><a href="#dev12">Dev 12</a></li>
  <li><a href="#dev13">Dev 13</a></li>
  <li><a href="#dev14">Dev 14</a></li>
  <li><a href="#beta1">Beta 1</a></li>
  <li><a href="#beta2">Beta 2</a></li>
  <li><a href="#beta3">Beta 3</a></li>
  <li><a href="#beta4">Beta 4</a></li>
  <li><a href="#beta5">Beta 5</a></li>
</ol>
<h2 id="dev9">Dev 9</h2>
<u>File Filtering:</u> You can now filter files listed in the directory browser by either their type or a combination of their name and file extension<br />
<u>Cookie Expiration Setting on Login:</u> You can now set how long you would like to be logged in for on the login page in seconds, minutes, hours, days, and weeks.<br />
<u>Major UAC Change:</u> The UAC file has been split into two, one for the actual managing the actual login procedure, the other for calling the login. This reduces the time taken to actually manage files, as well as better validates the login code.<br />
<u>Browser Bug Fix:</u> Hidden files are now calculated differently to better ensure they stay hidden. This is done by removing excess periods and slashes from the URL.<br />
<u>Index Bug Fix:</u> The fieldset 1 now displays properly.<br />
<u>Login Bug Fix:</u> The font of the "Need to Login" messages now displays red as intended.<br />
<u>Login Bug Fix:</u> The form's action for login has been removed instead of set to the current page as modern browsers go to the current page.<br />
<u>Login Bug Fix:</u> Upon setting a cookie for login, the page will refresh automatically to show that you have logged in (un)successfully.<br />
<u>Logout Bug Fix:</u> Being that most browsers don\'t remove expired cookies until they close, the message for the cookies still existing has been changed accordingly. Also a message has been added if you are already logged out.<br />
<u>View File Bug Fix:</u> As was originally intended, the view file now redirects to the browser if no file has been set.<br />
<u>Rename File, Edit File, and Create File:</u> Several context bugs.<br />
<u>Rename File and Delete Directory:</u> Theme bug fixes.<br />
<u>Everything:</u> Minor code consistency fixes/improvements.

<h2 id="dev10">Dev 10</h2>
<u>Change Mod Scripts:</u> A script for changing the ownership of files has been added. Directory support has not yet been added.<br />
<u>Everywhere:</u> The fieldset design has been implemented throughout the file management suite. This is done by using a function which can easily be changed from the cfg.php file. This should make future changes easier.<br />
<u>Everywhere:</u> Significant consistency throughout the suite.<br />
<u>Everywhere:</u> Fieldsets are now collapsible.<br />
<u>Index:</u> Only actions which you have permissions to use will be displayed.<br />
<u>View:</u> There will no longer be a processing script for directories. Instead you will go strait to the directory viewer.<br />
<u>View:</u> Added abililty to filter results when choosing your directory.<br />
<u>Filter Type:</u> Added "common" filter.<br />
<u>Styles:</u> Added formatting for drop-down list and input type "button".<br />
<u>Hidden Files:</u> Removed "view_dir_go.php" due to it no longer being used. Also added "chmod_file_do.php" and "container.php".<br />
<u>Major Login Bug Fix:</u> Opera became completely unuseable with the addition of UAC. This is only temporary, hopefully a more valid method will be used in the future as this one is not.<br />
<u>Login Bug:</u> Fixed captcha bug created in Dev9 that causes invalid captcha login to return to the normal login screen.<br />
<u>Login Bug:</u> Set the automatic time of 30 minutes in the cookie experation.<br />
<u>Context Bug:</u> Fixed login context bug regarding the login refresh bug fixed in Dev9.<br />
<u>Platform Bug Fix:</u> Removed font-size and height styling for form elements.<br />
<u>Browser Bug Fix:</u> Further fixed hidden files bug, this time with no prefix (instead of extra prefix).<br />
<u>Delete Directory Bug Fixes:</u> Several bug fixes regarding the delete directories page have been fixed.<br />

<h2 id="dev11">Dev 11</h2>
<u>Everywhere:</u> Significant code cleanup and consistancy.<br />
<u>Everywhere:</u> Header code is controlled with a function for easier changes in the future.<br />
<u>Styles:</u> Removal of button styles previously used.<br />
<u>Scripts:</u> Removed all instances of out2 and over2 functions, in favor of out and over.<br />
<u>Login:</u> Better layout conistancy with file managemnet forms.<br />
<u>Login:</u> Minor form code changes that do not make any impact besides easier developer managemnet of forms.<br />
<u>Login:</u> Removal of notification that you can clear the captcha by entering the form blank, as this is no longer supported with the addition of cookie experation setting.<br />
<u>Login:</u> Other smaller code changes that should make some speed impact.<br />
<u>Logout:</u> - Added "Return to Index" link.<br />
<u>Questions Database:</u> 4 captcha questions added.<br />
<u>Upload:</u> Several code changes.<br />
<u>View Directory:</u> File name filter bug fix which searches the extention as well, i.e. a name search for "t" matches hi.txt, because of the .txt extension.<br />
<u>View Directory:</u> Finalized hidden files bug fix.<br />
<u>Move and Create Directory:</u> A context bug has been fixed that upon trying to view the directory from the quick links section, you will instead view the file, as viewfile.php is referenced, not viewdir.php.<br />
<u>File Filter:</u> Several additional file types.<br />
<u>File Filter:</u> Severe bug fixed which makes it impossible to filter files outside the home directory.<br />
<u>Readme:</u>The readme has been converted to an HTML and PHP. It will utilize the standard file managment look, will not require login to view, and will also be able to display the level of support your browser has for the file manager. This feature is still rather buggy, nor does it include data on all browsers. It does, however, recognize most browsers, anyway.

<h2 id="dev12">Dev 12</h2>
<u>Restore a Backup:</u> Backups created by edting files can now be restored more easily through this new function.<br />
<u>Delete all Backups:</u> You can now delete all backups using this new function.<br />
<u>Directory Viewer:</u> Significant changes regarding what you can do have been made with the directory viewer. You can now move files, delete files and folders, and add new directories directly in the file browser, Also, you can now easily go back or foward in your browsing history, move up a directory, refresh the current directory, and go to the index directory through a toolbar. Finally, you can also logout more easily.<br />
<u>Delete Directories:</u> When deleting directories all files inside will also be deleted now.<br />
<u></u><u>Rename File, Move File, and Move Directory:</u> All of these files have been unusable for the past few Devs because of changes made a while ago regarding how form data is handled. Though these problems were not found out about until now, they have been fixed and should work again.<br />
<u>CSS Styles:</u> A display bug in certain browsers regarding the underlining of links has been found to be caused by the css underline property being applied twice in the styles.css files. One would specifiy it be underlined, another not. This has been fixed.<br />
<u>Create File:</u> A minor validation bug has been fixed regarding the opening <head> tag being used twice.

<h2 id="dev13">Dev 13</h2>
<u>UAC:</u> A full GUI was added for user management. Only base-directory Administrators have access to this.<br />
<u>View Directory:</u> Addition of copy to directory, to use as manual backups or simple copying without removing the source file.<br />
<u>View Directory:</u> A bug fix in the font filetype filter which made it unusable and carry-through filtering when you change the directory.<br />
<u>File Elimination:</u> Due to recent additions of images, a new folder named "images" has been added to contain images. Also, multi-step upload actions have been merged into the same script. Also, questions.php has been merged into uac2.php, browser_detection_support.php has been merged into browser_support.php, and file_type.php has been merged into viewdir.php.<br />
<u>View Directory - Move:</u> Added overwrite protection.<br />
<u>View File:</u> Removal of unnecessary code involving getting the file extention of the file.<br />
<u>View Directory Move</u> Fixed context bug.<br />
<u>Revert Backups:</u> Instead of listing time stamps, instead the actual save time is listed.

<h2 id="dev14">Dev 14</h2>
<u>Edit File:</u> HTML Tags (font color, family, and size, bold, italics, underline, and strikethrough, and backgrounds, links, and images) GUI Added.<br />
<u>Manage Backups:</u> You can now create backups of different directories. They will be saved to a root folder, so it is not restricted. Support to restore them will be added soon.<br />
<u>Manage Users:</u> Several small changes to better implement the FM\'s styles.<br />
<u>Scripts:</u> Added Internet Explorer Javascript Rollover support.

<h2 id="beta1">Beta 1</h2>
<u>Entire File Manager:</u> Completely Rewrote all File Manager Functions including Edit, Delete, Move, and Rename for both Files and Directories. The new files are now located in lib/.<br />
<u>Memo:</u> The new memo feature has been added. This is a personal memo for all users.<br />
<u>UAC:</u> If you are not logged in, then you will be redirected to the login screen.<br />
<u>UAC:</u> The UAC now requires MySQL. However, this will also allow for greater security and faster loading.<br />
<u>Index:</u> The index has been reorganized.<br />
<u>Edit File:</u> More tags have been added to the edit file feature. These include a CSS Div and Span, both of which have a CSS database to check for accuracy in CSS tags, as well as a Spoiler. Also, more colors have been added to the existing background and font color tags. Finally, all of this is now better implemented.<br />
<u>Browser Support:</u> The browser support page has been loaded to be less server intensive. However, it is not supported by all servers and browsers.<br /><br />
<u>Regressions:</u> Backups are no longer supported.

<h2 id="beta2">Beta 2</h2>
<u>Universal:</u> Page titles were changed for better indexing.<br />
<u>Style:</u> The new theme "Ice" has been used instead. Likewise, many pages have changed to reflect it.<br />
<u>Scripts:</u> The "headCollapse" function has been added to improve functionality.<br />
<u>Login:</u> A "Non-MySQL" configuration option has been added. Though you will still need MySQL for setting permissions and adding more than one user, you can now at least use the file manager without MySQL.<br />
<u>Login:</u> A "Remember Me" function has been added. It will store the username, both the username and password, or neither for a year\'s time.<br />
<u>View Directory:</u> The file browser backend has been completely rewritten.<br />
<u>Create File:</u> Upon creating a file, the source code, output code (if its HTML), and a link to edit the file will be shown.<br />
<u>Edit File:</u> The HTML editor has been modified. It now shows the HTML output before you submit the form.<br />
<u>View File:</u> Added image viewing container.<br />
<u>Directory Select:</u> Directories will be listed when selecting a directory if set in the configuration file.

<h2 id="beta3">Beta 3</h2>
<u>Global:</u> SQL queries now also include the $mysqlPrefix variable.<br />
<u>Global:</u> Pages are now generated using container functions documentStart() and documentEnd(). These ensure better consistency.<br />
<u>Global:</u> The use of "indent" divs have been removed for buttons.<br />
<u>Styles:</u> IE-specific stylesheet.<br />
<u>SQL Structure:</u> Users and Memo merged, Access Levels added.<br />
<u>Index:</u> Warnings are displayed for if the config.php file is writtable or if the install.php file exist.
<u>Directory Select Library Function:</u> Directories are now sorted for quicker viewing, while also indenting deeper directories.<br />
<u>Directory Select Library Function:</u> This function has been completely rewritten. It now requires PHP5, and runs considerably quicker in larger directories.<br />
<u>Directory Select Library Function:</u> It is now possible to generate files as well as directories. This requires Javascript, and only validates in HTML5 (due to the use of the data-* attribute).<br />
<u>Directory Select Library Function:</u> Recursive link bug fixed.<br />
<u>Directory Select Library Function:</u> $hideDotFiles now applies to the directorry select function, as well.<br />
<u>Browser Support:</u> A browser support page has been added.<br />
<u>Logout:</u> A bug was fixed with removing cookies when only a password or username exists.<br />
<u>UAC:</u> A complete rewrite of the UAC script allowing it to run much faster, while also being much cleaner.<br />
<u>UAC:</u> A new method of encyption is used that is considerably more secure, which involves a random hash and MD5 instead of SHA1.<br />
<u>Edit File:</u> Improved CSS alert to warn for tags that may be used in CSS3, CSS1, or unsupported CSS2.<br />
<u>Edit File:</u> Changed "Text Align" container to a div (previously it was unsupported).<br />
<u>Edit File:</u> Both Vertical and Horizontal modes can be used for HTML editting.<br />
<u>Edit File:</u> Javascript is now in its own file and only runs where appropriate.<br />
<u>User Creation and User Login:</u> Button to display the password.<br />
<u>Levels:</u> Now you can control the complete access of different users by using custom access levels.<br />
<u>Manage Access Levels:</u> Users can now view, modify, and add access levels.<br />
<u>Download:</u> Added the ability to download folders as compressed zip file.<br />
<u>Install:</u> install.php can now be used to install Fliler to the server.<br />
<u>Config:</u> $mysqlPrefix variable added.<br />
<u>Help:</u> Basic help page added.<br />
<u>Memo:</u> Rewritten due to the merge of the memo and user databases.<br />
<u>Boolean Output</u>: Colors are darkened.
<u>View</u>: Image zooming has been disabled. It will be re-enabled in the next beta.<br /><br />
<u>Regressions:</u> User data can no longer be modified.

<h2 id="beta4">Beta 4</h2>
<u>Global</u>: IE8 is now forced to use standards compliance mode.<br />
<u>Install</u>: The database can now also be automatically created.<br />
<u>Styles:</u> Some IE-CSS fixes, including alpha opacity.<br />
<u>Styles:</u> Improved table styles. Used only in viewdir.php right now.<br />
<u>Error Handler</u>: PHP execution errors are now also displayed using the error handler. In this case, the file and line will also be displayed.<br />
<u>Library Functions</u>: Die Errors are now returned as an array, and will usually be interpretted by a seperate function.<br />
<u>Library Functions</u>: All now contained in lib.php. Certain functions, like error.php, are now in the home directory.<br />
<u>View File</u>: Planned for months, you can now view video, audio, and document files as well as text and images. Different container options are offered for each of these. For documents, an AbiWord mode is supplied (requires AbiWord), a wvHtml mode (requires wv, works only with .doc), and generic HTML object and HTML iframe modes. For spreadsheets, a Gnumeric mode is supplied (requires Gnumeric), an HTML table mode (inluded, works only with CSV), and generic HTML object and iframe modes. For slideshows, HTML objects/iframes can be used. For video and audio, HTML-5 audio/video, HTML objects, and HTML iframes are supported.<br />
<u>View File</u>: Restored code highlighting.<br />
<u>View Directory</u>: When filtering files, more advanced name filters (*, ?, [], and {}) and multiple extensions can be shown using a comma seperated list.<br />
<u>View Directory</u>: Restored file deleting, now uses HTTP requests, and uses JQuery for animation<br />
<u>Directory Select</u>: Now using listFiles function. Slower, but more consistent.<br />
<u>List Files</u>: Completely rewritten. Several changes, primarily the ability to return a structured array of data, two seperate arrays containing files and directories, advanced and fast file filter support, the full use of glob for loading files, and recursive support.<br />
<u>Overwrite Trigger</u>: The overwrite trigger now requires a user to have delete permissions where not previously included. Bugs involving the wrong reference of the $perm variable have also been fixed.<br />
<u>Create File</u>: File contents showing now using &lt;blockquote&gt;.<br />
<u>Regressions</u>: Directory selecter no longer indented, hiding backup files no longer supported.<br />
<u>Permanent Regressions</u>: Although their origins were logical, the hidden files variable is no longer included. Instead, locked files should be use to prevent all access to a file.<br />

<h2 id="beta5">Beta 5</h2>
<u>Edit File</u>: Standard container HTML toolbar removed (1-24-10).<br />
<u>Edit File</u>: Standard container delayed HTML bug fixed (1-24-10).<br />
<u>Edit File</u>: Standard container float bug fixed (1-24-10).<br />
<u>Edit File</U>: Now uses fileData backend. (1-24-10).<br />
<u>Edit File</u>: Koivi Editor Introduced (1-24-10). Uses oxygen icons.<br />
<u>fileData()</u>: Now retrives the file contents if optional parameter is passed.<br />
<u>Error Reporting</u>: Compeletely overhauled to allow several configurable options including merged display (3-28-10).<br />
<u>UAC</u>: Rewritten. Functionality removed for "remember me" and automatic log-out as the former is handled by most modern browsers and the latter is bettered by persistent cookie (3-28-10).<br />
<u>Install</u>: Support for config.php file generation and more advanced installation. (3-29-10).<br />
<u>getBrowser()</u>: Added additional support and fixed a few bugs (3-29-10).<br />
<u>Login</u>: No longer redirects to the main page. The implementation may be buggy, ideally having all involved pages rewrite their placement of certain functions (3-29-10).<br />
<u>Containers</u>: Addition of table container (3-29-10).<br />
<u>Styles</u>: Added color classes for warnings (3-29-10).<br />
<u>Browser Support</u>: More accurately returns your support information due to the new use of MajorVersion and MinorVersion in the browser detection function. (3-30-10).<br />
<u>getBrowser()</u>: Fixed small bug (3-30-10).<br />
<u>View Directory</u>: Added file type description lookup. This can be configured with config.php (3-30-10).<br />
<u>MySQL Databases</u>: Added file type table (3-30-10).<br />
<u>View Directory</u>: Added delete file/delete directory functionality (3-31-10).<br />
<u>filetypes.php</u>: Added file type look-up page (3-31-10).<br />
<u>scripts.js</u>: Added two array functions, inArray and removeArrayElement (3-31-10).<br />
<u>viewdir.js</u>: Added deleteDir(), deleteFile(), createDir(), cutFiles(), copyFiles(), and pasteFiles() functions (3-31-10).<br />
<u>View Directory</u>: Added copy/cut/paste functionality (3-31-10).<br />
<u>Styles</u>: Replaced styles.css with styles.php. Will be used for browser overrides (3-31-10).<br />
<u>documentStart()</u>: Removed IE check, IE stylesheet, replaced styles.css with styles.php (3-31-10).<br />
<u>documentStart()</u>: Removed JQuery, GeSHi copyright referrence due to optional inclusion and use of several other libraries (3-31-10).<br />
<u>Config</u>: Added staticExpires variable for use with mostly static PHP pages like styles.css (3-31-10).<br />
<u>Ajax File Select</u>: Fixed "no-files" bug (3-31-10).<br />
<u>Changelog</u>: Split off of help.php (4-17-10).<br />
<u>Readme</u>: Changed to text file (4-17-10).<br />
<u>File Select</u>: Automatically populates the first choice's files (4-17-10).<br />
<u>File Select</u>: Respects "$fileSelect" variable (4-17-10).<br />
<u>Edit File Standard Container and Plain Container, Create File Simple Container</u>: Added input field for textbox size (4-17-10).<br />
<u>Edit File Standard Container</u>: Fixed vertical display toggle (4-17-10).<br />
<u>Create File</u>: Added force file extension option (4-17-10).<br />
<u>Create File</u>: Added help dialoug (4-17-10).<br />
<u>Move File</u>: Added "upload" parameter to use move_uploaded_files (4-19-10).<br />
<u>Upload Files</u>: Added multiple file support (through multiple="multiple" and "Add Another File" button). They are also handled properly by the backend (4-19-10).<br />
<u>Upload Files</u>: Added active file list including file previews if a capable browser (currently only Firefox 3.6) is used (4-19-10).<br />
<u>Styles</u>: Added generic iframe container (4-19-10).<br />
<u>View Files</u>: Fixed AbiWord bug (4-19-10).<br />
<u>View Files (Image)</u>: Added zoom input (4-19-10).<br />
<u>List Files</u>: Added excerpt option (4-19-10).<br />
<u>List Files</u>: Fixed trash extension bug (so x.doc~234 is a doc extension type) (4-19-10).<br />
<u>Upload Files</u>: Added URL upload support (4-20-10).<br />
<u>ReadFileFromString()</u>: Fixed empty return bug (4-21-10).<br />
<u>Upload Files</u>: Added URL previews (4-21-10).<br />
<u>Edit File</u>: Added document editing support w/ AbiWord conversion (4-22-10).<br />
<u>Edit File</u>: Changed AbiWord to single command w/ exec (4-22-10).<br />
<u>View File</u>: Changed AbiWord to single command w/ exec (4-22-10).<br />
<u>Edit File</u>: Removed TinyMCE editor due to file footprint. (4-22-10).<br />
<u>Edit File</u>: Made CKEditor default for document editing. (4-22-10).<br />
<u>Upload File</u>: Added help dialouge. (4-23-10).<br />
<u>Create File</u>: Add tabindex accessibility. (4-23-10).<br />
<u>View File</u>: Removed wvHtml container (4-23-10).<br />
<u>View File</u>: Only shows relevant containers (4-23-10).<br />
<u>View File</u>: Shows file data now (4-23-10).<br />
<u>View File</u>: Shows image data/embed now (4-24-10).<br />
<u>Move File, Rename File</u>: Added help dialouge (4-24-10).<br />
<u>View Directory, View File, Edit File, Upload File, Move File, Rename File</u>: Added urlencode() for file URLs to solve related bugs.<br />
<u>View Directory</u>: Added file creation (w/o content) (4-24-10).<br />
<u>Download File</u>: Added conversion support for images, SVG files, and documents (4-24-10).<br />
<u>View File, View Directory</u>: Removed table-lookup for file extensions (4-24-10).<br />
<u>Help</u>: Rewrote large parts of help (4-25-10).<br />
<u>Download Directory</u>: Added directory download function (4-25-10).<br />
<u>All First-Time Pages</u>: Added help dialogue and left/right/full structure. All functions now show a help entry displaying what they are, as well (4-25-10).<br />

<h2 id="beta6">Beta 6</h2>
<u>Upload Files</u>: Added drop-box technique (5-23-10).<br />
<u>Upload Files</u>: Added max uploads and max file size indicators (5-23-10).<br />
<u>UAC</u>: Added encytped passwords.<br />
<u>Config (Post-Config)</u>: Added PHP 5.0 requirement. May be increased to 5.3 soon (as it is most compatible with PHP 6) (6-2-10).<br />
<u>Config</u>: $errorsPhp, $errorsBufferedUser, $errorsCommon removed. The former two are no longer needed due to a re-written error handler, and all errors triggered by $errorsCommon should soon be removed (6-3-10).<br />
<u>Error Handler</u>: Rewrote to be less redundant. Also added support for E_DEPRICIATED and E_STRICT (although neither should be triggered) (6-3-10).<br />
<u>Styles</u>: Changed alert colors and class names for better consistency (9-04-10).<br />
<u>Config</u>: $errorsCommon re-added (9-04-10).<br />
<u>LibOld</u>: File created and getBrowser() function moved to (9-04-10).<br />
<u>getBrowser Class</u>: Wrote. Includes all of past functionality, fixes some bugs, and adds architecture detection (9-04-10).<br />
<u>Browser Page</u>: Updated with getBrowser class, added table to describe modern and unmodern versions of browsers (9-04-10).<br />
<U>Install</u>: Created fresh. Added most Javascript UI, but no backend (9-04-10).<br />
<u>Help</u>: Made minor changes and drafted skeleton of API calls (9-04-10).<br />
<u>Readme.txt</u>: Deleted, since everything is in the help page anyway (9-04-10).<br />
<u>Recaptcha</u>: If not found then things will degrade nicely (9-04-10).<br />
<u>Help</u>: Updated (9-9-10).<br />
<u>Download Directory</u>: File is downloaded and generated in two steps instead of one; if the file can be accessed through HTTP then no PHP will be used to download it; several changes in memory effiency: Download now requires only 1MB of memory and is able to download nearly any file with 1MB, and generation has been tested to work to create a 500MB file with only 1MB as well. Directories greater than 1GB still tend to fail generation, though, no matter what RAM is supplied (9-12-10).<br />
<u>Template</u>: Moved HTTP-Equiv Meta tags to HTTP headers (9-12-10).<br />
<u>Edit File</u>: Old simple WYSIYG editor added (Beta 2 Frontend; Beta 5 Backend). It is enabled by default when you the advanced trigger is used for the standard editor (for HTML files and Documents if you have Abiword installed for conversion). 
<?php
/* Document End */
echo documentEnd();
?>