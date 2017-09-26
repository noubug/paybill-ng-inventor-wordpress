<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//require INVENTOR_PAYBILL_DIR . 'libraries/PayPal-PHP-SDK/vendor/autoload.php';


/**
 * Class Inventor_PayPal_Logic
 *
 * @class Inventor_PayPal_Logic
 * @package Inventor_PayPal/Classes
 * @author Pragmatic Mates
 */
class Inventor_PayBill_Logic {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'process_payment' ), 9999 );
        add_action( 'init', array( __CLASS__, 'process_result' ), 9999 );
        add_filter( 'inventor_payment_gateways', array( __CLASS__, 'payment_gateways' ) );
    }

    public static function is_active() {

        $paybill_inventor_organisation_code = get_theme_mod( 'inventor_paybill_organisation_code', null );

        $paybill_inventor_secret_key = get_theme_mod( 'inventor_paybill_secret_key', null );

        $paybill_inventor_public_key = get_theme_mod( 'inventor_paybill_public_key', null );

        $paybill_inventor_payment_charge_bearer =
            get_theme_mod( 'inventor_paybill_payment_charge_bearer', null );

        $paybill_inventor_organisation_transaction_charge =
            get_theme_mod( 'inventor_paybill_organisation_transaction_charge', null );

        $paybill_inventor_paybill_sub_account_code =
            get_theme_mod( 'inventor_paybill_sub_account_code', null );

        $paybill_inventor_paybill_live_mode =
            get_theme_mod( 'inventor_paybill_live_mode', null );

        return ( ! empty( $paybill_inventor_organisation_code ) && ! empty( $paybill_inventor_secret_key ) &&

            ! empty( $paybill_inventor_public_key ) );
    }

    public static function payment_gateways( $gateways ) {
        if (  self::is_active() ) {

            $gateways['paybill-online-gateway'] = array(
                'id'      => 'paybill-online-gateway',
                'title'   => __( 'Paybill Online Gateway', 'inventor-paybill' ),
                'proceed' => true,
                'content' => Inventor_Template_Loader::load( 'paybill/email-form', array(), INVENTOR_PAYBILL_DIR ),
            );
        }

        return $gateways;
    }

    public static function process_payment() {

        if ( ! isset( $_POST['process-payment'] ) ) {
            return;
        }

        $gateway = ! empty( $_POST['payment_gateway'] ) ? $_POST['payment_gateway'] : null;

        if(empty( $_POST['email'] ) || $_POST['email'] = '') {
            return;
        }
        $settings = array(
            'payment_type'  => ! empty( $_POST['payment_type'] ) ? $_POST['payment_type'] : '',
            'object_id'  	=> ! empty( $_POST['object_id'] ) ? $_POST['object_id'] : '',
            'email'  => ! empty( $_POST['email'] ) ? $_POST['email'] : '',
        );

        $settings['billing_details'] = Inventor_Billing::get_billing_details_from_context( $_POST );

        $data = self::get_data( $settings['payment_type'], $settings['object_id'] );

        $order_id =uniqid();

        $custom_data = array("payment_type" => $settings['payment_type'],
                            "object_id" => $settings['object_id'],
                            "order_id" => $order_id,
                            "email" => ! empty( $_POST['email'] ) ? $_POST['email'] : '', );

        $data1 = array_merge($data, $custom_data);

        $full_payload = json_encode($data1);

        switch ( $gateway ) {
            case 'paybill-online-gateway':
                wp_safe_redirect( get_permalink( get_page_by_title('Inventor-Processing-PaybillNG') )."&payload_data=". urlencode($full_payload));
                exit();
                break;
        }
    }

    public static function get_data( $payment_type, $object_id ) {
        if ( empty( $payment_type ) || empty( $object_id ) ) {
            return false;
        }

        if ( ! in_array( $payment_type, apply_filters( 'inventor_payment_types', array() ) ) ) {
            return false;
        }

        $payment_data = apply_filters( 'inventor_prepare_payment', array(), $payment_type, $object_id );

        $data = array(
            'title'             => $payment_data['action_title'],
            'description'       => $payment_data['description'],
            'price'             => $payment_data['price'],
            'price_formatted'   => Inventor_Price::format_price( $payment_data['price'] ),
            'currency_code'     => Inventor_Price::default_currency_code(),
            'currency_symbol'   => Inventor_Price::default_currency_symbol(),
        );

        return $data;
    }

    public static function get_supported_currencies( $payment ) {
        if ( $payment == 'account' ) {
            return array( "NGN");
        }

        return array();
    }

    public static function get_paybill_context() {

        $paybill_inventor_organisation_code = get_theme_mod( 'inventor_paybill_organisation_code', null );

        $paybill_inventor_secret_key = get_theme_mod( 'inventor_paybill_secret_key', null );

        $paybill_inventor_public_key = get_theme_mod( 'inventor_paybill_public_key', null );

        $paybill_inventor_payment_charge_bearer =
            get_theme_mod( 'inventor_paybill_payment_charge_bearer', null );

        $paybill_inventor_organisation_transaction_charge =
            get_theme_mod( 'inventor_paybill_organisation_transaction_charge', null );

        $paybill_inventor_paybill_sub_account_code =
            get_theme_mod( 'inventor_paybill_sub_account_code', null );

        $paybill_inventor_paybill_live_mode =
            get_theme_mod( 'inventor_paybill_live_mode', null );


        if ( empty( $paybill_inventor_secret_key ) || empty( $paybill_inventor_public_key ) ) {
            return false;
        }


        $paybill_keys = array("organisation_code",
            "secret_key",
            "public_key",
            "payment_charge_bearer",
            "organisation_transaction_charge",
            "paybill_sub_account_code",
            "paybill_live_mode");

        $paybill_values = array($paybill_inventor_organisation_code,
            $paybill_inventor_secret_key,
            $paybill_inventor_public_key,
            $paybill_inventor_payment_charge_bearer,
            $paybill_inventor_organisation_transaction_charge,
            $paybill_inventor_paybill_sub_account_code,
            $paybill_inventor_paybill_live_mode);


         $full_array = array_combine($paybill_keys, $paybill_values);

        return $full_array;
    }

}

Inventor_PayBill_Logic::init();