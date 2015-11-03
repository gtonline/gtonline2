<?php
session_start();
$sidx = $_POST['sidx']; // get index row - i.e. user click to sort
$sord = $_POST['sord']; // get the direction
if(!$sidx) $sidx =1;

// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

$array_horaires = unserialize ($_SESSION['horaires']);
$query_projet = "SELECT * FROM ".$mysqlprefix."_projet WHERE active=1 ORDER BY nom";
$result_projet = mysql_query($query_projet) or die (mysql_error());
$i = 0;
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
header("Content-type: application/xhtml+xml;charset=utf-8"); } else {
header("Content-type: text/xml;charset=utf-8");
}
$et = ">";
echo "<?xml version='1.0' encoding='utf-8'?$et\n";
echo "<rows>";
echo "<page></page>";
echo "<total></total>";
echo "<records></records>";
echo "<result>";
$htf = 0;
$mtf = 0;
while ($row_projet = mysql_fetch_object($result_projet)){
    $query_action = "SELECT * FROM ".$mysqlprefix."_action WHERE active=1 ORDER BY nom";
    $result_action = mysql_query($query_action) or die (mysql_error());
    while ($row_action = mysql_fetch_object($result_action)){
	$ht = 0;
	$mt = 0;
	$query_inter = "SELECT * FROM ".$mysqlprefix."_inter WHERE
	    projet='".$row_projet->id_projet."'
	    AND action='".$row_action->id_action."'
	    AND date>='".$_POST['min_date']."'
	    AND date<='".$_POST['max_date']."'
	    AND user='".$_SESSION['id_user']."'";
	$result_inter = mysql_query($query_inter) or die (mysql_error());
	$nbr_inter = mysql_num_rows($result_inter);
	if ($nbr_inter != 0){
	    $total_projet = 0;
	    echo "<row id='". $i."'>";
	    echo "<cell><![CDATA[". $row_projet->nom ."]]></cell>";
	    echo "<cell><![CDATA[". $row_action->nom ."]]></cell>";
	    while($row_inter = mysql_fetch_object($result_inter)){
		if ($_SESSION['calcul'] == 0) {
		    $dayofweek = (date("w", $row_inter->date)) - 1;
		    $hourofday = explode(":", $array_horaires[$dayofweek]);
		    $stampofday = mktime($hourofday[0], $hourofday[1], "00", "1", "1", "1970") + 3600;
		    $inter_day = round($row_inter->heure_total / $stampofday, 3);
		    $total_projet += $inter_day;
		} else {
		    $harray = explode(":", date("H:i", ($row_inter->heure_total - 3600)));
		    $ht += $harray[0];
		    $mt += $harray[1];
		    if ($mt >= 60){
			$ht++;
			$mt -= 60;
		    }
		    $mt = ($mt < 10) ? "0".$mt : $mt;
		    $total_projet = $ht.':'.$mt;
		}
	    }
	    echo "<cell>". $total_projet ."</cell>";
	    if ($_SESSION['calcul'] == 0){
		$total_rapport += $total_projet;
	    } else {
		$htf += $ht;
		$mtf += $mt;
		if ($mtf >= 60) {
		    $htf++;
		    $mtf -= 60;
		}
	    }
	    $i++;
	    echo "</row>";
	}
    }
}
if ($_SESSION['calcul'] == 1){
    $htf = ($htf < 10) ? "0".$htf : $htf;
    $mtf = ($mtf < 10) ? "0".$mtf : $mtf;
    $total_rapport = $htf.":".$mtf;
}
echo "</result>";
echo "<userdata name='action'>Total</userdata>";
echo "<userdata name='duree'>".$total_rapport."</userdata>";
echo "</rows>";
?>