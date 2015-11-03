<?php
session_start();
require '../lang/'.$_SESSION['langue'].'.php';
// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

$debut_array = explode ('/', $_POST['from_date']);
$fin_array = explode ('/', $_POST['to_date']);
$debutstamp = mktime (0,0,0,$debut_array[1],$debut_array[0],$debut_array[2]);
$finstamp = mktime (23,59,59,$fin_array[1],$fin_array[0],$fin_array[2]);
$query = "DELETE FROM ".$mysqlprefix."_inter WHERE date >= ".$debutstamp." AND date <= ".$finstamp;
if (mysql_query($query)) {
    echo "var status = 1";
} else {
    echo mysql_error();
}
?>
