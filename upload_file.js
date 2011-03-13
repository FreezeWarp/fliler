/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

var usedFiles = new Array();
var inputInstance;
var fileSizes = new Array('B','KB','MB','GB','TB');
var inputInstance = 0;

function upFiles(id) {
  if (!id) { id = ''; }
  var fileInput = document.getElementById('file[' + id + ']');
  var files = fileInput.files;

  // Remove prior entries to update things.
  $("[x-parent=" + id + "]").remove();

  if (files.length > 0) {
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      handleFile(file,id);
    }
    return true;
  }
  else { return false; }
}

function handleFile(file,id) {
  var fileName = file.name;
  var fileSize = file.size;
  var metric = 0;
  var fileSizeFormatted;
  while (fileSize >= 1024) {
    fileSize = fileSize / 1024;
    metric += 1;
  }
  fileSizeFormatted = Math.round(fileSize) + fileSizes[metric];

  if (typeof FileReader == 'undefined') {
    $('#filesList').append('<tr x-parent="' + id + '"><td>' + fileName + '</td><td>' + fileSizeFormatted + '</td><td></td></tr>');
  }
  else {
    var reader = new FileReader();
    var fileType = fType(fileName);
    if (fileType == 'text') {
      reader.readAsText(file);
    }
    else {
      reader.readAsDataURL(file);
    }
    reader.onloadend = function() {
      var fileContent = reader.result;
      switch (fileType) {
        case 'image':
        fileContainer = '<img src="' + fileContent + '" alt="" style="max-width: 200px; height: auto;" />';
        break;
        case 'web':
        fileContainer = '<iframe src="' + fileContent + '" style="max-width: 200px; height: auto;"></iframe>';
        break;
        case 'text':
        fileContainer = fileContent;
        break;
        case 'audio':
        fileContainer = '<audio src="' + fileContent + '"></audio>';
        break;
        case 'video':
        fileContainer = '<video src="' + fileContent + '" style="max-width: 200px; height: auto;"></video>';
        break;
        default: fileContainer = ''; break;
      }

      $('#filesList').append('<tr x-parent="' + id + '"><td>' + fileName + '</td><td>' + fileSizeFormatted + '</td><td>' + fileContainer + '</td></tr>');
    };
  }
}

function upFiles2(id) {
  if (!id) { id = ''; }
  var fileInput = document.getElementById('file[' + id + ']');
  var file = fileInput.value;

  // Remove prior entries to update things.
  $("[x-parent=" + id + "]").remove();

  var fileType = fType(file);
  switch (fileType) {
    case 'image':
    var fileContainer = '<img src="' + file + '" alt="Image preview could not be loaded." style="max-width: 200px; height: auto;" />';
    break;
    case 'audio':
    fileContainer = '<audio src="' + file + '">Preview not supported.</audio>';
    break;
    case 'video':
    fileContainer = '<video src="' + file + '" style="max-width: 200px; height: auto;">Preview not supported.</video>';
    break;
    default: var fileContainer =''; break;
  }

  $('#filesList').append('<tr x-parent="' + id + '"><td>' + file + '</td><td>?</td><td>' + fileContainer + '</td></tr>');
}

/* Return the file type for use in a container.
 * NOTE: The file type should be relevant to the container for browser use. SVG can be used in an image tag, but WMA not in an audio tag (for most browsers). Also, this is most relevant to cutting-edge browsers that support FileReader() and DOMFile.
 * NOTE: DOC/DOCX support is included because its resonably common and isn't going away anytime soon. */
function fType(fileName) {
  if (fileName.match(/\.(jpg|jpeg|gif|png|svg)$/i)) {
    return 'image';
  }
  if (fileName.match(/\.(php|js|txt|pl|py|shtml|css|svg|htm|html|xml)$/i)) {
    return 'text';
  }
  if (fileName.match(/\.(oga|mp3|mp2|aac|wav)$/i)) {
    return 'audio';
  }
  if (fileName.match(/\.(ogg|ogv|mp4)$/i)) {
    return 'video';
  }
  if (fileName.match(/\.(doc|docx|odt|pdf)$/i)) {
    return 'doc';
  }
  return false;
}

function newField(fieldType) {
  filesBox = document.getElementById('filesBox');
  inputInstance += 1;
  switch (fieldType) {
    case 0:
    $('#filesBox').append('<br /><input name="file[]" id="file[' + inputInstance + ']" onChange="upFiles(' + inputInstance + ')" type="file" multiple="multiple" x-child="' + inputInstance + '" />');
    break;
    
    case 1:
    if (typeof $ == 'undefined') {
      filesBox.innerHTML += '<br /><input name="urls[]" id="file[' + inputInstance + ']" onChange="upFiles2(' + inputInstance + ')" type="text" x-child="' + inputInstance + '" />';
    }
    else {
      $('#filesBox').append('<br /><input name="urls[]" id="file[' + inputInstance + ']" onChange="upFiles2(' + inputInstance + ')" type="text" x-child="' + inputInstance + '" />');
    }
    break;
    
    case 2:
    // The Jqueryless fallback is a little buggy - previous file entries are deleted.
    if (typeof $ == 'undefined') {
      filesBox.innerHTML += '<br /><input name="file[]" id="file[' + inputInstance + ']" onChange="upFiles(' + inputInstance + ')" type="file" multiple="multiple" webkitdirectory="webkitdirectory" directory="directory" x-child="' + inputInstance + '" />';
    }
    else {
      $('#filesBox').append('<br /><input name="file[]" id="file[' + inputInstance + ']" onChange="upFiles(' + inputInstance + ')" type="file" multiple="multiple" webkitdirectory="webkitdirectory" directory="directory" x-child="' + inputInstance + '" />');
    }
    break;
  }
}

window.onload = function() {
    var dropbox = document.getElementById("filesList");
    dropbox.addEventListener("dragenter", dragenter, false);
    dropbox.addEventListener("dragover", dragover, false);
    dropbox.addEventListener("drop", drop, false);
 
    function dragenter(e) {
      e.stopPropagation();
      e.preventDefault();
    }
 
    function dragover(e) {
      e.stopPropagation();
      e.preventDefault();
    }
 
    function drop(e) {
      e.stopPropagation();
      e.preventDefault();
 
      var dt = e.dataTransfer;
      var files = dt.files;

      if (files.length > 0) {
        for (var i = 0; i < files.length; i++) {
          var file = files[i];
          handleFile(file,'');
        }
        return true;
      }
      else { return false; }
    }
}