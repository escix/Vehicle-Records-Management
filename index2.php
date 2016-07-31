<html>
 <head>
  <title>
   Vehicle Service Tracker :: VST Car repair tracker at <?php echo $orgname ?>
  </title>
 </head>
 <link rel='stylesheet' type='text/css' href='vst.css'>
 <BODY>
   <H1>
  <CENTER>
   <P CLASS='Header1'>Vehicle Service Tracker</P>
  </CENTER>
  </H1>

<?php
session_start();
//session_regenerate_id();

if (isset($_POST['login'])) {
if( isset($_POST['USERNAME']) && isset($_POST['PASSWD']) )
{
     $_SESSION['USERNAME'] = $_POST['USERNAME'];
     $_SESSION['PASSWD'] = $_POST['PASSWD'];

echo $_SESSION['USERNAME'];
echo $_SESSION['PASSWD'];

}
else{
echo "not good";}
}
?> 


   <form method=post action=checkuser2.php>
   <CENTER><table>
   <tr><td><B>UserName</B><td><input name=USERNAME type=text size=30>
   </tr></td>
   <tr><td><B>
   Password</B><td><input name=PASSWD type=password size=30 >


   </tr></td>
   </table></CENTER>
   <p>
   <CENTER><B><input type=submit name="login" value="Login" id='butt'></B></CENTER>
   </form>
 
 </body>
</html>
