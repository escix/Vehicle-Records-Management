<?php
//==========================================================================
// includes.php
//
// common function used by the tracker
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

//if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
//if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
//if (isset($_REQUEST['PASSWD'])) { $PASSWD=$_REQUEST['PASSWD'];} else { $PASSWD=""; }






session_start();

//if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
//$_SESSION['SID']=$SID;
$SID=$_SESSION['SID'];

//if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
//$_SESSION['USERNAME']=$USERNAME;
$USERNAME=$_SESSION['USERNAME'];

//if (isset($_REQUEST['PASSWD'])) { $PASSWD=$_REQUEST['PASSWD'];} else { $PASSWD=""; }
//$_SESSION['PASSWD']=$PASSWD;
$PASSWD=$_SESSION['PASSWD'];




include_once("config.php");

function getversion () {
  $VSTVERSION=shell_exec("./getvstversion");
  return $VSTVERSION;
} // function getversion

function validate_email($email){
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
     return true;
   } else {
     return false;
   }   
}


function check_unique_username ($dbconn,$USERNAME) {
$query = "SELECT * FROM clients WHERE USRNAME='$USERNAME'";
$result = mysqli_query($dbconn, $query);
$count = mysqli_num_rows($result);

 if($count>=1)
   {
    return true;
   }
 else
    {
	return false;
    }
}



function footer ($FROMLOC,$adminemail) {
  $FROMLOC=substr(substr($FROMLOC, -abs(strpos ($FROMLOC,".")) ),1);
  $VSTVERSION=getversion();
  echo "<BR>";
  echo "<TABLE width='100%'>";
  echo "<TR class='band'><TD>";
  echo "<a href='mailto:$adminemail?subject=Question%20about%20Vehicle%20Service%20Tracker&body=From:%20$FROMLOC'>";
  echo "<address>$adminemail</address></a>";
  echo "</TD><TD align='right'>";
  echo "<B>Vehicle Service Tracker v$VSTVERSION</B>";
  echo "</TD></TR>";
  echo "</TABLE>";
} // function footer


function authuser ($dbconn,$user,$sid) {
 if ($user!="demo") { // always allow demo user
  $SelectSID="select rtrim(SID) from clients where USRNAME='$user';";
  $result=$dbconn->query($SelectSID);
  $Result=$result->fetch_row();
  $SelectedSID=$Result[0];
//  if ( ($SelectedSID!=$sid) || !($sid) ) {
//    $dbconn->close();
//    header("Location: login.php?rc=14 $sid . $SelectedSID");
//    die("sent back to login");
//  } 
  //die ("allowed");
 } // if demo user
} // function authuser


function titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK) {
      $EditVehicleURL="newvehicle.php?SID=$SID&USERNAME=$USERNAME&";
      $EditVehicleURL.="EDIT=EDIT&VIN=$VIN&YEAR=$YEAR&MAKE=$MAKE&";
      $EditVehicleURL.="MODEL=$MODEL&COLOR=$COLOR&GAS=$GASMILE&";
      $EditVehicleURL.="IMAGE=$IMAGE&GORD=$GORD&MORK=$MORK";
      $EditVehicleURL=htmlentities($EditVehicleURL);

      // Show the title bar
      // --------------------------
      echo "<CENTER><B>";
      echo "<a href='main.php?SID=$SID&USERNAME=$USERNAME'>Back to Main</a>";
      echo "</B></CENTER>";
      echo "<p class='Header2'>Vehicle: $YEAR $MAKE $MODEL &nbsp; &nbsp; &nbsp; ";
      echo "Color: $COLOR &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; VIN: $VIN ";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<a href='".$EditVehicleURL."'>";
      echo "<small> [ edit vehicle ] </small></A></P>";
} // function titlebar

function TimeoutInsert($dbconn,$USERNAME) {
  $TimeOut="UPDATE clients SET LASTLOGIN=CURRENT_TIMESTAMP ";
  $TimeOut.="WHERE USRNAME='$USERNAME'";
  $dbconn->query($TimeOut);
  $dbconn->commit();
} // function TimeoutInsert

//function CheckTimeout($dbconn,$USERNAME) {
//  $Select="select LASTLOGIN
