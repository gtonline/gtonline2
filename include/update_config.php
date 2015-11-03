<?php
session_start();
$_SESSION[$_POST['param']] = $_POST['value'];
$xml = new SimpleXMLElement('../parameters.xml', null, true);
foreach($xml->xpath('//parameters/parameter') as $key => $ua) {
  if($ua->name == $_POST['param']){
      $xml->parameter[$key]->value = $_POST['value'];
  }
}
if ($xml->asXML('../parameters.xml')){
    echo 'var status = 1;';
    echo 'var param = "'.$_POST['param'].'";';
    echo 'var value = '.$_POST['value'].';';
} else {
    echo 'var status = 0;';
}
?>