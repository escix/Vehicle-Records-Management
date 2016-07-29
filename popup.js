//==========================================================================
// popup.js
//
// Javascript to decode the notes and display them in a popup window
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 
//
//==========================================================================


function openPopup(notes) {
     nWin = window.open('','nWin','height=255,width=250, toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes'); 
     nWin.document.write('<html>');
     nWin.document.write('<link rel="stylesheet" type="text/css" href="vst.css">');
     nWin.document.write('<head><title>Notes</title></head>');
     nWin.document.write('<body><center>');
     notes=notes.replace(/ANEWLINE/g,"<BR>");
     notes=notes.replace(/AAPOSTRP/g,"'");
     notes=notes.replace(/ACOLON/g,":");
     notes=notes.replace(/ASLASH/g,"/");
     notes=notes.replace(/APERCENT/g,"%");
     notes=notes.replace(/ASPACEA/g," ");
     notes=notes.replace(/ADBLQTE/g,'"');
     notes=notes.replace(/ALSTHAN/g,'<');
     notes=notes.replace(/AGRTHAN/g,'>');
     nWin.document.write(notes);
     nWin.document.write('<a href="#" onclick="self.close();return false;">Close</a>');
     nWin.document.write('</center>');
     nWin.document.write('</body></html>');
     nWin.focus();
     nWin.document.close();
     return false;
}

