<?php 

session_start();

if(!$_SESSION['user_ok'])
{header ('location: /index.php');}
else{}

//==========================================================================
// main.php
//
// The main summary screen showing all the vehicles, and their 
// statistics
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: Snyder, Kenneth J. 73blazer@snyderworld.org v2.0.4
//           - 23Mar2006 Added Welcome message with last login date/time
//           - 24Mar2006 Added Avg. miles per year display
//          Snyder, Kenneth J. 73blazer@snyderworld.org v2.1.0
//           - 23Feb2007Added Update Profile Link
//
//==========================================================================

$SID=$_SESSION['SID'];
$USERNAME=$_SESSION['USERNAME'];
$PASSWD=$_SESSION['PASSWD'];


include_once("includes.php");

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname); 
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

   authuser($dbconn,$USERNAME,$SID);
   setlocale(LC_MONETARY, 'en_US');

   function showaddsr ($USERNAME,$SID,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,$MORK,$GORD,$IMAGE,$GASMILE) {
    //echo "<CENTER>";
    echo "<table cellpadding='0'><tr><td>";
    echo "<FORM METHOD='POST' ACTION='showhistory.php'>";
    echo "<INPUT name='SID' type='hidden' VALUE='$SID'>";
    echo "<INPUT name='USERNAME' type='hidden' VALUE='$USERNAME'>";
    echo "<INPUT name='VIN' type='hidden' VALUE='$VIN'>";
    echo "<INPUT name='MAKE' type='hidden' VALUE='$MAKE'>";
    echo "<INPUT name='MODEL' type='hidden' VALUE='$MODEL'>";
    echo "<INPUT name='COLOR' type='hidden' VALUE='$COLOR'>";
    echo "<INPUT name='YEAR' type='hidden' VALUE='$YEAR'>";
    echo "<INPUT name='MORK' type='hidden' VALUE='$MORK'>";
    echo "<INPUT name='GORD' type='hidden' VALUE='$GORD'>";
    echo "<INPUT name='IMAGE' type='hidden' VALUE='$IMAGE'>";
    echo "<INPUT name='GASMILE' type='hidden' VALUE='$GASMILE'>";
    echo "<INPUT name='show' id='butt' type='Submit' value='Show Service History'>";
    echo "</FORM></td><td>";
    echo "<FORM METHOD='POST' ACTION='add.php'>";
    echo "<INPUT name='SID' type='hidden' VALUE='$SID'>";
    echo "<INPUT name='USERNAME' type='hidden' VALUE='$USERNAME'>";
    echo "<INPUT name='VIN' type='hidden' VALUE='$VIN'>";
    echo "<INPUT name='MAKE' type='hidden' VALUE='$MAKE'>";
    echo "<INPUT name='MODEL' type='hidden' VALUE='$MODEL'>";
    echo "<INPUT name='COLOR' type='hidden' VALUE='$COLOR'>";
    echo "<INPUT name='YEAR' type='hidden' VALUE='$YEAR'>";
    echo "<INPUT name='MORK' type='hidden' VALUE='$MORK'>";
    echo "<INPUT name='GORD' type='hidden' VALUE='$GORD'>";
    echo "<INPUT name='IMAGE' type='hidden' VALUE='$IMAGE'>";
    echo "<INPUT name='GASMILE' type='hidden' VALUE='$GASMILE'>";
    echo "<INPUT name='add' id='butt' type='Submit' value='Add New Service Record'>";
    echo "</FORM></td></tr></table>";
    //echo "</CENTER>";
   } //function showaddsr 
?>

<html>
 <head>
  <title>Vehicle Records Manager</title>
 </head>
 <link rel='stylesheet' type='text/css' href='vst.css'>
 <center><p class='Header1'><B>Vehicle Records Manager</B></p></center>
 <body>
