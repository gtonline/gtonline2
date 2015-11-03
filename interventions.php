<?php
$date = (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
session_start();
$day_start = unserialize ($_SESSION['day_start']);
$day_end = unserialize ($_SESSION['day_end']);
$day_horaire = unserialize ($_SESSION['horaires']);
$nb_day = date('N', strtotime($date)) - 1; // Determine le jour de la semaine au format numérique
@$array_start = explode(":", $day_start[$nb_day]);
@$array_end = explode(":", $day_end[$nb_day]);
@$array_horaire = explode(":", $day_horaire[$nb_day]);
require 'lang/'.$_SESSION['langue'].'.php';
require 'conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');
$query_projets = "SELECT * FROM ".$mysqlprefix."_projet WHERE active=1 ORDER BY nom ASC";
$result_projets = mysql_query($query_projets) or die(mysql_error());
$select_projets = '';
while ($row_projet = mysql_fetch_object($result_projets)){
    $query_join = "SELECT * FROM ".$mysqlprefix."_join WHERE join_projet = ".$row_projet->id_projet;
    $result_join = mysql_query($query_join) or die(mysql_error());
    if (mysql_num_rows($result_join) != 0){
        $select_projets .= $row_projet->id_projet.":".$row_projet->nom.";";
    }
}
?>
<script type="text/javascript">
    function OnHourShowCallback(hour) {
        if ((hour > <?php echo $array_end[0]; ?>) || (hour < <?php echo $array_start[0]; ?>)) {
        return false; // not valid
        }
        return true; // valid
    }
    function OnMinuteShowCallback(hour, minute) {
        if ((hour == <?php echo $array_end[0]; ?>) && (minute > <?php echo $array_end[1]; ?>)) { return false; } // not valid
        if ((hour == <?php echo $array_start[0]; ?>) && (minute < <?php echo $array_start[1]; ?>)) { return false; }   // not valid
        return true;  // valid
    }
    function OnDurationHourShowCallback(hour) {
        if ((hour > <?php echo $array_horaire[0]; ?>)) {
        return false; // not valid
        }
        return true; // valid
    }
    function OnDurationMinuteShowCallback(hour, minute) {
        if ((hour == <?php echo $array_horaire[0]; ?>) && (minute > <?php echo $array_horaire[1]; ?>)) { return false; } // not valid
        return true;  // valid
    }
    $(document).ready(function() {
	$("#list_inter").jqGrid({
	    url:'include/interventions_grid.php',
	    editurl:'include/interventions_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {date: '<?php echo $date; ?>'},
	    <?php
		$username = ($_SESSION['droits_user'] == 1) ? "'$grid_inter_username', " : '';
		$durée = ($_SESSION['calcul_inter'] == 1) ? "'$grid_inter_duree', " : "'$grid_inter_heure_debut', '$grid_inter_heure_fin', '$grid_inter_pause', ";
	    ?>
	    colNames:[<?php echo $username; ?><?php echo $durée; ?>'<?php echo $grid_inter_projet; ?>','<?php echo $grid_inter_action; ?>','<?php echo $grid_inter_heure_total; ?>','<?php echo $grid_inter_comment; ?>'],
	    colModel:[
		<?php if ($_SESSION['droits_user'] == 1) {?>
		{name:'username',
		    index:'username',
		    width:"90",
		    align:"left",
		    editable:false
		},
		<?php }
		if ($_SESSION['calcul_inter'] == 0) {
		?>
		{name:'heure_debut',
		    index:'heure_debut',
		    width:"90",
		    align:"left",
		    editable:true,
		    editrules:{required: true},
		    editoptions:{
			dataInit: function(element) {
			    <?php if ($_SESSION['affich_horo'] == 1) { ?>
			    $(element).timepicker({
					showPeriod: false,
					showPeriodLabels: <?php echo $_SESSION['horoampm']; ?>,
					showNowButton: <?php echo $_SESSION['horonow']; ?>,
					onHourShow: OnHourShowCallback,
					onMinuteShow: OnMinuteShowCallback,
					showOn: 'focus',
					myPosition: 'left center',
					atPosition: 'right+20 top'
				    });
				<?php } ?>
                          }
			}
		},
		{name:'heure_fin',
		index:'heure_fin',
		width:"90",
		align:"left",
		editable:true,
		editrules:{required: true},
		    editoptions:{
			dataInit: function(element) {
			  <?php if ($_SESSION['affich_horo'] == 1) { ?>$(element).timepicker({showPeriod: false,showPeriodLabels: <?php echo $_SESSION['horoampm']; ?>,showNowButton: <?php echo $_SESSION['horonow']; ?>,onHourShow: OnHourShowCallback,onMinuteShow: OnMinuteShowCallback,showOn: 'focus',myPosition: 'left center',atPosition: 'right+20 top'});<?php } ?>
			}
		    }
		},
		{name:'pause',
		index:'pause',
		width:"90",
		align:"left",
		editable:true,
		editrules:{required: true},
		    editoptions:{
			defaultValue: '00:00',
			dataInit: function(element) {
			  <?php if ($_SESSION['affich_horo'] == 1) { ?>$(element).timepicker({showPeriod: false,showPeriodLabels: <?php echo $_SESSION['horoampm']; ?>, myPosition:'left bottom',atPosition:'right+20 bottom'});<?php } ?>
			}
		    }},
		<?php } elseif ($_SESSION['calcul_inter'] == 1) { ?>
		{name:'heure_debut',
		    index:'heure_debut',
		    width:"90",
		    align:"left",
		    editable:true,
		    editrules:{required: true},
		    editoptions:{
			defaultValue: '01:00',
			dataInit: function(element) {
			    <?php if ($_SESSION['affich_horo'] == 1) { ?>
				    $(element).timepicker({
					showPeriod: false,
					showPeriodLabels: <?php echo $_SESSION['horoampm']; ?>,
					showNowButton: <?php echo $_SESSION['horonow']; ?>,
					onHourShow: OnDurationHourShowCallback,
					onMinuteShow: OnDurationMinuteShowCallback,
					showOn: 'focus',
					myPosition: 'left center',
					atPosition: 'right+20 top'
				    });
				<?php } ?>
                          }
			}
		},
		<?php } ?>
		{name:'projet',
		    index:'projet',
		    width:"150",
		    align:"left",
		    editable:true,
		    edittype:'select',
		    editoptions:{
			value: "<?php echo rtrim($select_projets, ';'); ?>",
			dataEvents: [
			    {  type: 'change',
			       fn: function(e) {
				   var id_inter = $('#id_g').val();
				   if (id_inter == '_empty'){
				       $("#action").parent().load('select.php?table=action', {'id_projet': this.value});
				   } else {
				       $("#action").parent().load('select.php?table=action', {'id_projet': this.value, 'id_inter': id_inter});
				   }
			       }
			    }
			 ]
		    }
		},
		{name:'action',
		    index:'action',
		    width:"150",
		    align:"left",
		    editable:true,
		    edittype:'select',
		    editoptions:{
			value: "1:Initialisation"
		    }
		},
		{name:'heure_total',index:'heure_total', width:"90", sortable:false, align:"left", editable:false},
		{name:'comment',index:'comment', editoptions: {rows:"4",cols:"30"}, sortable:false, editable:true, edittype: 'textarea'}
	    ],
	    pager: '#pager_inter',
	    emptyrecords: "<?php echo $grid_inter_emptyrecords; ?>",
	    pgbuttons: false,
	    pginput: false,
	    sortname: '<?php echo ($_SESSION['droits_user'] == 1) ? "username, " : ""; ?>heure_debut',
	    viewrecords: true,
	    recordtext: "",
	    sortorder: "asc",
	    autowidth: true,
	    headertitles: false,
	    height: "auto",
	    hidegrid: false,
	    footerrow: true,
	    userDataOnFooter: true,
	    caption:"<?php echo $grid_inter_caption.date('d/m/Y', strtotime($date)); ?>",
	    xmlReader: {root:"result",row:"row",page:"rows>page",total:"rows>total",records:"rows>records",id : "[id]",userdata : "userdata"}
	});
	$("#list_inter").jqGrid('navGrid','#pager_inter',
	    {edit:true,add:true,del:true,search:false}, //options
	    {afterShowForm : function (formid) {
		//$('#projet').focus();
		//$('.ui-timepicker').hide();
		},
		beforeShowForm: function(formid){
		var id_inter = $('#id_g').val();
		if (id_inter == '_empty'){
		    $("#action").parent().load('select.php?table=action', {'id_projet': $('#projet option:selected').val()});
		} else {
		    $("#action").parent().load('select.php?table=action', {'id_projet': $('#projet option:selected').val(), 'id_inter': $("#id_g").val()});
		}
	    }, editData: {date: '<?php echo $date; ?>'} ,closeAfterEdit:true}, // edit options
	    {afterShowForm : function (formid) {
		var d = new Date();
		if (d.getHours() > 9){
		    var hr = d.getHours();
		} else {
		    var hr = '0'+d.getHours();
		}
		if (d.getMinutes() > 9){
		    var mn = d.getMinutes();
		} else {
		    var mn = '0'+d.getMinutes();
		}
	    <?php if ($_SESSION['calcul_inter'] == 0) { ?>
		$('#heure_debut').val(hr+':'+mn);
		<?php } ?>
	    },
	    beforeShowForm: function(formid){
		$("#action").parent().load('select.php?table=action', {'id_projet':$('#projet option:selected').val()});
	    }, editData: {date: '<?php echo $date; ?>'} ,closeAfterAdd:true}, // add options
	    {width:280}, // del options
	    {} // search options
	);
    });
</script>
<table id="list_inter"></table>
<div id="pager_inter"></div>
<div id="test"></div>
