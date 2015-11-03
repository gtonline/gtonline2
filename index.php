<?php
if (file_exists('conf/config.session.php') === false) {
    header('Location: install/index.php');
}

require 'conf/config.session.php';
require 'include/pdo.php';

session_start();
$xml = new SimpleXMLElement('parameters.xml', null, true);
foreach ($xml->xpath('//parameters/parameter') as $ua) {
    $name = (string) $ua->name;
    $value = (string) $ua->value;
    $_SESSION[$name] = $value;
}

require 'lang/'.$_SESSION['langue'].'.php'; // Chargement du fichier de langue

$workingDay = unserialize($_SESSION['horaires']); // Desactivation des journées non travaillées
$daysToDisable = '';
foreach ($workingDay as $key => $value) {
    if ($value == '00:00') {
	if ($key != 6){
	    $daysToDisable .= ($key + 1).',';
	} else {
	   $daysToDisable .= '0,';
	}
    }
}
$daysToDisable = rtrim($daysToDisable, ',');
$query_champ = "SELECT * FROM ".$mysqlprefix."_info_user ORDER BY ".$mysqlprefix."_info_user.id_info";
$result_champ = $pdo->query($query_champ);
$result_champ->setFetchMode(PDO::FETCH_OBJ);
$disable = ($_SESSION['modif_user'] == 0) ? ' disabled="disabled"' : '';
$ajax_data = "";
$input_champ = "";
$require_verif = "";
$array_verif = "";
while( $row_champ = $result_champ->fetch() ) {
    $ajax_data .= ", $row_champ->colname: $('#$row_champ->colname').val()";
    $input_champ .= '<label for="'.$row_champ->colname.'">'.$row_champ->intitule.'</label><input type="text" size="30" name="'.$row_champ->colname.'" id="'.$row_champ->colname.'" />';
    if ($row_champ->require == 1){
        $require_verif .= '} else if ($("#'.$row_champ->colname.'").val() == "" || $("#'.$row_champ->colname.'").val() == "'.$row_champ->intitule.'") {
                        updateTips("'.$champ_error_msg.$row_champ->intitule.'.");
                        $("#'.$row_champ->colname.'").addClass("ui-state-error").focus();';
        $array_verif .= '.add($("#'.$row_champ->colname.'"))';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset=utf-8>
<title>GT Online</title>

<link type="text/css" href="css/<?php echo $_SESSION['skin']; ?>/jquery-ui-1.10.2.custom.min.css" rel="Stylesheet" />
<link type="text/css" href="css/gtonline.css" rel="Stylesheet" />
<link type="text/css" href="css/ui.jqgrid.css" rel="Stylesheet" />
<link type="text/css" href="css/jquery.ui.timepicker.css" rel="Stylesheet" />

<script charset="utf-8" src="js/jquery-1.9.1.min.js"></script>
<script charset="utf-8" src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script charset="utf-8" src="lang/jquery-ui-datepicker/jquery.ui.datepicker-<?php echo $_SESSION['langue']; ?>.js"></script>
<script charset="utf-8" src="lang/jquery-jqgrid/grid.locale-<?php echo $_SESSION['langue']; ?>.js"></script>
<script charset="utf-8" src="lang/jquery-ui-timepicker/jquery.ui.timepicker-<?php echo $_SESSION['langue']; ?>.min.js"></script>
<script charset="utf-8" src="js/jquery.jqGrid.min.js"></script>
<script charset="utf-8" src="js/jquery.ui.timepicker.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var max=0;
    $("label").width("auto");
    $("label").each(function(){ if ($(this).width() > max) max = $(this).width(); });
    $("label").width( max + 10 );
    var daysToDisable = [<?php echo $daysToDisable; ?>];

    function disableSpecificWeekDays(date) {
	var day = date.getDay();
        for (i = 0; i < daysToDisable.length; i++) {
	    if ($.inArray(day, daysToDisable) != -1) {
		return [false];
            }
        }
        return [true];
    }

    function updateTips( t ) {
	$( ".validateTips" ).text( t ).show().addClass( "ui-state-highlight" );
	setTimeout(function() {
	    $( ".validateTips" ).removeClass( "ui-state-highlight", 1500 );
	    $( ".validateTips" ).fadeOut('slow');
	}, 500 );
    }

    $("#datepicker").datepicker({
        firstDay: <?php echo $_SESSION['first_day']; ?>,
        showWeek: <?php echo $_SESSION['nbr_week']; ?>,
	dateFormat: "yy-mm-dd",
	yearRange: "-01:+01",
	onSelect: function(dateText, inst) {
            $("#tabs").on( "tabsbeforeload", function( event, ui ) {
                ui.ajaxSettings.url += ( /\?/.test( ui.ajaxSettings.url ) ? "&" : "?" ) + 'date=' + dateText;
            });
	    $("#tabs").tabs( "option", "active", 0 );
	    $("#tabs").tabs( "load", 0 );
	},
        onChangeMonthYear: function(year,month,inst){
            $("#tabs").on( "tabsbeforeload", function( event, ui ) {
                ui.ajaxSettings.url += ( /\?/.test( ui.ajaxSettings.url ) ? "&" : "?" ) + 'year=' + year + '&month=' + month;
            });
            $("#tabs").tabs("load", 2);
        },
        beforeShowDay: disableSpecificWeekDays
    });

    $( "#tabs" ).tabs({
	spinner: "Chargement en cours...",
	activate: function(event, ui) {
                if (ui.newTab.index() == droits_user + 3){
		    $("#datepicker").fadeOut("fast");
		    $("#tabs").fadeOut("fast");
		    $('#tabs').tabs("option", "disabled", []);
		    $.ajax({
			type: "POST",
			url: "include/killsession.php",
			data: {},
			dataType: "script",
			success: function(){}
		    });
		    window.location.reload();
		}
	},
	beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
            });
        } 
    });

    $("#datepicker").hide();
    $("#tabs").hide();

    var loginname = $( "#loginname" ),
    loginpass = $( "#loginpass" ),
    allFieldslogin = $( [] ).add( loginname ).add( loginpass ),
    user = $("#user"),
    pass = $("#pass"),
    verifpass = $("#verifpass"),
    nom = $("#nom"),
    prenom = $("#prenom"),
    service = $("#service"),
    allFieldsnewuser = $( [] ).add( user ).add( pass ).add(verifpass).add(nom).add(prenom).add(service)<?php echo $array_verif; ?>;

   $("#dialog_newuser").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: "center",
        buttons: {
	    "<?php echo $dialog_btn_create; ?>": function() {
		allFieldsnewuser.removeClass( "ui-state-error" );
                if (user.val() == "" || user.val() == "<?php echo $dialog_newuser_username; ?>") {
                    updateTips("<?php echo $error_username; ?>");
                    user.addClass("ui-state-error").focus();
                } else if (nom.val() == "" || nom.val() == "<?php echo $dialog_newuser_firstname; ?>") {
                    updateTips("<?php echo $error_nom; ?>");
                    nom.addClass("ui-state-error").focus();
                } else if (prenom.val() == "" || prenom.val() == "<?php echo $dialog_newuser_lastname; ?>") {
                    updateTips("<?php echo $error_prenom; ?>");
                    prenom.addClass("ui-state-error").focus();
               } else if (service.val() == "") {
                    updateTips("<?php echo $error_service; ?>");
                    service.addClass("ui-state-error").focus();
                } else if (pass.val() == "") {
                    updateTips("<?php echo $error_pass; ?>");
                    verifpass.val("");
                    pass.addClass("ui-state-error").focus();
                } else if (verifpass.val() == "") {
                    updateTips("<?php echo $error_passcheck; ?>");
                    verifpass.addClass("ui-state-error").focus();
                } else if (pass.val() != verifpass.val()){
                    updateTips("<?php echo $error_passverif; ?>");
		    pass.addClass("ui-state-error").val("").focus();
                    verifpass.addClass("ui-state-error").val("");
                <?php echo $require_verif; ?>} else {
		    $.ajax({
			type: "POST",
			url: "login.php",
			data: {action: "verif", user: user.val(),nom: nom.val(), prenom: prenom.val()},
			dataType: "script"})
			.done(function(data){
			    if (verif == 0 || verif == "0"){
				$.ajax({
				    type: "POST",
				    url: "login.php",
				    data: {user: user.val(), pass: pass.val(), nom: nom.val(), prenom: prenom.val(), service: service.val()<?php echo $ajax_data; ?>},
				    dataType: "script",
				    success: function(){
					switch (status) {
					    case "0":
					    case 0:
						updateTips(msg);
						break;
					    case "1":
					    case 1:
						$("#dialog_newuser").dialog("close");
						break;
					    default:
						updateTips("<?php echo $lng_internal_error; ?>");
						break;
					}
				     }
				 });
			    } else if (verif == "1" || verif == 1) {
				updateTips(msg);
				user.val("").addClass("ui-state-error").focus();
			    } else if (verif == "2" || verif == 2) {
				updateTips(msg);
				nom.val("").addClass("ui-state-error").focus();
			    }
			});
                }
            },
            "<?php echo $dialog_btn_cancel; ?>": function() {
		$( this ).dialog( "close" );
            }
	},
	close: function() {
            allFieldsnewuser.val( "" ).removeClass( "ui-state-error" );
            $( ".validateTips" ).val("");
            $("#dialog_login").dialog("open");
	}
    });

    $( "#dialog_login" ).dialog({
	autoOpen: true,
        modal: true,
        resizable: false,
        closeOnEscape: false,
        position: "center",
	open: function(event, ui) {  $(".ui-dialog-titlebar-close", $(this).parent()).hide(); },
        buttons: {
	    <?php if ($_SESSION['create_user'] == 1) { ?>
            "<?php echo $dialog_btn_inscription; ?>": function(){
                allFieldslogin.val("").removeClass( "ui-state-error" );
                $( ".validateTips" ).text("");
                $("#dialog_login").dialog("close");
                $("#dialog_newuser").dialog("open");
            },
	    <?php } ?>
            "<?php echo $dialog_btn_connexion; ?>": function() {
		allFieldslogin.removeClass( "ui-state-error" );
                $( ".validateTips" ).text("");
		$.ajax({
                    type: "POST",
                    url: "login.php",
                    data: {loginname: loginname.val(), loginpass: loginpass.val(), action: "login"},
                    dataType: "script"})
                    .done(function(data){
                        switch (status) {
                            case "0":
                            case 0:
                                loginname.addClass("ui-state-error").val("").focus();
                                updateTips("<?php echo $error_nouser; ?>");
                                break;

                            case "1":
                            case 1:
                                loginpass.addClass('ui-state-error').val("").focus();
                                updateTips("<?php echo $error_passdb; ?>");
                                break;

                            case "2":
                            case 2:
				if (droits_user == 0) {
				    $('#tabs_admin').remove();
				}
				loginname.val("").focus();
				loginpass.val("");
				$('#dialog_login').dialog('close');
				$('#datepicker').fadeIn('fast');
				$('#tabs').fadeIn('fast');
				$('#tabs').tabs( "option", "selected", 0 );
				$('#tabs').tabs( "load", 0 );
                                break;

                            default:
                                updateTips("<?php echo $lng_internal_error; ?>");
                                break;
                         }
                     });
            }
        }
    });
    $(document).on("keyup", "#dialog_login", function(e){
	if (e.keyCode == 13) {
	  $(':button:last').click();
	}
    });
     $(document).on('keyup', "#dialog_newuser", function(e){
	if (e.keyCode == 13) {
	  $(':button:last').click();
	}
     });
     $('#lost_password').click(function(){
	alert("<?php echo $dialog_lost_password; ?>");
     });
});
</script>
</head>
<body>
<div id="general">
    <div id="dialog_login" title="<?php echo $dialog_login; ?>">
	<form>
	    <label for="loginname"><?php echo $dialog_login_username; ?></label><input type="text" name="loginname" id="loginname" />
	    <label for="loginpass"><?php echo $dialog_login_userpass; ?></label><input type="password" name="loginpass" id="loginpass" />
	    <br /><a href="#" id="lost_password" style="font-size: 10px"><?php echo $link_lost_password; ?></a>
	</form>
	<p class="validateTips"></p>
    </div>
    <div id="dialog_newuser" title="<?php echo $dialog_newuser_titre; ?>">
	<form>
	    <label for="user"><?php echo $dialog_newuser_username; ?></label><input type="text" size="30" name="user" id="user" pattern="[A-Za-z]" required="required" />
	    <label for="pass"><?php echo $dialog_newuser_userpass; ?></label><input type="password" size="30" name="pass" id="pass" />
	    <label for="verifpass"><?php echo $dialog_newuser_verifpass; ?></label><input type="password" size="30" name="verifpass" id="verifpass" />
	    <p></p>
	    <label for="nom"><?php echo $dialog_newuser_firstname ?></label><input type="text" size="30" name="nom" id="nom" />
	    <label for="prenom"><?php echo $dialog_newuser_lastname; ?></label><input type="text" size="30" name="prenom" id="prenom" />
	    <label for="service"><?php echo $dialog_newuser_service; ?></label><input type="text" size="30" name="service" id="service" />
	    <?php echo $input_champ; ?>
	</form>
	<p class="validateTips"></p>
    </div>
    <div id="datepicker"></div>
    <div id="tabs">
        <ul>
            <li><a href="interventions.php"><?php echo $tabs_interventions; ?></a></li>
            <li><a href="informations.php"><?php echo $tabs_informations; ?></a></li>
            <li><a href="rapport_admin.php"><?php echo $tabs_rapport; ?></a></li>
            <li id="tabs_admin"><a href="administration.php"><?php echo $tabs_administration; ?></a></li>
            <li><a href="#tabs-4"><?php echo $tabs_deconnexion; ?></a></li>
	</ul>
	<div id="tabs-4">
            <p><?php echo $tabs_deconnexion_msg; ?></p>
	</div>
    </div>
    </div>
</body>
</html>