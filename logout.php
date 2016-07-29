<?php
//==========================================================================
// logout.php
//
// logs a user out of the vehicle service tracker
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

if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
include_once("includes.php");
$dbconn = odbc_connect("$dbname","$dbuid","$dbpasswd");
if ($dbconn==0) {
   $a = odbc_errormsg("DB2 Connect Failed. DB2 might not be running");
   echo($a);
 } else {
   authuser($dbconn,$USERNAME,$SID);


$LogoutUpdate="update clients set SID='LOGGEDOUT' where USRNAME='$USERNAME'";
$dbconn->$LogoutUpdate();
$header="Location: ".$homepage.$webpath;
header($header);

}
?>

