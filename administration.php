<?php
session_start();
require 'lang/'.$_SESSION['langue'].'.php';
?>
<script type="text/javascript">
  $(function() {
     $( "#accordion" ).accordion({
        collapsible: true,
        active : false,
	heightStyle: "content",
        activate: function (event, ui) {
          $url = $(ui.newHeader[0]).children('a').attr('href');
		$.post($url, function (data) {
	     $(ui.newHeader[0]).next().html(data);
          });
        }
     });
  });
</script>

<div id="accordion">
	<h3><a href="admin_export.php"><?php echo $accordion_export; ?></a></h3><div></div>
	<h3><a href="admin_user.php"><?php echo $accordion_user; ?></a></h3><div></div>
	<h3><a href="admin_projet.php"><?php echo $accordion_projet; ?></a></h3><div></div>
	<h3><a href="admin_action.php"><?php echo $accordion_action; ?></a></h3><div></div>
	<h3><a href="admin_join.php"><?php echo $accordion_join; ?></a></h3><div></div>
	<h3><a href="admin_hours.php"><?php echo $accordion_hours; ?></a></h3><div></div>
	<h3><a href="admin_parameters.php"><?php echo $accordion_parameters; ?></a></h3><div></div>
	<h3><a href="about.php"><?php echo $accordion_about; ?></a></h3><div></div>
</div>
<script>
$( document ).tooltip({
    items: "label[tooltip]",
    content: function() {
        return $( this ).attr( "tooltip" );
    },
    position: {
	my: "right top",
        at: "left-10 top"
    }
});
</script>