<?php
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Library Beta 6
 * Guess what?! - Time to go OOP. All new functions should be OOP, and we should gradually convert the rest of it to OOP. Finish goal of Beta 6. */

require('libold.php'); // Functions depreciated that may be removed soon.

class fileManager {
  public function __construct() {
    global $uploadDirectory,$accessDirectory;
    $baseDir = $uploadDirectory . '/' . $accessDirectory . '/';
    while (strstr($baseDir,'//') !== false) { $baseDir = str_replace('//','/',$baseDir); }
    $this->baseDir = $baseDir;
  }

  public function testValue($value) {
    if (!$value) return false;
  }

  public function setDir($dir) {
    $dir = $this->baseDir . $dir;

    while (strstr($dir,'//') !== false) { $dir = str_replace('//','/',$dir); }
    if (substr($dir,0,1) != '/') { $dir = '/' . $dir; }
    if (substr($dir,-1,1) != '/') { $dir = $dir . '/'; }

    $this->activeDir = $dir;
  }

  function parentDirectory($dir) {
    $dirPieces = explode('/',$dir);
    foreach ($dirPieces as $key => $piece) {
      if ($piece == '') {
        unset($dirPieces[$key]);
      }
    }

    $totalPieces = count($dirPieces) - 1;
    unset($dirPieces[$totalPieces]);
    $parentDirectory = implode('/',$dirPieces);
    return $parentDirectory . '/';
  }

  function filePart($path) {
    $dirPieces = explode('/',$path);
    foreach ($dirPieces as &$piece) {
      if (($piece === '') || ($piece === null)) {
        unset($piece);
      }
    }
    $totalPieces = count($dirPieces) - 1;
    $finalPiece = $dirPieces[$totalPieces];
    return $finalPiece;
  }

  public function setFile($dir,$file) {
    $dir = $this->setDir($dir);
    $fullPath = $this->activeDir . $file;

    $this->activeFile = $fullPath;
  }

  public function createFile($content = null,$overwrite = false) {
    $dir = $this->activeDir;
    if (!is_dir($this->activeDir)) {
      $this->createDir($overwrite,0777);
    }
  }

  public function createDir($overwrite = false,$perm = 0777) {
    echo $dir = $this->activeDir;
    echo $parentDir = $this->parentDirectory($this->activeDir);

    if (!is_dir($parentDir)) {
      $this->activeDir = $parentDir;
      $this->createDir(false,$perm);
    }
    if (is_dir($dir)) {
      if (!$overwrite) { trigger_error($dir . ' cannot be created because it already exists and is not to be overwritten',E_USER_ERROR); return false; }
      else {
        if(!deleteDir($dir)) { trigger_error($dir . ' already exists and cannot be deleted.',E_USER_ERROR); return false; }
      }
    }

    if(mkdir($dir,$perm)) { return true; }
    else { trigger_error($dir . ' can not be created for unknown reasons.',E_USER_ERROR); return false; }
  }
}

class fileConstruct {
  public function setString($string) {
    $this->string = $string;
  }

  public function safeFile() {
    $this->string = preg_replace('[\W]','_',$this->string);
  }
}

class getBrowser {
  public function __construct() {
    $this->browsers = array(
      'msie' => array('msie'),
      'firefox' => array('firefox','shiretoko','granparadiso','minefield','namoroka','phoenix','bonecho','fennec','firebird','prism'), // Minefield is a common Firefox "Alpha" code-word, "Narkoma" was 3.6-Dev and 4.0-dev, "Shiretoko" 3.5-Dev, "GranParadiso" 3.0-Dev, "Bonecho" 2.0-Dev, "Phoenix" 1.0-Dev, "Firebird" a rarely used name somtimes around the same time as Phoenix, "Fennec" Firefox Mobile, and Prism may not even exist.
      'chrome' => array('chrome','chromium'), // Chromium is the open-source project behind Chrome.
      'konqueror' => array('rekonq','konqueror','arora'), // Arora and Rekonq are spin-off browsers. Konqueror may use either WebKit or KHTML.
      'netscape' => array('netscape','navigator'),
      'opera' => array('opera'),
      'mosaic' => array('mosaic'),
      'lynx' => array('lynx'),
      'amaya' => array('amaya'),
      'omniweb' => array('omniweb'),
      'blackberry' => array('blackberry'),
      'dillo' => array('dillo'),
      'links' => array('links','elinks'),
      'icab' => array('icab'),
      'seamonkey' => array('seamonkey'), 
      'playstation 3' => array('playstation 3'),
      'galeon' => array('galeon'),
      'k-meleon' => array('k-meleon'),
      'camino' => array('camino'),
      'songbird' => array('songbird'),
      'wget' => array('wget'),
      'bluefish' => array('bluefish'),
      'epiphany' => array('epiphany'),
      'safari' => array('safari','iphone os','mobile safari'), // Iphone OS may not exist, Mobile Safari redundant
      'bot' => array('googlebot-image','googlebot','yahoo! slurp','msnbot','bingbot'), // MSNBot has been replaced by Bingbot, may not be needed.
    );

    $this->osfam = array(
      'Linux' => array('linux'),
      'Windows' => array('windows','win32'),
      'Macintosh' => array('mac os x','macintosh','mac_powerpc','mac platform x'), // The latter two may not exist.
      'Other' => array('darwin'), // Something from Apple
      'iOS' => array('ios','iphone os'), // iPhone, iPad, iPod; Apple does not yet use "iOS", instead opting for "iPhone OS" despite the name change - still seems like a good idea to be prepared.
      'Unix' => array('freebsd','openbsd','solaris'),
    );

    $this->osversion = array(
      'Windows' => array(
        '7' => 'windows nt 6.1', // Also server 2008 r2
        'Vista' => 'windows nt 6.0', // Also server 2008
        'Server 2003' => 'windows nt 5.2', // Also used with XP x64, XP Itanium/ia64m, and Home Server
        'XP' => 'windows nt 5.1',
        '2000' => 'windows nt 5.0',
        'NT' => 'windows nt', // NT not labeled below 5.0
        '98' => 'windows 98', // Similar to NT 4.1
        '95' => 'windows 95', // Similar to NT 4.0
        '3.1' => 'windows 3.1', // Similar to NT 3.1
      ),
    );

    $this->architectures = array(
      'i386' => array('i386','i686','intel','win32'), // Intel is used by Intel Macs.
      'ppc' => array('ppc','ppc64'), // ppc64 is redundant, and is very scarce being most "64-bit" era Macs run Intel.
      'amd64' => array('amd64','x86_64','win64','x64'), // amd64 may not be used.
    );

    $this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
  }

  // Set the user agent.
  public function setUserAgent($userAgent) {
    $this->userAgent = strtolower($userAgent);
    $this->length = strlen($this->userAgent); // Get the length of the agent.
  }

