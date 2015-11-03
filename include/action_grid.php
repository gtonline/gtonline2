<?php
session_start();
// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

if (isset($_POST['oper'])) { // Edition, Ajout, Suppression d'un entrée

    switch ($_POST['oper']) {
	case 'add':
	    $query = "INSERT INTO ".$mysqlprefix."_action
		(`id_action`, `user`, `nom`, `desc`, `date`, `active`)
		VALUES ('', '".$_SESSION['id_user']."', '".$_POST['nom']."', '".addslashes($_POST['desc'])."', '".time()."', '".$_POST['active']."')";
	    $result = mysql_query($query) or die (mysql_error());
	    break;
	case 'edit':
	    $query = "UPDATE ".$mysqlprefix."_action SET
		   `user` = '".$_SESSION['id_user']."',
		   `nom` = '".$_POST['nom']."',
		   `desc` = '".addslashes($_POST['desc'])."',
		   `active` = '".$_POST['active']."'
		   WHERE `id_action` = ".$_POST['id'];
	    echo $query;
	   $result = mysql_query($query) or die (mysql_error());
	    break;
	case 'del':
	    $result = mysql_query("DELETE FROM ".$mysqlprefix."_action WHERE id_action=".$_POST['id']) or die (mysql_error());
	    $result = mysql_query("DELETE FROM ".$mysqlprefix."_inter WHERE action=".$_POST['id']) or die (mysql_error());
	    break;
	case 'verif':
	    $result_verif = mysql_query("SELECT * FROM ".$mysqlprefix."_inter WHERE action=".$_POST['id']) or die (mysql_error());
	    echo 'var status_verif = '. mysql_affected_rows().';';
	    break;
    }
} else { // Initialisation du contenu de la table
    $sidx = $_POST['sidx']; // get index row - i.e. user click to sort
    $sord = $_POST['sord']; // get the direction
    if(!$sidx) $sidx = 1;
    $result = mysql_query("SELECT COUNT(*) AS count FROM ".$mysqlprefix."_action");
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $count = $row['count'];

    $SQL = "SELECT *
	    FROM ".$mysqlprefix."_action
	    ORDER BY $sidx $sord";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

    if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
	header("Content-type: application/xhtml+xml;charset=utf-8"); } else {
	header("Content-type: text/xml;charset=utf-8");
    }
    $et = ">";

    $heure_cumul = 0;
    echo "<?xml version='1.0' encoding='utf-8'?$et\n";
    echo "<rows>";
    echo "<page></page>";
    echo "<total></total>";
    echo "<records>".$count."</records>";
    echo "<result>";
    // be sure to put text data in CDATA
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	echo "<row id='". $row['id_action']."'>";
	echo "<cell><![CDATA[". $row['nom'] ."]]></cell>";
	echo "<cell><![CDATA[". $row['desc'] ."]]></cell>";
	echo "<cell>". $row['active'] ."</cell>";
	echo "</row>";
    }
    echo "</result>";
    echo "</rows>";
}
mysql_close($db)
?>