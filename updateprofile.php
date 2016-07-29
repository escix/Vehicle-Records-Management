<?php
//==========================================================================
// updateprofile.php
//
// The VST Profile Edit form
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 22Feb2007 73blazer@snyderworld.org v2.1.0
//
//
//==========================================================================
?>
<html>
  <link rel='stylesheet' type='text/css' href='vst.css'>
  <head>
    <title>Vehicle Service Tracker - Update Profile</title>
  </head>
  </body>
  <H1>
  <CENTER>
   <P CLASS='Header1'>Vehicle Service Tracker - Update Profile</P>
  </CENTER>
  </H1>

<?php
if (isset($_REQUEST['SID'])) { 
$SID=$_REQUEST['SID'];
$USERNAME=$_REQUEST['USERNAME'];

 } else { 
$SID=""; 
$USERNAME=""; }

include_once("includes.php");

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

authuser($dbconn,'$USERNAME','$SID');

include_once("profile.php");

// Set some generic things
$MAINURL=$homepage.$webpath."/main.php?SID=$SID&USERNAME=$USERNAME";
$BUTTONTXT='Update Profile';

// If the Form was submitted
if (isset($_POST['RegisterMe'])) {

$EMAIL = $_POST['EMAIL'];
$FIRST = $_POST['FIRST'];
$LAST = $_POST['LAST'];
$USERNAME = $_POST['USERNAME'];
$PASSWD = $_POST['PASSWD'];
$PASSWD2 = $_POST['PASSWD2'];




   // Encrypt the password
   // ----------------------
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
   if ( $PASSWD  == "" && $PASSWD2!="") {
    if (!isset($UIErrors)) $UIErrors="";
    $UIErrors.="<b><ul><li><font color=#ff0000>";
    $UIErrors.="You entered a Confirmation password but didn't enter a Password</font></li></ul></b>";
   }
   if ( $PASSWD2  == "" && $PASSWD!="") {
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
     RegisterForm($FIRST,$LAST,$EMAIL,$USERNAME,$MAINURL,"updateprofile.php",$BUTTONTXT,0,$SID);
    } else {
     // Alrighty..everything passes so lets update this user!
     // ----------------------------------------------------------
     $LAST2=str_replace("'","''",$LAST);
     $UpdateProfile="update clients set ";
     $UpdateProfile.="FIRST='$FIRST',";
     $UpdateProfile.="LAST='$LAST2',";
     $UpdateProfile.="EMAIL='$EMAIL'";
     if ($PASSWD!="") { $UpdateProfile.=",PASSWD='$EPASSWD'"; }
     $UpdateProfile.=" where USRNAME=''$USERNAME'";

     if ($dbconn->query($UpdateProfile)) {
        echo "<TABLE BORDER='0' WIDTH='100%' CELLSPACING='1' CELLPADDING='2'>";
        echo "<TR CLASS='band'>";
        echo "<TD>";
        echo "<A CLASS='header2'>";
        echo "</A>";
        echo "<BR>";
        echo "&nbsp;";
        echo "<A CLASS='header2'>";
        if ($PASSWD!="") { $pmes=" and password "; } else { $pmes=""; }
        echo "&nbsp;Profile $pmes for User [$USERNAME] have been Sucessfully Updated</A>";
        echo "<BLOCKQUOTE>";
        echo "First Name: $FIRST<br>";
        echo "Last Name: $LAST<br>";
        echo "eMail: $EMAIL<br>";
        echo "</BLOCKQUOTE> ";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";        
        echo "<center><big><B><a href=main.php?SID=$SID&USERNAME=$USERNAME>";
        echo "To Main</a></B></big></center>";

        // Send me and the person a mail
        $headers = "MIME-Version:1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "From: Vehicle Service Tracker Admin <$adminemail>";
        $Subject="Profile Successfully updated on Vehicle Service Tracker";

$message="
<html><body><center><B>Vehicle Service Tracker</B></center>
<br>
Hello $FIRST $LAST<BR>
<BR>
Your profile $pmes on the Vehicle Service Tracker has been suscessfully updated<BR>
Access the Tracker at <a href='$homepage$webpath'>
$homepage$webpath</a><BR><BR>
If the request was not initiated by you, please notify $orgname's administrator at $adminemail.<BR>
</body>
</html>
";

         mail($EMAIL,$Subject,$message,$headers);
      } else {
       echo "Something went wrong with the update...try again mabey?";
    } // if the insert is succesful

   }

 } else {// if submitted
   // Just someone trying to register..display the form
//  $ProfileSelect="select rtrim(FIRST),rtrim(LAST),rtrim(EMAIL) ";
//  $ProfileSelect.="from clients where ";
//  $ProfileSelect.="USRNAME='$USERNAME';";
//  $ProfileResult=$dbconn->$ProfileSelect();
//  if (! $ProfileResult ) die("Something went wrong with select from db2 clients");

//  while ($dbconn->fetch_row($ProfileResult)) {
//    $FIRST=$dbconn->result($ProfileResult,1);
//    $LAST=$dbconn->result($ProfileResult,2);
//    $EMAIL=$dbconn->result($ProfileResult,3);
//  }

   RegisterForm($FIRST,$LAST,$EMAIL,$USERNAME,$MAINURL,"updateprofile.php",$BUTTONTXT,0,$SID);
}

footer($_SERVER['PHP_SELF'],$adminemail);
$dbconn->commit();
$dbconn->close();
} // close dbconn
?>
</body>
</html>
