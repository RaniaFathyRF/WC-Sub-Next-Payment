<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('WCS_Next_Payment_Utility')) {

	class WCS_Next_Payment_Utility {

		/**
		 * @var WCS_Next_Payment_Utility
		 */
		public static $instance;


		private function __construct() {
		}

		/**
		 * Enqueue datepicker scripts
		 * @return void
		 */
		public static function enqueue_datepicker_scripts()
		{

			//Enqueue Datepicker JS Script
			wp_enqueue_script('wc_sub_box_extra_actions-datepicker', WCS_NEXT_PAYMENT_URL . 'assets/js/jquery-ui.min.js', array('jquery'), WCS_NEXT_PAYMENT_ASSETS_VERSION,'','true');
			//Enqueue Datepicker CSS Script
			wp_enqueue_style('wc_sub_box_extra_actions-datepicker', WCS_NEXT_PAYMENT_URL . 'assets/css/jquery-ui.min.css', false, WCS_NEXT_PAYMENT_ASSETS_VERSION);

		}

		/**
		 * @return WCS_Next_Payment_Utility|self
		 */
		public static function get_instance()
		{

			if (!isset(self::$instance) || is_null(self::$instance))
				self::$instance = new self();

			return self::$instance;

		}
	}
}
WCS_Next_Payment_Utility::get_instance();
