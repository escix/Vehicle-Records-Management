<?php
session_start();
//==========================================================================
// showhistory.php
//
// Show a vehicles complete service history
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 24Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org v2.0.4
//          Added Miles Per Year calculation and insert into DB
//
//==========================================================================
?>


<html>
<link rel='stylesheet' type='text/css' href='vst.css'>
<head>
   <script language="javascript" type="text/javascript" src="popup.js"></script>
</head>

<?php

if (!$_SESSION['SID']){$SID=$_REQUEST['SID'];}
//echo $SID;
$USERNAME=$_SESSION['USERNAME'];
$VIN =$_POST['VIN'];
$MAKE =$_POST['MAKE'];
$MODEL =$_POST['MODEL'];
$COLOR =$_POST['COLOR'];
$YEAR =$_POST['YEAR'];
$MORK =$_POST['MORK'];
$GORD =$_POST['GORD'];
$IMAGE =$_POST['IMAGE'];
$GASMILE=$_POST['GASMILE'];
$RO=$_POST['RO'];
$FromNewRO=$_POST['FromNewRO'];

 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];
 $starttime = $mtime;

function db2ts2int( $ts )
{
   $y = substr( $ts, 0, 4 );
   $m = substr( $ts, 5, 2 );
   $d = substr( $ts, 8, 2 );
   $h = substr( $ts, 11, 2 );
   $mi = substr( $ts, 14, 2 );
   $s = substr( $ts, 17, 2 );
   return mktime($h,$mi,$s,$m,$d,$y);
}

setlocale(LC_MONETARY, 'en_US');
if (isset($SID)) { $SID=$_SESSION['SID']; }
if (isset($USERNAME)) { $USERNAME=$_SESSION['USERNAME'];}
include_once("includes.php");



