<?php
// connect to the database
require 'conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

switch ($_GET['table']) {
    case 'action':
	if (isset($_POST['id_inter'])) {
	    $query_action = "SELECT action FROM ".$mysqlprefix."_inter WHERE id_inter=".$_POST['id_inter'];
	    $result_action = mysql_query($query_action);
	    $id_action = mysql_result($result_action, 0);
	} else {
	    $id_action = 0;
	}
	$query = "SELECT ".$mysqlprefix."_join.join_projet, ".$mysqlprefix."_join.join_action, ".$mysqlprefix."_join.join_order, ".$mysqlprefix."_action.id_action, ".$mysqlprefix."_action.nom, ".$mysqlprefix."_action.active FROM ".$mysqlprefix."_join, ".$mysqlprefix."_action WHERE ".$mysqlprefix."_join.join_projet = ".$_POST['id_projet']." AND ".$mysqlprefix."_join.join_action = ".$mysqlprefix."_action.id_action AND ".$mysqlprefix."_action.active = 1 ORDER BY ".$mysqlprefix."_join.join_order";
	$result=  mysql_query($query);
	echo '&nbsp;<select role="select" class="FormElement" id="action" name="action">';
	while ($row = mysql_fetch_row($result)) {
	    if ($row[3] == $id_action){
		echo '<option value="'.$row[3].'" selected="selected">'.$row[4].'</option>';
	    } else {
		echo '<option value="'.$row[3].'">'.$row[4].'</option>';
	    }
	}
	echo '</select>';
	break;
    case 'user':
	session_start();
	require 'lang/'.$_SESSION['langue'].'.php';
	echo '<select>';
	echo '<option value="0">'.$user_type_user.'</option>';
	echo '<option value="1">'.$user_type_admin.'</option>';
	echo '</select>';
	break;
}
?>
