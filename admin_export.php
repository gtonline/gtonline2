<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
?>
<script>
$(document).ready(function() {
    var export_message = $("#msg_error");
    var export_style = $("#export_style");
    var export_logo = $("#export_logo");
    var export_boite = $("#export_widget");
    var export_from_date = $("#from");
    var export_to_date = $("#to");
    var export_format = $("#format");
    var purge_message = $("#purge_msg_error");
    var purge_style = $("#purge_style");
    var purge_logo = $("#purge_logo");
    var purge_boite = $("#purge_widget");
    var purge_from_date = $("#from_purge");
    var purge_to_date = $("#to_purge");

    $('#dialog_export').dialog({
        autoOpen : false,
        resizable: false,
        closeOnEscape: false,
        position: "center",
        modal: true,
        height: 120,
        width:300,
        open: function(event, ui) {  $(".ui-dialog-titlebar-close", $(this).parent()).hide(); }
    });
    export_boite.hide();
    export_style.removeClass("ui-state-error").removeClass("ui-state-active").addClass("ui-state-highlight");
    purge_boite.hide();

    var max=0;
    $("label").width("auto");
    $("label").each(function(){
	if ($(this).width() > max)
	    max = $(this).width();
	});
    $("label").width(max + 10);

    // Traitement javascript export
    $( "#from" ).datepicker({
	defaultDate: "-1m",
	changeMonth: true,
	numberOfMonths: 3,
	onSelect: function( selectedDate ) {
	    $("#to").datepicker( "option", "minDate", selectedDate );
	}
    });
    $("#to").datepicker({
	defaultDate: "-1m",
	changeMonth: true,
	numberOfMonths: 3,
	OnSelect: function( selectedDate ) {
	    $("#from").datepicker( "option", "maxDate", selectedDate );
	}
    });

    $("#btn_export").button();
    $("#btn_export").click(function() {
	export_logo.removeClass("ui-icon-info").addClass("ui-icon-alert");
	export_from_date.removeClass("ui-state-error");
	export_to_date.removeClass("ui-state-error");
	if (export_from_date.val() == ""){
	    export_from_date.addClass("ui-state-error").focus();
            export_style.removeClass("ui-state-active").addClass("ui-state-error");
	    export_message.html("<?php echo $export_message_wrong; ?>");
	    export_boite.show().delay(2000).fadeOut('slow');
	} else if (export_to_date.val() == "") {
	    export_to_date.addClass("ui-state-error").focus();
            export_style.removeClass("ui-state-active").addClass("ui-state-error");
	    export_message.html("<?php echo $export_message_wrong; ?>");
	    export_boite.show().delay(2000).fadeOut('slow');
	} else {
            export_style.removeClass("ui-state-error").addClass("ui-state-active");
	    $('#dialog_export').dialog("open");
	    $.ajax({
		type: "POST",
		url: "include/export.php",
		data: {from_date: export_from_date.val(), to_date: export_to_date.val(), format:export_format.val()},
		dataType: "script",
		success: function(){
                    $('#dialog_export').dialog("close");
		    if (status) {
			export_logo.removeClass("ui-icon-alert").addClass("ui-icon-info");
			export_message.html("<?php echo $export_message_good; ?>");
			export_boite.show().delay(3000).fadeOut('slow');
			$("#list_export").trigger("reloadGrid");
		    } else {
			export_style.removeClass("ui-state-active").addClass("ui-state-error");
			export_logo.removeClass("ui-icon-info").addClass("ui-icon-alerte");
			export_message.html("<?php echo $export_emptyrecords; ?>");
			export_boite.show().delay(3000).fadeOut('slow');
			$("#list_export").trigger("reloadGrid");
		    }
		}
	    });
	}
    });
    // Traitement javascript purge
    $("#from_purge").datepicker({
	defaultDate: "-1m",
	changeMonth: true,
	numberOfMonths: 3,
	onSelect: function( selectedDate ) {
	    $("#to_purge").datepicker( "option", "minDate", selectedDate );
	}
    });
    $("#to_purge").datepicker({
	defaultDate: "-1m",
	changeMonth: true,
	numberOfMonths: 3,
	OnSelect: function( selectedDate ) {
	    $( "#from_purge" ).datepicker( "option", "maxDate", selectedDate );
	}
    });

    $("#btn_purge").button();
    $("#btn_purge").click(function() {
	purge_style.removeClass("ui-state-active").addClass("ui-state-error");
	purge_logo.removeClass("ui-icon-info").addClass("ui-icon-alert");
	purge_from_date.removeClass("ui-state-error");
	purge_to_date.removeClass("ui-state-error");
	if (purge_from_date.val() == ""){
	    purge_from_date.addClass("ui-state-error").focus();
	    purge_message.html("<?php echo $export_message_wrong; ?>");
	    purge_boite.show().delay(2000).fadeOut('slow');
	} else if (purge_to_date.val() == "") {
	    purge_to_date.addClass("ui-state-error").focus();
	    purge_message.html("<?php echo $export_message_wrong; ?>");
	    purge_boite.show().delay(2000).fadeOut('slow');
	} else {
	    $.ajax({
		type: "POST",
		url: "include/purge.php",
		data: {from_date: purge_from_date.val(), to_date: purge_to_date.val()},
		dataType: "script",
		success: function(){
		    if (status) {
			purge_style.removeClass("ui-state-error").addClass("ui-state-active");
			purge_logo.removeClass("ui-icon-alert").addClass("ui-icon-info");
			purge_message.html("<?php echo $purge_message_good; ?>");
			purge_boite.show().delay(2000).fadeOut('slow');
		    }
		}
	    });
	}
    });
    $("#list_export").jqGrid({
	    url:'include/export_grid.php',
	    editurl:'include/export_grid.php',
	    datatype: "xml",
	    mtype: 'POST',
	    postData: {},
	    colNames:['Nom', 'Date de début', 'Date de fin', 'Lien'],
	    colModel:[
		{name:'nom',index:'nom', width:50, align:"center", editable:false},
		{name:'debut',index:'debut',width:50,align:"center",editable:false},
		{name:'fin',index:'fin',width:50,align:"center",editable:false},
		{name:'link',index:'link',width:120,align:"center",editable:false,formatter:'showlink',
		formatter : function ( cellvalue, options, rowObject )
                    {
                        return "<a href='export/"+cellvalue+"' target='_blank'>"+cellvalue+"</a>";
                    }
		}
	    ],
	    pager: '#pager_export',
	    emptyrecords: "<?php echo $export_emptyrecords; ?>",
	    pgbuttons: false,
	    pginput: false,
	    sortname: 'debut',
	    viewrecords: false,
	    recordtext: "",
	    sortorder: "asc",
	    autowidth: true,
	    headertitles: true,
	    height: "auto",
	    hidegrid: false,
	    caption:"<?php echo $export_caption; ?>"
	});
	$("#list_export").jqGrid('navGrid','#pager_export',
	    {edit:false,
		add:false,
		del:true,
		refresh:true,
		search:false,
		delfunc:function(rowid) {
		    var ret = $("#list_export").jqGrid('getRowData',rowid);
		    $.ajax({
                        type: "POST",
                        url: "include/export_grid.php",
                        data: {link: ret.link, oper: "del"},
                        dataType: "script",
                        success: function(){
			    $("#list_export").jqGrid('delRowData',rowid);
                         }
                     });
		}
	    }, //options
	    {}, // edit options
	    {}, // add options
	    {width:280}, // del options
	    {} // search options
	);
});
</script>
<style>
#fountainG{
position:relative;
width:66px;
height:8px}

