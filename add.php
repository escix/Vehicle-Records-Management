<?php
session_start();

if(!$_SESSION['user_ok'])
{header ('location: /index.php');}
else{}





//==========================================================================
// add.php
//
// Form to add,error check and edit New vehicle repair orders
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
<?php

//if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
//if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
include_once("includes.php");


if (!$SID)($SID=$_SESSION['SID']);
if (!$USERNAME)($USERNAME=$_SESSION['USERNAME']);
if (!$VIN) {$VIN =$_REQUEST['VIN'];}
if (!$MAKE){$MAKE =$_REQUEST['MAKE'];}
if (!$MODEL){$MODEL =$_REQUEST['MODEL'];}
if (!$COLOR){$COLOR =$_REQUEST['COLOR'];}
if (!$YEAR){$YEAR =$_REQUEST['YEAR'];}
if (!$MORK){$MORK =$_REQUEST['MORK'];}
if (!$GORD){$GORD =$_REQUEST['GORD'];}
if (!$IMAGE){$IMAGE =$_REQUEST['IMAGE'];}
if (!$GASMILE){$GASMILE =$_REQUEST['GASMILE'];}
if (!$add){$add =$_REQUEST['add'];}
if (!$RO){$RO =$_REQUEST['RO'];}


//$SID=$_SESSION['SID'];
//$USERNAME=$_SESSION['USERNAME'];
//$VIN =$_POST['VIN'];
//$MAKE =$_POST['MAKE'];
//$MODEL =$_POST['MODEL'];
//$COLOR =$_POST['COLOR'];
//$YEAR =$_POST['YEAR'];
//$MORK =$_POST['MORK'];
//$GORD =$_POST['GORD'];
//$IMAGE =$_POST['IMAGE'];
//$GASMILE=$_POST['GASMILE'];
//$add=$_POST['add'];

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

   authuser($dbconn,$USERNAME,$SID);

   function NewROForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC) {
     echo "<form method=post action=add.php>";
     echo "<table cellpadding='5'>";
     echo "<tr><td rowspan='8'><img src='$IMAGE'></td></tr>";
     echo "<tr><td align='right'><B>";

 
     echo "<tr><td align='right'><B>";
     echo "Service Date</b></td><td>";
     echo "<input name=SERDATE type=date size=7 maxlength=7>";
     echo "</td></tr><tr><td align='right'><B>";



     echo "Odometer Reading</b></td><td>";
     echo "<input name=ODO type=text size=7 maxlength=7 value=$ODO>";
     echo "</td></tr><tr><td align='right'><B>";
     echo "General Short Decription of Service";
     echo "</b></td><td>";
     echo "<input name=DESC type=text size=60 maxlength=75 value=\"$DESC\">";
     echo "</td></tr>";
     echo "<input name=VIN type=hidden value=$VIN>";
     echo "<input name=YEAR type=hidden value=$YEAR>";
     echo "<input name=MAKE type=hidden value=\"$MAKE\">";
     echo "<input name=MODEL type=hidden value=\"$MODEL\">";
     echo "<input name=COLOR type=hidden value=$COLOR>";
     echo "<input name=SID type=hidden value=$SID>";
     echo "<input name=USERNAME type=hidden value=$USERNAME>";
     echo "<input name=IMAGE type=hidden value=$IMAGE>";
     echo "<input name=GASMILE type=hidden value=$GASMILE>";
     echo "<input name=GORD type=hidden value=$GORD>";
     echo "<input name=MORK type=hidden value=$MORK>";
     echo "</table>";
     echo "<p>";
     echo "<center><B>";
     echo "<input type='submit' name='addnewro' ";
     echo "value='Create New Service Record & Continue to Enter Line Items' ";
     echo "id='butt'></B></center>";
     echo "</form>";
   } // function show new ro form


   echo "<center><p class='Header1'>";
   echo "<title>Vehicle Service Tracker - Add A New Repair Order</title>";
   echo "<B>Vehicle Service Tracker - Add A New Repair Order</B></p></center>";


   if (isset($add)) {
      titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
      NewROForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,"","");
    } else {
      // Ok the user is trying to create a brand new RO..lets check his input
      $LastODOSelect="select MILEAGE from servicehist where VEHICLE='$VIN' ";
      $LastODOSelect.="order by MILEAGE DESC";
      $LastODOResult=$dbconn->query($LastODOSelect);
      if (! $LastODOResult ) die("Something went wrong with select last mileage");
      $row=mysqli_fetch_row($LastODOResult);
      $LastODO=$row[0];
      if ( $LastODO=="" ) $LastODO=0;

$ODO=$_POST['ODO'];
$DESC=$_POST['DESC'];
$SERDATE=$_POST['SERDATE'];

      if ( $ODO=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter the Vehicle's Odometer reading</font></li></ul></b>";
       } elseif (! is_numeric($ODO)) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="The odometer reading you entered [$ODO] is not a number";
         $UIErrors.="</font></li></ul></b>";
       } elseif ($LastODO>$ODO) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="The odometer reading you entered [";
         $UIErrors.=number_format($ODO)."] is smaller than the ";
         $UIErrors.="last known odometer reading of [".number_format($LastODO)."]";
         $UIErrors.="</font></li></ul></b>";
      }
      if ( $DESC=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter a general Service Description</font></li></ul></b>";
      }
      if (isset($UIErrors)) {
        titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
        echo "<b><center><font size=+1>";
        echo "There was trouble with proccessing your information.</font>";
        echo "</b></center>";
        echo $UIErrors;
        NewROForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC);
       } else {
        // OK. Insert this new vehicle!

        // Generate a Uniq RO number
        $NRONum=uniqid();
        // Strip off any tenths of odometer reading entered
        $ODO=intval($ODO);
        //Build the insert
        $MyInsert="insert into servicehist ";
        $MyInsert.="(repair_order,vehicle,service_date,mileage,service_short) values ('";
        $MyInsert.=$NRONum."','".$VIN."','".$SERDATE."','".$ODO."','".$DESC."');";
        echo $MyInsert;
 
        // Perform the insert and redirect or die
        if ($dbconn->query($MyInsert)) {
           $dbconn->commit();
           $dbconn->close();
           $LIHeader="Location: addli.php?SID=$SID&USERNAME=$USERNAME&RO=$NRONum&FromNewRO=0";
           $LIHeader.="&ODO=$ODO&VIN=$VIN&YEAR=$YEAR&MAKE=$MAKE&MODEL=$MODEL&COLOR=$COLOR";
           $LIHeader.="&GASMILE=$GASMILE&IMAGE=$IMAGE&GORD=$GORD&MORK=$MORK&DESC=$DESC";
           header($LIHeader);
         } else {
           $dbconn->commit();
           $dbconn->close();
           die ("Something went wrong with repair order insert.");
        }

      } // if ui errors or not


   } // if it's add or check add


} // if authorized to view this page
           $dbconn->commit();
           $dbconn->close();
 
?>
