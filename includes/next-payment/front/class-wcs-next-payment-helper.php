<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'WCS_Next_Payment_Helper' ) ) {
	class WCS_Next_Payment_Helper {
		private static $instance;

		/**
		 * Constructor
		 */
		public function __construct() {

		}

		/**
		 * Get next payment chosen by the user
		 * @param $post_data
		 * @param $format
		 * @return string
		 */
		public static function get_next_payment_date( $post_data, $format = 'Y-m-d H:i:s' ) {

			//Next payment date sent to the ajax in POST as 'selected_date'
			$selected_date = isset($post_data['selected_date'])? strip_tags( esc_attr($post_data['selected_date']) ): '';
			$set_next_date = date( $format, strtotime($selected_date));

			return $set_next_date;

		}

		/**
		 * Update next payment date for the subscription and add order note on admin dashboard
		 * @param $posted_data
		 * @param $subscription_id
		 * @return void
		 */
		public static function next_payment($posted_data, $subscription_id ) {

			if ( ! WCS_Next_Payment_Settings::is_next_payment_box_enabled_in_settings() ) {
				return;
			}

			$set_next_date = self::get_next_payment_date($posted_data);
			// Get the subscription
			$subscription  = wcs_get_subscription( $subscription_id );
			// Set the next payment date for the subscription
			$subscription->update_dates( array( 'next_payment' => $set_next_date ) );
			// Add order note in admin dashboard
			$note = sprintf( __( 'Next payment: date updated to %s', 'wc-sub-next-payment' ), $set_next_date );
			$subscription->add_order_note( $note );
			// Save changes
			$subscription->save();

		}

		/**
		 * @return self
		 */
		public static function get_instance()
		{
			if (!isset(self::$instance) || is_null(self::$instance))
				self::$instance = new self();

			return self::$instance;
		}


	}
}
WCS_Next_Payment_Helper::get_instance();
