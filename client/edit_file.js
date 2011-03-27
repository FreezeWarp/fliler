/* Copyright (c) 2011 by Joseph Parsons

 * This file is a part of Fliler.
 * Fliler is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

 * Fliler is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License along with Fliler.  If not, see <http://www.gnu.org/licenses/>. */

// From http://www.vladdy.net/demos/textareainsertion.html
function getSelection(ta) {
  var bits = [ta.value,'','',''];
  if(document.selection) {
    var vs = '#$%^%$#';
    var tr = document.selection.createRange()
    if(tr.parentElement() != ta) return null;
    bits[2] = tr.text;
    tr.text = vs;
    fb = ta.value.split(vs);
    tr.moveStart('character',-vs.length);
    tr.text = bits[2];
    bits[1] = fb[0];
    bits[3] = fb[1];
  }
  else {
    if(ta.selectionStart == ta.selectionEnd) return null;
    bits = (new RegExp('([\x00-\xff]{'+ta.selectionStart+'})([\x00-\xff]{'+(ta.selectionEnd - ta.selectionStart)+'})([\x00-\xff]*)')).exec(ta.value);
  }
  return bits;
}

function matchPTags(str) {
  str = ' ' + str + ' ';
  ot = str.split(/\<[b|u|i].*?\>/i);
  ct = str.split(/\<\/[b|u|i].*?\>/i);
  return true;
}

function addPTag(start,end) {
  var ta = document.getElementById('fileContent');
  bits = getSelection(ta);
  if(bits) {
    if(!matchPTags(bits[2])) {
      alert('Error: Selection containers unmatched opening/closing tags.');
      return;
    }
    var regex2 = new RegExp(start + '(.*?)' + end,'i');
    var regex3 = new RegExp(start + '$','i');
    var regex4 = new RegExp('^' + end,'i');
    if ((bits[2].match(regex2)) || ((bits[1].match(regex3)) || (bits[3].match(regex4)))) {
      ta.value = ta.value.replace(regex2,'$1');
    }
    else {
      ta.value = bits[1] + start + bits[2] + end + bits[3];
    }
  }
}

function addPTag2(tag,att,attValue) {
  var ta = document.getElementById('fileContent');
  bits = getSelection(ta);
  if(bits) {
    if(!matchPTags(bits[2])) {
      alert('Error: Selection containers unmatched opening/closing tags.');
      return;
    }
    var regex2 = new RegExp('<' + tag + ' style="' + att + ': (.*?);">','i');
    if (bits[2].match(regex2)) {
      var regex3 = new RegExp('</' + tag + '>');
      while(bits[2].match(regex2)) {
        bits[2] = bits[2].replace(regex2,'');
      }
      while(bits[2].match(regex3)) {
        bits[2] = bits[2].replace(regex3,'');
      }
      ta.value = bits[1] + bits[2] + bits[3];
    }
    ta.value = bits[1] + '<' + tag + ' style="' + att + ': ' + attValue + ';">' + bits[2] + '</' + tag + '>' + bits[3];
  }
}

function insertAtCursor(myValue) {
  text2 = document.getElementById('fileContent');
// Support for IE
  if (document.selection) {
    text2.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
  }
// Support for Firefox
  else if (text2.selectionStart == '0') {
    var startPos = text2.selectionStart;
    var endPos = text2.selectionEnd;
    text2.value = text2.value.substring(0, startPos) + myValue + text2.value.substring(endPos, text2.value.length);
  }
  else {
    text2.value += myValue;
  }
}

function resize(size) {
  document.getElementById('fileContent').style.height = size;
  if (document.getElementById('htmlOutput')) {
    document.getElementById('htmlOutput').style.height = size;
  }
}

function showOutput() {
  document.getElementById('htmlOutput').innerHTML = document.getElementById('fileContent').value;
}

function collapse() {
  if (document.getElementById('toolbar2').style.display == 'block') {
    document.getElementById('toolbar2').style.display = 'none';
    document.getElementById('toolbar2button').innerHTML = '&darr;';
  }
  else {
    document.getElementById('toolbar2').style.display = 'block';
    document.getElementById('toolbar2button').innerHTML = '&uarr;';
  }
}

