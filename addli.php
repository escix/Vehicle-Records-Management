<?php
//==========================================================================
// addli.php
//
// Form to add,error check and edit repair order line items
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
 <head>
   <script language="javascript" type="text/javascript" src="popup.js"></script>
 </head>
<link rel='stylesheet' type='text/css' href='vst.css'>
<body>
<?php
setlocale(LC_MONETARY, 'en_US');


function ReDisplayFormData ($Input) {
if (strpbrk($Input,"'")) {
  $Input='"'.$Input.'"';
 } else {
  $Input="'".$Input."'";
}
return $Input;
} // function ReDisplayFormData


function NewLIForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC,$RO,$NOTES,
                    $OPERATION,$SOURCE,$PART_NUMBER,$COST,$HOURS) {

// If the objects had single quote, we need to account for
// that when displaying it as a value (only displayed if
// they made an error on the original submit)
if ($OPERATION) { $OPERATION=ReDisplayFormData ($OPERATION); }
if ($SOURCE) { $SOURCE=ReDisplayFormData ($SOURCE); }
if ($PART_NUMBER) { $PART_NUMBER=ReDisplayFormData ($PART_NUMBER); }

?>

<form method=post action=addli.php>
<table cellpadding='2' width=90%>
<tr><td rowspan=6>
  <img src="<?php echo $IMAGE ?>">
</td></tr>
<tr><td>
     <b>Operation</b>
  </td><td colspan=2>
     <input name=OPERATION type=text size=75 maxlength=75
     <?php 
     echo "value=$OPERATION"; 
     ?>
     >
  </td></tr>
<tr><td>
     <b>Source</b>
  </td><td>
     <input name=SOURCE type=text size=30 maxlength=30 value=
     <?php
     if ($SOURCE) {
       echo "$SOURCE >";
      } else {
       echo "NA>";
     }
     ?>
  </td><td rowspan=5>
    <b>Notes </b>
    <textarea name=NOTES rows=4 cols=40><?php if ($NOTES) { echo $NOTES; } ?></textarea>
  </td></tr>

<tr><td>
     <b>Part Number</b>
  </td><td>
     <input name=PART_NUMBER type=text size=18 maxlength=18 value=
     <?php
     if ($PART_NUMBER) {
       echo "$PART_NUMBER >";
      } else {
       echo "NA>";
     }

     ?>
  </td></tr>
<tr><td>
     <b>Cost<b>
  </td><td>
     <input name=COST type=text size=6 maxlength=6 value=0.00>
  </td></tr>
<tr><td>
     <b>Hours<b>
  </td><td>
     <input name=HOURS type=text size=6 maxlength=6 value=0.00>
  </td></tr>
</table>
<input name=VIN type=hidden value=<?php echo $VIN ?> >
<input name=ODO type=hidden value=<?php echo $ODO ?> >
<input name=YEAR type=hidden value=<?php echo $YEAR ?> >
<input name=MAKE type=hidden value="<?php echo $MAKE ?> ">
<input name=MODEL type=hidden value="<?php echo $MODEL ?>">
<input name=COLOR type=hidden value=<?php echo $COLOR ?> >
<input name=DESC type=hidden value="<?php echo $DESC ?>">
<input name=GASMILE type=hidden value="<?php echo $GASMILE ?>">
<input name=IMAGE type=hidden value="<?php echo $IMAGE?>">
<input name=GORD type=hidden value="<?php echo $GORD ?>">
<input name=MORK type=hidden value="<?php echo $MORK ?>">
<input name=SID type=hidden value="<?php echo $SID ?>">
<input name=USERNAME type=hidden value="<?php echo $USERNAME ?>">
<input name=RO type=hidden value="<?php echo $RO ?>">
<p>
<CENTER><B>
<input type=submit id="butt"  value="Enter Line Item & Continue to Enter More Line Items ">
<B></CENTER>
<BR>
</form>
<BR>
<?php

} // NewLIForm

