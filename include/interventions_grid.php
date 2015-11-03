<?php
session_start();
$date = (isset($_POST['date'])) ? $_POST['date'] : $_GET['date'];
$min_date_stamp = strtotime($date);
$max_date_stamp = strtotime($date . ' 23:59');
// connect to the database
require '../lang/'.$_SESSION['langue'].'.php';
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

if (isset($_POST['oper'])) { // Edition, Ajout, Suppression d'un entrée
 $heure_debut = strtotime('1970-1-1 '.$_POST['heure_debut']);
 if ($_SESSION['calcul_inter'] == 0) {
    $heure_fin = strtotime('1970-1-1 '.$_POST['heure_fin']);
    $pause = strtotime('1970-1-1 '.$_POST['pause']) + 3600;
    $heure_total = ($heure_fin - $heure_debut) - $pause;
 } else {
     $heure_fin = 0;
     $pause = 0;
     $heure_total = $heure_debut + 3600;
 }
 switch ($_POST['oper']) {
     case 'add':
	 $query = "INSERT INTO ".$mysqlprefix."_inter
	     (id_inter, user, date, heure_debut, heure_fin, pause, heure_total, projet, action, commentaire)
	     VALUES ('', ".$_SESSION['id_user'].", ".strtotime($date).", ".$heure_debut.", ".$heure_fin.", ".$pause.", ".$heure_total.", ".$_POST['projet'].", ".$_POST['action'].", '".addslashes($_POST['comment'])."')";
	 $result = mysql_query($query) or die (mysql_error());
	 break;
     case 'edit':
	 $query = "UPDATE ".$mysqlprefix."_inter SET
		heure_debut = ".$heure_debut.",
		heure_fin = ".$heure_fin.",
		pause = ".$pause.",
		heure_total = ".$heure_total.",
		projet = ".$_POST['projet'].",
		action = ".$_POST['action'].",
		commentaire = '".addslashes($_POST['comment'])."'
		WHERE id_inter = ".$_POST['id'];
	$result = mysql_query($query) or die (mysql_error());
	 break;
     case 'del':
	 $query = "DELETE FROM ".$mysqlprefix."_inter WHERE id_inter=".$_POST['id'];
	 $result = mysql_query($query);
	 break;
 }
} else { // Initialisation du contenu de la table
    $sidx = $_POST['sidx']; // get index row - i.e. user click to sort
    $sord = $_POST['sord']; // get the direction
    if(!$sidx) $sidx =1;
    $where_admin = ($_SESSION['droits_user'] == 1) ? '' : 'user='.$_SESSION['id_user'].' AND ';
    $result_num = mysql_query("SELECT COUNT(*) AS count FROM ".$mysqlprefix."_inter WHERE ".$where_admin."date>=$min_date_stamp AND date<=$max_date_stamp");
    $row = mysql_fetch_array($result_num,MYSQL_ASSOC);
    $count = $row['count'];
    $and_admin = ($_SESSION['droits_user'] == 1) ? '' : 'AND '.$mysqlprefix.'_inter.user='.$_SESSION['id_user'];
    $SQL = "SELECT ".$mysqlprefix."_inter.id_inter as id_inter,
	    ".$mysqlprefix."_inter.user as user,
	    ".$mysqlprefix."_inter.date as date,
	    ".$mysqlprefix."_inter.heure_debut as heure_debut,
	    ".$mysqlprefix."_inter.heure_fin as heure_fin,
	    ".$mysqlprefix."_inter.pause as pause,
	    ".$mysqlprefix."_inter.heure_total as heure_total,
	    ".$mysqlprefix."_inter.projet as projet,
	    ".$mysqlprefix."_inter.action as action,
	    ".$mysqlprefix."_inter.commentaire as commentaire,
	    ".$mysqlprefix."_projet.id_projet,
	    ".$mysqlprefix."_projet.nom as nom_projet,
	    ".$mysqlprefix."_action.id_action,
	    ".$mysqlprefix."_action.nom as nom_action,
	    ".$mysqlprefix."_user.id_user,
	    ".$mysqlprefix."_user.user as username
	    FROM ".$mysqlprefix."_inter, ".$mysqlprefix."_projet, ".$mysqlprefix."_action, ".$mysqlprefix."_user
	    WHERE ".$mysqlprefix."_projet.id_projet = ".$mysqlprefix."_inter.projet
	    AND ".$mysqlprefix."_action.id_action = ".$mysqlprefix."_inter.action
	    AND ".$mysqlprefix."_inter.user = ".$mysqlprefix."_user.id_user
	    $and_admin
	    AND ".$mysqlprefix."_inter.date>=$min_date_stamp
	    AND ".$mysqlprefix."_inter.date<=$max_date_stamp
	    ORDER BY $sidx $sord";
    $result_inter = mysql_query( $SQL ) or die('Couldn\'t execute query.'.mysql_error());

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
    while($row = mysql_fetch_object($result_inter)) {
	    $heure_cumul = $heure_cumul + ($row->heure_total);
	    echo "<row id='". $row->id_inter."'>";
	    echo ($_SESSION['droits_user'] == 1) ? "<cell><![CDATA[". $row->username ."]]></cell>" : "";
	    if ($_SESSION['calcul_inter'] == 0) {
		echo "<cell>". date('H:i', $row->heure_debut) ."</cell>";
		echo "<cell>". date('H:i', $row->heure_fin) ."</cell>";
		echo "<cell>". date('H:i', ($row->pause - 3600)) ."</cell>";
	    } elseif ($_SESSION['calcul_inter'] == 1) {
		echo "<cell>". date('H:i', $row->heure_total - 3600) ."</cell>";
	    }
	    echo "<cell><![CDATA[". $row->nom_projet ."]]></cell>";
	    echo "<cell><![CDATA[". $row->nom_action ."]]></cell>";
	    if ($_SESSION['calcul_inter'] == 0) {
	    echo "<cell>". date('H:i', ($row->heure_total - 3600)) ."</cell>";
	    } else {
		echo "<cell>". date('H:i', ($heure_cumul - 3600)) ."</cell>";
	    }
	    echo "<cell><![CDATA[". $row->commentaire ."]]></cell>";
	    echo "</row>";
    }
    echo "</result>";
    echo "<userdata name='heure_total'>".date('H:i', ($heure_cumul - 3600))."</userdata>";
    echo "<userdata name='action'>".$grid_inter_cumul."</userdata>";
    echo "</rows>";
}
mysql_close($db)
?>