function validateCSS(string) {
  if(string.match(/^((azimuth|font-(family|style|weight|size|variant)|font|line-(height|spacing)|word-spacing|color|background-(color|image|repeat|attachment|position)|background|text-(decoration|transform|align|indent)|verticle-align|white-spacing|margin|margin-(top|bottom|left|right)|padding|padding-(top|bottom|left|right)|border-(color|style|width|collapse|spacing|top|left|right|bottom)|border-(top|left|right|bottom)-(width|color|style)|border|outline|outline-(style|color|width)|width|height|min-(width|height)|max-(width|height)|position|top|left|right|bottom|clip|overflow|z-index|float|clear|display|visibility|line-style|line-style-(type|image|position)|table-layout|empty-cells|caption-slide|content|counter-(increment|reset)|quotes|page-break-(before|after|inside)|orphans|widows|cursor|direction|unicode-bidi):(| )([^:;]+);(| ))+$/ig)) {
    return true;
  }
  else if(string.match(/^((alignment-(adjust|baseline)|animation-(delay|directory|duration|iteration-count|name|play-state|timing-function)|animation|appearance|azimuth|background-(attachment|break|clip|color|image|origin|position|repeat|size)|background|baseline-shift|binding|bookmark-(label|level|target)|border|border-(color|style|width|collapse|spacing|radius|length|top|left|right|bottom)|border-(top|left|right|bottom)-(width|color|style|radius|length)|bottom|box-(align|direction|flex|flex-group|lines|orient|pack|shadow|sizing)|caption-side|clear|clip|color|color-profile|column-(break-after|break-before|count|fill|gap|rule|rule-(color|style|width)|span|width)|columns|content|counter-(increment|reset)|crop|cue-(after|before)|cursor|direction|display|display-(model|role)|dominant-baseline|drop-initial-(after|before)-(adjust|align)|drop-initial-(size|value)|elevation|empty-cells|fit|fit-position|float|float-offset|font|font-(effect|emphasize|emphasize-(position|style)|family|size|size-adjust|smooth|stretch|style|variant|weight)|grid-(columns|rows)|hanging-punctuation|height|hyphenate-(after|before|character|lines|resource)|hyphens|icon|image-(orientation|resolution)|inline-box-align|left|letter-spacing|line-(height|stacking|stacking-(ruby|shift|strategy))|list-(style|style-(image|position|type))|margin|margin-(bottom|left|right|top)|mark|mark-(after|before)|marker-offset|marks|marquee-(direction|play-count|speed|style)|max-(height|width)|min-(width|height)|move-to|nav-(down|index|left|right|up)|opacity|orphans|outline|outline-(color|offset|style|width)|overflow|overflow-(style|x|y)|padding|padding-(bottom|left|right|top)|page|page-break-(after|before|inside)|page-policy|pause|pause-(after|before)|phonemes|pitch|pitch-range|play-during|position|presentation-level|punctuation-trim|quotes|rendering-intent|resize|rest|rest-(after|before)|richness|right|rotation|rotation-point|ruby-(align|overhang|position|span)|size|speak|speak-(header|numeral|punctuation)|speech-rate|stress|string-set|tab-side|table-layout|target|target-(name|new|position)|text-(align|align-last|decoration|emphasis|height|indent|justify|outline|replace|shadow|transform|wrap)|top|transition|transition-(delay|duration|property|timing-function)|unicode-bidi|vertical-align|visibility|voice-(balance|duration|family|pitch|pitch-range|rate|stress|volume)|volume|white-space|white-space-collapse|widows|width|word-(break|spacing|wrap)|z-index):(| )([^:;]+);(| ))+$/ig)) {
    alert('Warning: Though the current CSS code is proper, it uses elements which are depreciated, were introuced in CSS3, or are otherwise unsupported.');
    return false;
  }
  else {
    alert('Warning: There is an error in the CSS code you just submitted.');
    return false;
  }
}
function horizontalLayout() {
  var fileContent = document.getElementById('fileContent').style;
  fileContent.width = '100%';
  fileContent.height = '200px';
  fileContent.display = 'block';
  fileContent.float = 'none';
  var htmlOutput = document.getElementById('htmlOutput').style;
  htmlOutput.width = '100%';
  htmlOutput.height = '200px';
  htmlOutput.display = 'block';
  htmlOutput.overflow = 'auto';
  htmlOutput.border = '1px solid black';
  htmlOutput.float = 'none';
}
function verticalLayout() {
  var fileContent = document.getElementById('fileContent').style;
  fileContent.width = '49%';
  fileContent.height = '200px';
  fileContent.display = 'inline';
  fileContent.float = 'left';
  var htmlOutput = document.getElementById('htmlOutput').style;
  htmlOutput.width = '49%';
  htmlOutput.height = '200px';
  htmlOutput.display = 'inline';
  htmlOutput.float = 'right';
  htmlOutput.overflow = 'auto';
  htmlOutput.border = '1px solid black';
}