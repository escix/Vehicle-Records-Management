<?php 
//==========================================================================
// login.php
//
// Form to login to the Vehicle Service Tracker System
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 02Apr2006 Snyder, Kenneth J. 73blazer@snyderworld.org v2.0.4
//          - Added Forgot Password link
//
//==========================================================================

 include_once("includes.php");
 // Get the Version
 // -----------------
 $VSTVERSION=shell_exec("./getvstversion");
?>
<html>
 <head>
  <title>
   Vehicle Service Tracker :: VST Car repair tracker at <?php echo $orgname ?>
  </title>
 </head>
 <link rel='stylesheet' type='text/css' href='vst.css'>
 <BODY>
   <H1>
  <CENTER>
   <P CLASS='Header1'>Vehicle Service Tracker</P>
  </CENTER>
  </H1>

   <?php
   // Check if they tried something already
   // --------------------------------------
   if (isset($rc) && $rc=="10") { 
     echo "<B><CENTER><font color=#ff0000>Invalid UserName</font></CENTER></B>";
    } elseif (isset($rc) && $rc=="21") {
     echo "<B><CENTER><font color=#ff0000>Invalid Password</font></CENTER></B>";
    } elseif (isset($rc) && $rc=="14") {
     echo "<B><CENTER><font color=#ff0000>Sorry. Nice try. You Must Logon First!</font></CENTER></B>";
   }
   ?>   
   <form method=post action=checkuser.php>
   <CENTER><table>
   <tr><td><B>UserName</B><td><input name=USERNAME type=text size=30 value=
         <?php if (isset($USERNAME)) echo $USERNAME ?> >
   </tr></td>
   <tr><td><B>
   Password</B><td><input name=PASSWD type=password size=30>
   <?php
   if (!isset($USERNAME)) $USERNAME="";
   echo "<a href='forgotpasswd.php?USERNAME=$USERNAME'>";
   echo "Forgot password?";
   echo "</a>";
   ?>
   </tr></td>
   </table></CENTER>
   <p>
   <CENTER><B><input type=submit value="Login" id='butt'></B></CENTER>
   </form>
   <small><center><b>Try it! - Logon as demo/demo to see how it works.</b></center></small>
   <br>
<br>
   <hr noshade size=5 width=90% >
   <center><a href="VSTInfo.php">What is This?</a> | <a href="register.php">Register Now!</a> | 
   <a href="<?php echo $homepage ?>"> <?php echo $orgname ?></a>
   | <a href="http://www.snyderworld.org/VehicleServiceTracker">VST Project Page</a>
   | <a href="http://sourceforge.net/tracker/?func=add&group_id=163275&atid=827102">
     Submit a Feature Request</a>
   | <a href="http://sourceforge.net/tracker/?func=add&group_id=163275&atid=827099">
     Submit a Bug</a> 
   </center><hr noshade size=5 width=90% >
<?php footer($_SERVER['PHP_SELF'],$adminemail); ?>
 </body>
</html>
