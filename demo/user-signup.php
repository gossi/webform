<?php
namespace demo; // it's only here for ide autocompletion

use gossi\webform\Recaptcha;

use gossi\webform\CheckBox;

use gossi\webform\MultiLine;

use gossi\webform\ComboBox;
use gossi\webform\WebformErrors;
use gossi\webform\validation\MatchTest;
use gossi\webform\Week;
use gossi\webform\Password;
use gossi\webform\Radio;
use gossi\webform\Group;
use gossi\webform\Webform;
use gossi\webform\Color;
use gossi\webform\Area;
use gossi\webform\Submit;
use gossi\webform\Url;
use gossi\webform\Tel;
use gossi\webform\Email;
use gossi\webform\Number;
use gossi\webform\Range;
use gossi\webform\Date;
use gossi\webform\Time;
use gossi\webform\DateTime;
use gossi\webform\SingleLine;

include '../src/Autoload.php';
//include '../build/gossi-webform.phar';


$wf = new Webform(array('target' => $_SERVER['PHP_SELF']));
$wf->setLayout(Webform::LAYOUT_TABLE);

$personal = new Area($wf, array('label' => 'Personal Data', 'columns' => 2));

$left = new Area($personal, array('classes' => 'webform-area-blind'));

$firstName = new SingleLine($left, array('label' => 'First Name', 'required' => true));
$lastName = new SingleLine($left, array('label' => 'Last Name', 'required' => true));
$birthday = new Date($left, array('label' => 'Birthday', 'prependClass' => 'icon-date'));
$birthweek = new Week($left, array('label' => 'Birthweek'));
$birthtime = new Time($left, array('label' => 'Birthtime'));
$birth = new DateTime($left, array('label' => 'Birth'));
$sex = new Group($left, array('label' => 'Sex'/*, 'required' => true*/));
$male = new Radio($sex, array('label' => 'Male', 'value' => 'm'));
$female = new Radio($sex, array('label' => 'Female', 'value' => 'f'));

$right = new Area($personal, array('classes' => 'webform-area-blind'));

$size = new Number($right, array('label' => 'Size'));
$weight = new Number($right, array('label' => 'Weight'));
$color = new Color($right, array('label' => 'Favorite Color'));
$about = new MultiLine($right, array('label' => 'About yourself'));
$fruits = new ComboBox($right, array('label' => 'Favorite Fruit'));
$fruits->createOption('', '');
$fruits->createOption('', 'Banana');
$fruits->createOption('', 'Apple');
$fruits->createOption('', 'Ananas');

$sfruits = new ComboBox($right, array('label' => 'Secondary Fruit'));
$sfruits->createOption('', '');
$sfruits->createOption('', 'Banana');
$sfruits->createOption('', 'Apple');
$sfruits->createOption('', 'Ananas');

$food = new Group($right, array('label' => 'Favorite Food'));
$schnitzel = new CheckBox($food, array('label' => 'Schnitzel', 'value' => 'schnitzel'));
$spaghetti = new Checkbox($food, array('label' => 'Spaghetti', 'value' => 'spaghetti'));

$range = new Range($right, array('label' => 'Raaange'));

$contact = new Area($wf, array('label' => 'Contact Details'));

$email = new Email($contact, array('label' => 'Email'));
$tel = new Tel($contact, array('label' => 'Phone Number'));
$hp = new Url($contact, array('label' => 'Homepage', 'placeholder' => 'http://'));

$account = new Area($wf, array('label' => 'Account'));

$loginName = new SingleLine($account, array('label' => 'Login Name'));
$passwordA = new Password($account, array('label' => 'Password', 'required' => true));
$passwordB = new Password($account, array('label' => 'Repeat Password', 'required' => true));

$captcha = new Recaptcha($wf, array(
		'label' => 'Captcha', 
		'publicKey' => '6Ld1NNESAAAAAIwM-i1FlGAzwZ5HjDU1pM7ZECai', 
		'privateKey' => '6Ld1NNESAAAAANjbM98qp2g71cPCmEt7VlY7pWDk'
));

$submit = new Submit($wf, array('label' => 'Submit'));

$wf->addTest(new MatchTest('Passwords do not match', array($passwordA, $passwordB)));

// $xml = $wf->toXML();
// $xml->formatOutput = true;
// echo $xml->saveXML();

// print_r($_POST);
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>webform user signup demo</title>
	<link rel="stylesheet" href="../css/webform.css" type="text/css">
	<script src="../webshim/jquery-1.7.2.min.js"></script>
	<script src="../webshim/modernizr-custom.js"></script>
	<script src="../webshim/polyfiller.js"></script>
	<script src="../scripts/gossi-webform.js"></script>
</head>
<body>
<h1>User Signup Demo</h1>
<?php 
if ($wf->isValid()) {
	// do something here, form is valid
}
echo $wf->toHTML(); 
?>

<h1>Source</h1>
<?php //highlight_file(__FILE__);?>
</body>
</html>