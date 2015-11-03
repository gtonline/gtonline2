<?php
session_start();
require 'conf/config.session.php';
require 'lang/'.$_SESSION['langue'].'.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword) or die('Connection Error: ' . mysql_error());
mysql_select_db($mysqlmaindb) or die('Error connecting to db.');
if (isset($_POST['action']) && $_POST['action'] == "login"){
    $query = "SELECT * FROM ".$mysqlprefix."_user WHERE user='".$_POST['loginname']."'";
    $result = mysql_query($query) or die ($query);
    $info_user = mysql_fetch_row($result);
    if (!isset($info_user[3])) {
            echo 'var status = 0;';
    } elseif (isset($info_user[1]) && $info_user[2] != md5($_POST['loginpass'])) {
            echo 'var status = 1;';
    } else {
	    $_SESSION['droits_user'] = $info_user[3];
	    $_SESSION['id_user'] = $info_user[0];
            echo 'var status = 2;';
	    echo 'var droits_user = '.$info_user[3].';';
    }
} elseif (isset($_POST['action']) && $_POST['action'] == "verif") {
    $query = "SELECT id_user FROM ".$mysqlprefix."_user WHERE user = '".$_POST['user']."'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 0){
	echo "var verif = 1;";
	echo 'var msg = "'.$dialog_error_username.'";';
    } else {
	$query = "SELECT id_user FROM ".$mysqlprefix."_user WHERE nom = '".$_POST['nom']."' AND prenom = '".$_POST['prenom']."'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0){
	    echo "var verif = 2;";
	    echo 'var msg = "'.$dialog_error_surname.'";';
	} else {
	    echo "var verif = 0;";
	}
    }
} else {
    $query = "INSERT INTO ".$mysqlprefix."_user
		(id_user, user, pass, droits, nom, prenom, service)
		VALUES ('', '".$_POST['user']."', '".md5($_POST['pass'])."', '0', '".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['service']."')";
	if (mysql_query($query)) {
	    $id_user = mysql_insert_id();
	    foreach ($_POST as $key=>$value){
		$query_champ = "SELECT id_info FROM ".$mysqlprefix."_info_user WHERE colname='".$key."'";
		$result_champ = mysql_query($query_champ);
		if (mysql_affected_rows() != 0){
		    $id_info = mysql_result($result_champ, 0);
		    $query_champ = "INSERT INTO ".$mysqlprefix."_info_join (id_user, id_info, content) VALUES (".$id_user.", ".$id_info.", '".$value."')";
		    $result_champ = mysql_query($query_champ) or die ($query_champ);
		}
	    }
	    echo 'var status = 1';
	} else {
	    echo 'var status = 0,';
	    echo 'msg = "'.$query.'";';
	}
}
mysql_close($db);
?>