$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {
   authuser($dbconn,$USERNAME,$SID);
   function ShowAddSRButton ($SID,$USERNAME,$VIN,$MAKE,$MODEL,$COLOR,$YEAR,
                     $MORK,$GORD,$IMAGE,$GASMILE) { 
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
      echo "</FORM>";
    } // ShowAddSRButton

// if (isset($show) && $show=="Show Service History") {

    $EditVehicleURL="newvehicle.php?SID=$SID&USERNAME=$USERNAME&";
    $EditVehicleURL.="EDIT=EDIT&VIN=$VIN&YEAR=$YEAR&MAKE=$MAKE&";
    $EditVehicleURL.="MODEL=$MODEL&COLOR=$COLOR&GAS=$GASMILE&";
    $EditVehicleURL.="IMAGE=$IMAGE&GORD=$GORD&MORK=$MORK";
    $EditVehicleURL=htmlentities($EditVehicleURL);

    // Show the Service History
    // --------------------------
    echo "<head>\n";
    echo "<title>Vehicle Service Tracker</title>\n";
    echo "</head>\n";
    echo "<center><p class='Header1'><B>Vehicle Service Tracker - ";
    echo "<small>Service History Inquiry</small></B></p></center>\n";
    echo "<CENTER><B>";
    echo "<a href='main.php?SID=$SID&USERNAME=$USERNAME'>Back to Main</a>";
    echo "</B></CENTER>";
    echo "<p class='Header2'>Vehicle: $YEAR $MAKE $MODEL &nbsp; &nbsp; &nbsp; ";
    echo "Color: $COLOR &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; VIN: $VIN ";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<a href='".$EditVehicleURL."'>";
    echo "<small> [ edit vehicle ] </small></A>";
    echo "<CENTER>";
    ShowAddSRButton ($SID,$USERNAME,$VIN,$MAKE,$MODEL,$COLOR,$YEAR,
                     $MORK,$GORD,$IMAGE,$GASMILE);
    echo "</CENTER>";

    // Get this repair order's details
    // ------------------------------------------------------
    $ROSelect="select REPAIR_ORDER,SERVICE_DATE,MILEAGE,SERVICE_SHORT FROM ";
    $ROSelect.="servicehist where VEHICLE='$VIN' ORDER BY MILEAGE DESC ";
    $ROResult=$dbconn->query($ROSelect);
    $numROS=0;
    while ($RORS=mysqli_fetch_row($ROResult)) {
      $numROS++;
      $ROS[$numROS]     =$RORS[0];
      $RODATES[$numROS] =$RORS[1];
      $ODOS[$numROS]    =$RORS[2];
      $DESCS[$numROS]   =$RORS[3];
      // Set the last odo reading for cost/mile calculations
      if ($numROS==1) { 
        $LastODO=$ODOS[$numROS];
        $LastDate=db2ts2int($RODATES[$numROS]);
      }
    } 
    if ($numROS!=0)  {
       // Set the first odo reading for cost/mile calculations
       $FirstODO=$ODOS[$numROS];
       $FirstDate=db2ts2int($RODATES[$numROS]);
      
       $nYears =  (($LastDate - $FirstDate) / 31536000);
        
       //echo "Num Years [$nYears] ";
       //echo "FirstODO [$FirstODO] FirstDate [$FirstDate]";
       //echo "Total Repair Records [$numROS]<BR><BR>";
       $TotalCost=0;
       $TotalHours=0;
       for ($key=1; $key <= $numROS; $key++) {
         if ($MORK=="K") {$MorkSuffix=" Kilometers";}else{$MorkSuffix=" Miles";}
         // Get this repair orders line items and display them
         // -------------------------------------------------------
         //echo "Repair Order: $ROS[$key] key[$key]";
         echo "<CENTER><TABLE width=95%>";
         echo "<TR><TD align=center colspan=2><p class='header8'>$DESCS[$key]";
         if ($key==1) {
           // Show the Add line items for the first repair order
           echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
           echo "<A href='addli.php?SID=$SID&USERNAME=$USERNAME";
           echo "&VIN=$VIN&ODO=$ODOS[$key]&DESC=$DESCS[$key]&EditRO=1";
           echo "&YEAR=$YEAR&MAKE=$MAKE&MODEL=$MODEL&COLOR=$COLOR&IMAGE=$IMAGE";
           echo "&GASMILE=$GASMILE&GORD=$GORD&MORK=$MORK&RO=$ROS[$key]'";
           echo ">[ Add Line Items To Repair Order ]</A>";
         } // if first row
         echo "</TD></TR>";
         echo "<TR><TD><P class='header8'>Date: ";
         echo "$RODATES[$key]</TD>";
         echo "<TD><p class='header8'>Odometer: ";
         echo number_format($ODOS[$key]);
         echo "$MorkSuffix</TD></TR>";
         echo "</TABLE>";
         $SROSelect="select REPAIR_ORDER_INDEX,LINE_DATE,OPERATION,";
         $SROSelect.="SOURCE,PART_NUMBER,COST,HOURS,NOTES,LINE_INDEX ";
         $SROSelect.="FROM serviceline where REPAIR_ORDER=";
         $SROSelect.="'$ROS[$key]' ORDER BY REPAIR_ORDER_INDEX ASC";
         $SROResult=$dbconn->query($SROSelect);
         echo "<TABLE WIDTH='95%'>";
         echo "<TR>";
         echo "<TD class='header1'>Item</TD>";
         echo "<TD class='header1'>Date</TD>";
         echo "<TD class='header1'>Labor Operation</TD>";
         echo "<TD class='header1'>Source</TD>";
         echo "<TD class='header1'>Part Number</TD>";
         echo "<TD class='header1'>Cost</TD>";
         echo "<TD class='header1'>Hours</TD>";
         echo "</TR>";
         $TotalROCost=0;
         $TotalROHours=0;
   
         $numLines=0;
         while ($RTO=mysqli_fetch_row($SROResult)) {
           $numLines++;
           if ($numLines %2 == 0) { echo "<TR class='band'>"; } else { echo "<TR>"; }
           echo "<TD>";
           echo $RTO[0];
           $NOTES=$RTO[7];
           if ($NOTES!="") {
               $NOTES.="ANEWLINEANEWLINE";
               echo "<script>document.write(\"<a href='#' onclick=openPopup('";
               echo $NOTES;
               echo "');>[n]</a>\");";
               echo "</script>\n";

              echo "<noscript>";
              $TMPURL="notes.php?SID=$SID&USERNAME=$USERNAME&INFO=";
              $TMPURL.=nl2br($RTO[7]);
              echo "&nbsp;";
              echo "<a href='$TMPURL'>[n]</a>";
              echo "</noscript>";
           }
           echo "</TD><TD>";
           echo $RTO[1];
           echo "</TD><TD>";
           echo $RTO[2];
           echo "</TD><TD>";
           echo $RTO[3];
           echo "</TD><TD>";
           echo $RTO[4];
           echo "</TD><TD>";
           echo money_format('%n',$RTO[5]);
           echo "</TD><TD>";
           echo $RTO[6];
           echo "</TD></TR>";
           $TotalROCost=$TotalROCost+$RTO[5];
           $TotalROHours=$TotalROHours+$RTO[6];
         } // while ($ROT=mysqli_fetch_row($SROResult))
         echo "</TABLE>";
         echo "<TABLE WIDTH='95%'><TR>";
         echo "<TD class=header1>Total Cost: ";
         echo money_format('%n',$TotalROCost);
         echo "</TD>";
         echo "<TD class=header1>Total Hours: $TotalROHours</TD>";
         echo "</TR></TABLE></CENTER>";
         echo "<BR><BR>";
         $TotalCost=$TotalCost+$TotalROCost;
         $TotalHours=$TotalHours+$TotalROHours;
       } //foreach repair order
       $TotalCost=round($TotalCost,2);
       $TotalHours=round($TotalHours,2);
       if ($numROS==1) {
          // There's only 1 Repair order so some calculations
          // cannot be beformed unless at least 2 exists
          // --------------------------------------------- 
          $MilesDriven=0;
          $CPM=0;
          $AMPY=0;
        } else {
          // There's more than 1 repair order so we
          // can calculate everything...
          // -----------------------------------------
          $AMPY=round(($LastODO-$FirstODO)/$nYears);
          if ($MORK=="K") {
             $MilesDriven=(($LastODO-$FirstODO)*1);
           } else { 
             $MilesDriven=$LastODO-$FirstODO;
          }
          $CPM=round((($TotalCost/$MilesDriven)*100),1);
       } // if only 1 repair order
       echo "<CENTER>";
       echo "<TABLE BORDER='0' WIDTH='70%' CELLSPACING='1' CELLPADDING='2'>";
       echo "<TR class='header4'><TD colspan='3' align='center'>";
       echo "Totals for $YEAR $MAKE $MODEL &nbsp; &nbsp &nbsp; VIN: $VIN</TD></TR>";
       echo "<TR class='header3'><TD rowspan='5'><img src='$IMAGE'></TD>";
       echo "<TD>Total Hours Spent Maintaining Vehicle</TD>";
       echo "<TD>$TotalHours</TD></TR>";
       echo "<TR class='header3'><TD>Total $ Spent For Maintaince</TD>";
       echo "<TD>";
       echo money_format('%n',$TotalCost);
       echo "</TD></TR>";
       echo "<TR class='header3'><TD>Total Miles driven</TD>";
       echo "<TD>";
       if ($numROS==1) {
          echo "N/A";
         } else {
          echo number_format($MilesDriven);
       }
       echo "</TD></TR>";
       echo "<TR class='header3'><TD>";
       echo "average kms per year driven";
       if ($nYears<1) echo " (Extrapolated)";
       echo "</TD><TD>";
       echo number_format($AMPY);
       echo "</TD></TR>";
       echo "<TR class='header3'><TD>";
       echo "Cost per kms to maintain this vehicle (cents/km)</TD>";
       if ($numROS==1) {
          echo "<TD>N/A</TD></TR>";
         } else { 
          echo "<TD>$CPM</TD></TR>";
       }
       echo "</TABLE>";
       echo "</CENTER>";
       if ($MilesDriven==0) {
          echo "<BR>";
          echo "<B>There is only 1 repair order.<BR>";
          echo "Cost per km & Kms Driven can only be ";
          echo "calculated if there are at least 2 repair orders.</B><BR>";
       }
   
       // Insert the new calculated values into this vehicles history
       // we do it here so the main page doesn't have to go selecting
       // every line item for every vehicle when it loads
       // -------------------------------------------------------------
       $UpdateVehicleStats="update vehicles set ";
       $UpdateVehicleStats.="MILESDRIVEN=$MilesDriven,";
       $UpdateVehicleStats.="COSTPERMILE=$CPM,";
       $UpdateVehicleStats.="TOTALSPENT=$TotalCost,";
       $UpdateVehicleStats.="TOTALHOURS=$TotalHours,";
       $UpdateVehicleStats.="AVG_MILES_PER_YEAR=$AMPY ";
       if ($nYears<1) $UpdateVehicleStats.= ",EXAVG='E' ";
       $UpdateVehicleStats.="where VIN='$VIN'";
       //echo "Update stats statment [$UpdateVehicleStats]";
       $dbconn->query($UpdateVehicleStats);
       $dbconn->commit();
    } else {
       echo "There are no service records for this vehicle yet. Add some!<BR>";
   } // if there are any repair orders
   echo "<BR><BR><CENTER><B>";
   echo "<a href='main.php?SID=$SID&USERNAME=$USERNAME'>Back to Main</a>";
   echo "</B></CENTER>";
// } // show or add
$dbconn->close();
} // if dbconn



footer($PHP_SELF,$adminemail);

  $mtime = microtime();
  $mtime = explode(" ",$mtime);
  $mtime = $mtime[1] + $mtime[0];
  $endtime = $mtime;
  $totaltime = ($endtime - $starttime);
  echo "<br><br>This page was created in ".$totaltime." seconds";

?>


</html>


