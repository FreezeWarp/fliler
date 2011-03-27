/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Head Collapse
 * Parameters:
 ** id - The id to collapse.
 * Return: None */
function headCollapse(id) {
  if (typeof $ == 'undefined') {
    // No Jquery Fallback.
    if (document.getElementById(id).style.display == 'block') {
      document.getElementById(id).style.display = 'none';
    }
    else {
      document.getElementById(id).style.display = 'block';
    }
  }
  else {
    $('#' + id).slideToggle('slow');
  }
}

function resizeImage(id,newSize) {
  document.getElementById(id).height = newSize;
  document.getElementById(id).width = newSize;
}

function changeField(id) {
  var dir = $('#' + id).val();

  var content = http('fileSelect.php?d=' + dir);
  $('#fileSelect').html(content);
}

/* Added 12/30/09, Simplified 01/03/10 */
/* HTTP AJAX Request
 * Parameters:
 ** url - The url to query.
 ** getHeader - An array of headers to return. If false, will return the document's content, and no headers.
 * Return: Document Content if getHeader is false, specified headers in getHeader if getHeader is true.
 * Note: IE Support from http://blogs.msdn.com/b/xmlteam/archive/2006/10/23/using-the-right-version-of-msxml-in-internet-explorer.aspx */
function http(url,getHeader) {
  var query;
  try {
    query = new XMLHttpRequest();
  } catch (e) {
    try {
      query = new ActiveXObject("Msxml2.XMLHTTP.6.0"); /* IE7+ */
    } catch (e) {
      try {
        query = new ActiveXObject("Msxml2.XMLHTTP.3.0"); /* IE 5.5+ */
      } catch (e) {
        throw new Error("Error initializing AJAX. Please make sure your browser supports XMLHttpRequest().");
      }
    }
  }

  query.open("GET",url,false);
  query.send('');
  if (!getHeader) {
    return query.responseText;
  }
  else {
    var responses = new Array();
    for (i=0;i<getHeader.length;i++) {
      responses[getHeader[i]] = query.getResponseHeader(getHeader[i]);
    }
    return responses;
  }
}

/* Added 3/31/10 */
/* In Array
 * Parameters:
 ** value - The value to check for.
 ** array - The array to check in.
 * Return: true if found, false if not found. */
function inArray(value, array) {
  for (var i = 0; i < array.length; i++) {
    if(value == array[i]) return true;
  }
  return false;
}

/* Added 3/31/10 */
/* Remove Array Elements
 * Parameters:
 ** value - The value in the array to search for.
 ** array - The array to be searched through.
 ** all - Whether or not to stop after the first value was removed.
 * Return: true */
function removeArrayElement(value, array, all) {
  for (var i = 0; i< array.length; i++) {
    if (value == array[i]) {
      array.splice(i,1);
      if (!all) return true;
    }
  }
  return true;
}

/* added 4/17/10 */
function help(content) {
  var id = 'help';
  if (typeof $ == 'undefined') {
    document.getElementById(id).innerHTML = content;
  }
  else {
    $('#' + id).html(content);
  }
}