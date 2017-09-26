<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Inventor_Paypal_Customizations
 *
 * @access public
 * @package Inventor_Paypal/Classes/Customizations
 * @return void
 */
class Inventor_Paybill_Customizations {
	/**
	 * Initialize customizations
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {
		self::includes();
	}

	/**
	 * Include all customizations
	 *
	 * @access public
	 * @return void
	 */
	public static function includes() {
		require_once INVENTOR_PAYBILL_DIR . 'includes/customizations/class-inventor-paybill-customizations-paybill.php';
	}
}

Inventor_Paybill_Customizations::init();