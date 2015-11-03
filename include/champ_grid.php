<?php
// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

if (isset($_POST['oper'])) { // Edition, Ajout, Suppression d'un entrée
    switch ($_POST['oper']) {
	case 'add':
	    $query = "INSERT INTO ".$mysqlprefix."_info_user
		(`id_info`, `intitule`, `colname`, `require`)
		VALUES ('', '".$_POST['intitule']."', '".$_POST['colname']."', '".$_POST['require']."')";
	    $result = mysql_query($query) or die (mysql_error().":".$query);
	    $id_info = mysql_insert_id();
	    $query_user = "SELECT id_user FROM ".$mysqlprefix."_user ORDER BY id_user ASC";
	    $result_user = mysql_query($query_user);
	    while ($row = mysql_fetch_array($result_user)){
		$query_join = "INSERT INTO ".$mysqlprefix."_info_join
		(id_user, id_info, content)
		VALUES (".$row['id_user'].", ".$id_info.", '')";
		$result_join = mysql_query($query_join) or die ($query_join);
	    }
	    break;
	case 'edit':
	    $query = "UPDATE ".$mysqlprefix."_info_user SET
		   intitule = '".$_POST['intitule']."',
		   colname = '".$_POST['colname']."',
		   `require` = '".$_POST['require']."'
		   WHERE id_info = ".$_POST['id'];
	    $result = mysql_query($query) or die (mysql_error());
	    break;
	case 'del':
	    $result = mysql_query("DELETE FROM ".$mysqlprefix."_info_user WHERE id_info=".$_POST['id']) or die (mysql_error());
	    $result = mysql_query("DELETE FROM ".$mysqlprefix."_info_join WHERE id_info=".$_POST['id']) or die (mysql_error());
	    break;
    }
} else { // Initialisation du contenu de la table
    $sidx = $_POST['sidx']; // get index row - i.e. user click to sort
    $sord = $_POST['sord']; // get the direction
    if(!$sidx) $sidx = 1;
    $result = mysql_query("SELECT COUNT(*) AS count FROM ".$mysqlprefix."_info_user");
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $count = $row['count'];

    $SQL = "SELECT *
	    FROM ".$mysqlprefix."_info_user
	    ORDER BY $sidx $sord";
    $result = mysql_query( $SQL ) or die('Couldn t execute query.'.mysql_error());

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
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	    echo "<row id='". $row['id_info']."'>";
	    echo "<cell>". $row['id_info'] ."</cell>";
	    echo "<cell><![CDATA[". $row['intitule'] ."]]></cell>";
	    echo "<cell><![CDATA[". $row['colname'] ."]]></cell>";
	    echo "<cell>". $row['require'] ."</cell>";
	    echo "</row>";
    }
    echo "</result>";
    echo "</rows>";
}
mysql_close($db)
?>