  // Get the specific useragent browser. The class function below should be called to indentify the browser.
  public function getBrowserBit() {;
    foreach($this->browsers as $class => $browsers) { // Run through each browser.
      foreach ($browsers as $browser) {
        $n = stripos($this->userAgent, $browser); // Start by checking if the browser exists in the user agent.

        if ($n !== false) { // If n strictly returns false,then the string was not found above. 0 is at the beginning (like Opera 9), anything above 0 is somewhere else in the user agent.
          $this->browser = $browser;
          $break = true;
          break;
        }
      }
      if ($break) {
        $this->browserClass = $class;
        break;
      }
    }
  }

  // Get the specific useragent browser. The class function below should be called to indentify the browser.
  public function getPlatformBit() {
    foreach($this->osfam as $fam => $oses) { // Run through each browser.
      foreach ($oses as $os) {
        if (strstr($this->userAgent,$os)) {
          $this->osFamily = $os;
          $break = true;
          break;
        }
      }
      if ($break) {
        $this->operatingSystem = $fam;
        break;
      }
    }
  }

  // Get the architecture, useful for debians, etc.
  public function getArcBit() {
    foreach($this->architectures as $mainarc => $arcs) { // Run through each browser.
      foreach ($arcs as $arc) {
        if (strstr($this->userAgent,$arc)) {
          $this->architecture = $mainarc;
          $break =  true;
          break;
        }
      }
      if ($break) {
        break;
      }
    }
  }

  public function getVersionBit() {
    // Override for Opera 10. In an ideal world, this same annoying trick will be used in IE-10 (probably 4-5 years from writing this) and Chrome 10 (if Google sticks with its 6-week schedule, probably Feburary of 2011). Any bets?
    // This is also required for Safari, but executes slightly differently.
    if (stristr($this->userAgent,'version')) {
      $n = stripos($this-userAgent,'version');
    }
    else {
      $n = stripos($this->userAgent, $this->browser);
    }

    $j = $n + 1; // Find the position where the navigator was placed. After this, we should see the version.

    for ($j; $j <= $this->length; $j++) { // Run through until the end of the string, though it should break sooner.
      $s = substr($this->userAgent,$j,1); // This is the hopeful position of the version, plus whatever iteration.
      if (is_numeric($s)) { // Make sure its numeric. Remember, decimals in PHP is_numeric function return true, so 5., 5.0., and 5.00000001e3 are all numeric (though the last one could pose problems if actually encountered).
        switch ($sdec) {
          case 0: $this->majorVersion .= $s; break;
          case 1: $this->minorVersion .= $s; break;
          case 2: $this->minorVersion2 .= $s; break;
          case 3: $this->minorVersion3 .= $s; break;
        }
        $this->version .= $s;
        $check = true;
      }
      elseif ($s == '.') {
        $this->version .= '.';
        $sdec += 1;
      }
      // If s is not a decimal, denoting a further version, and a version was found above, exit.
      elseif (($s != '.') && ($check)) {
        break;
      }
    }
  }
}

// Converts file name to file that can be safely executed anywhere.
function safeFile($string) {
  return $string = preg_replace('[\W]','_',$string);
}

/* createFile v.2 */
function createFile($dir,$file,$content = null,$overwrite = 0) {
  if ($dir) {
    $dir = formatDir($dir);
    if (!is_dir($dir)) {
      if (!createDir($dir,0777)) {
        trigger_error($dir  . 'does not exist and cannot be created.',E_USER_ERROR);
      }
    }
    $file = $dir . $file;
  }

  if (lockedFile($file)) {
    trigger_error($file . ' is protected.',E_USER_ERROR);
    return false;
  }
  else {
    if (file_exists($file)) {
      if (!$overwrite) { trigger_error($file . ' already exists and is not to be overwritten.',E_USER_ERROR); return false;}
      if (!is_writable($file)) { trigger_error($file . ' already exists and is not writable.',E_USER_ERROR); return false; }
      if(!deleteFile(null,$file)) { trigger_error($file . ' already exists and cannot be removed',E_USER_ERROR); return false; }
    }

    if ($content) {
      if (file_put_contents($file, $content)) { return true; }
      else { trigger_error($file . ' cannot be written for unknown reasons.',E_USER_ERROR); return false; }
    }
    else {
      if(touch($file)) { return true; }
      else { trigger_error($file . ' cannot be written for unknown reasons.',E_USER_ERROR); return false; }
    }
  }
}

/* uploadFile v.2
 * Security Note on Using Rename() instead: If a file already exists, it must be overwritten, a permission only granted if a file can be deleted, which usually is higher than moving/renaming files. This largely makes move_uploaded_file() useless. */ 
function uploadFile($dir,$file,$overwrite = 0) {
  if ($dir) {
    $dir = formatDir($dir);
    if (!is_dir($dir)) {
      if(!createDir($dir,0777)) { trigger_error($dir . ' Cannot be created.',E_USER_ERROR); return false; }
    }
    $file = $dir . $file;
  }

  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); return false; }
  else {
    if (file_exists($file)) {
      if ($overwrite == 0) { trigger_error($file . ' already exists and is not to be overwritten.',E_USER_ERROR); return false; }
      if (!is_writable($file)) { trigger_error($file . ' already exists and is not writable.',E_USER_ERROR); return false; }

      if(!deleteFile(null,$file)) { trigger_error($file . ' cannot be removed.',E_USER_ERROR); return false; }
    }

    if(rename($_FILES["file"]["tmp_name"],$file)) { return true; }
    else { trigger_error($file . ' cannot be uploaded for unknown reasons.',E_USER_ERROR); return false; }
  }
}

/* moveFile() v.2.1 */
function moveFile($oldDir,$newDir,$oldFile,$newFile,$overwrite = 0,$upload = false,$url = false) {
  if (!$newFile) { $newFile = $oldFile; }
  if ($oldDir) { $oldDir = formatDir($oldDir); $oldFile = $oldDir . $oldFile; }
  if ($newDir) { $newDir = formatDir($newDir); $newFile = $newDir . $newFile; }

  if (lockedFile($oldFile)) { trigger_error($oldFile . ' is protected.',E_USER_ERROR); return false; }
  elseif (lockedFile($newFile)) { trigger_error($newFile . ' is protected.',E_USER_ERROR); return false; }
  elseif (!file_exists($oldFile)) { trigger_error($oldFile . ' does not exist.',E_USER_ERROR); return false; }
  else {
    if (file_exists($newFile)) {
      if ($overwrite == 0) { trigger_error($newFile . ' already exists and is not to be overwritten.',E_USER_ERROR); return false; }
      else {
        if (!is_writable($newFile)) { trigger_error($newFile . ' already exists and can not be overwritten because it is not writable.',E_USER_ERROR); return false; }
        if (!deleteFile(null,$newFile)) { trigger_error($newFile . ' already exists and can not be overwritten for unknown reasons.',E_USER_ERROR); return false; }
      }
    }

    if (!file_exists($oldFile)) { trigger_error($oldFile . ' does not exist.',E_USER_ERROR); return false; }
    if (!is_writable($oldFile)) { trigger_error($oldFile . ' is not writable.',E_USER_ERROR); return false; }
    if ((!is_writable($newDir)) && ($newDir == true)) { trigger_error($newDir . ' is not writable.',E_USER_ERROR); return false; }

    if ($upload) {
      if (!is_uploaded_file($oldFile)) { trigger_error($oldFile . ' is not an uploaded file, but will be moved as one.',E_USER_WARNING); }
      if (move_uploaded_file($oldFile,$newFile)) { return true; }
      else { trigger_error($oldFile . ' could not be uplaoded for unknown reasons.',E_USER_ERROR); return false; }
    }
    else {
      if (rename($oldFile,$newFile)) { return true; }
      else { trigger_error($oldFile . ' could not be moved for unknown reasons.',E_USER_ERROR); return false; }
    }
  }
}

