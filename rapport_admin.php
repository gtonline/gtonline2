<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
$current_month = (isset($_GET['month'])) ? $_GET['month'] : date("m");
$current_month_lng = $lng_month[($current_month-1)];
$current_year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$min_date = mktime("00", "00", "00", $current_month, "1", $current_year);
$max_date = mktime("23", "59", "00", $current_month, "31", $current_year);
?>
<script type="text/javascript">
$(window).ready(function(){
    function mysum(val, name, record)
    {
        hv = val.split(":");
	hr = record[name].split(":");
	if (hv[0] == "") {
	    hfinale = hr[0];
	    mfinale = hr[1];
	} else {
	    hfinale = parseInt(hv[0]) + parseInt(hr[0]);
	    mfinale = parseInt(hv[1]) + parseInt(hr[1]);
	}
	if (mfinale >= 60) {
	    hfinale = hfinale + 1;
	    mfinale = mfinale - 60;
	}
	if (mfinale < 10 && mfinale.toString().length != 2) {
	    mfinale = "0" + mfinale;
	}
	return (hfinale + ":" + mfinale);

    }
    $("#list486").jqGrid({
	url:'include/rapport_grid.php',
	datatype: "xml",
	mtype: 'POST',
        postData: {min_date: '<?php echo $min_date; ?>', max_date: '<?php echo $max_date; ?>'},
	height: 'auto',
   	colNames:['<?php echo $rapport_col_projet; ?>','<?php echo $rapport_col_action; ?>', '<?php echo $rapport_col_duree.$rapport_col_unite[$_SESSION['calcul']]; ?>'],
   	colModel:[
   		{name:'projet',index:'projet'},
   		{name:'action',index:'action'},
		<?php if ($_SESSION['calcul'] == 1 ){ ?>
   		//{name:"duree",index:"duree",sorttype:"date",formatter:"date",formatoptions: { srcformat:"H:i", newformat:"H:i" },summaryType:mysum}
		{name:"duree",index:"duree",sorttype:"date",summaryType:mysum}
		<?php } else { ?>
		{name:'duree',index:'duree',sorttype:"float",formatter:"number",formatoptions: { decimalPlaces: "2" },summaryType:"sum"}
		<?php } ?>
   	],
   	pager: "#plist486",
   	viewrecords: false,
	rowNum: 100,
   	sortname: 'projet',
   	grouping:true,
   	groupingView : {
   		groupField : ['projet'],
   		groupSummary : [true],
   		groupColumnShow : [false],
   		groupText : ['<b>{0}</b>'],
   		groupCollapse : false,
		groupOrder: ['asc'],
		showSummaryOnHide: true
   	},
        footerrow: true,
        userDataOnFooter: true,
   	caption: "<?php echo $rapport_caption.$current_month_lng." ".$current_year; ?>",
	viewrecords: false,
	autowidth: true,
	headertitles: true,
	height: "auto",
	hidegrid: false,
	pgbuttons: false,
	pginput: false
    });
});
</script>
<!--Selectionnez un utilisateur :
<select>
    <option id="1">User 1</option>
    <option id="2">User 2</option>
</select>-->
<table id="list486"></table>
<div id="plist486"></div>