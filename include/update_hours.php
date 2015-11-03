<?php
session_start();
$_SESSION['day_start'] = serialize ($_POST['day_start']);
$_SESSION['day_end'] = serialize ($_POST['day_end']);
$_SESSION['day_pause'] = serialize ($_POST['day_pause']);
$_SESSION['horaires'] = serialize ($_POST['horaires']);
$xml = new SimpleXMLElement('../parameters.xml', Null, True);
foreach($xml->xpath('//parameters/parameter') as $key => $ua) {
  switch ($ua->name){
      case 'day_start':
	  $xml->parameter[$key]->value = serialize ($_POST['day_start']);
	  break;
      case 'day_end':
	  $xml->parameter[$key]->value = serialize ($_POST['day_end']);
	  break;
      case 'day_pause':
	  $xml->parameter[$key]->value = serialize ($_POST['day_pause']);
	  break;
      case 'horaires':
	  $xml->parameter[$key]->value = serialize ($_POST['horaires']);
	  break;
  }
}
if ($xml->asXML('../parameters.xml')){
    echo 'var status = 1;';
} else {
    echo 'var status = 2;';
}
?>