function renameFile($dir,$oldFile,$newFile,$overwrite = 0) {
  if ($dir) { $dir = formatDir($dir); $oldFile = $dir . $oldFile; $newFile = $dir . $newFile; }

  if (lockedFile($oldFile)) { trigger_error($oldFile . ' is protected.',E_USER_ERROR); return false; }
  elseif (lockedFile($newFile)) { trigger_error($newFile . ' is protected.',E_USER_ERROR); return false; }
  elseif (!file_exists($oldFile)) { trigger_error($oldFile . ' does not exist.',E_USER_ERROR); return false; }
  else {
    if (file_exists($newFile)) {
      if ($overwrite == 0) { trigger_error($newFile . ' already exists and is not to be overwritten.',E_USER_ERROR); return false;}
      if (!is_writable($newFile)) { trigger_error($newFile . ' already exists and can not be overwritten because it is not writable.',E_USER_ERROR); return false; }

      if (!deleteFile(null,$newFile)) { trigger_error($newFile . ' already exists and can not be overwritten for unknown reasons.'); return false; }
    }

    if (!is_writable($oldFile)) { trigger_error($oldFile . ' is not writable.',E_USER_ERROR); return false; }

    if(rename($oldFile,$newFile)) { return true; }
    else { return false; }
  }
}

function readFileIntoString($dir,$file,$limit = true,$safeMode = false) {

  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }

  if (lockedFile($file)) { trigger_error($file . ' is locked.',E_USER_ERROR); }
  else {
    if (!is_file($file)) { trigger_error($file . ' is not a file.',E_USER_ERROR); return false; }
    if (!is_readable($file)) { trigger_error($file . ' is not readable.',E_USER_ERROR); return false; }
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_WARNING); }

    /* The file_get_contents function will only process $limit as no limit if its not set, so we eval the code to make everything work. */
    $file_get_contents = '$content = file_get_contents($file' . ((($limit === true) || ($limit === false)) ? '' : ',0,null,-1,$limit') . ');';
    eval($file_get_contents);
    if (false !== $content) { 
      if (strlen($content) == 0) { trigger_error($file . ' is empty.',E_USER_WARNING); return ''; }
      return $content;
    }
    else { trigger_error($file . ' cannot be read for unknown reasons.',E_USER_ERROR); return false; }
  }
}

function writeFileFromString($dir,$file,$content) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }

  if (lockedFile($file)) { trigger_error($file . '  is protected.',E_USER_ERROR); return false; }
  else {
    if (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); return false; }
    if (!is_readable($file)) { trigger_error($file . ' is not readable.', E_USER_ERROR); return false; }
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_ERROR); return false; }
    if (empty($content)) { trigger_error('No content was specified to be written.',E_USER_WARNING); return false; }

    if (file_put_contents($file,$content)) { return true; }
    else { trigger_error($file . ' could not be edited for unknown reasons.',E_USER_ERROR); return false;  }
  }
}

/*function removeBackups($dir,$file) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }

  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); return false; }
  else {
    if (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); return false; }
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_WARNING); return false; }
    $files = listFiles(null,$file . '~');
    if (empty($files)) { trigger_error('No backups exist.',E_USER_ERROR); return false; }
    foreach ($files as $file) { deleteFile(null,$file['full']); }
    return true;
  }
  if (!isset($dieErrors)) {
    return true;
  }
  else {
    return false;
  }
}

function listBackups($dir,$file) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }
  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); return false; }
  else {
    if (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); return false; }
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_WARNING); return false; }
    $files = listFiles(null,$file . '~');
    if (empty($files)) { trigger_error('No backups exist.',E_USER_ERROR); return false; }
    foreach ($files as $file) {
      $string .= '<option value="' . $file['lastMod'][0] . '">' . $file['lastMod'][1] . '</option>';
    }
    return $string;
  }
}

function revertBackup($file,$ut) {
  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); }
  else {
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_ERROR); return false; }
    if (file_exists($file)) {
      if (!unlink($file)) { trigger_error($file . ' can not be deleted.',E_USER_ERROR); }
    }
    if(rename($file . '~' . $ut,$file)) { return true; }
    else { return false; }
  }
}*/

function chmodFile($dir,$file,$value) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }

  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); }
  elseif (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); }
  else {
    if (!is_writable($file)) { trigger_error($file . ' is not writable.',E_USER_ERROR); }

    if (chmod($file,$value)) { return true; }
    else { trigger_error($file . ' could not be modified.',E_USER_ERROR); }
  }
}

function chmodDir($dir,$value,$valueDec = false) {
  $dir = formatDir($dir);

  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); }
  elseif (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); }
  else {
    if (!is_writable($dir)) { trigger_error($file . ' is not wrtiable.',E_USER_ERROR); }
    else {
      if (chmod($dir,$value)) { return true; }
      else { return false; }
    }
  }
}

function deleteFile($dir,$file) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }

  if (lockedFile($file)) { trigger_error($file . ' is protected.',E_USER_ERROR); }
  else {
    if (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); }
    if (!is_writable($file)) { trigger_error($file . ' is not readable.',E_USER_ERROR); }
    if(unlink($file)) { return true; }
    else { trigger_error($file . ' could not be deleted for unknown reasons.',E_USER_ERROR); }
  }
}

function createDir($dir,$overwrite = 0,$perm = 0777) {
  $dir = formatDir($dir);
  $parentDir = parentDirectory($dir);

  if (lockedFile($dir)) { trigger_error($dir . ' is protected.',E_USER_ERROR); return false; }
  //elseif (!is_writable($parentDir)) { trigger_error('The parent directory, ' . $dir . ' is not writable.',E_USER_ERROR); return false; }
  else {
    if (is_dir($dir)) {
      if (!$overwrite) { trigger_error($dir . ' cannot be created because it already exists and is not to be overwritten',E_USER_ERROR); return false; }
      else {
        if(!deleteDir($dir)) { trigger_error($dir . ' already exists and cannot be deleted.',E_USER_ERROR); return false; }
      }
    }
    if(mkdir($dir,$perm)) {  }
    else { trigger_error($dir . ' can not be created for unknown reasons.',E_USER_ERROR); return false; }
  }
}

