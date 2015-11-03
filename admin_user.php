<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
// connect to the database
require 'conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword)
or die('Connection Error: ' . mysql_error());

mysql_select_db($mysqlmaindb) or die('Error connecting to db.');
$query_champ = "SELECT * FROM ".$mysqlprefix."_info_user ORDER BY id_info";
$result_champ = mysql_query($query_champ);
$colNames = "";
$colModel = "";
while ($row_champ = mysql_fetch_object($result_champ)){
    $require = ($row_champ->require == 0) ? 'false' : 'true';
    $colNames .= "'$row_champ->intitule',";
    $colModel .= "{name:'$row_champ->colname',index:'$row_champ->colname', width:90, align:'center', editable:true, editrules:{required: $require}},\r\n";
}
?>
<script type="text/javascript">
    $(document).ready(function() {
	$('#dialog_message').hide();
	$("#list_champ").jqGrid({
	    url:'include/champ_grid.php',
	    editurl:'include/champ_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {},
	    colNames:['ID','<?php echo $champ_col_intitule; ?>','<?php echo $champ_col_name; ?>','<?php echo $champ_col_require; ?>'],
	    colModel:[
		{name:'id_info',index:'id_info', width:15, align:"center", editable:false, editrules:{required: false}},
		{name:'intitule',index:'intitule', width:90, align:"center", editable:true, editrules:{required: true}},
		{name:'colname',index:'colname', width:90, align:"center", editable:true, editrules:{required: true}},
		{name:'require',index:'require', width:90, align:"center", editable:true, edittype:'checkbox', formatter:'checkbox', editoptions: { value:"1:0" }}
	    ],
	    pager: '#pager_champ',
	    emptyrecords: "<?php echo $champ_emptyrecords; ?>",
	    pgbuttons: false,
	    pginput: false,
	    sortname: 'id_info',
	    viewrecords: false,
	    recordtext: "",
	    sortorder: "asc",
	    autowidth: true,
	    headertitles: true,
	    height: "auto",
	    hidegrid: false,
	    caption:"<?php echo $champ_caption; ?>"
	});
	$("#list_champ").jqGrid('navGrid','#pager_champ',
	    {edit:true,add:true,del:true,search:false}, //options
	    {closeAfterEdit:true,
	    onClose: function(){
		$( "#accordion" ).accordion( "option", "active", false );
		$( "#accordion" ).accordion( "option", "active", 1 );
	    }}, // edit options
	    {closeAfterAdd:true,
	    onClose: function(){
		$( "#accordion" ).accordion( "option", "active", false );
		$( "#accordion" ).accordion( "option", "active", 1 );
	    }}, // add options
	    {width:280,
	    onClose: function(){
		$( "#accordion" ).accordion( "option", "active", false );
		$( "#accordion" ).accordion( "option", "active", 1 );
	    }}, // del options
	    {} // search options
	);
	$("#list_user").jqGrid({
	    url:'include/users_grid.php',
	    editurl:'include/users_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {},
	    colNames:['<?php echo $users_col_nom; ?>', '<?php echo $users_col_prenom; ?>', '<?php echo $users_col_service; ?>',<?php echo $colNames; ?>'<?php echo $users_col_identifiant; ?>','<?php echo $users_col_droits; ?>','<?php echo $users_col_password; ?>'],
	    colModel:[
		{name:'nom',index:'nom', width:90, align:'center', editable:true, editrules:{required: true}},
		{name:'prenom',index:'prenom', width:90, align:'center', editable:true, editrules:{required: true}},
		{name:'service',index:'service', width:90, align:'center', editable:true, editrules:{required: true}},
		<?php echo $colModel; ?>
		{name:'user',index:'user', width:90, sortable:false, align:'center', editable:true, editrules:{required: true}},
		{name:'droits',
		    index:'droits',
		    width:200,
		    align:"center",
		    editable:true,
		    edittype:'select',
		    editoptions:{
			dataUrl:'select.php?table=user'
		    }
		},
		{name:'pass', index:'pass', sortable:false, hidden:true, editable:true, editrules:{edithidden: true}}
	    ],
	    pager: '#pager_user',
	    emptyrecords: "<?php echo $users_emptyrecords; ?>",
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
	    caption:"<?php echo $users_caption; ?>"
	});
	$("#list_user").jqGrid('navGrid','#pager_user',
	    {edit:true,add:<?php echo ($_SESSION['create_user'] == 0) ? "true" : "false"; ?>,del:true,search:false,
		delfunc:function(id) {
		    if (id == <?php echo $_SESSION['id_user']; ?>) {
			$("#dialog_user").html(" <p>Désolé, vous ne pouvez pas vous supprimer.</p>")
                        $("#dialog_user").dialog({
			    width: 350,
                            buttons: {
				"Ok": function() {
				    $( this ).html("");
                                    $( this ).dialog( "close" );
                                }
			    },
                            modal: true,
                            resizable: false
                        });
		    } else {
		    $.ajax({
                        type: "POST",
                        url: "include/users_grid.php",
                        data: {id: id, oper: "verif"},
                        dataType: "script",
                        success: function(){
                            switch (status_verif) {
                                case "0":
                                case 0:
                                    $.ajax({
					type: "POST",
					url: "include/users_grid.php",
					data: {id: id, oper: "del"},
					dataType: "script",
                                        success: function(){
                                            $("#list_user").trigger("reloadGrid");
                                         }
				     });
                                    break;
                                default:
                                    $("#dialog_user").html(" <p>Vous êtes sur le point de supprimer un utilisateur associé à "+status_verif+" interventions.<br />Si vous continuez, les interventions seront aussi supprimées.<br />Voulez continuez ?</p>")
                                    $("#dialog_user").dialog({
                                        width: 500,
                                        buttons: {
                                            "Ok": function() {
                                                $.ajax({
                                                     type: "POST",
                                                     url: "include/users_grid.php",
                                                     data: {id: id, oper: "del"},
                                                     dataType: "script",
                                                     beforeSend: function (){
                                                         $("#dialog_user").html("");
                                                         $("#dialog_user").dialog( "close" );
                                                     },
                                                     success: function(){
                                                         $("#list_user").trigger("reloadGrid");
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
		}
	    }, //options
	    {closeAfterEdit:true,
	    beforeSubmit: function(postdata,formid){
		if (postdata.pass != ''){
		    $('#dialog_message').dialog({
			resizable: false,
			modal: true,
			my: "center top",
			at: "center top",
			of: window,
			buttons: {
			    Ok: function() {
				$( this ).dialog( "close" );
				return;
			    }
			}
		    });
		}
		$.ajax({
		    type: "POST",
		    async: false,
		    url: "include/users_grid.php",
		    data: {oper: "verif_edit", id_user: postdata.list_user_id,user: postdata.user,nom: postdata.nom, prenom: postdata.prenom},
		    dataType: "script",
		    success: function(){
			$('#verif_user').html(verif);
			return;
		    }
		});
		    switch ($('#verif_user').html()){
			case "0":
			case 0:
			    return[true,""];
			    break;
			case "1":
			case 1:
			    $('#user').val("").focus();
			    return[false,"<?php echo $dialog_error_username; ?>"];
			    break;
			case "2":
			case 2:
			    $('#nom').val("").focus();
			    return[false,"<?php echo $dialog_error_surname; ?>"];
			    break;
		    }
	    }
	    }, // edit options
	    {closeAfterAdd:true,
	    beforeSubmit: function(postdata,formid){
		$.ajax({
		    type: "POST",
		    async: false,
		    url: "include/users_grid.php",
		    data: {oper: "verif_add", user: postdata.user,nom: postdata.nom, prenom: postdata.prenom},
		    dataType: "script",
		    success: function(){
			$('#verif_user').html(verif);
			return;
		    }
		});
		    switch ($('#verif_user').html()){
			case "0":
			case 0:
			    return[true,""];
			    break;
			case "1":
			case 1:
			    $('#user').val("").focus();
			    return[false,"<?php echo $dialog_error_username; ?>"];
			    break;
			case "2":
			case 2:
			    $('#nom').val("").focus();
			    return[false,"<?php echo $dialog_error_surname; ?>"];
			    break;
		    }
	    }
	    }, // add options
	    {width:280}, // del options
	    {} // search options
	);
    });
</script>
<div id="dialog_user" title="<?php echo $users_title_delete; ?>"></div>
<table id="list_user"></table>
<div id="pager_user"></div>
<p></p>
<table id="list_champ"></table>
<div id="pager_champ"></div>
<div id="verif_user" style="visibility: hidden">verif</div>
<div id="dialog_message" title="<?php echo $users_title_modif; ?>">
    <p>
        <span class="ui-icon ui-icon-info" style="float: left; margin: 0 7px 50px 0;"></span>
        <?php echo $users_intro_modif; ?>
    </p>
    <p>
        <?php echo $users_text_modif; ?>
    </p>
</div>