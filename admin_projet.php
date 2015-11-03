<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
?>
<script type="text/javascript">
    $(document).ready(function() {
	$(".id_projet").hide();
	$("#list_projet").jqGrid({
	    url:'include/projet_grid.php',
	    editurl:'include/projet_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {},
	    colNames:['<?php echo $projet_col_nom; ?>', '<?php echo $projet_col_description; ?>', '<?php echo $projet_col_etat; ?>'],
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
	    pager: '#pager_projet',
	    emptyrecords: "<?php echo $projet_emptyrecords; ?>",
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
	    caption:"<?php echo $projet_caption; ?>"
	});
	$("#list_projet").jqGrid('navGrid','#pager_projet',
	    {edit:true,
		add:true,
		del:true,
		refresh:true,
		search:false,
		delfunc:function(id) {
		    $.ajax({
                        type: "POST",
                        url: "include/projet_grid.php",
                        data: {id: id, oper: "verif"},
                        dataType: "script",
                        success: function(){
                            switch (status_verif) {
                                case "0":
                                case 0:
                                    $.ajax({
					type: "POST",
					url: "include/projet_grid.php",
					data: {id: id, oper: "del"},
					dataType: "script",
                                        success: function(){
                                            $("#list_projet").trigger("reloadGrid");
                                         }
				     });
                                    break;
                                default:
                                    $("#dialog_projet").html(" <p>Vous êtes sur le point de supprimer un projet associé à "+status_verif+" interventions.<br />Si vous continuez, les interventions seront aussi supprimées.<br />Voulez continuez ?</p>")
                                    $("#dialog_projet").dialog({
                                        width: 500,
                                        buttons: {
                                            "Ok": function() {
                                                $.ajax({
                                                     type: "POST",
                                                     url: "include/projet_grid.php",
                                                     data: {id: id, oper: "del"},
                                                     dataType: "script",
                                                     beforeSend: function (){
                                                         $("#dialog_projet").html("");
                                                         $("#dialog_projet").dialog( "close" );
                                                     },
                                                     success: function(){
                                                         $("#list_projet").trigger("reloadGrid");
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
<div id="dialog_projet" title="Suppression"></div>
<table id="list_projet"></table>
<div id="pager_projet"></div>