function moveDir($oldDir,$newDir,$overwrite = 0) {
  $oldDir = formatDir($oldDir);
  $newDir = formatDir($newDir);

  if(lockedFile($oldDir)) { trigger_error($oldDir . ' is protected.',E_USER_ERROR); return false; }
  elseif (lockedFile($newDir)) { trigger_error($newDir . ' is protected.',E_USER_ERROR); return false; }
  elseif (!is_dir($oldDir)) { trigger_error($oldDir . ' does not exist.',E_USER_ERROR); return false; }
  else {
    if (is_dir($newDir)) {
      if (!$overwrite) { trigger_error($newDir . ' already exists, and is not to be overwritten.',E_USER_ERROR); return false; }
      else { 
        if(!deleteDir($newDir)) { trigger_error($newDir . ' already exists, and can not be deleted.'); return false; }
      }
    }

    if (!is_writable($oldDir)) { trigger_error($oldDir . ' is not writable.',E_USER_ERROR); return false; }
    if(rename($oldDir,$newDir)) { return true; }
    else { return false; }
  }
}


function deleteDir($dir) {
  $dir = formatDir($dir);

  if (lockedFile($dir)) { trigger_error($dir . ' is protected.',E_USER_ERROR); return false; }
  elseif (!is_dir($dir)) { trigger_error($dir . ' does not exist.',E_USER_ERROR); return false; }
  else {
    if (!is_writable($dir)) { trigger_error($dir . ' is not writable.',E_USER_ERROR); return false; }
    ////function listFiles($dir,$nameFilter = null,$extFilter = null,$hiddenFiles = null,$type = false,$recursive = false,$mode = false,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false)) {
    $files = listFiles($dir,null,null,null,false,true,'seperate',array());
    foreach($files['files'] as $file) { deleteFile(null,$file['full']); }
    foreach($files['dirs'] as $dir) { deleteDir($dir['full']); }

    if (rmdir($dir)) { return true; }
    else { return false; }
  }
}

/* Should be modified to be class. */

/* isDot (string $file)
 * $file - A string containing only the file name (not the directory).
 * Returns true if the $file conforms to the Fliler dot file pattern (/^.(.+?)/) */
function isDot ($file) {
  if (strpos($file,'.') === 0) {
    return true;
  }
  return false;
}

/* isBackup (string $file)
 * $file - A string containing only the file name (not the directory)
 * Returns true if the $file conforms to the Fliler backup file pattern (/~[0-9]+$/) */
function isBackup ($file) {
  if (preg_match('/~[0-9]+$/',$file)) {
    return true;
  }
  return false;
}

/* size(valid directory string $dir, valid file string $file)
 * $dir - A sting containing a valid directory.
 * $file - A string containing a valid file w/o the directory.
 * Returns an array, the first value being and integer with number of bytes in the file, the second being a friendly string of this. */
function size($dir,$file) {
  $size = filesize($dir . $file);
  $friendlySize = friendlySize($size);
  return array($size,$friendlySize);
}

function friendlySize($b,$r = 2) {
  $values = array(
    'B', 'KiB', 'MiB',
    'GiB', 'TiB', 'PiB',
    'EiB', 'ZiB', 'YiB',
  );
  $v = 0;

  while ($b >= 1024) {
    $b /= 1024;
    $v += 1;
  }
  $friendlySize = round($b,$r) . $values[$v];
  return $friendlySize;
}

/* mod(valid directory string $dir, valid file string $file)
 * $dir - A sting containing a valid directory.
 * $file - A string containing a valid file w/o the directory.
 * Returns an array, the first value being and integer with the modification time of the file, the second being a friendly string of this*/
function mod($dir,$file) {
  $modTime = filemtime($dir . $file);
  $friendlyMod = date('M d, Y (g:iA)', $modTime);
  return array($modTime,$friendlyMod);
}

function getOwner($dir,$file) {
  $owner = fileowner($dir . $file);
  $owner = posix_getpwuid($owner);
  $owner = $owner['name'];
  return $owner;
}

/* getExt (string $file)
 * Finds the extension according to the Fliler backup pattern. */
function getExt($file) {
  $extParts = explode('.',$file);
  if (strstr($file,'.')) {
    $ext = $extParts[count($extParts) - 1];
    $extParts = explode('~',$ext);
    $ext = $extParts[0];
  }
  else {
    $ext = '';
  }
  return $ext;
}

/* getName(string $file)
 * Finds the name of a file w/o the extension according to the Fliler backup pattern. */
function getName($file) {
  $fileParts = explode ('.', $file);
  $fileWinName = $fileParts[0];
  $fileParts = explode ('/', $fileWinName);
  $fileWinName = $fileParts[count($fileParts) - 1];
  return $fileWinName;
}

function getMime($dir,$file) {
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo,$dir . $file);
  finfo_close($finfo);
  return $mime;
}

/* fileData(string $dir, string $file) */
function fileData($dir,$file,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false)) {
  global $uploadDirectory, $tmpPathLocal, $zipRecreateDir;
  if (!$dir) {
    $dir = parentDirectory($file);
    $file = filePart($file);
  }
  else {
    $dir = formatDir($dir);
  }


  // FTP Directory
  if (strstr($dir,'ftp:')) {
    echo 'FTP Mode';
  }
  elseif (strstr($dir,'flilerBackup:')) {
    echo 'Fliler Backup Mode';
  }
  elseif (strstr($dir,'zip:')) {
    $filePath = preg_replace('/(.+)zip:(.+)\//','$1$2',$dir);
    $fileData = fileData(null, $filePath);
    $dest = $uploadDirectory . $tmpPathLocal . $fileData['file'] . '/';
    if (is_dir($dest) && !$zipRecreateDir) {

    }
    else {
      unzip(null,$fileData['full'],$dest);
    }

    return fileData($dest,$file,$data);
  }
  else {
  if (lockedFile($dir . $file)) {
    trigger_error($dir . $file . ' is protected.',E_USER_ERROR);
    return false;
  }
  elseif (!file_exists($dir . $file)) {
    trigger_error($dir . $file . ' does not exist.',E_USER_ERROR);
    return false;
  }
  else {
    // Backup file check
    if ($data['backup']) $return['backup'] = isBackup($file);

    // Dot check
    if ($data['dot']) $return['dot'] = isDot($file);

    // Size Check
    if ($data['size']) $return['size'] = size($dir,$file);

    // Last Mod Check
    if ($data['lastMod']) $return['lastMod'] = mod($dir,$file);

    // Extension Check
    if ($data['ext']) $return['ext'] = getExt($file);

    // Name Check
    if ($data['name']) $return['name'] = getName($file);

    // Owner Check
    if ($data['owner']) $return['owner'] = getOwner($dir,$file);

    // Mime Check
    if ($data['mime']) $return['mime'] = getMime($dir,$file);

    // Content Check
    if (($data['content'] !== false && $data['content'] > 0) && ($return['size'][0] > 0 || !$data['size'])) {
      $return['content'] = @readFileIntoString($dir,$file,$data['content']);
    }

    $return['full'] = $dir . $file;
    $return['file'] = $file;
    $return['dir'] = $dir;

    return $return;
  }
  }
}