<?php
 if (isset($LASTDATE) && $LASTDATE!="" && isset($LASTTIME) && isset($FIRST)) {
   echo "<center>";
   echo "Welcome, $FIRST. You last logged in on $LASTDATE at $LASTTIME";
   echo "</center><br>";
 }
 echo "<center><B>";
 echo "<a href='newvehicle.php?SID=$SID&USERNAME=$USERNAME'>Add New Vehicle</a>";
 echo " | ";
 if ($USERNAME=='demo') { // Lockout Demo from updating profile
   echo "<small>Update Profile</small>";
  } else {
   echo "<a href='updateprofile.php?id=SID=$SID&USERNAME=$USERNAME'>Update Profile</a>";
 }
 echo " | ";
echo "<a href='logout.php?SID=$SID&USERNAME=$USERNAME'>Logout</a>";
 echo "</B></center>";
 $VehiclesSelect="select VIN,YEAR,MAKE,MODEL,COLOR,";
 $VehiclesSelect.="C_CREATE,ODOMORK,";
 $VehiclesSelect.="COSTPERMILE,MILESDRIVEN,TOTALSPENT,TOTALHOURS,IMAGE,";
 $VehiclesSelect.="GASMILE,GASORDIESEL,AVG_MILES_PER_YEAR,EXAVG ";
 $VehiclesSelect.="from vehicles where ";
 $VehiclesSelect.="OWNER='$USERNAME' order by C_CREATE;";
 $VehiclesResult=$dbconn->query($VehiclesSelect);
 if (! $VehiclesResult ) die("Something went wrong with select from db2 vehicles"); 
 $Vehicles=0;
 $TotalAMPY=0;
 
while ($rows=mysqli_fetch_assoc($VehiclesResult)) {
   $VINS[$Vehicles]=$rows[VIN];
   $YEARS[$Vehicles]=$rows[YEAR];
   $MAKES[$Vehicles]=$rows[MAKE];
   $MODELS[$Vehicles]=$rows[MODEL];
   $COLORS[$Vehicles]=$rows[COLOR];
   $CCREATES[$Vehicles]=$rows[C_CREATE];
   $MORK[$Vehicles]=$rows[ODOMORK];
   $CPMS[$Vehicles]=$rows[COSTPERMILE];
   $MILESDRIVENS[$Vehicles]=$rows[MILESDRIVEN];
   $TOTALSPENTS[$Vehicles]=$rows[TOTALSPENT];
   $TOTALHOURS[$Vehicles]=$rows[TOTALHOURS];
   $IMAGES[$Vehicles]=$rows[IMAGE];
   $GASMILES[$Vehicles]=$rows[GASMILE];
   $GORDS[$Vehicles]=$rows[GASORDIESEL];
   $AMPYS[$Vehicles]=$rows[AVG_MILES_PER_YEAR];
   $ExtrapolatedAvg[$Vehicles]=$rows[EXAVG];
   $TotalAMPY=$TotalAMPY+$AMPYS[$Vehicles];
   $Vehicles++;
 } // for every row