.fountainG{
position:absolute;
top:0;
background-color:#000000;
width:8px;
height:8px;
-moz-animation-name:bounce_fountainG;
-moz-animation-duration:1.2s;
-moz-animation-iteration-count:infinite;
-moz-animation-direction:linear;
-moz-transform:scale(.3);
-moz-border-radius:6px;
-webkit-animation-name:bounce_fountainG;
-webkit-animation-duration:1.2s;
-webkit-animation-iteration-count:infinite;
-webkit-animation-direction:linear;
-webkit-transform:scale(.3);
-webkit-border-radius:6px;
-ms-animation-name:bounce_fountainG;
-ms-animation-duration:1.2s;
-ms-animation-iteration-count:infinite;
-ms-animation-direction:linear;
-ms-transform:scale(.3);
-ms-border-radius:6px;
-o-animation-name:bounce_fountainG;
-o-animation-duration:1.2s;
-o-animation-iteration-count:infinite;
-o-animation-direction:linear;
-o-transform:scale(.3);
-o-border-radius:6px;
animation-name:bounce_fountainG;
animation-duration:1.2s;
animation-iteration-count:infinite;
animation-direction:linear;
transform:scale(.3);
border-radius:6px;
}

#fountainG_1{
left:0;
-moz-animation-delay:0.48s;
-webkit-animation-delay:0.48s;
-ms-animation-delay:0.48s;
-o-animation-delay:0.48s;
animation-delay:0.48s;
}

#fountainG_2{
left:8px;
-moz-animation-delay:0.6s;
-webkit-animation-delay:0.6s;
-ms-animation-delay:0.6s;
-o-animation-delay:0.6s;
animation-delay:0.6s;
}

#fountainG_3{
left:17px;
-moz-animation-delay:0.72s;
-webkit-animation-delay:0.72s;
-ms-animation-delay:0.72s;
-o-animation-delay:0.72s;
animation-delay:0.72s;
}