function parentDirectory($dir) {
  $dirPieces = explode('/',$dir);
  foreach ($dirPieces as &$piece) {
    if (($piece === '') || ($piece === null)) {
      unset($piece);
    }
  }
  $totalPieces = count($dirPieces) - 1;
  unset($dirPieces[$totalPieces]);
  $parentDirectory = implode('/',$dirPieces);
  return $parentDirectory . '/';
}

function filePart($path) {
  $dirPieces = explode('/',$path);
  foreach ($dirPieces as &$piece) {
    if (($piece === '') || ($piece === null)) {
      unset($piece);
    }
  }
  $totalPieces = count($dirPieces) - 1;
  $finalPiece = $dirPieces[$totalPieces];
  return $finalPiece;
}

function unzip($dir,$file,$dest) {
  global $uploadDirectory, $tmpPathLocal;

  if (!$dir) {
    $dir = parentDirectory($file);
    $file = filePart($file);
  }
  else {
    $dir = formatDir($dir);
  }

  if (!$dest) {
    $dest = $uploadDirectory . $tmpPathLocal . $file . '/';
  }

  if (lockedFile($dir . $file)) {
    trigger_error($dir . $file . ' is protected.',E_USER_ERROR);
    return false;
  }
  elseif (!file_exists($dir . $file)) {
    trigger_error($dir . $file . ' does not exist.',E_USER_ERROR);
    return false;
  }
  else {
    $zip = new ZipArchive;
    $zipFile = $zip->open($dir . $file);
    if ($zipFile === true) {
      $zip->extractTo($dest);
    }
    else {
      trigger_error($dir . $file . ' could not be opened.',E_USER_ERROR);
    }

    $zip->close();
  }
}

/* List Files Function
 ** Parameters **
 * $dir - The directory to scan.
 * $nameFilter - A glob filter for names.
 * $extFilter - A glob filter for extensions, formatted in an array.
 * $hideFiles - An array of file that should not be shown.
 * $type - A mixed value containing 'dir' to only show directores, 'file' to only show files, or anything else to show everything.
 * $recursive - Whether files should be scanned recursively.
 * $mode - How the returned files should be formatted
   ** structured: In this recursive mode, file contents are stored in a deeper array for the directory's "content" field.
   ** seperate: In this non-recursive mode, files are returned in index 1 as an array and directories in index 0.
   ** Otherwise files and directories occupy the same dimension of the same array.
 * $data - An array of what data should be included in the scan.

 ** Config Parameters **
 * $ignoreBackupSyntax - Whether extensions should be determined using backup syntax.
 * $hideDotFiles - Whether or not dot files should be hidden.
 * $memorySafety - Will attempt exit if nearing PHP out-of-memory. */
function listFiles($dir,$nameFilter = null,$extFilter = null,$hiddenFiles = null,$type = false,$recursive = false,$mode = false,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false)) {
  global $ignoreBackupSyntax, $hideDotFiles, $uploadDirectory, $tmpPathLocal, $zipRecreateDir;

  // Create the file and dir containers.
  $files = array();
  $dirs = array();

  // As with the general library structure, it should be possible to have $dir be null and $nameFilter containing the directory. Handle this here.
  if (!$dir) {
    $dir = parentDirectory($nameFilter);
    $nameFilter = filePart($nameFilter);
  }

  // FTP Directory
  if (strstr($dir,'ftp:')) {
    echo 'FTP Mode';
  }
  elseif (strstr($dir,'flilerBackup:')) {
    echo 'Fliler Backup Mode';
  }
  elseif (strstr($dir,'zip:')) {
    $filePath = preg_replace('/(.+)zip:(.+)\//','$1$2',$dir);
    $fileData = fileData(null, $filePath);
    $dest = $uploadDirectory . $tmpPathLocal . $fileData['file'] . '/';
    if (is_dir($dest) && !$zipRecreateDir) {

    }
    else {
      unzip(null,$fileData['full'],$dest);
    }

    return listFiles($dest,$nameFilter,$extFilter,$hiddenFiles,$type,$recursive,$mode,$data);
  }
  else {
  // Make sure the directory exists.
  if (is_dir($dir)) {
    // Glob is considerably faster than either scandir or readdir. It also is fairly extensible, so filters are easier to implement.
    $nameString = (($nameFilter) ? $nameFilter : '') . '*';
    // The backup syntax is no longer used with Fliler (backups are stored in the DB), but "~" still denotes a backup file in several Unix environments, most notably KDE (and to some extent Gnome). While this practice is less common, we include support for the ~ unless otherwise disabled with use of "ignoreBackupSyntax".
    $extString = (!empty($extFilter) ? '.{' . $extFilter . '}' . ($ignoreBackupSyntax ? '' : '{,~}*') : null);
    $dotString = (($hideDotFiles) ? '' : '{,.}');

    if ($type === 'dir') $flags = GLOB_BRACE|GLOB_ONLYDIR;
    else $flags = GLOB_BRACE;
    $dirFiles = glob($dir . $dotString . $nameString . $extString,$flags);

    foreach ($dirFiles as $file) {
      // Glob returns whole file names, so remove the directory from the file.
      $file = filePart($file);

      // Make sure the file is not '.' or '..'.
      if ($file == '.' || $file == '..') continue;
      // Make sure the file is not a hidden file.
      elseif (($hiddenFiles) && (in_array($dir . $file,$hiddenFiles))) continue;
      // Make sure the file is readable.
      elseif (!is_readable($dir . $file)) continue;
      // Get the file's data.
      elseif (!$fileData = @fileData($dir, $file, $data)) continue;

      // Check if the file is actually a directory.
      if (is_dir($dir . $file)) {
        if ($type === 'file') continue;
        $fileData['type'] = 'dir';

        if ($recursive) {
          $append = listFiles($dir . $file . '/',$nameFilter,$extFilter,$hiddenFiles,$type,$recursive,$mode,$data);

          switch($mode) {
            // Returns data in a structured, multi-dimensional array.
            case 'structured':
            $fileData['contents'] = $append;
            $files[] = $fileData;
            break;

            // Returns two arrays, one with the files and one with the directories.
            case 'seperate':
            $dirs[] = $fileData;
            $files = array_merge($files,$append['files']);
            $dirs = array_merge($dirs,$append['dirs']);
            break;

            // Return data in a single-dimensional array.
            default:
            $files[] = $fileData;
            $files = array_merge($files,$append);
            break;
          }
        }
        else {
          if ($mode == 'seperate') $dirs[] = $fileData;
          else $files[] = $fileData;
        }
      }
      elseif (is_file($dir . $file)) {
        $fileData['type'] = 'file';
        $files[] = $fileData;
      }
    }

    return (($mode == 'seperate') ? array('dirs' => $dirs, 'files' => $files) : $files);
  }
  else {
    trigger_error($dir . ' does not exist.',E_USER_ERROR);
    return false;
  }
  }
}

