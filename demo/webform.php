<?php
namespace test;
include '../src/Autoload.php';
//include '../build/gossi-webform.phar';



use gossi\webform\Color;

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
$tel = new webform\Tel($personal);
$tel->setLabel('Phone Number');
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

$weight = new webform\Number($personal);
$weight->setLabel('Weight');


$color = new Color($personal);
$color->setLabel('Favorite Color');




$submit = new webform\Submit($wf);
$submit->setLabel('Submit');
?>
<!doctype html>
<html>
<head>
	<title>webform demo</title>
	<link rel="stylesheet" href="../css/webform.css" type="text/css">
	<link rel="stylesheet" href="../webshim/smoothness/jquery-ui-1.8.18.custom.css" type="text/css">
	<script src="../webshim/jquery-1.7.1.min.js"></script>
	<script src="../webshim/extras/modernizr-custom.js"></script>
	<script src="../webshim/polyfiller.js"></script>
	<script>
	$.webshims.setOptions('forms-ext', {
		datepicker: {
			dateFormat: 'yy-mm-dd',
			constrainInput: true,
			changeMonth: true,
			changeYear: true,
			showWeek: true
		}
	});
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