#fountainG_4{
left:25px;
-moz-animation-delay:0.84s;
-webkit-animation-delay:0.84s;
-ms-animation-delay:0.84s;
-o-animation-delay:0.84s;
animation-delay:0.84s;
}

#fountainG_5{
left:33px;
-moz-animation-delay:0.96s;
-webkit-animation-delay:0.96s;
-ms-animation-delay:0.96s;
-o-animation-delay:0.96s;
animation-delay:0.96s;
}

#fountainG_6{
left:41px;
-moz-animation-delay:1.08s;
-webkit-animation-delay:1.08s;
-ms-animation-delay:1.08s;
-o-animation-delay:1.08s;
animation-delay:1.08s;
}

#fountainG_7{
left:50px;
-moz-animation-delay:1.2s;
-webkit-animation-delay:1.2s;
-ms-animation-delay:1.2s;
-o-animation-delay:1.2s;
animation-delay:1.2s;
}

#fountainG_8{
left:58px;
-moz-animation-delay:1.32s;
-webkit-animation-delay:1.32s;
-ms-animation-delay:1.32s;
-o-animation-delay:1.32s;
animation-delay:1.32s;
}

@-moz-keyframes bounce_fountainG{
0%{
-moz-transform:scale(1);
background-color:#000000;
}

100%{
-moz-transform:scale(.3);
background-color:#CCCCCC;
}

}

@-webkit-keyframes bounce_fountainG{
0%{
-webkit-transform:scale(1);
background-color:#000000;
}

100%{
-webkit-transform:scale(.3);
background-color:#CCCCCC;
}

}

@-ms-keyframes bounce_fountainG{
0%{
-ms-transform:scale(1);
background-color:#000000;
}

100%{
-ms-transform:scale(.3);
background-color:#CCCCCC;
}

}

@-o-keyframes bounce_fountainG{
0%{
-o-transform:scale(1);
background-color:#000000;
}

100%{
-o-transform:scale(.3);
background-color:#CCCCCC;
}

}

@keyframes bounce_fountainG{
0%{
transform:scale(1);
background-color:#000000;
}

100%{
transform:scale(.3);
background-color:#CCCCCC;
}

}

</style>
<div class="ui-windows ui-widget ui-widget-content ui-corner-all twelve columns">
   <div class="ui-windows-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span class="ui-windows-title"><?php echo $export_title; ?></span>
   </div>
   <div class="ui-windows-content ui-widget-content">
	    <table id="list_export"></table>
	    <div id="pager_export"></div><br />
	    <label for="from"><?php echo $export_from; ?></label><input type="text" id="from" /><br />
	    <label for="to"><?php echo $export_to; ?></label><input type="text" id="to" /><br />
	    <label for="format">Format</label><select id="format"><option>csv</option><option>pdf</option></select><br />
	    <button id="btn_export"><?php echo $export_button; ?></button>
	    <div class="ui-widget" id="export_widget" style="float:right;width:350px; margin-top: 7px">
		<div id="export_style" class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		    <span id="export_logo" class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		    <div id="msg_error"></div>
		</div>
	    </div>
   </div>
</div>
<div class="ui-windows ui-widget ui-widget-content ui-corner-all twelve columns">
   <div class="ui-windows-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
      <span class="ui-windows-title"><?php echo $purge_title; ?></span>
   </div>
   <div class="ui-windows-content ui-widget-content">
	    <label for="from_purge"><?php echo $purge_from; ?></label><input type="text" id="from_purge" /><br />
	    <label for="to_purge"><?php echo $purge_to; ?></label><input type="text" id="to_purge" /><br />
	    <button id="btn_purge"><?php echo $purge_button; ?></button>
	    <div class="ui-widget" id="purge_widget" style="float:right;width:350px; margin-top: 7px">
		<div id="purge_style" class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		    <span id="purge_logo" class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		    <div id="purge_msg_error"></div>
		</div>
	    </div>
   </div>
</div>
<div id="dialog_export" title="Exportation" style="text-align: center;">
    <span style="font-weight: bold; font-size: 1.2em;"><?php echo $export_message_progress; ?></span>
    <div id="fountainG" style="margin-right: auto; margin-left: auto; margin-top: 10px">
    <div id="fountainG_1" class="fountainG">
    </div>
    <div id="fountainG_2" class="fountainG">
    </div>
    <div id="fountainG_3" class="fountainG">
    </div>
    <div id="fountainG_4" class="fountainG">
    </div>
    <div id="fountainG_5" class="fountainG">
    </div>
    <div id="fountainG_6" class="fountainG">
    </div>
    <div id="fountainG_7" class="fountainG">
    </div>
    <div id="fountainG_8" class="fountainG">
    </div>
    </div>
</div>