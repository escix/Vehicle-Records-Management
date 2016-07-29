<?php
//==========================================================================
// VSTInfo.php
//
// Show information on what the Vehicle Service Tracker is all about
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

 include_once("includes.php");
 // Get the Version
 // -----------------
 $VSTVERSION=shell_exec("./getvstversion");
?>
<html>
 <head>
  <title><?php echo $orgname ?>'s
       Vehicle Service Tracker Version <?php echo $VSTVERSION ?></title>
 </head>
 <link rel='stylesheet' type='text/css' href='vst.css'>

    <BODY>
      <H1>
    <CENTER>
     <P CLASS='Header1'>Vehicle Service Tracker - What's This?</P>
    </CENTER>
     </H1>



<b>What is this?</b>
<br>
The Vehicle Service Tracker is an in depth tracking system to track
all the service that has been performed on your vehicles. You'll be able
to keep track of repairs with line-by-line precision, including part numbers,
source, cost and hours for each line item. You will get for each vehicle it's cost
per mile to operate.  This application
is aimed at anyone who wishes to keep detailed service records of thier
vehicles. That would include the do-it-yourselfer, or the average Joe who
has thier vehicle serviced by others. You can have as many vehicles as
you want, so feel free to keep track of lawn mowers and any other 
piece of machinery that requires service. You will be able to enter 
line-by-line repair orders, and see them listed by vehicle via an inquiry. 
Each vehicle inquiry has each repair session broken down, with prices, 
and hours if you entered them, and a vehicle total at the bottom. 
Simple yet powerful.
<p>
<b>What this is not!</b>
<br>
I've seen the other service/garage sites that claim they will keep track of
your vehicles service history, and they don't offer detailed tracking. They
only want information about you and what vehicles you own , so they can
direct you and your personal information to a service location (hopefully)near
you.&nbsp; That's thier whole point, you service a car at one of thier
partners, they get a kickback. They also sell all your information so
you'll be spammed via email and snail mail.&nbsp; 
They don't care if you want to enter detailed
information, like exactly what parts were replaced, or the cost and hours
associated with any service. In fact, they make it hard for you to enter
any detailed information yourself (god forbid you serviced the vehicle
yourself or took it to a non-participating garage) because they want the
information to come from a service garage that you've had your car serviced
at. There's not line-by-line entry, and the service descriptions usuall MUST be
on thier list, or you can't enter it. (You won't find 'Installed Doubler' or
'Greased shackles' on their forced lists either!) Oh, and by the way, if you don't
live in a metropolis in the United States, your screwed, because none of
thier "service partners" are located outside of a large US city.
<p>
<b>Technical Notes:</b>
<br>
<ul>
<li>
You should be able to use this site with almost any browser. If you're
having trouble in a particular browser, please let me know and I'll correct
it. Try it from your web-enabled cell-phone!
</li>