function dieErrors($dieErrors) {
  $return .= '<ul>';
  foreach ($dieErrors as $dieError) {
    $return .= '<li>' . $dieError . '</li>';
  }
  $return .= '</ul>';
  return $return;
}

function readArray($array) {
  global $uploadDirectory;
  static $selected, $listings;

  foreach($array as $entry) {
    // Usually one or two "uninitialized string offset" notices will appear here, so hide errors.
    $alias = @str_replace($uploadDirectory,null,$entry['full']);
    $count = substr_count($alias,'/');
    $listings .= '<option value="' . $alias . '" ' . ((!$selected) ? 'selected="selected"' : '') . '>' . str_repeat('&nbsp;&nbsp;',$count) . $alias . '/</option>';
    $selected = true;
  }
  return $listings;
}

function getMemLimit($val = false) {
  if (!$val) { $val = trim(ini_get('memory_limit')); }

  return parseConfigNum($val);
}

//http://us3.php.net/manual/en/ini.core.php
function parseConfigNum($v){
  $l = substr($v, -1);
  $ret = substr($v, 0, -1);
  switch(strtoupper($l)){
    case 'P': $ret *= 1024;
    case 'T': $ret *= 1024;
    case 'G': $ret *= 1024;
    case 'M': $ret *= 1024;
    case 'K': $ret *= 1024;
    break;
  }
  return $ret;
}

function listDirs($dirName = 'dir',$fileName = 'file',$tabIndex = null) {
  global $uploadDirectory, $directorySelect, $fileSelect, $selectMaxDepth, $hideDotFiles;
  static $listings;

  if ($directorySelect) {
    $files = array_merge(array(''),listFiles($uploadDirectory,null,null,null,'dir',true,false,array()));
  }

  if ($files) {
    $listings .= '<select name="' . $dirName . '" id="' . $dirName . '"' . (($fileSelect) ? ' onchange="changeField(\'' . $dirName . '\')" onselect="changeField(\'' . $dirName . '\')"' : '') . (($tabIndex) ? ' tabindex="' . $tabIndex . '"' : '') . '>
' .  readArray($files) . '
</select>' . (($fileSelect) ? '
<script>changeField("' . $dirName . '");</script>' : '');
  }
  else {
    $listings .= '<input name="' . $dirName . '" type="text" />';
  }
  return $listings;
}

function lockedFile($file) {
  global $lockedFiles;
  if (in_array($file,$lockedFiles)) {
    return true;
  }
  else {
    return false;
  }
}

/* Format Directory
 * Pass $special as true to automatically include the access directory and upload directory. This should NOT be used in library functions, which should be capable of doing stuff everywhere. */
function formatDir($dir,$special = false) {
  global $uploadDirectory,$accessDirectory;
  if ($special) {
    $dir = $uploadDirectory . $accessDirectory . $dir;
  }
  while (strstr($dir,'//') !== false) {
    $dir = str_replace('//','/',$dir);
  }
  if (substr($dir,0,1) != '/') {
    $dir = '/' . $dir;
  }
  if (substr($dir,-1,1) != '/') {
    $dir = $dir . '/';
  }
  return $dir;
}

function cleanDirUrl($f,$e,$n = '',$t = '',$s = '',$r = '') {
  static $path;
  $path = '';

  if ($f) {
    $path .= '&f=' . $f;
    if ($e) { $path .= '&e=' . $e; }
    if ($n) { $path .= '&n=' . $n; }
  }
  elseif (isset($t)) { $path .= '&t=' . $t; }

  if ($s) { $path .= '&s=' . $s; }
  if ($r) { $path .= '&r=' . $r; }

  return $path;
}

function highlightCode($dir,$file,$geshiPath = '.geshi/geshi.php') {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }
  $data = fileData(null,$file,array('name' => true, 'content' => true, 'ext' => true));

  if (is_file($geshiPath)) {
    require_once($geshiPath);

    // The second parameter "Language" is unrequired and will be disregarded shortly.
    $geshi = new GeSHi ($data['content'],'php');
    $lang = $geshi->get_language_name_from_extension($data['ext']);
    /* If GeSHi is unable to determine the file language, return plain text. */
    if ($lang == '') {
      $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      $geshi->set_language('');
      $content = $geshi->parse_code();
    }
    else {
      $geshi->enable_classes();
      $geshi->set_language($lang);
      $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      $content = $geshi->parse_code();
    }
    return '<link rel="stylesheet" type="text/css" href=".geshi/geshi_styles.css" />' . $content;
  }
  else {
    return nl2br($data['content']);
  }
}

function randomString($length = 4) {
  for ($zero; $zero < $length; $zero += 1) {
    $type = rand(0,2);
    switch ($type) {
      // Add a number.
      case 0:
      $string .= rand(0,9);
      break;
      // Add a lowercase letter.
      case 1:
      $string .= chr(rand(65,90));
      break;
      // Add an uppercase letter.
      case 2:
      $string .= chr(rand(97,122));
      break;
    }
  }
  return $string;
}

function fileSelect($dir) {
  global $hideDotFiles;
  static $return;

  $dir = formatDir($dir);//function listFiles($dir,$nameFilter = null,$extFilter = null,$hiddenFiles = null,$type = false,$recursive = false,$mode = false,$data = array('backup' => true,'dot' => true,'size' => true,'lastMod' => true,'ext' => true,'name' => true,'owner' => true,'mime' => false,'content' => false)) {
  $files = listFiles($dir,null,null,null,'file',false,false,array());
  if ($files) {
    $return .= '<select name="file" id="file">';
    foreach ($files as $file) {
      $return .= '<option value="' . $file['file'] . '">' . $file['file'] . '</option>';
    }
    $return .= '</select>';
    return $return;
  }
  else {
    return '<select name="file" id="file" disabled="disabled"></select>';
  }
}

