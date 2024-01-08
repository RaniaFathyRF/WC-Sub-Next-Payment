<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WCS_Next_Payment_Front')) {
    class WCS_Next_Payment_Front
    {
        private static $instance;

        const NEXT_PAYMENT = 'next_payment';

        /**
         * WCS_Next_Payment_Front constructor.
         */
        public function __construct()
        {

            // Enqueue scripts
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 20);
            // Add next payment button
            add_filter('wcs_view_subscription_actions', array($this, 'add_next_payment_button'), 20, 2);
            // Add next payment popup content
            add_action('woocommerce_subscription_after_actions', array($this, 'add_next_payment_popup_content'), 20);
            //Add filter to next payment display loader
            add_filter('wc_sub_box_extra_action_display_loader_html', array($this, 'wcs_next_payment_display_loader'), 20);
            // Add AJAX handler for next payment confirmation
            add_action('wp_ajax_handle_confirm_next_payment', array($this, 'handle_confirm_next_payment'), 20);

        }

        /**
         * Enqueue scripts
         * @return void
         */
        public function enqueue_scripts()
        {
            // If the next payment checkbox is checked, show the button
            if (!WCS_Next_Payment_Settings::is_next_payment_box_enabled_in_settings())
                return;

            $subscription_id = get_query_var('view-subscription');
            $subscription = wcs_get_subscription($subscription_id);
            if (!WC_Sub_Next_Payment_Utility::is_wcs_subscription($subscription))
                return;
            //Enqueue micromodal scripts
            WC_Sub_Next_Payment_Utility::enqueue_micromodal_scripts();
            //Enqueue datepicker scripts
            WCS_Next_Payment_Utility::enqueue_datepicker_scripts();
            //Enqueue popup next payment style
            wp_enqueue_style('wc-sub-box-next-payment-popup-style', WCS_NEXT_PAYMENT_URL . 'assets/css/wcs-next-payment-popup.css', array(), WCS_NEXT_PAYMENT_ASSETS_VERSION);
            //Enqueue next payment popup next payment script
            wp_enqueue_script('wc-sub-box-next-payment-popup-script', WCS_NEXT_PAYMENT_URL . 'assets/js/wcs-next-payment-popup.js', array('jquery', 'wc_sub_box_extra_actions-micromodal', 'wc_sub_box_extra_actions-datepicker'), WCS_NEXT_PAYMENT_ASSETS_VERSION, true);
            wp_localize_script('wc-sub-box-next-payment-popup-script', 'next_payment_object', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('next_payment_nonce')));

        }

        /**
         * Add next payment button
         * @param $actions
         * @param $subscription
         * @return mixed
         */
        public function add_next_payment_button($actions, $subscription)
        {
            // If the next payment checkbox is checked, show the button
            if (!WCS_Next_Payment_Settings::is_next_payment_box_enabled_in_settings())
                return $actions;

            if (!WC_Sub_Next_Payment_Utility::is_wcs_subscription($subscription))
                return $actions;

            $url = esc_url(wc_get_account_endpoint_url('subscriptions'));
            $actions[self::NEXT_PAYMENT] = array(
                'url' => $url,
                'name' => __('Next Payment', 'wc-sub-next-payment'),
            );
            return $actions;

        }

        /**
         * Add next payment popup content
         * @param $subscription
         * @return void
         */
        public function add_next_payment_popup_content($subscription)
        {
            // If the next payment checkbox is checked, show the button
            if (!WCS_Next_Payment_Settings::is_next_payment_box_enabled_in_settings())
                return;

            if (!WC_Sub_Next_Payment_Utility::is_wcs_subscription($subscription))
                return;
            //Include the popup template
            include WCS_NEXT_PAYMENT_PATH . 'templates/wcs-next-payment-popup.php';

        }

        public function wcs_next_payment_display_loader($display_loader = false)
        {
            if (WCS_Next_Payment_Settings::is_next_payment_box_enabled_in_settings())
                return true;
            return $display_loader;
        }

        /**
         * Handle next payment confirmation button upon clicking it in the popup
         * @return void
         */
        public function handle_confirm_next_payment()
        {

            // Check the nonce for security
            check_ajax_referer('next_payment_nonce', 'nonce');
            // Get the subscription ID
            $subscription_id = $_POST['subscription_id'] ?? '';
            // Update the next payment date based on his Subscription ID
            WCS_Next_Payment_Helper::next_payment($_POST, $subscription_id);
            // Return success
            wp_send_json_success("success");

        }

        /**
         * Get instance
         * @return self
         */
        public static function get_instance()
        {

            if (!isset(self::$instance) || is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;

        }
    }
}
WCS_Next_Payment_Front::get_instance();