<li>
This application does not use Cookies or rely javascript! Pure HTML here baby.
(Ok, the one little snippet of javascript is to display notes in a popup window,
but I detect if you have javascript enabled first, and if not then the notes
will just display in a new window. But a popup is much cleaner for that display,
thus I did use javascript if you have it turned on. But it's definatly not nessesary!)
All error checking is done using intelligent PHP backend. 
So feel free to enter that new service record when your truck breaks
down 500mi from home and all you have is a web-enabled cell-phone.
</li

<li>
The data is backed up regularly, so you won't lose anything.</li>

<li>
You'll be notified of any known site outages in advance by e-mail</li>

<li>
Your email and all the information you keep here will be keep in the 
strictess of confidence. It will never be sold to anyone or viewed by anyone but
you and perhaps me on occasion me when I'm performing maintance</li>

<li>
If for some godforsaken reason I decide to take the site down for good,
you'll be provided with all the information that you were keeping here.</li>

<li>
I'm open to suggestions for improving the Tracker. If there's something
you wish to see, please e-mail me. I'd be more than happy to incorporate
any decent suggestions. You'll find my e-mail address at the bottom of
any page.</li>

<li>
The site is running on an IBM RS/6000 44P270 w/ 4 350Mhz Processors 
(those are RISC Power3 processors, so don't confuse them with Intel architecture), w/ 5GB RAM 
running AIX v5.3.0.3, with DB2 v8.2FP8 as the RDBMS and 
<a href='http://www.apache.org'>Apache</a> 2.0.53 for the webserver 
using <a href='http://www.php.net'>php</a> 5.0.4 as the scripting language</li>
<li> This application is open source. I published it under the GNU General 
Public License. <a href="http://www.snyderworld.org/VehicleServiceTracker">The project page can be viewed here where you can</a>
download the code.
</ul>


<b>Future Enhancments:</b>
<ul>
<li>
Ability to edit line items on last repair order
</li>
<li>
Automatic e-mail notification of a regular service. The site will calculate your
approximate mileage from the mileage you entered on previous repair orders,
and automatically send an email when that calculated mileage reaches a
point where a service needs to be performed. Only you will pick and choose
the auto notifications.</li>

<li> Total cost of ownership. So purchase cost, insurance, registration fees,
     fuel costs, etc will all be taken into account </li>
<li> Vehicle in service or not in service. So you can either not show those
     vehicles anymore on the main summary, or at least they will fall to the
     bottom of the vehicle summary</li>
</ul>

<b>Version History:</b>
<ul>
<li> v1.00: 23Mar2003 Initial Version</li>
<li>v1.10: 28Apr2003 Added Vehicle Totals</li>
<li>v1.20: 24Mar2004 Added Cost/Mile in totals, Gas Or Diesel, and Miles or Kilometer options on Vehicles </li>
<li>v2.00: 20Feb2006 Major Update
  <ul>
   <li>Reformatted everything</li>
   <li>Ported from Aolserver to apache</li>
   <li>Ported from Oracle to DB2</li>
   <li>Ported from tcl to php</li>
   <li>Added Edit Vehicle</li>
   <li>Added Stylesheet</li>
   <li>Added summarys to main screen w/vehicle stats too</li>
   <li>Added picture to vehicle identity. Picture shows on main summary</li>
  </ul>
 </li> 
<li>v2.01: 15Mar2006 Feature Enhancment
  <ul>
   <li>
   Added email the registrar her/his information when he registers
   </li>
  </ul>
</li>
<li>v2.02: 19Mar2006 Feature Enhancment
  <ul>
   <li>Added NOTES field for line items.<br>
    For displaying of notes, it detects javascript and if enabled 
    it displays the notes in a popup when user clicks, otherwise
    show the notes in a new window when user clicks</li>
  </ul>
</li>
<li>v2.03: 20Mar2006 Bug Fix/Feature Enhancment
  <ul>
   <li>Added error checking on add/edit repair order form input.<br>
    Very robust if I might add. Try, try to break it. I dare you! 
    Go ahead, Seb, Paul, Dom, try, I bet you a beer you can't 
    cause that form to error. 
    </li>
  </ul>
</li>
<li>v2.0.4 24Mar2006 Feature Enhancments
 <ul>
   <li>Added LASTLOGIN to clients table. Show user his last login date/time</li>
 </ul>
 <ul>
   <li>Added Average miles per year calculation</li>
 </ul>
</li>
<li>v2.1.0 23Feb2007 Feature Enhancments/Bug fix
  <ul>
   <li>Fixed Edit/New Vehicle page bug where it was displaying echophp</li>
  </ul>
  <ul>
   <li>Hash passwords into database</li>
  </ul>
  <ul>
   <li>Added 'Update Profile' link on main page. Allows to change password, email address..etc</li>
  </ul>
  <ul>
   <li>Added Forgot Password link on login page</li>
  </ul>
</li>
</ul>
<hr noshade size=5 width=100% >
<center><a href="index.php">Back To Main</a> | <a href="register.php">Register Now!</a> | <a href="http://www.snyderworld.org/VehicleServiceTracker">VST Project Page</a></center>
<hr noshade size=5 width=100% >
<?php footer($PHP_SELF,$adminemail); ?>
</body>
</html>