function downloadFile($dir,$file,$fileName = null,$mime = null) {
  if ($dir) { $dir = formatDir($dir); $file = $dir . $file; }
  
  if (lockedFile($oldFile)) { trigger_error($oldFile . ' is protected.',E_USER_ERROR); return false; }
  if (!file_exists($file)) { trigger_error($file . ' does not exist.',E_USER_ERROR); return false; }
  if (!is_readable($file)) { trigger_error($file . ' can not be read.',E_USER_ERROR); return false; }

  error_reporting(0);
  $data = fileData(null,$file,array('ext' => true,'mime' => true));

  if (!$mime) {
    $mime = $data['mime'];
  }
  if (!$fileName) { $fileName = $data['file']; }

  header('Content-Description: Directory Download'); // Specify the description of the file to the browser.
  header('Content-type: ' . $mime); // The mime type the file should be treated as.
  header('Content-Disposition: attachment; filename=' . $fileName); // The file name.
  header('Content-Transfer-Encoding: binary'); // Binary transfer.

  // Don't cache.
  header('Expires: 0');
  header("Cache-control: private");

  // Clear the output buffer for the sake of CPU and RAM.
  ob_end_clean();
  flush();

  $fp = fopen($data['full'], 'r');
  while (!feof($fp)) {
    echo fread($fp, (getMemLimit() > 32 * 1024 * 1024 ? 16 * 1024 * 1024 : 1024 * 1024)); // Packet size of 16MB, or 1MB if sufficient RAM is not available.
  }
}

/* Container Function
 * $title: The title of the container.
 * $message: The message in the container.
 * $class: The CSS class to be used. 1, .5, and 0 are reserved integers for use as Critical Error, Warning Error, and Notice.
 * $hide: Whether the container should be collapsed by default.
 * $lock: Whether to prevent the show/hide functionality. Can not be used with $hide. */
function container($title,$message,$class = false,$hide = false,$lock = false) {
  global $occurence, $containerType;
  $occurence += 1;

  switch ($class) {
    case null: case 0: $class = 'alert3'; break;
    case .5: $class = 'alert2'; break;
    case 1: $class = 'alert1'; break;
    default: $class = $err; break;
  }
  switch ($hide) {
    case null: case 0: $display = 'block'; break;
    case 1: $display = 'none'; break;
    default: $display = 'block'; break;
  }
  if ($containerType == 'table') {
    return '<table class="container">
<thead' . (($lock) ? '' : ' onclick="headCollapse(\'message' . $occurence . '\')"') . '><tr class="containerHeader"><td class="' . $class . '">' . $title . '</td></tr></thead>
<tbody id="message' . $occurence . '" style="display: ' . $display . ';"><tr><td>' . $message . '</td></tr></tbody>
</table>
';
  }
  else {
    return '<fieldset class="container">
<legend' . (($lock) ? '' : ' onclick="headCollapse(\'message' . $occurence . '\')"') . '><span class="' . $class . '">' . $title . '</span></legend>
<div id="message' . $occurence . '">' . $message . '</div>
</fieldset>
';
  }
}

/* Document Start */
function documentStart($title) {
  global $branding, $user, $enableBrowserDetection, $enableJquery;

  if ($enableBrowserDetection) {
    $browser = getBrowser();
    if ($browser['browser'] == 'msie') {
      switch ($browser['majorVersion']) {
        case 6: $enableJquery = true; $include = 'styles-ie6.js'; break;
        case 7: $enableJquery = true; $include = 'styles-ie7.js'; break;
      }
    }
  }

  $return = '<!DOCTYPE HTML>
<!-- Generated Source Copyright (c) 2010 by Joseph Parsons
  -- The generated source of this file is a part of Fliler.
  -- Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
  -- Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
  -- You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. -->
<html>
<head>
  <title>' . $branding . ': Online File Manager - ' . $title . '</title>
  <meta name="author" content="Joseph Parsons" />
  <meta name="generator" content="Fliler v.B6" />
  <link rel="stylesheet" type="text/css" href="styles.css" />' . (($enableJquery) ? '
  <script type="text/javascript" src="jquery.js"></script>' : '') . (($include) ? '<script type="text/javascript" src="' . $include . '"></script>' : '') . '
  <script type="text/javascript" src="scripts.js"></script>
</head>
<body>
  <div style="float: right; text-align: right;">Logged in as ' . $user['username'] . ' (<a href="logout.php">Logout</a>)<br /><a href="index.php">Return to the Index</a></div>
  <h1 onclick="headCollapse(\'content\')" id="headertext">' . $title . '</h1><hr />
  <div id="content">';
  return $return;
}

function documentEnd() {
  if (defined('DOC_ENDED')) {
    return false;
  }
  else {
    return '</div>
</body>
</html>';
  define('DOC_ENDED',true); // This may be redundant... I know I added it for /some/ reason.
  }
}

/* Boolean Output
 * function boolOutput ( mixed $input [, string $style = 'font-style: italic;' [, bool $color = true]])

 ** Parameters **
 * $input - The input data. This is used loosely.
 * $style - This is the CSS styling. Use null to not style (default "font-style: italics;")
 * $color - This specifies whether colors should be used to highlight return values.

 ** Return Values **
 * The function returns a string formatted with the style and color paramters if input loosely matches null, true, or false, or it returns the input string if it returns none of the above. */
function boolOutput($input,$style = 'font-style: italic;',$color = true) {
  // Create the CSS string.
  $styles = $style;
  if ($color) {
    $colors = array(
      null => 'rbg(0,0,0)',
      true => 'rgb(0,127,0)',
      false => 'rgb(127,0,0)',
    );
    $styles .= 'color: ' . $colors[$input];
  }

  // Check strictly to see if the input is null, or if it is empty.
  if (($input === null) || ($input === '')) {
    return '<span style="' . $styles . '">Null</span>';
  }
  // Check loosely to see if the input is false.
  elseif ($input == false) {
    return '<span style="' . $styles . '">False</span>';
  }
  // Check loosely to see if the input is true.
  elseif ($input == true) {
    return '<span style="' . $styles . '">True</span>';
  }
  // If the input is none of the above, return it unmodified.
  else {
    return $input;
  }
}

/* URL will skip file_exsts and is_readable actions, and will encode the string with urlencode(). */
function copyFile($oldFile,$newFile,$overwrite = 0,$url = false) {
  if (lockedFile($oldFile)) { trigger_error($oldFile . ' is protected.',E_USER_ERROR); return false; }
  elseif (lockedFile($newFile)) { trigger_error($newFile . ' is protected.',E_USER_ERROR); return false; }
  else {
    if (!$url) {
      if (!file_exists($oldFile)) { trigger_error($oldFile . ' does not exist.',E_USER_ERROR); return false; }
      if (!is_readable($oldFile)) { trigger_error($oldFile . ' is not readable.',E_USER_ERROR); return false; }
    }
    if (file_exists($newFile)) {
      if ($overwrite == 0) { trigger_error($newFile . ' already exists and can not be overwritten.',E_USER_ERROR); return false; }
      if (!is_writable($newFile)) { trigger_error($newFile . ' already exists and is not writable.',E_USER_ERROR); return false; }
      if (!deleteFile($newFile)) { trigger_error($newFile . ' already exists and can not be deleted.',E_USER_ERROR); return false; }
    }
    if (!copy($oldFile,$newFile)) { return false; }
    else { return true; }
  }
}

