<?php
/* Copyright (c) 2010 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Pre-Processing */
require_once('config.php');

/* Document Start */
echo documentStart('Browser Support');
?>

<h2>Table of Contents</h2>
  <ol>
    <li><a href="#recommended">Recommended Browsers</a></li>
    <li><a href="#bugs">Bugs Browsers</a></li>
    <li><a href="#your">Your Browsers</a></li>
  </ol>

<h2 id="recommended">Recommended Browsers</h2>
  <ol>
    <li><a href="http://www.mozilla.com/en-US/firefox/firefox.html">Mozilla Firefox 3.6</a></li>
    <li><a href="http://www.google.com/chrome">Google Chrome</a></li>
  </ol>

<h2 id="bugs">Browser Support Bugs</h2>
  <b>Internet Explorer 6:</b> HTML-5 Video Unsupported, XML HTTP Requests Unsupported, Table Row Hover Unsupported<br />
  <b>Internet Explorer 7:</b> HTML-5 Video Unsupported, Table Row Hover Untested<br />
  <b>Internet Explorer 8:</b> HTML-5 Video Unsupported<br />
  <b>Firefox 3.0:</b> HTML-5 Video Unsupported<br />

<h2 id="oldNew">Old vs. New</h2>
  <table class="generic nofill">
    <tr>
      <th>Browser</th>
      <th>Old</th>
      <th>New</th>
      <th>Cutting Edge</th>
    </tr>

    <tr>
      <td>Apple Safari</td>
      <td>&le;4</td>
      <td>5</td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>Google Chrome</td>
      <td>&le;9</td>
      <td>10</td>
      <td>11</td>
    </tr>

    <tr>
      <td>Internet Explorer</td>
      <td>&le;7</td>
      <td>8</td>
      <td>9</td>
    </tr>

    <tr>
      <td>Mozilla Firefox</td>
      <td>&le;3.5</td>
      <td>3.6</td>
      <td>4.0</td>
    </tr>

    <tr>
      <td>Opera</td>
      <td>&le;10.60</td>
      <td>11.0</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<h2 id="your">Your Browser</h2>
<?php
/* Document Content */
$browser = new getBrowser;
$browser->getBrowserBit();
$browser->getVersionBit();
$browser->getPlatformBit();
$browser->getArcBit();
switch ($browser->browser) {
  case 'msie':
  if ($browser->majorVersion >= 9) {
    echo 'You are using a version of Internet Explorer that should work reasonably well.';
  }
  elseif ($browser->majorVersion == 8) {
    echo 'You are using an up-to-date version of Internet Explorer. However, we recommend switching to Mozilla Firefox, Apple Safari, or Google Chrome. Internet Explorer, while packaged with Microsoft Windows, is not tested heavily, and may not support many functions, such as Javascript, and the general CSS layout used.';
  }
  else {
    echo 'You are using an old version of Internet Explorer. We strongly recommending upgrading to Internet Explorer 8 or switching to Mozilla Firefox, Apple Safari, or Google Chrome. Internet Explorer, while packaged with Microsoft Windows, is not tested heavily, and may not support many functions, such as Javascript, and the general CSS layout used. Versions before 8 amplify this problem, as well.';
  }
  break;

  case 'firefox':
  if ($browser->majorVersion >= 4) {
    echo 'You are using an expirimental version of Mozilla Firefox. Mozilla Firefox is well supported by Filer, and should work well.';
  }
  elseif ($browser->majorVersion == 3 && $browser->minorVersion >= 6) {
    echo 'You are using an up-to-date version of Mozilla Firefox 3. Mozilla Firefox is well supported by Filer, and should work well.';
  }
  elseif ($browser->majorVersion == 3) {
    echo 'You are using an old version of Mozilla Firefox 3. Mozilla Firefox is well supported by Filer, but newer versions will allow additional features, such as CSS3 and HTML5, to be utilized. Older versions are also now tested. <b><a href="http://www.mozilla.com/en-US/firefox/firefox.html">We recommend upgrading</a></b>.';
  }
  else {
    echo 'You are using an old version of Mozilla Firefox. We recommend upgrading. While Mozilla Firefox 2.0 and 3.0 are very good browsers, we only test Firefox in versions 3.5 and newer, such as 3.6 and 3.7.';
  }
  break;

  case 'safari':
  if ($browser->majorVersion >= 5) {
    echo 'You are using an up-to-date version of Apple Safari. Apple Safari is not tested heavily to work with Fliler, but its modern use of the opensource WebKit engine helps ensure support of it.';
  }
  else {
    echo 'You are using an old version of Apple Safari. <b><a href="http://www.apple.com/safari/download/">We recommend upgrading.</a></b>';
  }
  break;

  case 'chrome':
  if ($browser->majorVersion >= 10) {
    echo 'You are using an up-to-date version of Google Chrome or Chromium.';
  }
  else {
    echo 'You are using an old version of Google Chrome. <b><a href="http://www.google.com/chrome">We recommend upgrading.</a></b>';
  }
  break;

  case 'opera':
  if ($browser->majorVersion >= 11) {
    echo 'You are using an up-to-date version of Opera 11. However, Opera is not used for testing, and may not be fully supported. While it should still work, keep in mind theremay be some bugs.';
  }
  else {
    echo 'You are using an old version of Opera. We recommend upgrading or switching to one of the browsers listed above..';
  }
  break;

  default:
  echo 'You are using an unknown browser. We recommended using <a href="http://www.mozilla.com/en-US/firefox/firefox.html">Mozilla Firefox 3.5</a>, <a href="http://www.google.com/chrome">Google Chrome 3</a>, or <a href="http://www.apple.com/safari/download/">Apple Safari 4</a>, all of which are well tested and supported.';
  break;
}

echo '
  <div style="padding-left: 50px;">
  <h3>Browser Details</h3>
    <b>Browser:</b> ' . $browser->browserClass . ' (specifically ' . $browser->browser . ')<br />
    <b>Version:</b> ' . $browser->version . '<br />
    <b>Major Version:</b> ' . $browser->majorVersion . '<br />
    <b>Minor Version:</b> ' . $browser->minorVersion . '<br />
    <b>Second Minor Version:</b> ' . $browser->minorVersion2 . '<br />
    <b>Third Minor Version:</b> ' . $browser->minorVersion3 . '<br />
    <b>Platform:</b> ' . $browser->osFamily . ' (specifically ' . $browser->operatingSystem  . ')<br />
    <b>Architecture:</b> ' . $browser->architecture . '<br />
    <b>Full User Agent:</b> ' . $browser->userAgent . '
  </div>';
/* Document End */
echo documentEnd();
?>