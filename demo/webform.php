<?php
//include '../build/gossi-webform.phar';
include '../src/Autoload.php';

use gossi\webform;

$wf = new webform\Webform();
$wf->setLayout(webform\Webform::LAYOUT_TABLE);

$personal = new webform\Area($wf);
$personal->setLabel('Personal Data');

$firstName = new webform\SingleLine($personal);
$firstName->setLabel('First Name');
$lastName = new webform\SingleLine($personal);
$lastName->setLabel('Last Name');
$email = new webform\Email($personal);
$email->setLabel('Email');
$hp = new webform\Url($personal);
$hp->setLabel('Homepage');
$hp->setPlaceholder('http://');
$birthday = new webform\Date($personal);
$birthday->setLabel('Birthday');
$birthday->setPlaceholder('YYYY-MM-DD');

$size = new webform\Range($personal);
$size->setLabel('Size');
$size->setMin(1);
$size->setMax(50);
$size->setStep(10);

$submit = new webform\Submit($wf);
$submit->setLabel('Submit');
?>
<!doctype html>
<html>
<head>
	<title>webform demo</title>
	<link rel="stylesheet" href="../css/webform.css" type="text/css">
	<script src="../js/jquery-1.7.1.min.js"></script>
	<script src="../js/extras/modernizr-custom.js"></script>
	<script src="../js/polyfiller.js"></script>
	<script>
	$.webshims.polyfill("forms forms-ext");
	</script>
</head>
<body>
<h1>Webform Demo</h1>
<?php 
// $xml = $wf->toXML();
// $xml->formatOutput = true; 
// echo $xml->saveHTML();
echo $wf->toHTML(); 
?>

<h1>Source</h1>
<?php //highlight_file(__FILE__);?>
</body>
</html>