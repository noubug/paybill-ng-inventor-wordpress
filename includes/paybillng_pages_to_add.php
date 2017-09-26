<?php
/**
 * Created by PhpStorm.
 * User: kobi
 * Date: 21/04/2017
 * Time: 12:06 PM
 */
class InventorPaybillngPagesToAdd
{
    public static function paybillng_pages() {

        $pages_definitions = array(
            'load-paybillng' => array(
                'title' => __( 'Inventor-PayBill.NG-Result', 'inventor-paybill' ),
                'content' => '[inventor-paybill-get-payment]'
            ),
            'processing-paybillng' => array(
                'title' => __( 'Inventor-Processing-PaybillNG', 'inventor-paybill' ),
                'content' => '[inventor-paybill-process-payment]'
            ),
            'successful-paybillng' => array(
                'title' => __( 'Successful-PaybillNG', 'inventor-paybill' ),
                'content' => '[inventor-paybill-successful-payment]'
            ),
            'failed-paybillng' => array(
                'title' => __( 'Failed-PaybillNG', 'inventor-paybill' ),
                'content' => '[inventor-paybill-failed-payment]'
            )
        );

        return $pages_definitions;
    }

}