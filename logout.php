<?php
session_start();
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

if (isset($_SESSION['SID'])) { $SID=$_SESSION['SID']; }
if (isset($_SESSION['USERNAME'])) { $USERNAME=$_SESSION['USERNAME'];}
include_once("includes.php");

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

   authuser($dbconn,$USERNAME,$SID);


$LogoutUpdate="update clients set SID='LOGGEDOUT' where USRNAME='$USERNAME'";
$dbconn->query($LogoutUpdate);
$header="Location: ".$homepage.$webpath;
header($header);

}
?>

