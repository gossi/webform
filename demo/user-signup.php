<?php
namespace demo; // it's only here for ide autocompletion

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
$birthday = new Date($left, array('label' => 'Birthday'));
$birthweek = new Week($left, array('label' => 'Birthweek'));
$birthtime = new Time($left, array('label' => 'Birthtime'));
$birth = new DateTime($left, array('label' => 'Birth'));
$sex = new Group($left, array('label' => 'Sex'));
$male = new Radio($sex, array('label' => 'Male'));
$female = new Radio($sex, array('label' => 'Female'));

$right = new Area($personal, array('classes' => 'webform-area-blind'));

$size = new Number($right, array('label' => 'Size'));
$weight = new Number($right, array('label' => 'Weight'));
$color = new Color($right, array('label' => 'Favorite Color'));

$contact = new Area($wf, array('label' => 'Contact Details'));

$email = new Email($contact, array('label' => 'Email'));
$tel = new Tel($contact, array('label' => 'Phone Number'));
$hp = new Url($contact, array('label' => 'Homepage', 'placeholder' => 'http://'));

$account = new Area($wf, array('label' => 'Account'));

$loginName = new SingleLine($account, array('label' => 'Login Name'));
$passwordA = new Password($account, array('label' => 'Password', 'required' => true));
$passwordB = new Password($account, array('label' => 'Repeat Password', 'required' => true));

$submit = new Submit($wf, array('label' => 'Submit'));

$wf->addTest(new MatchTest('passwords do not match', array($passwordA, $passwordB)));
?>
<!doctype html>
<html>
<head>
	<title>webform user signup demo</title>
	<link rel="stylesheet" href="../css/webform.css" type="text/css">
	<!-- <link rel="stylesheet" href="../webshim/smoothness/jquery-ui-1.8.18.custom.css" type="text/css">-->
	<script src="../webshim/jquery-1.7.1.min.js"></script>
	<script src="../webshim/extras/modernizr-custom.js"></script>
	<script src="../webshim/polyfiller.js"></script>
	<script>
	$.webshims.setOptions({
		extendNative: false,
		'forms-ext': {
			datepicker: {
				dateFormat: 'yy-mm-dd',
				constrainInput: true,
				changeMonth: true,
				changeYear: true,
				showWeek: false
			}
		}
	});
	$.webshims.polyfill("forms forms-ext");
	</script>
	
</head>
<body>
<h1>User Signup Demo</h1>
<?php 
// $xml = $wf->toXML();
// $xml->formatOutput = true; 
// echo $xml->saveHTML();
if ($wf->isValid()) {
	
}
echo $wf->toHTML(); 
?>

<h1>Source</h1>
<?php //highlight_file(__FILE__);?>
</body>
</html>