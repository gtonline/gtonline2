<?php
// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

if (isset($_POST['oper'])) { // Edition, Ajout, Suppression d'un entrée
    switch ($_POST['oper']) {
	case 'add':
	    $query_add = "INSERT INTO ".$mysqlprefix."_user
		(id_user, user, droits, nom, prenom, service, pass)
		VALUES ('', '".$_POST['user']."', ".$_POST['droits'].", '".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['service']."', '".md5($_POST['pass'])."')";
	    $result_add = mysql_query($query_add) or die (mysql_error());
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
	    break;
	case 'edit':
	    $password = ($_POST['pass'] == "") ? "" : ", pass = '".md5($_POST['pass'])."' ";
	    $query_edit = "UPDATE ".$mysqlprefix."_user SET
		   user = '".$_POST['user']."',
		   droits = ".$_POST['droits'].",
		   nom = '".$_POST['nom']."',
		   prenom = '".$_POST['prenom']."',
		   service = '".$_POST['service']."'
		   $password
		   WHERE id_user = ".$_POST['id'];
	    $result_edit = mysql_query($query_edit) or die (mysql_error());
	    foreach ($_POST as $key=>$value){
		$query_champ = "SELECT id_info FROM ".$mysqlprefix."_info_user WHERE colname='".$key."'";
		$result_champ = mysql_query($query_champ);
		if (mysql_affected_rows() != 0){
		    $id_info = mysql_result($result_champ, 0);
		    $query_champ = "UPDATE ".$mysqlprefix."_info_join SET content='".$value."' WHERE id_user = ".$_POST['id']." AND id_info = ".$id_info;
		    $result_champ = mysql_query($query_champ) or die ($query_champ);
		}
	    }
	    break;
	case 'del':
	    $result_del_user = mysql_query("DELETE FROM ".$mysqlprefix."_user WHERE id_user=".$_POST['id']) or die (mysql_error());
	    $result_del_inter = mysql_query("DELETE FROM ".$mysqlprefix."_inter WHERE user=".$_POST['id']) or die (mysql_error());
	    break;
	case 'verif':
	    $result_verif = mysql_query("SELECT * FROM ".$mysqlprefix."_inter WHERE user=".$_POST['id']) or die (mysql_error());
	    echo "var status_verif = ". mysql_affected_rows().";";
	    break;
	case 'verif_add':
	    $query_verif_add = "SELECT id_user FROM ".$mysqlprefix."_user WHERE user = '".$_POST['user']."'";
	    $result_verif_add = mysql_query($query_verif_add);
	    if (mysql_num_rows($result_verif_add) != 0){
		echo "var verif = 1;";
	    } else {
		$query_user = "SELECT id_user FROM ".$mysqlprefix."_user WHERE nom = '".$_POST['nom']."' AND prenom = '".$_POST['prenom']."'";
		$result_user = mysql_query($query_user);
		if (mysql_num_rows($result_user) != 0){
		    echo "var verif = 2;";
		} else {
		    echo "var verif = 0;";
		}
	    }
	    break;
	case 'verif_edit':
	    $query_verif_edit = "SELECT id_user FROM ".$mysqlprefix."_user WHERE user = '".$_POST['user']."' AND id_user != ".$_POST['id_user'];
	    $result_verif_edit = mysql_query($query_verif_edit);
	    if (mysql_num_rows($result_verif_edit) != 0){
		echo "var verif = 1;";
	    } else {
		$query_user = "SELECT id_user FROM ".$mysqlprefix."_user WHERE nom = '".$_POST['nom']."' AND prenom = '".$_POST['prenom']."' AND id_user != ".$_POST['id_user'];
		$result_user = mysql_query($query_user);
		if (mysql_num_rows($result_user) != 0){
		    echo "var verif = 2;";
		} else {
		    echo "var verif = 0;";
		}
	    }
	    break;
    }
} else { // Initialisation du contenu de la table
    $sidx = $_POST['sidx']; // get index row - i.e. user click to sort
    $sord = $_POST['sord']; // get the direction
    if(!$sidx) $sidx = 1;
    $result_count = mysql_query("SELECT COUNT(*) AS count FROM ".$mysqlprefix."_user");
    $row = mysql_fetch_array($result_count,MYSQL_ASSOC);
    $count = $row['count'];

    $query_user = "SELECT ".$mysqlprefix."_user.id_user,
	    ".$mysqlprefix."_user.user,
	    ".$mysqlprefix."_user.droits,
	    ".$mysqlprefix."_user.nom,
	    ".$mysqlprefix."_user.prenom,
	    ".$mysqlprefix."_user.service
	    FROM ".$mysqlprefix."_user
	    ORDER BY ".$mysqlprefix."_user.$sidx $sord";
    $result_user = mysql_query( $query_user ) or die('Couldn t execute query.'.mysql_error());

    if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
    header("Content-type: application/xhtml+xml;charset=utf-8"); } else {
    header("Content-type: text/xml;charset=utf-8");
    }
    $et = ">";

    echo "<?xml version='1.0' encoding='utf-8'?$et\n";
    echo "<rows>";
    echo "<page></page>";
    echo "<total></total>";
    echo "<records>".$count."</records>";
    echo "<result>";
    // be sure to put text data in CDATA
    while($row = mysql_fetch_array($result_user,MYSQL_ASSOC)) {
	$query_champ = "SELECT * FROM ".$mysqlprefix."_info_join, ".$mysqlprefix."_info_user WHERE ".$mysqlprefix."_info_join.id_user = ".$row['id_user']." AND ".$mysqlprefix."_info_user.id_info = ".$mysqlprefix."_info_join.id_info ORDER BY ".$mysqlprefix."_info_join.id_info";
	$result_champ = mysql_query($query_champ) or die ($query_champ);
	$droits = ($row['droits'] == 0) ? "Utilisateur" : "Administrateur";
	echo "<row id='". $row['id_user']."'>";
	echo "<cell><![CDATA[". $row['nom'] ."]]></cell>";
	echo "<cell><![CDATA[". $row['prenom'] ."]]></cell>";
	echo "<cell><![CDATA[". $row['service'] ."]]></cell>";
	while ($row_champ = mysql_fetch_array($result_champ)) {
	    echo "<cell><![CDATA[". $row_champ['content'] ."]]></cell>";
	}
	echo "<cell><![CDATA[". $row['user'] ."]]></cell>";
	echo "<cell><![CDATA[". $droits ."]]></cell>";
	echo "<cell></cell>";
	echo "</row>";
    }
    echo "</result>";
    echo "</rows>";
}
mysql_close($db)
?>