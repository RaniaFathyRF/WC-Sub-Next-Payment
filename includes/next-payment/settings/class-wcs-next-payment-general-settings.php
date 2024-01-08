<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WCS_Next_Payment_Settings' ) ) {
	class WCS_Next_Payment_Settings {
		private static $instance;
		const NEXT_PAYMENT_ID = 'wcs_enable_next_payment';
		const NEXT_PAYMENT_BOX_ENABLE_KEY = 'enable_next_payment_box';

		/**
		 * WCS_Next_Payment_Settings constructor.
		 */
		public function __construct() {

			// Add next payment option in WooCommerce settings admin panel
			add_filter( 'wc_sub_next_payment_add_settings', array( $this, 'add_next_payment_option' ), 20 );

		}

		/**
		 * Add next payment option in WooCommerce settings admin panel
		 * @param $settings
		 * @return mixed
		 */
		public function add_next_payment_option( $settings ) {

			$settings[ self::NEXT_PAYMENT_BOX_ENABLE_KEY ] = array(
				'name'    => __( 'Enable Next Payment Feature', 'wc-sub-next-payment' ),
				'id'      => self::NEXT_PAYMENT_ID,
				'type'    => 'checkbox',
				'default' => 'no'
			);
			return $settings;

		}

		/**
		 * Check if next payment box is checked and enabled
		 * @return bool
		 */
		public static function is_next_payment_box_enabled_in_settings() {

			return ( WC_Sub_Next_Payment_Utility::wc_sub_box_get_settings_options( self::NEXT_PAYMENT_ID ) != 'no') ? true : false;

		}

		/**
		 * Get instance
		 * @return WCS_Next_Payment_Settings
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) || is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

WCS_Next_Payment_Settings::get_instance();