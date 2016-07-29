<?php
//==========================================================================
// Register.php
//
// The VST Registration Form Display and Checking
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 73blazer@snyderworld.org
//
// Revised: 
//  16Mar2006 73blazer@snyderworld.org
//   - Added checks to allow last name with apostrophe
//      DB2 uses double '' to insert one ', MySQL Addslashes no good here
//  22Feb2007 73blazer@snyderworld.org v2.1.0
//   - Changed form call to meet new forms parameters which was changed
//     to accomodate the update profile functions
//   - Hash password on insertion into database
//
//==========================================================================
?>
<html>
  <link rel='stylesheet' type='text/css' href='vst.css'>
  <head>
    <title>Vehicle Service Tracker - Registration</title>
  </head>
  </body>
  <H1>
  <CENTER>
   <P CLASS='Header1'>Vehicle Service Tracker - Registration</P>
  </CENTER>
  </H1>

<?php
include_once("includes.php");
$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname); //odbc_connect("$dbname","$dbuid","$dbpasswd");
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

include_once("profile.php");
$MAINURL=$homepage.$webpath;
$BUTTONTXT='Register!';

if (isset($_POST['RegisterMe'])) {

        $EMAIL = $_POST['EMAIL'];
        $FIRST = $_POST['FIRST'];
        $LAST = $_POST['LAST'];
        $USERNAME = $_POST['USERNAME'];
        $PASSWD = $_POST['PASSWD'];
        $PASSWD2 = $_POST['PASSWD2'];



   // Encrpt the password
   // --------------------
   $EPASSWD=sha1($PASSWD);  

   // Then This was submitted...lets check the input..
   // --------------------------------------------------
   if ( ! validate_email($EMAIL) ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="Your eMail Address doesn't look right</font></li></b>";
    $UIErrors.="We need your full internet eMail address<BR>";
    $UIErrors.="Examples of a proper e-mail address are:";
    $UIErrors.="<ul><li>joebob1234@aol.com</li>";
    $UIErrors.="<li>73joebob@us.ogrady.net</li>";
    $UIErrors.="<li>John_Doe@nowhere.au</li></ul>";
    $UIErrors.="</ul>";
   }
   if ( $FIRST=="" ) { 
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You didn't enter your First name</font></li></ul></b>";
   }
   if ( $LAST == "" ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You didn't enter your Last name</font></li></ul></b>";
   }
   if ( $USERNAME == "" ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You didn't enter a UserName</font></li></ul></b>";
   }
   if ($USERNAME != "" && check_unique_username($dbconn,$USERNAME)) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="That username is already in use. Please choose another";
    $UIErrors.="</font></li></ul></b>";
   }
   if ( $PASSWD  == "" ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You didn't enter a Password</font></li></ul></b>";
   }
   if ( $PASSWD2  == "" ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You didn't enter a Confirmation Password</font></li></ul></b>";
   }
   if ( $PASSWD!="" && $PASSWD2!="" && $PASSWD == $USERNAME ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You used your username for a password. This is not allowed";
    $UIErrors.="</font></li></ul></b>";
   }
   if ( $PASSWD!="" && $PASSWD2!="" && $PASSWD==$PASSWD2 && strlen($PASSWD)<5 ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="Your Password is not at least 6 characters in length";
    $UIErrors.="</font></li></ul></b>";
   }
   if ( $PASSWD  != $PASSWD2 ) {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="Your passwords don't match</font></li></ul></b>";
   }
   if (isset($UIErrors)) {
     echo "<b><center><font size=+1>";
     echo "There was trouble with proccessing your information.</font>";
     echo "</b></center>";
     echo $UIErrors;
     RegisterForm($FIRST,$LAST,$EMAIL,$USERNAME,$MAINURL,"register.php",$BUTTONTXT);
    } else {
     // Alrighty..everything passes so lets insert this new user!
     // ----------------------------------------------------------
     $SID=md5(uniqid(rand(),TRUE));
     $LAST2=str_replace("'","''",$LAST);
     $NewUserInsert="insert into clients ";
     $NewUserInsert.="(USRNAME,FIRST,LAST,EMAIL,PASSWD,C_CREATE,SID) values ('";
     $NewUserInsert.=$USERNAME."','";
     $NewUserInsert.=$FIRST."','";
     $NewUserInsert.=$LAST2."','";
     $NewUserInsert.=$EMAIL."','";
     $NewUserInsert.=$EPASSWD."',current_timestamp,'$SID');";

if($dbconn->query($NewUserInsert)===TRUE)
{

       echo "<TABLE BORDER='0' WIDTH='100%' CELLSPACING='1' CELLPADDING='2'>";
        echo "<TR CLASS='band'>";
        echo "<TD>";
        echo "<A CLASS='header2'>";
        echo "</A>";
        echo "<BR>";
        echo "&nbsp;";
        echo "<A CLASS='header2'>";
        echo "&nbsp;$USERNAME has been Sucessfully Registered!</A>";
        echo "<BLOCKQUOTE>";
        echo "First Name: $FIRST<br>";
        echo "Last Name: $LAST<br>";
        echo "eMail: $EMAIL<br>";
        echo "</BLOCKQUOTE> ";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";        
        echo "<BR><center><B>The first thing you need to do is to ";
        echo "<a href=newvehicle.php?SID=$SID&USERNAME=$USERNAME>";
        echo "add a vehicle</a></b><br>";
        echo "<br></center>";
        echo "<center><big><B><a href=main.php?SID=$SID&USERNAME=$USERNAME>";
        echo "To Main</a></B></big></center>";

        // Send me and the person a mail
        $headers = "MIME-Version:1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "From: Vehicle Service Tracker Admin <$adminemail>";
        $Subject="Sucessfully registered for Vehicle Service Tracker";


$message="
<html><body><center><B>Welcome to the Vehicle Service Tracker</B></center>
<br>
Welcome $FIRST $LAST<BR>
<BR>
You have suscessfully registered.<BR>
Access the Tracker at <a href='$homepage$webpath'>
$homepage$webpath</a><BR>
Your Username: $USERNAME<BR>
Your Password: $PASSWD
</body>
</html>
";

         mail($EMAIL,$Subject,$message,$headers);

         // Send a mail to the administrator 
         $adminsubject="New user on VST";
         $message="$USERNAME has registered for VST";
         mail($reg_email,$adminsubject,$message,$headers);
      } else {
       echo "Something went wrong with the insert...try again mabey?";
    } // if the insert is succesful

   }

 } else {// if submitted
   // Just someone trying to register..display the form
   RegisterForm("","","","",$MAINURL,"register.php",$BUTTONTXT);
}

footer($PHP_SELF,$adminemail);
$dbconn->commit();
$dbconn->close();
} // close dbconn
?>
</body>
</html>
