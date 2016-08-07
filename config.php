<?php
//==========================================================================
// config.php
//
// Vehicle Service Tracker Configuration file
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


// Vehicle Service Tracker Configuration file
// v2.03 (works with versions 2.00->2.03)
// -------------------------------------------------


// Email of the applications administrator
// ----------------------------------------------------------------
$adminemail="admin@admin.com";


// Address(s) where you want to be mailed when someone registered
// (comma seperated list if more than 1) 
// ----------------------------------------------------------------
$reg_email="admin@admin.com";


// Set the Long Field path for vehicle's picture storage
// Could be absolute or relative.
// If absolute ensure that path is accessible from your webserver
// Don't worry, these are just thumbnails and are very small
// ----------------------------------------------------------------
$LFPath="pics";


// Organization name
// ----------------------------------------------------------------
$orgname="vst.com";


// Org Homepage 
// ----------------------------------------------------------------
$homepage="http://vst.com";

// Web path where tracker is installed
// do not include your root path as defined in your webserver
// ie. If you use apache and in your httpd.conf your DocumentRoot
//     is /www, and you untared this app in /www/VST, this variable
//       would be /VST
// ----------------------------------------------------------------
$webpath="";


// Database parameters
// (leave uid and passwd ="" if they are not needed
// ----------------------------------------------------------------
$dbname="myvstdb";
$my_host="192.168.1.103";
$my_user="myvstdbuser";
$dbuid="";
$dbpasswd="password";

?>
