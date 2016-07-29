<?php 
//==========================================================================
// forgotpasswd.php
//
// Form to email the user his login password
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 02Apr2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 23Feb2007 Snyder, Kenneth J 73blazer@snyderworld.org v2.1.0
//   - Updated because passwords are now encrypted
//     Reset the users password to a random string and mail that to him
//
//==========================================================================

 include_once("includes.php");
 // Get the Version
 // -----------------
 $VSTVERSION=shell_exec("./getvstversion");
 $formpresent=0;

?>
<html>
 <head>
  <title>
   Vehicle Service Tracker :: Forgot Password
  </title>
 </head>
 <link rel='stylesheet' type='text/css' href='vst.css'>
 <BODY>
   <H1>
  <CENTER>
   <P CLASS='Header1'>Vehicle Service Tracker - Forgotton Password</P>
  </CENTER>
  </H1>

<?php
function generatePassword ($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKMNPQRSTUVWXYZ@!"; 
    
  // set up a counter
  $i = 0; 
    
  // add random characters to $password until $length is reached
  while ($i < $length) { 

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}
        


 if (isset($FP) && $FP!="") {
   if ($USERNAME=='demo') { 
     header("Location: login.php?rc=40");
     die("Sorry");   
   }
      $dbconn = odbc_connect("$dbname","$dbuid","$dbpasswd");
      if (!$dbconn) {
        die("No Connection to database. Please try later");
       } else {
        // Generate a new password and mail it 
        $Select="select email from vst.clients where usrname='$USERNAME'";
        $Result=odbc_exec($dbconn,$Select);
        if (odbc_fetch_row($Result)) {
           $passwd=generatePassword();
           $epasswd=sha1($passwd);
           $email=odbc_result($Result,1);
           $Insert="update VST.CLIENTS set PASSWD='$epasswd' where USRNAME='$USERNAME'";
           $InsertResult=odbc_exec($dbconn,$Insert);
           if ($InsertResult!=0) {

              odbc_commit($dbconn);
              $subject="Your password for Vehicle Service Tracker at $orgname";

              $message="Your password for $orgname's Vehicle Service Tracker has been reset to ";
              $message.="[$passwd]\r\n";
              $message.="Your User ID [$USERNAME]\r\n\r\n";
              $message.="You can Login with your new password\r\n";
              $message.="Feel free to change it with the \"Update Profile\" on the main page\r\n\r\n";
              $message.="You are recieving this notification because a request has been";
              $message.=" made for a forgotton password. If the request was not ";
              $message.="initiated by you, please notify $orgname's administrator ";
              $message.="at $adminemail. Rest assured your information has not been ";
              $message.="compromised. The password is only shown in this mail and ";
              $message.="was never displayed during the request.\r\nThank you.\r\n\r\n";
              $message.="As always, you can access the tracker at: $homepage$webpath";
      
              $headers = "MIME-Version:1.0\r\n";
              $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
              $headers .= "From: Vehicle Service Tracker Admin <$adminemail>";

              mail($email,$subject,$message,$headers);
              odbc_close($dbconn);
    
              echo "<br>";
              echo "<center>";
              echo "<b>Your password has been reset and mailed to your email address on file<b>";
              echo "<center>";
            } else {
              echo "<center><font color=#ff0000><b>";
              echo "Something went wrong with DB2 Insert of new passwd";
              echo "</b></font></center><br>";
              $formpresent=1;
              $USERNAME="";
           } // if insert ok
            
          } else {
           echo "<center><font color=#ff0000><b>";
           echo "The UserName [$USERNAME] is not on file";
           echo "</b></font></center><br>";
           $formpresent=1;
           $USERNAME="";
         } // if (odbc_fetch_row($Result)) if there was a user by that anme 
      } // if dbconn
     } else {
      $formpresent=1;
    }

    if ($formpresent) {
?>
   <small><center>Enter your UserName and we will email your 
   password to the email address on file</center></small><br>
   <form method=post action=forgotpasswd.php>
   <CENTER><table>
   <tr><td><B>UserName</B><td><input name=USERNAME type=text size=30 value=
         <?php if (isset($USERNAME)) echo $USERNAME; ?> ></td></tr>
   </table></CENTER>
   <p>
   <CENTER><B><input type=submit name="FP" value="Email me my password"
     id='butt'></B></CENTER>
   </form>
   <br>
<?php
}
?>
   <br>
   <hr noshade size=5 width=90% >
   <center> 
   <?php
   if (isset($USERNAME) && $USERNAME!="") {
     $LoginURL="http://www.snyderworld.org/VST?USERNAME=$USERNAME";
    } else {
     $LoginURL="http://www.snyderworld.org/VST";
   }
   ?>
   <a href="<?php echo $LoginURL ?>">
     Back to Login</a> 
   </center><hr noshade size=5 width=90% >
<?php footer($PHP_SELF,$adminemail); ?>
 </body>
</html>
