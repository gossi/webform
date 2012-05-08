<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

include_once __DIR__.'/../../../lib/recaptcha-1.11/recaptchalib.php';

/**
 * Represents a recaptcha control
 */
class Recaptcha extends Control {

	private $publicKey;
	private $privateKey;
	private $theme = 'clean';

	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$this->config($config, array('publicKey', 'privateKey', 'theme'));
	}

	public function getPrivateKey() {
		return $this->privateKey;
	}

	public function getPublicKey() {
		return $this->publicKey;
	}

	public function getTheme() {
		return $this->theme;
	}

	public function setPrivateKey($privateKey) {
		$this->privateKey = $privateKey;
	}

	public function setPublicKey($publicKey) {
		$this->publicKey = $publicKey;
	}

	/**
	 * Sets the theme for the recaptcha. 
	 * 
	 * @see https://developers.google.com/recaptcha/docs/customization
	 * @param String $theme Accepted values are: clean, white, blackglass, red, custom 
	 */
	public function setTheme($theme = 'clean') {
		$this->theme = $theme;
	}

	public function toXML() {
		$useSSL = false;
		$target = $this->getWebform()->getTarget();
		if (strpos($target, 'https')) {
			$useSSL = true;
		} else if (!strpos($target, 'http') 
				&& array_key_exists('HTTPS', $_SERVER)
				&& $_SERVER['HTTPS'] == 'on') {
			$useSSL = true;
		}
		
		$recaptcha = recaptcha_get_html($this->publicKey, null, $useSSL);
		$xml = $this->createXML('Recaptcha');
		$root = $xml->documentElement;
		$html = $xml->createElement('html', $recaptcha);
		$html->setAttribute('lang', $this->getWebform()->getLanguage());
		$html->setAttribute('theme', $this->theme);
		$root->appendChild($html);

		return $xml;
	}
	
	public function validate() {
		// get captcha response
		$response = recaptcha_check_answer ($this->privateKey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);

		// ... check if valid
		if (!$response->is_valid) {
			$this->addError($this->getWebform()->getI18n('//error/recaptcha'));
		}

		// throw errors if present
		if ($this->hasErrors()) {
			$errors = new WebformErrors();
			$errors->addErrors($this->errors);
			throw $errors;
		}
	}
}
?>