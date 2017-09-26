<?php
/**
 * Created by PhpStorm.
 * User: kobi
 * Date: 25/09/2017
 * Time: 9:23 PM
 */
class VerifyPayment {

    function verifyUserPayment()
    {

        $data_ref = filter_var($_GET['ref'], FILTER_SANITIZE_STRING);

        $data_order_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

        $payment_id = filter_var($_GET['payment_type'], FILTER_SANITIZE_STRING);

        $object_id = filter_var($_GET['object_id'], FILTER_SANITIZE_STRING);


        $data = "";
        if (filter_var($data_ref, FILTER_SANITIZE_STRING) == '' || filter_var($data_ref, FILTER_SANITIZE_STRING) == null || !filter_var($data_ref, FILTER_SANITIZE_STRING)) {

            wp_safe_redirect(home_url());

        } else {

            $data = esc_attr($data_ref);
        }


        if (filter_var($data_order_id, FILTER_SANITIZE_STRING) == '' || filter_var($data_order_id, FILTER_SANITIZE_STRING) == null || !filter_var($data_order_id, FILTER_SANITIZE_STRING)) {

            wp_safe_redirect(home_url());

        } else {

            $order_id = esc_attr($data_order_id);
        }


        try {
            $payload = Inventor_PayBill_Logic::get_paybill_context();
            $secret_key = $payload['secret_key'];

            $url = "https://paybill.ng/api/paynou/transaction/status/";

            $args = array(
                'timeout' => 200,
                'httpversion' => '1.1',
                'headers' => array(
                    'Authorization' => 'Bearer '.$secret_key)
            );

            $my_response = wp_remote_get($url.$data, $args);

            if (!empty($my_response)) {

                if( is_wp_error( $my_response ) ) {
                    echo $my_response->get_error_message();
                    $success = false;

                    $is_valid = false;

                    if ( ! $is_valid ) {
                        $success = false;
                    }

                    $this->transactionResult($success, $is_valid, $payment_id, $object_id);

                }

                $my_response = json_decode($my_response['body']);


                if ($my_response->data->status == "SUCCESSFUL") {

                    // validate payment
                    $success = true;

                    $is_valid = true;

                    if ( ! $is_valid ) {
                        $success = true;
                    }

                    $this->transactionResult($success, $is_valid, $payment_id, $object_id);

                }
                elseif (($my_response->data->status === "error") || ($my_response->data->status === "CANCELED")) {

                    $success = false;

                    $is_valid = false;

                    if ( ! $is_valid ) {
                        $success = false;
                    }

                    $this->transactionResult($success, $is_valid, $payment_id, $object_id);
                }
                else {

                    $success = false;

                    $is_valid = false;

                    if ( ! $is_valid ) {
                        $success = false;
                    }

                    $this->transactionResult($success, $is_valid, $payment_id, $object_id);

                }
            }

        }
        catch (ErrorException $e)
        {
            $success = false;

            $is_valid = false;

            if ( ! $is_valid ) {
                $success = false;
            }

            $this->transactionResult($success, $is_valid, $payment_id, $object_id);

        }
    }

    function transactionResult($success, $is_valid, $payment_id, $object_id) {
        // billing_details
        $billing_details = Inventor_Billing::get_billing_details_from_context( $_GET );

        // hook inventor action
       do_action( 'inventor_payment_processed', $success, $_GET['gateway'], $_GET['payment_type'], $_GET['paymentId'], $_GET['object_id'], $_GET['price'], $_GET['currency_code'], $_GET['user_id'], $billing_details );

        // handle payment
        if ( $success ) {
            if( ! $is_valid ) {
                Inventor_Utilities::show_message( 'danger', __( 'Payment is invalid.', 'inventor-paybill' ) );
            } else if ( ! in_array( $_GET['payment_type'], apply_filters( 'inventor_payment_types', array() ) ) ) {
                Inventor_Utilities::show_message( 'danger', __( 'Undefined payment type.', 'inventor-paybill' ) );
            } else {
                Inventor_Utilities::show_message( 'success', __( 'Payment has been successful.', 'inventor-paybill' ) );
            }
        } else {
            Inventor_Utilities::show_message( 'danger', __( 'Payment failed, canceled or is invalid.', 'inventor-paybill' ) );
        }

        // after payment page
        $redirect_url = Inventor_Utilities::get_after_payment_url( $payment_id, $object_id);

        wp_redirect( $redirect_url );
        exit();
    }

}

$load = new VerifyPayment();
$load->verifyUserPayment();