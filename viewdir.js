/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* Delete File Function
 * Paramaters:
 ** file = The file to be transfered.
 ** id = The unique row ID to be removed when the file is removed.
 * Return: None
 * Requires: http Function (scripts.js)
 * Recommends: JQuery Library
 * Queries: viewdir_delete.php */
function deleteFile(file,id) {
  var data = (http('viewdir_delete.php?f=' + file,Array('Message','Status')));
  alert(data['Message']);
  if (data['Status'] == 1) {
    if (typeof $ == 'undefined') {
      document.getElementById(id).style.display = 'none';
    }
    else {
      $('#' + id).animate( {opacity: 0.0}, 500, function() { 
        $('#' + id).remove();
      });
    }
  }
}

/* Delete Directory Function
 * Parameters:
 ** file = The directory to be deleted.
 ** id = The unique row ID to be removed when the file is removed.
 * Return: None
 * Requires http Function (scripts.js)
 * Recommends: JQuery Library
 * Queries: viewdir_delete.php */
function deleteDir(file,id) {
  var data = (http('viewdir_delete.php?d=' + file,Array('Message','Status')));
  alert(data['Message']);
  if (data['Status'] == 1) {
    if (typeof $ == 'undefined') {
      document.getElementById(id).style.display = 'none';
    }
    else {
      $('#' + id).animate( {opacity: 0.0}, 500, function() { 
        $('#' + id).remove();
      });
    }
  }
}

/* Create Directory Function
 * Parameters:
 ** file - The directory to be created.
 * Return: None
 * Requires: http Function (scripts.js)
 * Recommends: JQuery Library
 * Queries: viewdir_createDir.php */
function createDir(file) {
  var data = (http('viewdir_createDir.php?d=' + file,Array('Message','Status')));
  alert(data['Message']);
  if (data['Status'] == 1) {
    if (typeof $ == 'undefined') {
      document.getElementById('content').innerHTML = '<span id="contentReloadNotice" style="display: block;">Your data has been updated. <a href="javascript:location.reload();">Refresh?</a></span>' + document.getElementById('content').innerHTML;
    }
    else {
      $('#content').prepend('<span id="contentReloadNotice" style="display: none;">Your data has been updated. <a href="javascript:location.reload();">Refresh?</a></span>');
      $('#contentReloadNotice').slideDown();
    }
  }
}

/* Create File Function
 * Parameters:
 ** file - The directory to be created.
 * Return: None
 * Requires: http Function (scripts.js)
 * Recommends: JQuery Library
 * Queries: viewdir_createFile.php */
function createFile(file) {
  var data = (http('viewdir_createFile.php?f=' + file,Array('Message','Status')));
  alert(data['Message']);
  if (data['Status'] == 1) {
    if (typeof $ == 'undefined') {
      document.getElementById('content').innerHTML = '<span id="contentReloadNotice" style="display: block;">Your data has been updated. <a href="javascript:location.reload();">Refresh?</a></span>' + document.getElementById('content').innerHTML;
    }
    else {
      $('#content').prepend('<span id="contentReloadNotice" style="display: none;">Your data has been updated. <a href="javascript:location.reload();">Refresh?</a></span>');
      $('#contentReloadNotice').slideDown();
    }
  }
}

/*** Copy, Cut, and Paste Files
 *** Added 3/31/10 ***/
/* Create arrays and strings that will be used . */
var copyFilesArray = new Array();
var cutFilesArray = new Array();
var cutFilesIdArray = new Array();
var copyFilesString;
var cutFilesString;
/* Copy Files
 * Paramaters:
 ** file - The file to be added to the copyFilesArray.
 ** id - The id of the row the file is contained in.
 * Requires: inArray (scripts.js), removeArrayElement(scripts.js)
 * Recommends: None
 * Return: None */
function copyFiles(file,id) {
  if (inArray(file,copyFilesArray)) {
    removeArrayElement(file,copyFilesArray);
    document.getElementById(id).style.fontWeight = 'normal';
  }
  else {
    copyFilesArray.push(file);
    document.getElementById(id).style.fontWeight = 'bold';
  }
}

/* Cut Files
 * Parameters:
 ** file - The file to cut.
 ** id - The id of the row the file is contained in.
 * Requires: inArray (scripts.js), removeArrayElement(scripts.js)
 * Recommends: JQuery
 * Returns: None. */
function cutFiles(file,id) {
  if (inArray(file,cutFilesArray)) {
    removeArrayElement(file,cutFilesArray);
    removeArrayElement(id,cutFilesIdArray);
    if (typeof $ == 'undefined') {
      document.getElementById(id).style.opacity = '1';
    }
    else {
      $('#' + id).animate( { opacity: 1 }, 250);
    }
  }
  else {
    cutFilesArray.push(file);
    cutFilesIdArray.push(id);
    if (typeof $ == 'undefined') {
      document.getElementById(id).style.opacity = '.5';
    }
    else {
      $('#' + id).animate( { opacity: 0.5 }, 250);
    }
  }
}

function clearCutFiles(idArray) {
  for (var i = 0; i < idArray.length; i++) {
    id = idArray[i];
    if (typeof $ == 'undefined') {
      document.getElementById(id).style.display = 'none';
    }
    else {
      $('#' + id).animate( {opacity: 0.0}, 500, function(){
      document.getElementById(id).style.display = 'none';
      });
    }
  }
}

/* Paste Files
 * Paramters:
 ** dir - The directory the files should be pasted in.
 * Requires: http (scripts.js)
 * Return: False if no array elements, none otherwise. */
function pasteFiles(dir) {
  if ((copyFilesArray.length == 0) && (cutFilesArray.length == 0)) {
    alert('You have selected no files to paste.'); return false;
  }
  else {
    if (cutFilesArray.length > 0) {
      var data = http('viewdir_move.php?d=' + dir + '&f=' + cutFilesArray.toString(),Array('Message','Status'));
      alert(data['Message']);

      // Clean Up
      clearCutFiles(cutFilesIdArray);
      cutFilesIdArray = new Array();
      cutFilesArray = new Array();
    }
    if (copyFilesArray.length > 0) {
      var data = http('viewdir_copy.php?d=' + dir + '&f=' + copyFilesArray.toString(),Array('Message','Status'));
      alert(data['Message']);
    }
  }
}