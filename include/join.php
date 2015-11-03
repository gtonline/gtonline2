<?php
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

switch ($_POST['action']) {
    case "add":
	$query = "INSERT INTO ".$mysqlprefix."_join SET
		join_projet=".$_POST['id_projet'].",
		join_action=".$_POST['id_action'];
	if (mysql_query($query)) echo 'var status = "true";';
	break;
    case "del":
	$query = "DELETE FROM ".$mysqlprefix."_join
	    WHERE join_projet=".$_POST['id_projet']."
	    AND join_action=".$_POST['id_action'];
	if (mysql_query($query)) echo 'var status = "true";';
    case "sort":
	if (isset($_POST['order'])){
	    foreach($_POST['order'] as $key=>$value){
		$query_order = "UPDATE ".$mysqlprefix."_join SET join_order=".$key." WHERE join_action=".$value." AND join_projet=".$_POST['id_projet'];
		mysql_query($query_order) or die(mysql_error());
	    }
	}
    default:
	break;
}
?>
