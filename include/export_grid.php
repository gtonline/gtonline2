<?php
session_start();
$arr_grid = array();

if (isset($_POST['oper']) && $_POST['oper'] === 'del') { // Edition, Ajout, Suppression d'un entrée
    $link = strip_tags($_POST['link']);
    if (unlink('../export/'.$link)){
	echo 'var status = 1;';
    }
} else { // Initialisation du contenu de la table

$MyDirectory = opendir('../export') or die('Erreur');
while($Entry = @readdir($MyDirectory)) {
    $arr_Entry = explode('.', $Entry);
    if($Entry != '.' && $Entry != '..' && isset($arr_Entry[1])) {
	$arr_file = explode('_', $arr_Entry[0]);
	$arr_from_date = str_split($arr_file[1], 2);
	$arr_to_date = str_split($arr_file[2], 2);
	$arr_temp = array ($arr_file[0], $arr_from_date[0].'/'.$arr_from_date[1].'/'.$arr_from_date[2].$arr_from_date[3], $arr_to_date[0].'/'.$arr_to_date[1].'/'.$arr_to_date[2].$arr_to_date[3], $Entry);
	array_push($arr_grid, $arr_temp);
    }
}
closedir($MyDirectory);

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
// be sure to put text data in CDATA
foreach($arr_grid as $key=>$value) {
	echo "<row id='". $key."'>";
	echo "<cell><![CDATA[". $value[0] ."]]></cell>";
	echo "<cell>". $value[1] ."</cell>";
	echo "<cell>". $value[2] ."</cell>";
	echo "<cell><![CDATA[". $value[3] ."]]></cell>";
	echo "</row>";
}
echo "</result>";
echo "</rows>";
}
?>
