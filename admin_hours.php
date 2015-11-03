<?php
session_start();
$array_day_start = unserialize ($_SESSION['day_start']);
$array_day_end = unserialize ($_SESSION['day_end']);
$array_day_pause = unserialize ($_SESSION['day_pause']);
$array_horaires = unserialize ($_SESSION['horaires']);
require 'lang/'.$_SESSION['langue'].'.php';
?>
<script>
function difheure(heuredeb,heurefin){
   hd=heuredeb.split(":");
   hf=heurefin.split(":");
   hd[0] = eval(hd[0]); hd[1] = eval(hd[1])
   hf[0] = eval(hf[0]); hf[1] = eval(hf[1])
   if(hf[2] < hd[2]){hf[1] = hf[1]-1;}
   if(hf[1] < hd[1]){hf[0] = hf[0]-1; hf[1] = hf[1] + 60;}
   if(hf[0] < hd[0]){hf[0] = hf[0] + 24;}
   hfinale = hf[0] - hd[0];
   if (hfinale < 10) { hfinale = "0" + hfinale }
   mfinale = hf[1] - hd[1];
   if (mfinale < 10) { mfinale = "0" + mfinale }
   return ( hfinale + ":" + mfinale);
}
$(document).ready(function() {
    var max=0;
    $("label").width("auto");
    $("label").each(function(){
	if ($(this).width() > max)
	    max = $(this).width();
	});
    $("label").width(max + 10);

    var max_day=0;
    $(".day").width=("auto");
    $(".day").each(function(){
	if ($(this).width() > max_day)
	    max_day = $(this).width();
    });
    $(".day").width(max_day + 10);

    $('#form_hours select').change(function() {
	$.ajax({
	    type: "POST",
	    url: "include/update_config.php",
            data: {param: $(this).attr('id'), value: $(this).val()},
            dataType: "script",
            success: function(){
		switch (status) {
		    case "0":
                    case 0:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
			$("#valid_"+param).show().delay("1000").fadeOut("2000");
                        break;
                    case "1":
                    case 1:
			$(".validateTips").removeClass('ui-icon-close').addClass('ui-icon-check');
			$("#valid_"+param).show().delay("1000").fadeOut("2000");
                        break;
                    default:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
                        $("#valid_"+param).show().delay("1000").fadeOut("2000");
                        break;
                }
           }
        });
    });

    $(".validateTips").hide();
    <?php if ($_SESSION['affich_horo'] == 1) { ?>
   $("#form_hours input").each(function(){
       $(this).timepicker({showPeriod: false,showPeriodLabels: <?php echo $_SESSION['horoampm']; ?>,showNowButton: <?php echo $_SESSION['horonow']; ?>});
   });
   <?php } ?>

   $("#form_hours input").change(function(){
        indice = $(this).attr('name');
        temp_debut = $(".AM[name="+indice+"]").val();
        temp_fin = $(".PM[name="+indice+"]").val();
        temp_pause = $(".pause[name="+indice+"]").val();
        temp_total = difheure(temp_debut, temp_fin);
        if (temp_debut == "00:00" && temp_fin == "00:00") {
            $(".pause[name="+indice+"]").val("00:00");
        }
        $(".total[name="+indice+"]").val(difheure(temp_pause, temp_total));

	var day_start = new Array;
        var day_end = new Array;
        var day_pause = new Array;
        var horaires = new Array;
	$(".AM").each(function(){
            day_start.push($(this).val());
        });
        $(".PM").each(function(){
            day_end.push($(this).val());
        });
        $(".pause").each(function(){
            day_pause.push($(this).val());
        });
        $(".total").each(function(){
            horaires.push($(this).val());
        });
	$.ajax({
	    type: "POST",
	    url: "include/update_hours.php",
            data: {day_start: day_start, day_end: day_end, day_pause: day_pause, horaires: horaires},
            dataType: "script",
            success: function(){
		switch (status) {
		    case "0":
                    case 0:
			$(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
			$("#valid_"+indice).show().delay("1000").fadeOut("2000");
                        break;
                    case "1":
                    case 1:
			$(".validateTips").removeClass('ui-icon-close').addClass('ui-icon-check');
			$("#valid_"+indice).show().delay("1000").fadeOut("2000");
                        break;
                    default:
                        $(".validateTips").removeClass('ui-icon-check').addClass('ui-icon-close');
                        $("#valid_"+indice).show().delay("1000").fadeOut("2000");
                        break;
                }
           }
        });
   });
});
</script>
<form id="form_hours">
    <label tooltip="<?php echo $hours_tooltip_inter; ?>"><?php echo $hours_label_inter; ?></label>
    <select name="calcul_inter" id="calcul_inter">
	<?php
	foreach ($hours_select_inter as $key => $value) {
	    $option = ($_SESSION['calcul_inter'] == $key) ? " selected=selected" : "";
	    echo '<option value="'.$key.'"'.$option.'>'.$value.'</option>';
	}
	?>
    </select><span id="valid_calcul_inter" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br /><br />
    <label tooltip="<?php echo $hours_tooltip_rapport; ?>"><?php echo $hours_label_rapport; ?></label>
    <select name="calcul" id="calcul">
	<?php
	foreach ($hours_select_rapport as $key => $value) {
	    $option = ($_SESSION['calcul'] == $key) ? " selected=selected" : "";
	    echo '<option value="'.$key.'"'.$option.'>'.$value.'</option>';
	}
	?>
    </select><span id="valid_calcul" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br /><br />
    <label tooltip="<?php echo $hours_tooltip_users; ?>"><?php echo $hours_label_users; ?></label>
    <?php
	foreach ($lng_day as $key => $value) {
	    $indice = $key + 1;
	    if ($key == 0) {
		echo '<span class="day">'.$value.'</span><input class="AM" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[0].'" value="'.$array_day_start[$key].'" /><input class="PM" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[1].'" value="'.$array_day_end[$key].'" /><input class="pause" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[2].'" value="'.$array_day_pause[$key].'" /><input type="time" class="total" name="'.$indice.'" size="5" value="'.$array_horaires[$key].'" disabled="disabled" /><span id="valid_'.$indice.'" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br />'."\r\n";
	    /*} elseif ($key == 6) {
		break;*/
	    } else {
		echo '<label>&nbsp;</label><span class="day">'.$value.'</span><input class="AM" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[0].'" value="'.$array_day_start[$key].'" /><input class="PM" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[1].'" value="'.$array_day_end[$key].'" /><input class="pause" name="'.$indice.'" type="time" size="5" placeholder="'.$hours_placeholder_users[2].'" value="'.$array_day_pause[$key].'" /><input type="time" class="total" name="'.$indice.'" size="5" value="'.$array_horaires[$key].'" disabled="disabled" /><span id="valid_'.$indice.'" class="validateTips ui-icon ui-icon-check" style="display: inline;">&nbsp;&nbsp;</span><br />'."\r\n";
	    }
	}
	?>
</form>