mysqli_free_result($VehiclesResult);

 if ($Vehicles==0) {
   echo "You have no vehicles. Add some!";
  } else {

   echo "<CENTER>";
   echo "<TABLE WIDTH='90%' CELLPADDING=2 CELLSPACING=1 BORDER=0>\n";

   for ($y = 0; $y <= $Vehicles-1; $y++) {
     if ($y %2 == 0) {
       echo "<TR CLASS='band'>";
      } else {
       echo "<TR>";
     }
     echo "<TD width='10%'>";
     if (!$IMAGES[$y])  $IMAGES[$y]='pics/car.jpg'; 
     echo "<IMG src='$IMAGES[$y]'>";
     $EditVehicleURL="newvehicle.php?SID=$SID&USERNAME=$USERNAME&";
     $EditVehicleURL.="EDIT=EDIT&VIN=$VINS[$y]&YEAR=$YEARS[$y]&MAKE=$MAKES[$y]&";
     $EditVehicleURL.="MODEL=$MODELS[$y]&COLOR=$COLORS[$y]&GAS=$GASMILES[$y]&";
     $EditVehicleURL.="IMAGE=$IMAGES[$y]&GORD=$GORDS[$y]&MORK=$MORK[$y]";
     //echo $EditVehicleURL;
     $EditVehicleURL=htmlentities($EditVehicleURL);
     echo "</TD>";
     echo "<TD width='50%'>";
     echo "<B>$YEARS[$y] $MAKES[$y] $MODELS[$y]</B><BR>";
     echo "VIN: $VINS[$y]<BR>Color: $COLORS[$y]";
     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
     echo "<a href='".$EditVehicleURL."'>";
     echo "<small> [ edit vehicle ] </small></A>";
     $CCREATES[$y]=date("d M Y",strtotime($CCREATES[$y]));
     echo "<BR>Vehicle Added: $CCREATES[$y] ";
     echo "<BR>";

     showaddsr($USERNAME,$SID,$VINS[$y],$YEARS[$y],$MAKES[$y],
                  $MODELS[$y],$COLORS[$y],$MORK[$y],$GORDS[$y],$IMAGES[$y],$GASMILES[$y]);
     echo "</TD>";
     echo "<TD width='40%'>";
     echo "<TABLE border='2' width='100%' >";
     echo "<TR><TD width='70%' align='right'>";
     echo "<p class='header8'><small>Cost per distance (cents/unit)</small></p></TD>";
     echo "<TD width='30%'><p class='header8'>";
     if ($CPMS[$y]=="") {
        echo "N/A";
      } else {
        echo "$CPMS[$y]";
     }
     echo "</p></TD></TR>";
     echo "<TR><TD width='70%' align='right'>";
     echo "<p class='header8'><small>Total \$ Spent</small></p></TD>";
     echo "<TD width='30%' ><p class='header8'>";
     if ($TOTALSPENTS[$y]=="") {
        echo "N/A";
      } else {

        echo '$' . number_format($TOTALSPENTS[$y], 2);
     }
     echo "</p></TD></TR>";
     echo "<TR><TD width='70%' align='right'>";
     echo "<p class='header8'><small>Total Hours Spent Maintaining</small></p></TD>";
     echo "<TD width='30%'><p class='header8'>";
     if ($TOTALHOURS[$y]=="") {
        echo "N/A";
      } else {
        echo "$TOTALHOURS[$y]";
     }
     echo "</p></TD></TR>";
     echo "</p></TD></TR>";
     echo "<TR><TD width='70%' align='right'>";
     echo "<p class='header8'><small>Average distance/Year";
     if ($ExtrapolatedAvg[$y]) echo " (Extrapolated)";
     echo "</small></p></TD>";
     echo "<TD width='30%'><p class='header8'>";
     if ($AMPYS[$y]) {
        echo number_format($AMPYS[$y]);
      } else {
        echo "N/A";
     }
     echo "</p></TD></TR>";
     echo "</TABLE>";
     echo "</TD></TR>\n";
   }
   echo "</TABLE>";
   echo "</CENTER>";
   echo "<BR>";
   echo "<p class='header2' align='right'>Average Distance/Per Year driven for all Vehicles: ";
   echo number_format($TotalAMPY);
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>";
 } // if ($Vehicles==0) {
 echo "<center><B>";
 echo "<a href='newvehicle.php?SID=$SID&USERNAME=$USERNAME'>Add New Vehicle</a>";
 echo " | ";
 if ($USERNAME=='demo') { // Lockout Demo from updating profile
   echo "<small>Update Profile</small>";
  } else {
   echo "<a href='updateprofile.php?SID=$SID&USERNAME=$USERNAME'>Update Profile</a>";
 }
 echo " | ";
 //echo "<a href='logout.php?SID=$SID&USERNAME=$USERNAME'>Logout</a>";
 echo "</B></center>";
 footer($_SERVER['PHP_SELF'],$adminemail);
?>

 </body>
</html>

<?php
TimeoutInsert($dbconn,$USERNAME);
} // if dbconn
?>
