<?php
/**
 * Plugin Name: Inventor Paybill
 * Version: 1.4.0
 * Description: Adds PayBill.NG Payment Gateway
 * Author: Kobi Slevin/Noubug
 * Author URI: http://noubug.com
 * Plugin URI:
 * Text Domain: inventor-paybill
 * Domain Path: /languages/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! class_exists( 'Inventor_PayBill' ) && class_exists( 'Inventor' ) ) {
    /**
     * Class Inventor_Paybill
     *
     * @class Inventor_PayBill
     * @package Inventor_PayBill
     * @author Pragmatic Mates
     */

    final class Inventor_PayBill {
        const DOMAIN = 'inventor-paybill';
        /**
         * Initialize Inventor_PayBill plugin
         */

        public function __construct() {
            $this->constants();
            $this->includes();
            if ( class_exists( 'Inventor_Utilities' ) ) {
                Inventor_Utilities::load_plugin_textdomain( self::DOMAIN, __FILE__ );
            }
        }

        /**
         * Defines constants
         *
         * @access public
         * @return void
         */
        public function constants() {
            define( 'INVENTOR_PAYBILL_DIR', plugin_dir_path( __FILE__ ) );
        }

        /**
         * Include classes
         *
         * @access public
         * @return void
         */
        public function includes() {
            require_once INVENTOR_PAYBILL_DIR . 'includes/class-inventor-paybill-customizations.php';
            require_once INVENTOR_PAYBILL_DIR . 'includes/class-inventor-paybill-logic.php';
        }

    }

    new Inventor_PayBill();

    function inventor_paybillng_plugin_activated()
    {
        include INVENTOR_PAYBILL_DIR . 'includes/paybillng_activate_plugin.php';
        InventorPayBillNGActivate::plugin_activated();
    }

    register_activation_hook( __FILE__, 'inventor_paybillng_plugin_activated');

    function inventor_paybillng_plugin_deactivated()
    {
        include INVENTOR_PAYBILL_DIR . 'includes/paybillng_deactivate_plugin.php';
        InventorPayBillNGDeactivatePlugin::plugin_deactivated();
    }

    register_deactivation_hook( __FILE__, 'inventor_paybillng_plugin_deactivated');

    Include_once( INVENTOR_PAYBILL_DIR . 'includes/inventor-paybill-templates.php' ) ;

    function load_short_codes() {
        add_action( 'template_redirect', 'pay_bill_ng_result_form_custom_redirect' );
        add_action( 'template_redirect', 'pay_bill_ng_processing_form_custom_redirect' );
        add_shortcode('inventor-paybill-get-payment', array( 'PayBill_Generate_Pages', 'render_page_after_payment' ) );
        add_shortcode('inventor-paybill-process-payment', array( 'PayBill_Generate_Pages', 'render_payment_page' ) );
    }

    add_action( 'init', 'load_short_codes');
}



    function pay_bill_ng_result_form_custom_redirect()
    {
        if (is_page('Inventor-PayBill.NG-Result')) {
            $data_ref = '';

            $data_order_id = '';

            $ref = filter_var($_GET['ref'], FILTER_SANITIZE_STRING);

            $order_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);


            if (filter_var($ref, FILTER_SANITIZE_STRING) == '' || filter_var($ref, FILTER_SANITIZE_STRING) == null || !filter_var($ref, FILTER_SANITIZE_STRING)) {

                wp_safe_redirect(home_url());

            } else {
                $data_ref = esc_attr($ref);
            }


            if (filter_var($order_id, FILTER_SANITIZE_STRING) == '' || filter_var($order_id, FILTER_SANITIZE_STRING) == null || !filter_var($order_id, FILTER_SANITIZE_STRING)) {

                wp_safe_redirect(home_url());

            } else {
                $data_order_id = esc_attr($data_order_id);
            }

            if ($data_ref == null & $data_order_id == null) {
                wp_safe_redirect(home_url());
                exit();
            }

            include INVENTOR_PAYBILL_DIR . 'templates/inventor-paybill-get-payment.php';
        }

    }

    function pay_bill_ng_processing_form_custom_redirect()
    {
        if (is_page('Inventor-Processing-PaybillNG')) {

            //include INVENTOR_PAYBILL_DIR . 'templates/inventor-paybill-process-payment_page.php';
    }
}

