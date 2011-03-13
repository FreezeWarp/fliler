/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

/* This is an IE6 hack file that requires JQuery. */

window.onload = function() {
  $('input[type=button], input[type=reset], input[type=submit]').css({'width' : '100px', 'margin-left' : '6px', 'margin-right': '6px'});
  $('img').css({'border' : 0});
  $('input[type=file], input[type=text], input[type=password], input[type=search], input[type=url], input[type=number]').css({'width' : '250px'});
  $('input:focus, button:focus, select:focus, textarea:focus, input:hover, button:hover, select:hover')
}