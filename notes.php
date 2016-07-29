<?php
//==========================================================================
// notes.php
//
// Displays user's line item notes. 
// Only called if they don't have javascript enabled
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
?>

<html>
<link rel='stylesheet' type='text/css' href='vst.css'>
<body>
<center>
<?php

if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
include_once("includes.php");
$dbconn = odbc_connect("$dbname","$dbuid","$dbpasswd");
authuser($dbconn,$USERNAME,$SID);

// decode info
$INFO=str_replace("ADBLQTE",'"',$INFO);
$INFO=str_replace("AAPOSTRP","'",$INFO);
$INFO=str_replace("ANEWLINE","<BR>",$INFO);
$INFO=str_replace("ASLASH","/",$INFO);
$INFO=str_replace("ACOLON",":",$INFO);
$INFO=str_replace("APERCENT","%",$INFO);
$INFO=str_replace("ASPACEA"," ",$INFO);
$INFO=str_replace("ALSTHAN","<",$INFO);
$INFO=str_replace("AGRTHAN",">",$INFO);


echo $INFO;
odbc_close_all();
?>
</center>
</body>
</html>

