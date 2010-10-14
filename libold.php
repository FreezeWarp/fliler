<?php
/* get_browser Replacement - Works faster, doesn't require browsercap.ini, more future-proof
 * getBrowser ([$agent = false])
 * return array('browser','version','majorVersion','minorVersion',platform','userAgent') */
function getBrowser($agent = false) {
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
?>