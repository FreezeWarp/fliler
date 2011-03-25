<?php
/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* get_browser Replacement - Works faster, doesn't require browsercap.ini, more future-proof
 * getBrowser ([$agent = false])
 * return array('browser','version','majorVersion','minorVersion',platform','userAgent') */
function getBrowser($agent = false) {
  trigger_error('getBrowser function is deprecated.',E_USER_DEPRECATED);
  static $l, $j, $check, $navigatorVersion, $majorVersion, $minorVersion, $minorVersion2, $minorVersion3, $sdec;
  // The agents we should check. This should be a comma seperated list. Note that order is very important: Mozilla is referrenced in nearly every user agent on earth, while Safari appears in Chrome, etc. Any user agent that might appear in another should be placed last generally.
  // Also note that early versions of Opera appear as IE if msie comes before opera. This may not be a bad thing, since this was intentional on Opera's part, but it does mess up the versioning system.
  $browsers = 'msie,firefox,shiretoko,granparadiso,minefield,namoroka,phoenix,bonecho,chrome,chromium,konqueror,safari,netscape,navigator,opera,mosaic,lynx,amaya,omniweb,iphone os,blackberry,dillo,elinks,icab,links,android,seamonkey,firebird,playstation 3,galeon,k-meleon,camino,prism,songbird,wget,bluefish,epiphany,googlebot-image,googlebot,yahoo! slurp,msnbot';

  // This is used to determine if a user agent above is a development of a browser above. All entries below should be above, in the relative same order. Finally, the key is what the output should be, the value is a comma-seperated list that will output as the key.
  /* Notekeeping:
   * Minefield - Firefox Pre-Alpha
   * Narkoma - Firefox 3.6 Dev
   * Shiretoko - Firefox 3.5 Dev
   * Bonecho - Firefox 3.0 Dev
   * Phoenix - Early (pre-1.0) Firefox Builds */
  $merge['firefox'] = 'shiretoko,granparadiso,minefield,namoroka,bonecho,phoenix';
  $merge['chrome'] = 'chrome,chromium';

  // Turn the above into an array.
  $browsers = explode(',', $browsers);

  // Get the agent if none is provided, or use the one provided.
  if (!$agent) {
    $agent = strToLower($_SERVER['HTTP_USER_AGENT']);
  }
  // Get the length of the agent.
  $l = strlen($agent);

  // Run through each browser.
  for ($i = 0; $i < count($browsers); $i++) {
    $browser = $browsers[$i];
    // Start by checking if the browser exists in the user agent.
    $n = stripos($agent, $browser);
    // If n strictly returns false,then the string was not found above. 0 is at the beginning (like Opera 9), anything above 0 is somewhere else in the user agent.
    if ($n !== false) {
      $version = '';
      $navigator = $browser;

      // Override for Opera 10. In an ideal world, this same annoying trick will be used in IE-10 (probably 4-5 years from writing this) and Chrome 10 (anywhere between 4 and 10 years from writing this). Any bets?
      if (stristr($agent,'version')) {
        $n = stripos($agent,'version');
      }

      // Find the position where the navigator was placed. After this, we should see the version.
      $j = $n + strlen($navigatorVersion) + 1;
      // Run through until the end of the string, though it should break sooner.
      for ($j; $j <= $l; $j++) {
        // This is the hopeful position of the version, plus whatever iteration.
        $s = substr($agent,$j,1);
        // Make sure its numeric. Remember, decimals in PHP is_numeric function return true, so 5., 5.0,. and 5.00000001e3 are all numeric (though the last one could pose problems if actually encountered).
        if (is_numeric($s)) {
          switch ($sdec) {
            case 0: $majorVersion .= $s; break;
            case 1: $minorVersion .= $s; break;
            case 2: $minorVersion2 .= $s; break;
            case 3: $minorVersion3 .= $s; break;
          }
          $version .= $s;
          $check = true;
        }
        elseif ($s == '.') {
          $version .= '.';
          $sdec += 1;
        }
        // If s is not a decimal, denoting a further version, and a version was found above, exit.
        elseif (($s != '.') && ($check)) {
          break;
        }
      }
      break;
    }
  }

  //
  if (stristr($agent, 'linux')) {
    $platform = 'linux';
  }
  else if (stristr($agent, 'macintosh') || stristr($agent, 'mac platform x')) {
    $platform = 'mac';
  }
  else if (stristr($agent, 'windows') || stristr($agent, 'win32')) {
    $platform = 'windows';
  }

  foreach ($merge as $key => $value) {
    // Note: split() and POSIX was depreciated in PHP 5.3.0, so preg_split is now used here.
    $values = preg_split('/,/', $merge[$key]);
    if (in_array($navigator,$values)) {
      $navigator = $key;
    }
  }

  $userData = array(
    'browser' => $navigator,
    'version' => $version,
    'majorVersion' => $majorVersion,
    'minorVersion' => $minorVersion,
    'minorVersion2' => $minorVersion2,
    'minorVersion3' => $minorVersion3,
    'platform' => $platform,
    'userAgent' => $agent,
  );
  return $userData;
}



/* createFile v.2 */
function createFile($dir,$file,$content = null,$overwrite = 0) {
  trigger_error('createFile function is deprecated.',E_USER_DEPRECATED);
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
?>