function csvtoTable($filename) {
  $fileContents = file_get_contents($filename);
  $replaced = preg_replace("/(\n|\r)/m", '</tr><tr>', $fileContents);
  $replaced = preg_replace('/\'(.*?)\'(,|)/', '<td>$1</td>', $fileContents);
  $replaced = preg_replace('/"(.*?)"(,|)/', '<td>$1</td>', $fileContents);
  $replaced = str_replace("\n", "<tr></tr>", $replaced);
  $replaced = str_replace("\r", "<tr></tr>", $replaced);
  return '<table border="1">' . $replaced . '</table>';
}

function bufferErrors() {
  global $errors,$warnings,$notices,$usererrors,$userwarnings,$usernotices,$errorsCommon,$errorsDetailed,$errorsBuffered,$errosPhp;
  static $return;
  $error_text = array(
    E_USER_ERROR => 'Error',
    E_USER_WARNING => 'Warning',
    E_USER_NOTICE => 'Notice',
    E_ERROR => 'PHP Error',
    E_WARNING => 'PHP Warning',
    E_NOTICE => 'PHP Notice',
    E_STRICT => 'Strict Error',
    E_DEPRECATED => 'Depreciated Error',
  );

  $error_codes = array(
    E_USER_ERROR => 1,
    E_USER_WARNING => .5,
    E_USER_NOTICE => 0,
    E_ERROR => 1,
    E_WARNING => .5,
    E_NOTICE => 0,
    E_STRICT => .5,
    E_DEPRECATED => .5,
  );

  foreach ($error_text as $errno => $value) {
    if ($errors[$errno]) {
      $return .= container($value,'<ul>' . $errors[$errno] . '</ul>',$error_codes[$errno]);
    }
  }
  return $return;
}



function errorHandler($errno, $errstr, $errfile, $errline) {
  global $errors,$errorsBuffered,$errorsDetailed,$errorsCommon;

  $error_text = array(
    E_USER_ERROR => 'Error',
    E_USER_WARNING => 'Warning',
    E_USER_NOTICE => 'Notice',
    E_ERROR => 'PHP Error',
    E_WARNING => 'PHP Warning',
    E_NOTICE => 'PHP Notice',
    E_STRICT => 'Strict Error',
    E_DEPRECATED => 'Depreciated Error',
  );

  $error_codes = array(
    E_USER_ERROR => 1,
    E_USER_WARNING => .5,
    E_USER_NOTICE => 0,
    E_ERROR => 1,
    E_WARNING => .5,
    E_NOTICE => 0,
    E_STRICT => .5,
    E_DEPRECATED => .5,
  );

  if (error_reporting()) {
    if ($errno == E_NOTICE && !$errorsCommon) return true;

    // Start by generating the string. We'll determine whether or not to echo it later.
    $errorString = $errstr . (($errorsDetailed) ? ' on line ' . $errline . ' in file ' . $errfile : '') . '<br />';
    if ($errorsBuffered) {
      $errors[$errno] .= '<li>' . $errorString . '</li>';
    } else {
     echo container($error_text[$errno],$errorString,$error_codes[$errno]);
    }
    // Log error.
    error_log($errorString);
  }

  // Don't execute PHP internal error handler
  return true;
}

function callback($buffer) {
  global $errorsBuffered,$errorsBufferedPlacement,$enableGzBuffering;

  /* Error Buffering */
  switch($errorsBufferedPlacement) {
    case 'docstart':
    $search = '/^(.+?)/e'; $replace = '[ERR]$1';
    break;
    case 'bodyopen':
    $search = '/<body>/'; $replace = '<body>[ERR]';
    break;
    case 'header':
    $search = '/<h1 onclick="headCollapse\(\)" id="headertext">(.+?)<\/h1>/'; $replace = '<h1 onclick="headCollapse()" id="headertext">$1</h1>[ERR]';
    break;
    case 'contentstart':
    $search = '/<div id="content">/'; $replace = '<div id="content">[ERR]';
    break;
    case 'contentend':
    // Note that this should be agressive, meaning
    $search = '/<\/div><\/body>/'; $replace = '[ERR]</div></body>';
    break;
    case 'bodyclose':
    $search = '/<\/body>/'; $replace = '[ERR]</body>';
    break;
  }
  if ($errorsBuffered) {
    if (preg_match($search,$buffer)) {
      $return = preg_replace($search,str_replace('[ERR]',bufferErrors(),$replace),$buffer);
    }
    else {
      $return = preg_replace('/^(.+?)/m',bufferErrors() . '$1',$buffer);
    }
  }
  else { $return = $buffer; }
  return $return;
}

function splitScripts($scripts) {
  if ($scripts['onClick']) {
    $js .= ' onClick="' . $scripts['onClick'] . '"';
  }
  if ($scripts['onHover']) {
    $js .= ' onHover="' . $scripts['onHover'] . '"';
  }
}

class form {
  private $action;
  private $method = 'post';
  private $id = '';
  private $target = '_self';
  private $enctype = 'application/x-www-form-urlencoded';
  private $class;
  private $style;
  private $script;
  public $form;
  function __construct() {

  }
  function element($type = 'text',$id = '',$value = '',$tabIndex = '',$label = '',$class,$style,$script) {
    if (!$id) {
      trigger_error('No element ID specified.',E_USER_WARNING);
      exit;
    }
    switch($type) {
      case 'text':
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'password':
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="password" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'hidden':
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'button':
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="button" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'reset':
      if (!$value) { $value = 'Reset'; }
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="reset" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'submit':
      if (!$value) { $value = 'Submit'; }
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label>' : '') . '<input type="submit" id="' . $id . '" name="' . $id . '" value="' . $value . '" tabindex="' . $tabIndex . '" />';
      break;
      case 'textarea':
      $this->form .= (($label) ? '<label for="' . $id . '">' . $label . '</label><br />' : '') . '<textarea id="' . $id . '" name="' . $id . '" tabindex="' . $tabIndex . '">' . $value . '</textarea>';
      break;
      case 'email':

      break;
      case 'url':

      break;
      case 'range':

      break;
      case 'slider':

      break;
      case 'time':

      break;
      case 'date':

      break;
    }
  }
  function nl($times) {
    for ($i = 0; $i < $times;$i ++) $this->form .= '<br />';
  }
  function setId($id) {
    $this->id = $id;
  }
  function setAction($action = '') {
    if (!$action) { $action = $_SERVER['PHP_SELF']; }
    $this->action = $action;
  }
  function input($code) {
    $this->form .= $code;
  }
  function getForm() {
    $outputForm = '<form action="' . $this->action . '" method="' . $this->method . '" id="' . $this->id . '" name="' . $this->id . '" target="' . $this->target . '" enctype="' . $this->enctype . '">' . $this->form . '</form>';
    return $outputForm;
  }
}

function jsEscape($string) {
  return str_replace(array("\n","\n\r","'"),array('','','\\\''),$string);
}
?>