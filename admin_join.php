<?php
function mysql_table_exists($table){
    $query = "SELECT COUNT(*) FROM $table";
    $result = mysql_query($query);
    $num_rows = @mysql_num_rows($result);
    if($num_rows)
	return TRUE;
    else
	return FALSE;
}
session_start();
require 'conf/config.session.php';
require 'lang/'.$_SESSION['langue'].'.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');

if (mysql_table_exists($mysqlprefix.'_join') == FALSE){
    $query_create_join = "CREATE TABLE IF NOT EXISTS `".$mysqlprefix."_join` (
			`join_projet` int(255) NOT NULL,
			`join_action` int(255) NOT NULL,
			`join_order` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $result_create_join = mysql_query($query_create_join) or die (mysql_error());
}

$query_actions = "SELECT * FROM ".$mysqlprefix."_action WHERE active=1 ORDER BY date DESC";
$result_actions = mysql_query($query_actions);

$query_projets = "SELECT * FROM ".$mysqlprefix."_projet WHERE active=1 ORDER BY date DESC";
$result_projets = mysql_query($query_projets);
?>
    <style type="text/css">
    h1 { padding: .2em; margin: 0; }
    #actions { float:left; width: 330px; margin-right: 2em;}
    #catalog ul { list-style-type: none; margin: 0; padding: 15px; }
    #accordion_projet { width: 330px; float: left; }
    #accordion_projet ol { list-style-type: none; margin: 0; padding: 10px; }
    li { margin-bottom: 5px; padding: 5px; }
    .ui-state-highlight { height: 1.2em; line-height: 1.2em; }
    .ui-icon {float:right}
    .my_tooltip {width: 400px}
    #test {color: white; display: none}
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#accordion_projet').accordion({
            heightStyle: "content",
	    collapsible: true
        });
        $('#accordion_projet ol').droppable({
	    activeClass: "ui-state-default",
           hoverClass: "ui-state-hover",
           accept: ":not(.ui-sortable-helper)",
           drop: function(event,ui){
               $('#test').html('false');
	       var x = $(this).attr('id');
		$( '#accordion_projet ol[id="'+x+'"] li' ).each(function(){
                    if ( $(this).attr('id') == ui.draggable.attr('id')){
                        $('#test').html('true');
                    }
		});
                if ($('#test').html() == "false"){
		    var id_projet = $(this).attr('id');
		    var id_action = ui.draggable.attr('id');
		    $.ajax({
			type: "POST",
			url: "include/join.php",
			data: {action: "add", id_projet: id_projet, id_action: id_action},
			dataType: "script",
			success: function(){
			    if (status == 'true' || status == true){
				$( "<li></li>" ).text( ui.draggable.text() ).attr('id', ui.draggable.attr('id')).appendTo( '#accordion_projet ol[id="'+id_projet+'"]' ).addClass("ui-state-default");
			    }
		       }
		    });
                }
           }
        }).sortable({
	    placeholder: "ui-state-highlight",
            sort: function() {
                $( '#accordion_projet ol' ).removeClass( "ui-state-default" );
            },
	    update: function(event, ui) {
		var x = $(this).attr('id');
		var s = $(this).sortable('toArray');
		$.post("include/join.php", { action: "sort", id_projet: x, order: s} );
	    }
        });
        $( "#catalog li" ).draggable({
            appendTo: "body",
            helper: "clone",
	    opacity: "0.7",
	    cursor: "move"
        });
	$("#actions div").droppable({
	    hoverClass: "ui-state-hover",
	    drop: function( event, ui ) {
		var id_projet = ui.draggable.parent().attr('id');
		var id_action = $( "#accordion_projet div").find( ui.draggable ).attr('id');
		$.ajax({
		    type: "POST",
		    url: "include/join.php",
		    data: {action: "del", id_projet: id_projet, id_action: id_action},
		    dataType: "script",
		    success: function(){
			if (status == 'true' || status == true){
			    $( "#accordion_projet div").find( ui.draggable ).remove();
			}
		    }
		});
            }
	});
	$('#test').hide();
    });
    </script>
<div id="actions">
    <h1 class="ui-widget-header ui-corner-top"><span class="ui-dialog-title"><?php echo $association_actions; ?></span><span class="ui-icon ui-icon-lightbulb" tooltip="<?php echo $association_tooltip; ?>">Aide</span></h1>
    <div id="catalog" class="ui-widget-content">
            <ul id="lst_catalog">
		<?php while ($row_action = mysql_fetch_object($result_actions)) {
		    echo '<li id="'.$row_action->id_action.'" class="ui-state-default">'.$row_action->nom.'</li>';
		} ?>
            </ul>
    </div>
</div>
<div id="accordion_projet">
    <?php while($row_projet = mysql_fetch_object($result_projets)){
        echo '<h3>'.$row_projet->nom.'</h3>';
        echo '<div><ol class="list" id="'.$row_projet->id_projet.'">';
	$query_join = "SELECT ".$mysqlprefix."_join.join_projet, ".$mysqlprefix."_join.join_action, ".$mysqlprefix."_join.join_order, ".$mysqlprefix."_action.id_action, ".$mysqlprefix."_action.nom, ".$mysqlprefix."_action.active FROM ".$mysqlprefix."_join, ".$mysqlprefix."_action WHERE ".$mysqlprefix."_join.join_projet=".$row_projet->id_projet." AND ".$mysqlprefix."_join.join_action = ".$mysqlprefix."_action.id_action AND ".$mysqlprefix."_action.active = 1 ORDER BY ".$mysqlprefix."_join.join_order";
	$result_join = mysql_query($query_join) or die (mysql_error());
	while ($row_join = mysql_fetch_object($result_join)){
	    echo '<li id="'.$row_join->join_action.'" class="ui-state-default">'.$row_join->nom.'</li>';
	}
	echo '</ol></div>';
    } ?>
</div>
<div id="test">false</div>
<script>
    $( document ).tooltip({
    items: "span[tooltip]",
    tooltipClass: "my_tooltip",
    content: function() {
        return $( this ).attr( "tooltip" );
    },
    position: {
	my: "right top",
        at: "left-5 top"
    }
});
</script>