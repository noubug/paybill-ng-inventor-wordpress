<?php
/**
 * Created by PhpStorm.
 * User: kobi
 * Date: 24/09/2017
 * Time: 4:14 PM
 */
class ProcessPayBillNGUserPayment {

    function __construct()
    {
        $this->getAllUserData();
    }

    function getAllUserData() {

        try {

            $content = $_GET['payload_data'];
            $data = json_decode(stripslashes($content), true);
            $payload = Inventor_PayBill_Logic::get_paybill_context();

            $price = $data['price'];

            if ($price == null || $price == '') {

                $this->transactionResult(false, false, $data['payment_type'], $data['object_id']);

            }

//        if ($data['currency_code'] != "NGN") {
//
//            $this->transactionResult(true, false, $data['payment_type'], $data['object_id']);
//
//        }

            echo '<script type="text/javascript" src="https://paybill.ng/assets/paynou/js/v1/paynou.inline.min.js"></script>';

            ?>


            <style>
                .loader {
                    margin-top: 30px;
                    border: 16px solid #f3f3f3; /* Light grey */
                    border-top: 16px solid #3498db; /* Blue */
                    border-radius: 50%;
                    width: 120px;
                    height: 120px;
                    animation: spin 2s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .document-title {
                    display: none;
                }


            </style>

            <div align="center">
                <div class="loader"></div>
            </div>


            <script type="text/javascript">

               // $data['price']
                PayBillService.load({
                    'customer_email': '<?php $data['email'] ?>',
                    'amount': 4444,
                    'organization_code': '<?= $payload['organisation_code']  ?>',
                    'organization_unique_reference': '<?= $data['order_id'] ?>',
                    'organization_public_key': '<?= $payload['public_key']  ?>',
                    'currency':'NGN',
                    'onClose': function (ref) {
                        window.location.href = "<?= esc_url( get_permalink( get_page_by_title('Inventor-PayBill.NG-Result') ) ); ?>&id=<?= $data['order_id'] ?>&ref="+ ref+"&payment_type=<?= $data['payment_type'] ?>&object_id=<?= $data['object_id'] ?>";
                    }
                });

            </script>


        <?php


        } catch
        (Exception $e){
            echo $e->getMessage();
        }

    }


    function transactionResult($success, $is_valid, $payment_id, $object_id) {

        // create transaction
        // Inventor_Post_Type_Transaction::create_transaction( $_GET['gateway'], $success, $_GET['user_id'], $_GET['payment_type'], $_GET['paymentId'], $_GET['object_id'], $_GET['price'], $_GET['currency_code'], $params );

        // billing_details
        $billing_details = Inventor_Billing::get_billing_details_from_context( $_GET );

        // hook inventor action
        do_action( 'inventor_payment_processed', $success, $_GET['gateway'], $_GET['payment_type'], $_GET['paymentId'], $_GET['object_id'], $_GET['price'], $_GET['currency_code'], $_GET['user_id'], $billing_details );

        // handle payment
        if ( $success ) {
            if( ! $is_valid ) {
                Inventor_Utilities::show_message( 'danger', __( 'Only currency naira (NGN) is allowed ', 'inventor-paybill' ) );
            }
        }else {

            Inventor_Utilities::show_message( 'danger', __( 'Price Invalid', 'inventor-paybill' ) );
        }

        // after payment page
        $redirect_url = Inventor_Utilities::get_after_payment_url( $payment_id, $object_id);

        wp_redirect( $redirect_url );
        exit();

    }

}

new ProcessPayBillNGUserPayment();

?>