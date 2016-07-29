<?php
session_start();


//==========================================================================
// checkuser.php
//
// Check user login information, called from login.php
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 23Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org v2.0.4
//          - Select LASTLOGIN, insert LASTLOGIN. Pass LASTLOGIN to main
//          22Feb2007 Snyder, Kenneth J. 73blazer@snyderworld.org v2.1.0
//          - Changed password to use hash
//
//==========================================================================
?>

<html>
 <body>

<?php
session_regenerate_id();

if(!isset($_SESSION['USERNAME']))
 { $_SESSION['USERNAME']=$_POST['USERNAME'];
  $_SESSION['PASSWD']=$_POST['PASSWD'];
}
else{
header("Location: index.php");
}

include_once("includes.php");

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);

$USERNAME=$_SESSION['USERNAME'];
$PASSWD=$_SESSION['PASSWD'];

$PASSWD=sha1($PASSWD);
if (!$dbconn) {
   $a = "DB2 Connect Failed. DB2 might not be running";
   echo($a);
 } else {

   if ( $USERNAME ) {
     // Make the select for the user
     // -------------------------------
     $SelectLogin="select LAST,FIRST,PASSWD,date(LASTLOGIN),";
     $SelectLogin.="time(LASTLOGIN) from ";
     $SelectLogin.="clients where USRNAME='$USERNAME'";
     $SelectLoginResult = $dbconn->query($SelectLogin);

     // Set the results if there are any
     // -------------------------------------------
if   ($SelectLoginResult){
     $row = $SelectLoginResult->fetch_row();
     $DBLAST=$row[0];
     $DBFIRST=$row[1];
     $DBPASSWD=$row[2];
     $LASTDATE=$row[3];
     $LASTTIME=$row[4];
}
     // Check if there are any results and if the password is right
     // -------------------------------------------------------------
     if ( ! $DBPASSWD ) {
       $dbconn->commit();
       $dbconn->close();
      header("Location:"); // index.php?rc=10");
      } elseif ($DBPASSWD==$PASSWD) {
       $SID=md5(uniqid(rand(),TRUE));
       $SIDUpdate="update clients set SID='$SID',";
       $SIDUpdate.="LASTLOGIN=CURRENT_TIMESTAMP where USRNAME='$USERNAME';";
       $irc=$dbconn->query($SIDUpdate);
       $dbconn->commit();
       $dbconn->close();
       echo "IRC $irc";
       if ( $irc ) {
         //--------------------------
	if(!isset($_SESSION['SID']))
	 { $_SESSION['SID']=$SID;
         }
         $headers="Location: main.php?SID=$SID&USERNAME=$USERNAME&";
         $headers.="LASTDATE=$LASTDATE&FIRST=$DBFIRST&LASTTIME=$LASTTIME";
         header($headers);
	exit;
        } else {
         die("Something wrong with update...no db connection");
       } // if ($irc)
      } else {
       //echo "Invalid password [$PASSWD] dbpassword [$DBPASSWD]";
       $dbconn->commit();
       $dbconn->close();
       header("Location: index.php?rc=21&USERNAME=$USERNAME");
     } // if (! $DBPASSWD)
    } else {
	$dbconn->commit();
       $dbconn->close();
     header("Location:"); //index.php?rc=14");
   } // if ($USERNAME)
   
} // DB Connect passed
?>
 </body>
</html>
