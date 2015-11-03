<?php
function add_return ($data){
    $new_data = '';
    $array_data = explode (" ", $data);
    if (count($array_data) > 2){
	foreach($array_data as $key=>$value){
	    if ($key == 2 ) $new_data .= '<br />';
	    $new_data .= $value.' ';
	}
    } else {
	$new_data = $data;
    }
    return $new_data;
}
session_start();
require '../lang/'.$_SESSION['langue'].'.php';
// connect to the database
require '../conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

$debut_array = explode ('/', $_POST['from_date']);
$fin_array = explode ('/', $_POST['to_date']);
$debutstamp = mktime (0,0,0,$debut_array[1],$debut_array[0],$debut_array[2]);
$finstamp = mktime (23,59,59,$fin_array[1],$fin_array[0],$fin_array[2]);
switch ($_POST['format']){
    case "csv":
	if ($_SESSION['calcul_inter'] == 0) {
	    $list = array (
		array('UserID', $grid_inter_username, $users_col_nom, $users_col_prenom, $users_col_service),
	    );
	    $query_champ = "SELECT * FROM ".$mysqlprefix."_info_user ORDER BY id_info";
	    $result_champ = mysql_query($query_champ);
	    while ($row_champ = mysql_fetch_object($result_champ)){
		array_push($list[0], $row_champ->intitule);
	    }
	    array_push($list[0], $export_date, $export_start_hour, $export_end_hour, $grid_inter_pause, $grid_inter_heure_total, $rapport_col_projet, $rapport_col_action, $grid_inter_comment);
	} else {
	    $list = array (
		array('UserID', $grid_inter_username, $users_col_nom, $users_col_prenom, $users_col_service),
	    );
	    $query_champ = "SELECT * FROM ".$mysqlprefix."_info_user ORDER BY id_info";
	    $result_champ = mysql_query($query_champ);
	    while ($row_champ = mysql_fetch_object($result_champ)){
		array_push($list[0], $row_champ->intitule);
	    }
	    array_push($list[0], $export_date, $grid_inter_duree, $rapport_col_projet, $rapport_col_action, $grid_inter_comment);
	}

	$mysql_query = "SELECT ".$mysqlprefix."_inter.user, ".$mysqlprefix."_inter.date, ".$mysqlprefix."_inter.heure_debut, ".$mysqlprefix."_inter.heure_fin, ".$mysqlprefix."_inter.pause, ".$mysqlprefix."_inter.heure_total, ".$mysqlprefix."_inter.projet, ".$mysqlprefix."_inter.action, ".$mysqlprefix."_inter.commentaire, ".$mysqlprefix."_user.id_user, ".$mysqlprefix."_user.user as login, ".$mysqlprefix."_user.nom, ".$mysqlprefix."_user.prenom, ".$mysqlprefix."_user.service, ".$mysqlprefix."_projet.id_projet, ".$mysqlprefix."_projet.nom as nom_projet, ".$mysqlprefix."_action.id_action, ".$mysqlprefix."_action.nom as nom_action
			FROM ".$mysqlprefix."_inter, ".$mysqlprefix."_user, ".$mysqlprefix."_projet, ".$mysqlprefix."_action
			WHERE ".$mysqlprefix."_user.id_user = ".$mysqlprefix."_inter.user AND
			".$mysqlprefix."_projet.id_projet = ".$mysqlprefix."_inter.projet AND
			".$mysqlprefix."_action.id_action = ".$mysqlprefix."_inter.action AND
			".$mysqlprefix."_inter.date >= $debutstamp AND
			".$mysqlprefix."_inter.date <= $finstamp
			ORDER BY ".$mysqlprefix."_inter.date ASC";

	$mysql_result = mysql_query ($mysql_query) or die ($mysql_query);
	$i = 1;
	while ( $listinter = mysql_fetch_object ($mysql_result)) {
	    if ($_SESSION['calcul_inter'] == 0){
		$list[$i] = array( $listinter->user,
				$listinter->login,
				$listinter->nom,
				$listinter->prenom,
				$listinter->service,);
		$query_champ = "SELECT * FROM ".$mysqlprefix."_info_join WHERE id_user = ".$listinter->id_user." ORDER BY id_info ASC";
		$result_champ = mysql_query($query_champ);
		while ($row_champ = mysql_fetch_object($result_champ)){
		    array_push($list[$i], $row_champ->content);
		}
		array_push($list[$i], date('d/m/Y', $listinter->date),
				date('H:i', $listinter->heure_debut),
				date('H:i', $listinter->heure_fin),
				date('H:i', ($listinter->pause-3600)),
				date('H:i', ($listinter->heure_total-3600)),
				$listinter->nom_projet,
				$listinter->nom_action,
				$listinter->commentaire
				);
		$i++;
	    } else {
		$list[$i] = array( $listinter->user,
				$listinter->login,
				$listinter->nom,
				$listinter->prenom,
				$listinter->service);
		$query_champ = "SELECT * FROM ".$mysqlprefix."_info_join WHERE id_user = ".$listinter->id_user." ORDER BY id_info ASC";
		$result_champ = mysql_query($query_champ);
		while ($row_champ = mysql_fetch_object($result_champ)){
		    array_push($list[$i], $row_champ->content);
		}
		array_push($list[$i], date('d/m/Y', $listinter->date),
				date('H:i', ($listinter->heure_total-3600)),
				$listinter->nom_projet,
				$listinter->nom_action,
				$listinter->commentaire
				);
		$i++;
	    }
	}
	$filename = 'export_'.$debut_array[0].$debut_array[1].$debut_array[2]."_".$fin_array[0].$fin_array[1].$fin_array[2].'.csv';
	if ($fp = fopen('../export/'.$filename, 'w')) {
	    foreach ($list as $fields) {
		fputcsv($fp, $fields, ';', '"');
	    }
	    fclose($fp);
	    echo 'var status = 1;';
	}
	break;

    case "pdf":
	$mysql_query = "SELECT ".$mysqlprefix."_inter.user, ".$mysqlprefix."_inter.date, ".$mysqlprefix."_inter.heure_debut, ".$mysqlprefix."_inter.heure_fin, ".$mysqlprefix."_inter.pause, ".$mysqlprefix."_inter.heure_total, ".$mysqlprefix."_inter.projet, ".$mysqlprefix."_inter.action, ".$mysqlprefix."_inter.commentaire, ".$mysqlprefix."_user.id_user, ".$mysqlprefix."_user.user as login, ".$mysqlprefix."_user.nom, ".$mysqlprefix."_user.prenom, ".$mysqlprefix."_user.service, ".$mysqlprefix."_projet.id_projet, ".$mysqlprefix."_projet.nom as nom_projet, ".$mysqlprefix."_action.id_action, ".$mysqlprefix."_action.nom as nom_action
			FROM ".$mysqlprefix."_inter, ".$mysqlprefix."_user, ".$mysqlprefix."_projet, ".$mysqlprefix."_action
			WHERE ".$mysqlprefix."_user.id_user = ".$mysqlprefix."_inter.user AND
			".$mysqlprefix."_projet.id_projet = ".$mysqlprefix."_inter.projet AND
			".$mysqlprefix."_action.id_action = ".$mysqlprefix."_inter.action AND
			".$mysqlprefix."_inter.date >= $debutstamp AND
			".$mysqlprefix."_inter.date <= $finstamp
			ORDER BY ".$mysqlprefix."_inter.date ASC";

	$mysql_result = mysql_query ($mysql_query) or die ($mysql_query);
	$content = '
	<style type="text/css">
	page
	{
	    font-size: 11px;
	}
	td
	{
	    border: solid 1px black;
	    margin: 0px 0px 0px 0px;
	    padding: 3px 3px 3px 3px;
	}
	.noborder td{
	    border: solid 0px black;
	}
	thead tr{
	    color: white;
	    background-color: black;
	    font-weight: bold;
	}
	thead td{
	    border: 1px solid white;
	}
	.center
	{
	    text-align:center;
	}
	</style>
	<page backtop="10mm" backbottom="10mm">
	<page_header>
	    <table style="width: 100%; border: solid 0px black;" class="noborder">
		<tr>
		    <td style="text-align: left; width: 33%"></td>
		    <td style="text-align: center; width: 34%">Exportation du '.$_POST['from_date'].' au '.$_POST['to_date'].'</td>
		    <td style="text-align: right; width: 33%">'.date('d/m/Y').'</td>
		</tr>
	    </table>
	</page_header>
	<page_footer>
	    <table style="width: 100%; border: solid 0px black;" class="noborder">
		<tr>
		    <td style="text-align: left; width: 50%">export_'.$debut_array[0].$debut_array[1].$debut_array[2].'_'.$fin_array[0].$fin_array[1].$fin_array[2].'.pdf</td>
		    <td style="text-align: right; width: 50%">page [[page_cu]]/[[page_nb]]</td>
		</tr>
	    </table>
	</page_footer>
	    <table cellspacing="0" cellpadding="2" border="1" width="100%" align="center">
		<thead>
		    <tr>
			<td>'.$grid_inter_username.'</td>
			<td>'.$users_col_nom.'</td>
			<td>'.$users_col_prenom.'</td>
			<td>'.$users_col_service.'</td>';
	    $query_champ = "SELECT * FROM ".$mysqlprefix."_info_user ORDER BY id_info";
	    $result_champ = mysql_query($query_champ);
	    while ($row_champ = mysql_fetch_object($result_champ)){
		$content .= '<td>'.add_return($row_champ->intitule).'</td>';
	    }
	    $content .= '<td>'.$export_date.'</td>';
	if ($_SESSION['calcul_inter'] == 0) {
	    $content .= '<td class="center">'.add_return($export_start_hour).'</td>
			<td class="center">'.add_return($export_end_hour).'</td>
			<td class="center">'.$grid_inter_pause.'</td>
			<td class="center">'.$grid_inter_heure_total.'</td>';
	} else {
	    $content .= '<td class="center">'.$grid_inter_duree.'</td>';
	}
	$content .= "<td>$rapport_col_projet</td>
		    <td>$rapport_col_action</td>
		    <td>$grid_inter_comment</td>
		    </tr>
		</thead>
		<tbody>";
	while ($listinter = mysql_fetch_object($mysql_result)){
	    $content .= "<tr>
			<td>$listinter->login</td>
			<td>$listinter->nom</td>
			<td>$listinter->prenom</td>
			<td>$listinter->service</td>";
	    $query_champ = "SELECT * FROM ".$mysqlprefix."_info_join WHERE id_user = ".$listinter->id_user." ORDER BY id_info ASC";
	    $result_champ = mysql_query($query_champ);
	    while ($row_champ = mysql_fetch_object($result_champ)){
		$content .= '<td>'.$row_champ->content.'</td>';
	    }
	    $content .= "<td>".date('d/m/Y', $listinter->date)."</td>";
	if ($_SESSION['calcul_inter'] == 0) {
	    $content .= '<td class="center">'.date('H:i', $listinter->heure_debut).'</td>
			<td class="center">'.date('H:i', $listinter->heure_fin).'</td>
			<td class="center">'.date('H:i', ($listinter->pause-3600)).'</td>
			<td class="center">'.date('H:i', ($listinter->heure_total-3600)).'</td>';
	} else {
	    $content .= '<td class="center">'.date('H:i', ($listinter->heure_total-3600)).'</td>';
	}
	    $content .= "<td>$listinter->nom_projet</td>
			<td>$listinter->nom_action</td>
			<td>".nl2br($listinter->commentaire)."</td></tr>";
	}
	    $content .= "</tbody>
	    </table></page>";
	//echo $content;

	require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');

	// get the HTML
	//ob_start();
	//include(dirname('__FILE__').'/export_pdf.php');
	//$content = ob_get_clean();
	try
	{
	    // init HTML2PDF
	    $html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', array(10, 10, 10, 10));
	    $html2pdf->pdf->SetAuthor('GT Online');
	    $html2pdf->pdf->SetTitle('Exportation du '.$_POST['from_date'].' au '.$_POST['to_date']);
	    //$html2pdf->setModeDebug();

	    // display the full page
	    $html2pdf->pdf->SetDisplayMode('default');

	    // convert
	    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));

	    // send the PDF
	    $filename = 'export_'.$debut_array[0].$debut_array[1].$debut_array[2]."_".$fin_array[0].$fin_array[1].$fin_array[2].'.pdf';
	    $html2pdf->Output('../export/'.$filename, 'F');
	    echo 'var status = 1;';
	}
	catch(HTML2PDF_exception $e) {
	    echo $e;
	    exit;
	}
	break;
}
?>
