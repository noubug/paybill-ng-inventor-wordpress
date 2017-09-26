<?php
/**
 * Created by PhpStorm.
 * User: kobi
 * Date: 21/04/2017
 * Time: 12:06 PM
 */

class InventorPayBillNGDeactivatePlugin
{
    public static function plugin_deactivated()
    {
        include INVENTOR_PAYBILL_DIR. 'includes/paybillng_pages_to_add.php';
        $page_definitions = InventorPaybillngPagesToAdd::paybillng_pages();

        foreach ( $page_definitions as $slug => $page ) {
            $page = get_page_by_title( $page['title'] );
            wp_delete_post($page->ID, true);
        }

        flush_rewrite_rules();
    }
}