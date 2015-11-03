<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
require 'conf/config.session.php';
$db = mysql_connect($mysqlserveur, $mysqllogin, $mysqlpassword) or die('Connection Error: ' . mysql_error());
mysql_select_db($mysqlmaindb) or die('Error connecting to db.');
$query = "SELECT * FROM ".$mysqlprefix."_user WHERE id_user=".$_SESSION['id_user'];
$result = mysql_query($query) or die ($query);
$info_user = mysql_fetch_row($result);
$query_champ = "SELECT ".$mysqlprefix."_info_join.id_user, ".$mysqlprefix."_info_join.id_info, ".$mysqlprefix."_info_join.content, ".$mysqlprefix."_info_user.id_info, ".$mysqlprefix."_info_user.intitule, ".$mysqlprefix."_info_user.colname, ".$mysqlprefix."_info_user.require FROM ".$mysqlprefix."_info_join, ".$mysqlprefix."_info_user WHERE ".$mysqlprefix."_info_join.id_user = ".$_SESSION['id_user']." AND ".$mysqlprefix."_info_user.id_info = ".$mysqlprefix."_info_join.id_info ORDER BY ".$mysqlprefix."_info_join.id_info";
$result_champ = mysql_query($query_champ);
$disable = ($_SESSION['modif_user'] == 0) ? ' disabled="disabled"' : '';
$ajax_data = "";
$input_champ = "";
$require_champ = "";
$removeclass = "";
while ($row_champ = mysql_fetch_object($result_champ)){
    $ajax_data .= ", $row_champ->colname: $('#$row_champ->colname').val()";
    $input_champ .= '<label for="'.$row_champ->colname.'">'.$row_champ->intitule.'</label><input type="text" id="'.$row_champ->colname.'" size="30" value="'.$row_champ->content.'"'.$disable.' /><br />';
    if ($row_champ->require == 1){
        $require_champ = '} else if ($("#'.$row_champ->colname.'").val() == "") {
		message.html("'.$champ_error_msg.$row_champ->intitule.'");
		boite.show().delay(2000).fadeOut("slow");
		$("#'.$row_champ->colname.'").val("").addClass("ui-state-error").focus();'."\r\n";
        $removeclass = '$("#'.$row_champ->colname.'").removeClass("ui-state-error");'."\r\n";
    }
}
?>
<script>
    $(function() {
	var max=0;
	$("label").width("auto");
	$("label").each(function(){
	    if ($(this).width() > max)
		max = $(this).width();
	});
	$("label").width(max + 10);

	var message = $("#msg_error"),
	    type = $("#info_type"),
	    logo = $("#info_logo"),
	    boite = $("#info_widget"),
            newpass = $("#newpass"),
            verifpass = $("#verifpass"),
            username = $("#username"),
            firstname = $("#firstname"),
            lastname = $("#lastname"),
            service = $("#service");

	boite.hide();
	$( "button" ).button();
	$( "button" ).click(function() {
            type.removeClass("ui-state-highlight").addClass("ui-state-error");
            logo.removeClass("ui-icon-info").addClass("ui-icon-alert");
	    newpass.removeClass("ui-state-error");
	    verifpass.removeClass("ui-state-error");
            username.removeClass("ui-state-error");
            firstname.removeClass("ui-state-error");
            lastname.removeClass("ui-state-error");
            service.removeClass("ui-state-error");
            <?php echo $removeclass; ?>
            if (username.val() == ""){
                message.html("<?php echo $error_username; ?>");
                boite.show().delay(2000).fadeOut('slow');
		username.val("").addClass("ui-state-error").focus();
            } else if (firstname.val() == "") {
                message.html("<?php echo $error_nom; ?>");
                boite.show().delay(2000).fadeOut('slow');
		firstname.val("").addClass("ui-state-error").focus();
            } else if (lastname.val() == "") {
                message.html("<?php echo $error_prenom; ?>");
                boite.show().delay(2000).fadeOut('slow');
		lastname.val("").addClass("ui-state-error").focus();
            } else if (service.val() == "") {
                message.html("<?php echo $error_service; ?>");
                boite.show().delay(2000).fadeOut('slow');
		service.val("").addClass("ui-state-error").focus();
            } else if (newpass.val() != "" && newpass.val() != verifpass.val()) {
		message.html("<?php echo $info_message_wrong; ?>");
		boite.show().delay(2000).fadeOut('slow');
		newpass.val("").addClass("ui-state-error").focus();
		verifpass.val("").addClass("ui-state-error");
	    <?php echo $require_champ; ?>
            } else {
		$.ajax({
		    type: "POST",
		    url: "update.php",
		    data: {task: "user_info", id_user: "<?php echo $_SESSION['id_user']; ?>", username: $("#username").val(), newpass: $("#newpass").val(), firstname: $("#firstname").val(), lastname: $("#lastname").val(), service: $("#service").val()<?php echo $ajax_data; ?>},
		    dataType: "script",
		    success: function(){
			if (status) {
                            type.removeClass("ui-state-error").addClass("ui-state-highlight");
                            logo.removeClass("ui-icon-alert").addClass("ui-icon-info");
			    message.html("<?php echo $info_message_good; ?>");
			    boite.show().delay(2000).fadeOut('slow');
			}
		    }
		});
	    }
	});
    });
</script>
<form name="form_info" id="form_info" method="post">
    <label for="username"><?php echo $info_username; ?></label><input type="text" id="username" size="30" value="<?php echo $info_user[1]; ?>" placeholder="<?php echo $info_username_holder; ?>"<?php echo $disable; ?> /><br />
    <label for="newpass"><?php echo $info_newpass; ?>*</label><input type="password" id="newpass" size="30" placeholder="<?php echo $info_newpass_holder; ?>" /><br />
    <label for="verifpass"><?php echo $info_verifpass; ?>*</label><input type="password" id="verifpass"size="30" placeholder="<?php echo $info_verifpass_holder; ?>" /><br /><br />
    <label for="firstname"><?php echo $info_firstname; ?></label><input type="text" id="firstname" size="30" value="<?php echo $info_user[4]; ?>" placeholder="<?php echo $info_firstname_holder; ?>"<?php echo $disable; ?> /><br />
    <label for="lastname"><?php echo $info_lastname; ?></label><input type="text" id="lastname" size="30" placeholder="<?php echo $info_lastname_holder; ?>" value="<?php echo $info_user[5]; ?>"<?php echo $disable; ?> /><br />
    <label for="service"><?php echo $info_service; ?></label><input type="text" id="service" size="30" placeholder="<?php echo $info_service_holder; ?>" value="<?php echo $info_user[6]; ?>"<?php echo $disable; ?> /><br />
    <?php echo $input_champ; ?>
    <p class="comment">* <?php echo $info_comment; ?></p>
</form>
<button id="update"><?php echo $info_button; ?></button>
<div class="ui-widget" id="info_widget" style="float:right;width:350px; margin-top: 7px">
    <div id="info_type" class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
	<span id="info_logo" class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
	<div id="msg_error"><strong>Alert:</strong> Sample ui-state-error style.</div>
    </div>
</div>
