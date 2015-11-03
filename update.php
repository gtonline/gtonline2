<?php
require 'conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword) or die('Connection Error: ' . mysql_error());
mysql_select_db($mysqlmaindb) or die('Error connecting to db.');
switch ($_POST['task']) {
    case 'user_info':
	if ($_POST['newpass'] != '') {
	    $query = "UPDATE ".$mysqlprefix."_user
		SET user='".$_POST['username']."',
		    pass='".md5($_POST['newpass'])."',
		    nom='".$_POST['firstname']."',
		    prenom='".$_POST['lastname']."',
		    service='".$_POST['service']."'
		WHERE id_user=".$_POST['id_user'] ;
	    if (mysql_query($query)){
		echo 'var status = true;';
	    } else {
		echo 'var status = false;';
	    }
	} else {
	    $query = "UPDATE ".$mysqlprefix."_user
		SET user='".$_POST['username']."',
		    nom='".$_POST['firstname']."',
		    prenom='".$_POST['lastname']."',
		    service='".$_POST['service']."'
		WHERE id_user=".$_POST['id_user'] ;
	    if (mysql_query($query)){
		foreach ($_POST as $key=>$value){
		    $query_champ = "SELECT id_info FROM ".$mysqlprefix."_info_user WHERE colname='".$key."'";
		    $result_champ = mysql_query($query_champ);
		    if (mysql_affected_rows() != 0){
			$id_info = mysql_result($result_champ, 0);
			$query_champ = "UPDATE ".$mysqlprefix."_info_join SET content='".$value."' WHERE id_user = ".$_POST['id_user']." AND id_info = ".$id_info;
			mysql_query($query_champ) or die ($query_champ);
		    }
		}
		echo 'var status = true;';
	    } else {
		echo 'var status = false;';
	    }
	}
	break;
}
?>