<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
?>
<script type="text/javascript">
    $(document).ready(function() {
	$("#list_action").jqGrid({
	    url:'include/action_grid.php',
	    editurl:'include/action_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {},
	    colNames:['<?php echo $action_col_nom; ?>', '<?php echo $action_col_description; ?>', '<?php echo $action_col_etat; ?>'],
	    colModel:[
		{name:'nom',index:'nom', width:90, align:"center", editable:true, editrules:{required: true}},
		{name:'desc',
		index:'desc',
		width:90,
		align:"center",
		editable:true,
		edittype: 'textarea',
		editoptions: {rows:"4",cols:"30"}
		},
		{name:'active',
		index:'active',
		width:90,
		align:"center",
		editable:true,
		edittype:'checkbox',
                formatter:'checkbox',
		editoptions: { value:"1:0" }
		}
	    ],
            rowNum: 100,
	    pager: '#pager_action',
	    emptyrecords: "<?php echo $action_emptyrecords; ?>",
	    pgbuttons: false,
	    pginput: false,
	    sortname: 'nom',
	    viewrecords: false,
	    recordtext: "",
	    sortorder: "asc",
	    autowidth: true,
	    headertitles: true,
	    height: "auto",
	    hidegrid: false,
	    caption:"<?php echo $action_caption; ?>"
	});
	$("#list_action").jqGrid('navGrid','#pager_action',
	    {edit:true,add:true,del:true,search:false,
		delfunc:function(id) {
		    $.ajax({
                        type: "POST",
                        url: "include/action_grid.php",
                        data: {id: id, oper: "verif"},
                        dataType: "script",
                        success: function(){
                            switch (status_verif) {
                                case "0":
                                case 0:
                                    $.ajax({
					type: "POST",
					url: "include/action_grid.php",
					data: {id: id, oper: "del"},
					dataType: "script",
                                        success: function(){
                                            $("#list_action").trigger("reloadGrid");
                                         }
				     });
                                    break;
                                default:
                                    $("#dialog_action").html(" <p>Vous êtes sur le point de supprimer une action associée à "+status_verif+" interventions.<br />Si vous continuez, les interventions seront aussi supprimées.<br />Voulez continuez ?</p>")
                                    $("#dialog_action").dialog({
                                        width: 500,
                                        buttons: {
                                            "Ok": function() {
                                                $.ajax({
                                                     type: "POST",
                                                     url: "include/action_grid.php",
                                                     data: {id: id, oper: "del"},
                                                     dataType: "script",
                                                     beforeSend: function (){
                                                         $("#dialog_action").html("");
                                                         $("#dialog_action").dialog( "close" );
                                                     },
                                                     success: function(){
                                                         $("#list_action").trigger("reloadGrid");
                                                      }
                                                 });
                                            },
                                            Annuler: function() {
                                                $( this ).html("");
                                                $( this ).dialog( "close" );
                                            }
                                        },
                                        modal: true,
                                        resizable: false
                                     });
                                    break;
                            }
                         }
                     });
		}
	    }, //options
	    {closeAfterEdit:true}, // edit options
	    {closeAfterAdd:true}, // add options
	    {width:280}, // del options
	    {} // search options
	);
    });
</script>
<div id="dialog_action" title="Suppression"></div>
<table id="list_action"></table>
<div id="pager_action"></div>