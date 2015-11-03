<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
$MyDirectory = opendir('./lang') or die('Erreur');
$indice = 0;
?>
<script>
$(document).ready(function() {

    var max=0;
    $("label").width("auto");
    $("label").each(function(){
	if ($(this).width() > max)
	    max = $(this).width();
	});
    $("label").width(max + 10);

    $('#form_parameters select').change(function() {
	var id_focusout = $(this).attr('id');
	var value_focusout = $(this).val();
	$.ajax({
	    type: "POST",
	    url: "include/update_config.php",
            data: {param: id_focusout, value: value_focusout},
            dataType: "script",
            success: function(){
		switch (status) {
		    case "0":
                    case 0:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
			$("#valid_"+id_focusout).show().delay("1000").fadeOut("2000");
                        break;
                    case "1":
                    case 1:
			$(".validateTips").removeClass('ui-icon-close').addClass('ui-icon-check');
			$("#valid_"+id_focusout).show().delay("1000").fadeOut("2000");
			switch (param){
			    case "first_day":
				$("#datepicker").datepicker("option", "firstDay", value);
				$("#datepicker").datepicker("refresh");
				break;
			}
                        break;
                    default:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
                        $("#valid_"+id_focusout).show().delay("1000").fadeOut("2000");
                        break;
                }
           }
        });
    });
    $('#form_parameters input').change(function() {
	var id_focusout = $(this).attr('name');
	var value_focusout = $(this).val();
	$.ajax({
	    type: "POST",
	    url: "include/update_config.php",
            data: {param: id_focusout, value: value_focusout},
            dataType: "script",
            success: function(){
		switch (status) {
		    case "0":
                    case 0:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
			$("#valid_"+id_focusout).show().delay("1000").fadeOut("2000");
                        break;
                    case "1":
                    case 1:
			$(".validateTips").removeClass('ui-icon-close').addClass('ui-icon-check');
			$("#valid_"+id_focusout).show().delay("1000").fadeOut("2000");
			switch (param){
			    case "nbr_week":
				$("#datepicker").datepicker("option", "showWeek", value);
				$("#datepicker").datepicker("refresh");
				break;
			}
                        break;
                    default:
                        $(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
                        $("#valid_"+id_focusout).delay("1000").show().fadeOut("2000");
                        break;
                }
           }
        });
    });
    $(".validateTips").hide();
});
</script>
<form id="form_parameters">
<div class="ui-windows ui-widget ui-widget-content ui-corner-all twelve columns">
   <div class="ui-windows-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span class="ui-windows-title"><?php echo $param_titre_interface; ?></span>
   </div>
   <div class="ui-windows-content ui-widget-content">
	   <label tooltip="<?php echo $param_tooltip_langue; ?>"><?php echo $param_label_langue; ?> *</label>
	   <select id="langue" name="langue" style="display:inline">
	   <?php
		while($Entry = @readdir($MyDirectory)) {
		    if (is_dir($MyDirectory."/".$Entry) === false){
			$arr_Entry = explode(".", $Entry);
			if($Entry != '.' && $Entry != '..' && @$arr_Entry[1] == "php") {
			    $option = ($_SESSION['langue'] == $arr_Entry[0]) ? " selected=selected" : "";
			    echo '<option value="'.$arr_Entry[0].'"'.$option.'>'.$param_select_langue[$indice].'</option>';
			    $indice++;
			}
		    }
		}
		closedir($MyDirectory);
	   ?>

	   </select><span id="valid_langue" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br />
	   <label tooltip="<?php echo $param_tooltip_interface; ?>"><?php echo $param_label_interface; ?> *</label>
	   <select id="skin" name="skin">
	       <option value="start"<?php echo ($_SESSION['skin'] == "start") ? " selected=selected" : ""; ?>>Start</option>
	       <option value="ui-lightness"<?php echo ($_SESSION['skin'] == "ui-lightness") ? " selected=selected" : ""; ?>>UI Lightness</option>
	       <option value="sunny"<?php echo ($_SESSION['skin'] == "sunny") ? " selected=selected" : ""; ?>>Sunny</option>
	       <option value="overcast"<?php echo ($_SESSION['skin'] == "overcast") ? " selected=selected" : ""; ?>>Overcast</option>
               <option value="humanity"<?php echo ($_SESSION['skin'] == "humanity") ? " selected=selected" : ""; ?>>Humanity</option>
               <option value="ui-darkness"<?php echo ($_SESSION['skin'] == "ui-darkness") ? " selected=selected" : ""; ?>>UI Darkness</option>
	   </select><span id="valid_skin" class="validateTips ui-icon ui-icon-check" style="display: inline">&nbsp;&nbsp;</span>
   </div>
</div>
<div class="ui-windows ui-widget ui-widget-content ui-corner-all">
   <div class="ui-windows-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span class="ui-windows-title"><?php echo $param_titre_cal; ?></span>
   </div>
   <div style="width: 100%;" class="ui-windows-content ui-widget-content">
	   <label tooltip="<?php echo $param_tooltip_semaine; ?>"><?php echo $param_label_semaine; ?></label>
	   <input type="radio" id="nbr_week1" name="nbr_week" value="true"<?php echo ($_SESSION['nbr_week'] == "true") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[0]; ?>&nbsp;<span id="valid_nbr_week" class="validateTips ui-icon ui-icon-check" style="display: inline">&nbsp;&nbsp;</span><br />
	   <label>&nbsp;</label><input type="radio" id="nbr_week2" name="nbr_week" value="false"<?php echo ($_SESSION['nbr_week'] == "false") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[1]; ?><br />
	   <label for="first_day" tooltip="<?php echo $param_tooltip_firstday; ?>"><?php echo $param_label_firstday; ?></label>
	   <select id="first_day" name="first_day">
	       <?php
	       foreach ($lng_day as $key=>$value){
		   if ($key != 6){
		       $indice = $key + 1;
		       $option = ($_SESSION['first_day'] == $indice) ? " selected=selected" : "";
		       echo '<option value="'.$indice.'"'.$option.'>'.$value.'</option>';
		   } else {
		       $indice = 0;
		       $option = ($_SESSION['first_day'] == $indice) ? " selected=selected" : "";
		       echo '<option value="'.$indice.'"'.$option.'>'.$value.'</option>';
		   }
	       }
		?>
	   </select><span id="valid_first_day" class="validateTips ui-icon ui-icon-check" style="display: inline">&nbsp;&nbsp;</span><br /><br />
	   <label tooltip="<?php echo $param_tooltip_affich_horo; ?>"><?php echo $param_label_affich_horo; ?></label>
	    <select name="affich_horo" id="affich_horo">
	    <?php
	    foreach ($lng_yes_no as $key => $value) {
		$option = ($_SESSION['affich_horo'] == $key) ? " selected=selected" : "";
		echo '<option value="'.$key.'"'.$option.'>'.$value.'</option>';
	    }
	    ?>
	    </select><span id="valid_affich_horo" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br />
	   <label tooltip="<?php echo $param_tooltip_ampm; ?>"><?php echo $param_label_ampm; ?></label>
	   <input type="radio" id="horoampm1" name="horoampm" value="true"<?php echo ($_SESSION['horoampm'] == "true") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[0]; ?>&nbsp;<span id="valid_horoampm" class="validateTips ui-icon ui-icon-check" style="display: inline">&nbsp;&nbsp;</span><br />
	   <label>&nbsp;</label><input type="radio" id="horoampm2" name="horoampm" value="false"<?php echo ($_SESSION['horoampm'] == "false") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[1]; ?><br />
	   <label tooltip="<?php echo $param_tooltip_now; ?>"><?php echo $param_label_now; ?></label>
	   <input type="radio" id="horonow1" name="horonow" value="true"<?php echo ($_SESSION['horonow'] == "true") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[0]; ?>&nbsp;<span id="valid_horonow" class="validateTips ui-icon ui-icon-check" style="display: inline">&nbsp;&nbsp;</span><br />
	   <label>&nbsp;</label><input type="radio" id="horonow2" name="horonow" value="false"<?php echo ($_SESSION['horonow'] == "false") ? " checked=checked" : ""; ?> /><?php echo $param_select_truefalse[1]; ?><br />
   </div>
</div>
<div class="ui-windows ui-widget ui-widget-content ui-corner-all">
    <div class="ui-windows-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
	<span class="ui-windows-title"><?php echo $param_titre_users; ?></span>
    </div>
    <div style="width: 100%;" class="ui-windows-content ui-widget-content">
	<label tooltip="<?php echo $param_tooltip_create; ?>"><?php echo $param_label_create; ?></label>
	<select name="create_user" id="create_user">
	<?php
	foreach ($lng_yes_no as $key => $value) {
	    $option = ($_SESSION['create_user'] == $key) ? " selected=selected" : "";
	    echo '<option value="'.$key.'"'.$option.'>'.$value.'</option>';
	}
	?>
	</select><span id="valid_create_user" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br />
	<label tooltip="<?php echo $param_tooltip_modif; ?>"><?php echo $param_label_modif; ?></label>
	<select name="modif_user" id="modif_user">
	<?php
	foreach ($lng_yes_no as $key => $value) {
	    $option = ($_SESSION['modif_user'] == $key) ? " selected=selected" : "";
	    echo '<option value="'.$key.'"'.$option.'>'.$value.'</option>';
	}
	?>
	</select><span id="valid_modif_user" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span>
   </div>
</div>
</form>
<p style="color: red; font-weight: bold">* <?php echo $param_tilde; ?></p>