function ShowHistory ($dbconn,$MORK,$RO,$DESC,$TDATE,$ODO,$SID,$USERNAME) {
  if ($MORK=="K") {$MorkSuffix=" Kilometers";}else{$MorkSuffix=" Miles";}
  // Get this repair orders line items and display them
  // -------------------------------------------------------
  echo "<CENTER><TABLE width=95%>";
  echo "<TR><TD align=center colspan=2><p class='header8'>$DESC</TD></TR>";
  echo "<TR><TD><P class='header8'>Date: ";
  echo "$TDATE</TD>";
  echo "<TD><p class='header8'>Odometer: ";
  echo number_format($ODO);
  echo "$MorkSuffix</TD></TR>";
  echo "</TABLE>";
  $SROSelect="select REPAIR_ORDER_INDEX,DATE(LINE_DATE),OPERATION,";
  $SROSelect.="SOURCE,PART_NUMBER,COST,HOURS,NOTES,LINE_INDEX ";
  $SROSelect.="FROM vst.SERVICELINE where REPAIR_ORDER=";
  $SROSelect.="'$RO' ORDER BY REPAIR_ORDER_INDEX ASC  FOR FETCH ONLY";
  $SROResult=odbc_exec($dbconn,$SROSelect);
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
  $AlreadyPutFunction=0;
  while (odbc_fetch_row($SROResult)) {
    $numLines++;
    if ($numLines %2 == 0) { echo "<TR class='band'>"; } else { echo "<TR>"; }
    echo "<TD>";
    echo odbc_result($SROResult,1);
    //$NOTES=nl2br(odbc_result($SROResult,8));
    $NOTES=odbc_result($SROResult,8);
    if ($NOTES!="") {
      $NOTES.="ANEWLINEANEWLINE";
      if ($AlreadyPutFunction==0) {
        $AlreadyPutFunction=1;
      }
      // Ok, this is cool stuff here. I depise pop-ups, but,
      // I despise nasty tables with useless info even more.
      // So I felt a pop-up was warrented for the notes display
      // as I didn't know how to cleanly display 2000 character
      // of notes for each line-item. So I here I use javascript
      // for a small pop-up. I write with document.write, that
      // way it only writes the pop-up URL if the user has
      // javascript enabled. If no javascript, it will display the
      // stuff between the noscript tags below. That code just
      // opens a whole new window. So everyone wins.
      echo "<script>document.write(\"<a href='#' onclick=openPopup('";
      echo $NOTES;
      echo "');>[n]</a>\");";
      echo "</script>\n";


      
      echo "<noscript>";
      $TMPURL="notes.php?SID=$SID&USERNAME=$USERNAME&INFO=";
      $TMPURL.=$NOTES;
      echo "&nbsp;";
      echo "<a href='$TMPURL'>[n]</a>";
      echo "</noscript>";
    }
    echo "</TD><TD>";
    echo odbc_result($SROResult,2);
    echo "</TD><TD>";
    echo odbc_result($SROResult,3);
    echo "</TD><TD>";
    echo odbc_result($SROResult,4);
    echo "</TD><TD>";
    echo odbc_result($SROResult,5);
    echo "</TD><TD>";
    echo money_format('%n',odbc_result($SROResult,6));
    echo "</TD><TD>";
    echo odbc_result($SROResult,7);
    echo "</TD></TR>";
    $TotalROCost=$TotalROCost+odbc_result($SROResult,6);
    $TotalROHours=$TotalROHours+odbc_result($SROResult,7);
  } // while (odbc_fetch_row($SROResult)) (for each line item)
  echo "</TABLE>";
  echo "<TABLE WIDTH='95%'><TR>";
  echo "<TD class=header1>Total Cost: ";
  echo money_format('%n',$TotalROCost);
  echo "</TD>";
  echo "<TD class=header1>Total Hours: $TotalROHours</TD>";
  echo "</TR></TABLE></CENTER>";
  echo "<BR><BR>";

} // ShowHistory


