<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Inventor_Customizations_PayPal
 *
 * @class Inventor_PayPal_Customizations_PayPal
 * @package Inventor_PayPal/Classes/Customizations
 * @author Pragmatic Mates
 */
class Inventor_PayBill_Customizations_Paybill {
    /**
     * Initialize customization type
     *
     * @access public
     * @return void
     */
    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'customizations' ) );
    }

    /**
     * Customizations
     *
     * @access public
     * @param object $wp_customize
     * @return void
     */

    public static function customizations( $wp_customize ) {

        $wp_customize->add_section( 'inventor_paybill', array(
            'title'     => __( 'Inventor PayBill.NG', 'inventor_paybill' ),
            'priority'  => 1,
        ) );

        // PayBill Organisation Code
        $wp_customize->add_setting( 'inventor_paybill_organisation_code', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('inventor_paybill_organisation_code', array(
            'label'         => __( 'Organisation Code', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_organisation_code',
        ));
        // PayBill Organisation Code


        // PayBill Secret Key
        $wp_customize->add_setting( 'inventor_paybill_secret_key', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('inventor_paybill_secret_key', array(
            'label'         => __( 'Secret Key', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_secret_key',
        ));
        // PayBill Secret Key


        //PayBill Public Key
        $wp_customize->add_setting( 'inventor_paybill_public_key', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control( 'inventor_paybill_public_key', array(
            'label'         => __( 'Public Key', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_public_key',
        ));
        //PayBill Public Key

        //PayBill Payment Charge Bearer
        $wp_customize->add_setting( 'inventor_paybill_payment_charge_bearer', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('inventor_paybill_payment_charge_bearer', array(
            'label'         => __( 'Payment Charge Bearer', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_payment_charge_bearer',

        ));
        //PayBill Payment Charge Bearer

        $wp_customize->add_setting( 'inventor_paybill_organisation_transaction_charge', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('inventor_paybill_organisation_transaction_charge', array(
            'label'         => __( 'Organisation Transaction Charge', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_organisation_transaction_charge',

        ));

        //Inventor PayBill Sub Account Code
        $wp_customize->add_setting( 'inventor_paybill_sub_account_code', array(
            'default'           => null,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'inventor_paybill_sub_account_code', array(
            'label'         => __( 'Sub Account', 'inventor-paybill' ),
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_sub_account_code',
        ) );


        //Inventor PayBill Live Mode
        $wp_customize->add_setting( 'inventor_paybill_live_mode', array(
            'default'           => false,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'inventor_paybill_live_mode', array(
            'label'         => __( 'Live Mode', 'inventor_paybill' ),
            'type'          => 'checkbox',
            'section'       => 'inventor_paybill',
            'settings'      => 'inventor_paybill_live_mode',
        ) );
    }
}

Inventor_PayBill_Customizations_Paybill::init();