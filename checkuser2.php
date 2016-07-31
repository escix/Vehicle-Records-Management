<?php
session_start();
session_regenerate_id();

$_SESSION['USERNAME']=$_POST['USERNAME'];
$_SESSION['PASSWD']=$_POST['PASSWD'];

echo "something dfddddd wrong";
echo $_SESSION['USERNAME'];
echo $_SESSION['PASSWD'];


?>
