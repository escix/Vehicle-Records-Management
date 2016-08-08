<?php


session_start();

//if(!$_SESSION['user_ok'])
//{header ('location: /index.php');}
//else{}



//==========================================================================
// includes.php
//
// Profile Form include
//
// Copyright (c) 2006 Kenneth J. Snyder
// Licensed under the GNU GPL. For full terms see the file LICENSE
// -------------------------------------------------------------------------
//
// Created: 22Feb2007 Snyder, Kenneth J. 73blazer@snyderworld.org
//
// Revised:
//
//==========================================================================


function RegisterForm ($FIRST,$LAST,$EMAIL,$USERNAME,$MAINURL,$FACTION,$BUTTONTXT,$REG=1,$SID="") {
if (strpbrk($LAST,"'")) {
  $LAST='"'.$LAST.'"';
 } else {
  $LAST="'".$LAST."'";
}
?>
  <form method='post' action='<?php echo $FACTION ?>'>
<?php
  if ($REG) {
    echo "<CENTER><B>";
    echo " In order to use this site, you need to provide us with some basic information";
    echo " </B></CENTER>";
   } else {
    echo "<input type='hidden' name='SID' value='$SID'>";
  }
?>
  <CENTER>
    This information will only be used for correspondence in relation to this site.
    <BR>
    This information will never be sold or given to anyone.
    You will never recieve spam from me.</CENTER><BR><BR>
   <CENTER><table>
     <tr><td>
     <B>First Name</B><td>
     <input name=FIRST type=text size=20 value='<?php echo $FIRST ?>'>
     </B>
     <tr><td><B>Last Name</B><td>
     <input name=LAST type=text size=20 value=<?php echo $LAST ?>></B>
     <tr><td><B>E-Mail Address</B><td>
     <input name=EMAIL type=text maxlength=50 size=50 value='<?php echo $EMAIL ?>'></B>
     </table></CENTER><BR>
   <BR><CENTER><B>
<?php 
  if ($REG) { 
    echo "Pick a User Name & Password<BR>"; 
    echo "You will use these to logon to this site in the future</B>";
   } else { 
    echo "Change your Password.<BR></B>(Leave password fields blank if your not updating the password)";
   }
?>
   <table>
     <tr><td><B>User Name<B><td>
<?php
if ($REG) {
   echo "<input name=USERNAME type=text size=30 value='$USERNAME'></B>";
 } else {
   echo "<input name=USERNAME type=hidden  value='$USERNAME'>$USERNAME</B>";
}
?>
     <tr><td><B>Password<B><td><input name=PASSWD maxlength=30 type=password size=30></B>
     <tr><td><B>Password Confirm<B><td><input name=PASSWD2 type=password maxlength=30 size=30></B>
   </table></CENTER>
   <br>
<?php
if ($REG) { echo "<center><B>All Fields Are Required</B></center>"; }
?>
   <p>
   <CENTER><B><input name='RegisterMe' type='submit' value='<?php echo $BUTTONTXT ?>' id='butt'></B></CENTER>
</form>
<br>
<hr noshade size=5 width=90% >
 <center>
<?php
   if ($REG) { echo "<a href='VSTInfo.php'>About This Site</a> |"; }
?>
 <a href="<?php echo $MAINURL ?>">
    Back to Main</a></center>
<hr noshade size=5 width=90% >

<?php
} // end function show register form

