<?php

session_start();

if(!$_SESSION['user_ok'])
{header ('location: /index.php');}
else{}

//==========================================================================
// newvehicle.php
//
// Form to add,error check and edit New vehicle's
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 10Mar2006 Snyder, Kenneth J. 73blazer@snyderworld.org 
//
// Revised: 22Feb2007 Snyder, Kenneth J. 73blazer@snyderworld.org v2.1.0
//           - Fixed bug on form displaying echophp text in the fields
//
//==========================================================================
?>

<html>
<?php 
if ( isset($_REQUEST['EDIT']) || isset($_REQUEST['ADDOREDIT'])) {
  echo "<title>Vehicle Service Tracker - Edit a Vehicle</title>";
 } else {
  echo "<title>Vehicle Service Tracker - Add a Vehicle</title>";
}
?>
<link rel='stylesheet' type='text/css' href='vst.css'>
<body>
<?php


$SID=$_REQUEST['SID'];
$USERNAME=$_SESSION['USERNAME'];
$PASSWD=$_SESSION['PASSWD'];
include_once("includes.php");

$dbconn = mysqli_connect($my_host, $my_user, $dbpasswd, $dbname);
if (!$dbconn) {
   $a = "Mysql Connect Failed. MySQL might not be running";
   echo($a);
 } else {

   authuser($dbconn,$USERNAME,$SID);

   function check_unique_VIN ($dbconn,$VIN) {
     $Select="SELECT * FROM vehicles where VIN='$VIN'";
     $Result=$dbconn->query($Select);
     $count = $Result->num_rows;
     if ($count>=1) {
       return true;
      } else {
       return false;
     }
   } // function check_unique_VIN

   function AddVehicle($AorE,$SID,$USERNAME,$YEAR,$MAKE,
                    $MODEL,$COLOR,$VIN,$GAS,$IMAGE,$MORK,$GORD,$OLDVIN) {
  ?>
  <b><CENTER>Please Enter the following information about the Vehicle</CENTER></b>
  <form enctype="multipart/form-data" method=post action=newvehicle.php>
  <CENTER>
  <table width=100% cellpadding='2'><tr><td rowspan=8 width=30%><img src='<?php echo $_GET['$IMAGE'] ?>'>
  </td></tr>
  <tr><td align='right'><B>VIN</B></td><td>
  <input name=VIN type=text size=20 maxlength=18 value='<?php echo $_GET['VIN'] ?>'>
  </td></tr>
  <tr><td align='right'><B>Year</B></td><td>
  <input name="YEAR" type="text" maxlength=4 size="4" value='<?php echo $YEAR?>'>
  </td></tr>
  <tr><td align='right'><B>Make</B></td><td>
  <input name=MAKE type=text size=20 maxlength='20' value='<?php echo $MAKE?>'>
  </td></tr>
  <tr><td align='right'><B>Model</B></td><td>
  <input name=MODEL type=text size=30 maxlength='40' value='<?php echo $MODEL?>'>
  </td></tr>
  <tr><td align='right'><B>Color</B></td><td>
  <input name=COLOR type=text size=25 maxlength='25' value='<?php echo $COLOR?>'>
  </td></tr>
  <tr><td align='right'><B>Fuel Economy</td><td></B>
  <input name=GAS type=text size=5 value='<?php echo $GAS?>'>
  <input type="radio" name="EORM" value="M" checked> Litres/100 Kilometers</B>
  <input type="radio" name="EORM" value="E" > Miles/Gallon
  </td></tr>
  </table>
   <B>Vehicles Odometer is in </B>


  <?php
  if ($MORK=="K") {
     echo "<input type='radio' name='MORK' value='M'> Miles";
     echo "<input type='radio' name='MORK' value='K' checked> Kilometers</B><br>";
    } else {
     echo "<input type='radio' name='MORK' value='M' checked> Miles ";
     echo "<input type='radio' name='MORK' value='K'> Kilometers</B><br>";
  }
  ?>
  <B>This vehicle is </B>
  <?php
  if ($GORD=="D") {
     echo "<input type='radio' name='GORD' value='G'> Gas Powered";
     echo "<input type='radio' name='GORD' value='D' checked> Diesel Powered</B> </CENTER>";
   } else {
     echo "<input type='radio' name='GORD' value='G' checked> Gas Powered";
     echo "<input type='radio' name='GORD' value='D'> Diesel Powered</B> </CENTER>";
  }
  ?>
  <p>
  <input name=SID type=hidden value=$SID>
  <input name=USERNAME type=hidden value=$USERNAME>
  <input name=OLDVIN type=hidden value='<?php echo $_POST['$OLDVIN'] ?>'>
  <input name=AorE type=hidden value='<?php echo $_POST['$AorE'] ?>'>
  <input name=OLDIMG type=hidden value='<?php echo $_POST['$IMAGE'] ?>'>
  <CENTER>
  <B>
  <B>You can have an image of your Vehicle.<BR>
  Upload any size or type, it will be thumbnailed for you</b><BR>
  <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
  <input name='vehpic' size='48' type='file' ><br><br>



  <?php
  if ($AorE=="A") {
    echo "<input name='ADDOREDIT' id='butt' type='submit' value='Add Vehicle'>";
   } else {
    echo "<input name='ADDOREDIT' id='butt' type='submit' value='Edit Vehicle'>";
  }
  ?>
  </B>
  </form><BR><BR>
  <?php
} // function show addvehicle form

$VIN=$_POST['VIN'];
$YEAR=$_POST['YEAR'];
$MAKE=$_POST['MAKE'];
$MODEL=$_POST['MODEL'];
$COLOR=$_POST['COLOR'];
$GAS=$_POST['GAS'];
$EORM=$_POST['EORM'];
$MORK=$_POST['MORK'];
$GORD=$_POST['GORD'];
$OLDVIN=$_POST['OLDVIN'];
$AorE=$_POST['AorE'];
$OLDIMG=$_POST['OLDIMG'];


 
   echo "<CENTER><p class='Header1'>";
   if ( isset($_REQUEST['EDIT']) || isset($_REQUEST['ADDOREDIT'])) {
      echo "<B>Vehicle Service Tracker - Edit a Vehicle</B></p></CENTER>";
     } else {
      echo "<B>Vehicle Service Tracker - Add a Vehicle</B></p></CENTER>";
   }  
   if (isset($_REQUEST['ADDOREDIT'])) {
     // Ok..they submitted something..let's check it
     // ---------------------------------------------
     $VIN=strtoupper($VIN);
     if ($ADDOREDIT=="Add Vehicle") { 
        if ( $VIN=="" ) { 
          if (!isset($UIErrors)) $UIErrors="";
          $UIErrors.="<b><ul><li><font color=#ff0000>";
          $UIErrors.="You didn't enter a VIN</font></li></ul></b>";
         } elseif (check_unique_VIN($dbconn,$VIN)===true ) {
          if (!isset($UIErrors)) $UIErrors="";
          $UIErrors.="<b><ul><li><font color=#ff0000>";
          $UIErrors.="There is already a vehicle in the database ";
          $UIErrors.="with VIN [$VIN].</font></li></ul></b>";
        }
      } else {
        if ( $OLDVIN!=$VIN ) {
            if (check_unique_VIN($dbconn,$VIN)) {
               if (!isset($UIErrors)) $UIErrors="";
               //echo "old img: $OLDIMG";
               $UIErrors.="<b><ul><li><font color=#ff0000>";
               $UIErrors.="There is already a vehicle in the database ";
               $UIErrors.="with the new VIN [$VIN] you entered.</font></li></ul></b>";
               $VIN=$OLDVIN;

            }
        }
     }

     if ( $YEAR=="" ) { 
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="You didn't enter the vehicle YEAR</font></li></ul></b>";
      } elseif (strlen($YEAR)!=4) {
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="The vehicle year you entered [$YEAR] is not 4 digits</font></li></ul></b>";
     }
     if ( $MAKE=="" ) { 
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="You didn't enter the vehicle MAKE</font></li></ul></b>";
     }
     if ( $MODEL=="" ) { 
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="You didn't enter the vehicle MODEL</font></li></ul></b>";
     }
     if ( $COLOR=="" ) { 
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="You didn't enter the vehicle's COLOR</font></li></ul></b>";
     }
     if ( $GAS=="" ) { 
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="You didn't enter the vehicle's Gas Mileage</font></li></ul></b>";
     }

     //$ImgInfo="";
     if (is_uploaded_file($_FILES['vehpic']['tmp_name']))
           $ImgInfo = getimagesize($_FILES['vehpic']['tmp_name']);
     if (is_uploaded_file($_FILES['vehpic']['tmp_name']) && ($ImgInfo[2]!=1 && 
       $ImgInfo[2]!=2 && $ImgInfo[2]!=3 && $ImgInfo[2]!=6 && $ImgInfo[2]!=7 && 
       $ImgInfo[2]!=8 && $ImgInfo[2]!=15 && $ImgInfo[2]!=16)) {
       if (!isset($UIErrors)) $UIErrors="";
       $UIErrors.="<b><ul><li><font color=#ff0000>";
       $UIErrors.="The File you uploaded is not a supported image file</font></li></ul></b>";
     }

     if (isset($UIErrors)) {
        echo "<b><CENTER><font size=+1>";
        echo "There was trouble with proccessing your information.</font>";
        echo "</b></CENTER>";
        echo $UIErrors;
        if ($ADDOREDIT=="Edit Vehicle") {
          $IMAGE=$OLDIMG;
         } else {
          $IMAGE="$LFPath/car.jpg";
        }
        AddVehicle($AorE,$SID,$USERNAME,$YEAR,$MAKE,$MODEL,
                     $COLOR,$VIN,$GAS,$IMAGE,$MORK,$GORD,$OLDVIN);
      } else {
       // OK. Insert this new vehicle!
       $APicID=md5(uniqid()); 
       if (is_uploaded_file($_FILES['vehpic']['tmp_name'])) {
          // check the file is less than the maximum file size
          if($_FILES['vehpic']['size'] < 5000000) {
             // get the image info..
             //move_uploaded_file($_FILES['vehpic']['tmp_name'],$ImgLF);
             $ImgLF="$LFPath/$APicID.jpg";
 
             // This should convert whatever format it's in to max height 120
             // and make it a jpeg. Horray for ImageMagick!
 
             $command="/usr/local/bin/convert -scale 120 ".$_FILES['vehpic']['tmp_name']." ".$ImgLF;
             shell_exec($command);
             //echo "going for unlink old[$OLDIMG]";
             if ($ADDOREDIT=="Edit Vehicle" && $OLDIMG!="pics/car.jpg") {
                  //echo "DELTEING [$OLDIMG] !!!!!";
                  unlink($OLDIMG);
             }
   
             
 
           } else {
            echo "file to big!";
          } // if size is ok
        } else {
         if ($ADDOREDIT=="Add Vehicle") {
           // No Image Uploaded. Use the default
           shell_exec("cp $LFPath/car.jpg $LFPath/$APicID.jpg");
           $ImgLF="$LFPath/$APicID.jpg";
          } else {
           // It's an edit, and they didn't choose another photo...
           // so re-use the same photo
           // ------------------------------------------------------
           $ImgLF=$OLDIMG;
         }
       }// if uploaded file exists

       // Convert the gas mileage to english for storage if
       // user chose metric
       // If metric convert litre/100km to miles/gallon for
       // storage
       // -----------------------------------------------------
//       if ($EORM=="M")  $GAS=round(235.214587266016/$GAS,1);

       // Do the insert or update
       // --------------------------
       if ($ADDOREDIT=="Edit Vehicle") {
          //echo "Image [$ImgLF]";
          $VehicleInsert="update vehicles set ";
          $VehicleInsert.="YEAR=".$YEAR.",MAKE='".$MAKE."',MODEL='".$MODEL."',COLOR='";
          $VehicleInsert.=$COLOR."',IMAGE='".$ImgLF."',VIN='".$VIN."' where VIN='".$OLDVIN."'";
        } else {
          $VehicleInsert="insert into vehicles ";
          $VehicleInsert.="(VIN,YEAR,MAKE,MODEL,COLOR,C_CREATE,OWNER,ODOMORK,";
          $VehicleInsert.="GASMILE,GASORDIESEL,IMAGE) values ('";
          $VehicleInsert.=$VIN."',".$YEAR.",'".$MAKE."','".$MODEL."','";
          $VehicleInsert.=$COLOR."',current_timestamp,'".$USERNAME."','";
          $VehicleInsert.=$MORK."',".$GAS.",'".$GORD."','".$ImgLF."')";

       }
       
       if ($dbconn->query($VehicleInsert)) {
          echo "<TABLE BORDER='0' WIDTH='100%' CELLSPACING='1' CELLPADDING='2'>";
          echo "<TR CLASS='band'>";
          echo "<TD>";
          echo "<A CLASS='header2'>";
          echo "</A><BR>";
          echo "&nbsp;";
          echo "<table width=100%><TR><TD>";
          echo "<img src='$ImgLF'>";
          echo "</TD><TD>";
          echo "<A CLASS='header2'>";
          echo "&nbsp;$YEAR $MAKE $MODEL has been Sucessfully ";
          if ($ADDOREDIT=="Edit Vehicle") { echo "Changed"; } else { echo "Added"; }
          echo "!</A></TD></TR></TABLE>";
          echo "<BLOCKQUOTE><B>";
          echo "VIN: $VIN<br>";
          echo "Year: $YEAR<br>";
          echo "Make: $MAKE<br>";
          echo "Model: $MODEL<br>";
          echo "Color: $COLOR<br>";
          echo "Fuel Economy: $GAS<br>";
          echo "Odometer is in: $MORK<br>";
          echo "Vehicle is: $GORD Powered<br>";
          echo "</B></BLOCKQUOTE> ";
          echo "</TD>";
          echo "</TR>";
          echo "</TABLE>";        
        } else {
         echo "Something went wrong with vehicle insert. Try again mabey?";
       } // if vehicle insert into db succeded
     } // if there were errors in the input
    } else {
     // Just someone trying to add or edit a vehicle
     if (isset($_GET['EDIT'])) {
       $OLDVIN=$VIN;
       $AorE="E";
       if ($IMAGE=="") $IMAGE="$LFPath/car.jpg";
       AddVehicle($AorE,$SID,$USERNAME,$YEAR,$MAKE,$MODEL,$COLOR,
                   $VIN,$GAS,$IMAGE,$MORK,$GORD,$OLDVIN);
      } else {
       $AorE="A";
       $IMAGE="$LFPath/car.jpg";
       AddVehicle($AorE,$SID,$USERNAME,"","","","","","",$IMAGE,"","","");
     } // if form was called to add a new or edit existing
     
   }

echo "<CENTER><b>";
echo "<a href=main.php?SID=". $SID . "&USERNAME=" . $USERNAME . ">Back to Main</a>";
echo "</CENTER></b>";

footer($PHP_SELF,$adminemail); 


} // if authorized to view this page
?>