if (isset($_REQUEST['SID'])) { $SID=$_REQUEST['SID']; } else { $SID=""; }
if (isset($_REQUEST['USERNAME'])) { $USERNAME=$_REQUEST['USERNAME'];} else { $USERNAME=""; }
include_once("includes.php");
$dbconn = odbc_connect("$dbname","$dbuid","$dbpasswd");
if ($dbconn==0) {
   $a = odbc_errormsg("DB2 Connect Failed. DB2 might not be running");
   echo($a);
 } else {
   authuser($dbconn,$USERNAME,$SID);
   echo "<center><p class='Header1'>";
   echo "<title>Vehicle Service Tracker - Add Line Items</title>";
   echo "<B>Vehicle Service Tracker - Add Repair Order Line Items</B></p></center>";

   if (isset($FromNewRO)) {
      titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
      NewLIForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC,$RO,"",
                    "","","","","");
    } else if (isset($EditRO)) {
      titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
           NewLIForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                       $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC,$RO,"",
                       "","","","","");
           echo "<center>";
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
           echo "<INPUT name='show' type ='submit' id='butt' value='Repair Order is Complete'>";
           echo "</form>";
           echo "</center>";

           $today=date("dMY");
           ShowHistory ($dbconn,$MORK,$RO,$DESC,$today,$ODO,$SID,$USERNAME);
 
    } else {
      // Ok the user is trying to create a brand new RO..lets check his input

      if ( $COST=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter the Cost of the Operation</font></li></ul></b>";
       } elseif (! is_numeric($COST)) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="The cost you entered [$COST] is not a number";
         $UIErrors.="</font></li></ul></b>";
      }
      if ( $HOURS=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter the Number of Hours</font></li></ul></b>";
       } elseif (! is_numeric($HOURS)) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="The number of hours you entered [$HOURS] is not a number";
         $UIErrors.="</font></li></ul></b>";
      }

      if ( $OPERATION=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter a Service Operation Description</font></li></ul></b>";
      }
      if ( $PART_NUMBER=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter a Part Number</font></li></ul></b>";
      }
      if ( $SOURCE=="" ) {
         if (!isset($UIErrors)) $UIErrors="";
         $UIErrors.="<b><ul><li><font color=#ff0000>";
         $UIErrors.="You didn't enter a Source</font></li></ul></b>";
      }

      if (isset($UIErrors)) {
        titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
        echo "<b><center><font size=+1>";
        echo "There was trouble with proccessing your information.</font>";
        echo "</b></center>";
        echo $UIErrors;
        NewLIForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC,$RO,$NOTES,
                    $OPERATION,$SOURCE,$PART_NUMBER,$COST,$HOURS);
        $today=date("dMY");
        ShowHistory ($dbconn,$MORK,$RO,$DESC,$today,$ODO,$SID,$USERNAME);
       } else {
        // OK. Insert this Line Item!
        $NOTES = preg_replace('#(http://www\.|http://)([\w-]+\.)([a-z]+\.)+([\w\?&=\-\./%]*)?#i',"<a href=\"$0\" target=\"_blank\">$0</a>",$NOTES);
        $NOTES=str_replace('"',"ADBLQTE",$NOTES);

        $NOTES=str_replace("'","AAPOSTRP",$NOTES);
        $NOTES=str_replace("\r\n","ANEWLINE",$NOTES);
        $NOTES=str_replace("/","ASLASH",$NOTES);
        $NOTES=str_replace(":","ACOLON",$NOTES);
        $NOTES=str_replace("%","APERCENT",$NOTES);
        $NOTES=str_replace(" ","ASPACEA",$NOTES);
        $NOTES=str_replace("<","ALSTHAN",$NOTES);
        $NOTES=str_replace(">","AGRTHAN",$NOTES);
        //echo "\nNOTES [$NOTES]\n";

        // Replace any single quote with double single quote for db2 insert
        $OPERATION2=str_replace("'","''",$OPERATION);
        $SOURCE2=str_replace("'","''",$SOURCE);
        $PART_NUMBER2=str_replace("'","''",$PART_NUMBER);
        $MyInsert="insert into VST.SERVICELINE ";
        $MyInsert.="(REPAIR_ORDER,LINE_DATE,OPERATION,SOURCE,PART_NUMBER,";
        $MyInsert.="COST,HOURS,NOTES) values (";
        $MyInsert.="'$RO',current_timestamp,'$OPERATION2','$SOURCE2','$PART_NUMBER2'";
        $MyInsert.=",$COST,$HOURS,'$NOTES')";
        //echo $MyInsert;
 
        //Perform the insert and redirect or die
        if (odbc_exec($dbconn,$MyInsert)) {
           //odbc_commit($dbconn);
           //odbc_close_all();
           //die ("Insert OK!");
           titleBar ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                    $GASMILE,$IMAGE,$GORD,$MORK);
           NewLIForm ($SID,$USERNAME,$VIN,$YEAR,$MAKE,$MODEL,$COLOR,
                       $GASMILE,$IMAGE,$GORD,$MORK,$ODO,$DESC,$RO,"",
                       "","","","","");
           echo "<center>";
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
           echo "<INPUT name='show' type ='submit' id='butt' value='Repair Order is Complete'>";
           echo "</form>";
           echo "</center>";

           $today=date("dMY");
           ShowHistory ($dbconn,$MORK,$RO,$DESC,$today,$ODO,$SID,$USERNAME);
         
           //header("Location: addli.php?SID=$SID&USERNAME=$USERNAME&RO=$RO");
         } else {
           odbc_commit($dbconn);
           odbc_close_all();
           die ("Something went wrong with line item insert.");
        }

      } // if ui errors or not


   } // if it's add or check add


} // if authorized to view this page
odbc_commit($dbconn);
odbc_close_all();

?>
