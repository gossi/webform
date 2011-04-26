<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

if (!class_exists('\gossi\webform\Autoload', false)) {
	/**
	 * webform Autoloader
	 */
	class Autoload {
		protected static $path = '';
		protected static $registered = false;

		/**
		 * Init the Autoloader
		 * @param String $path root path
		 */
		public static function init($path) {
			self::register();
			self::$path = $path;
		}

		/**
		 * Registers this Autoload
		 */
		public static function register() {
			if (!self::$registered) {
				spl_autoload_register('gossi\webform\Autoload::load');
			}
			self::$registered = true;
		}

		/**
		 * Loads a class.
		 * 
		 * @param String $class the classname to load
		 */
		public static function load($class) {
			
			if (strtolower(substr($class, 0, 14)) !== 'gossi\\webform\\') {
				return false;
			}
			
			echo $class.'<br>';
			echo self::$path.'<br>';
			
			$file = self::$path . DIRECTORY_SEPARATOR. str_replace('gossi\\webform\\', DIRECTORY_SEPARATOR, $class) . '.php';
			echo $file;
			if (file_exists($file)) {
				require_once $file;
				if (!class_exists($class, false) && !interface_exists($class, false)) {
					die(new Exception('Class ' . $class . ' was not present in ' . $file .'") [gossi\\webform\\Autoload]'));
				}
			}
			
		} 
	}
}
Autoload::init(__DIR__);
?>
