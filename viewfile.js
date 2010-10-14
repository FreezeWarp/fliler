
/* Copyright (c) 2009 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

var imageHeight;
var imageWidth;
function resizeImage(id,ratio) {
  image = document.getElementById(id);
  if (!imageHeight) {
    imageHeight = image.height;
    imageWidth = image.width;
  }
  image.height = imageHeight * ratio;
  image.width = imageWidth * ratio;
}