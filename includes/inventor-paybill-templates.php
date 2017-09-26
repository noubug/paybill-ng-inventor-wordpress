<?php
class PayBill_Generate_Pages {

    /** Renders the contents of the given template to a string and returns it.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    private static function get_template_html( $template_name, $attributes = null ){
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'personalize_login_before_' . $template_name );

        require( INVENTOR_PAYBILL_DIR .'templates/' . $template_name . '.php');

        do_action( 'personalize_login_after_' . $template_name );

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public static function render_payment_page(  $attributes, $content = null ) {

        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        return self::get_template_html( 'inventor-paybill-process-payment_page', $attributes );
    }


    public static function render_page_after_payment(  $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        return self::get_template_html( 'inventor-paybill-get-payment', $attributes );
    }

    public function __construct() {

        self::render_payment_page($attributes = "", $content = null);
        self::render_page_after_payment($attributes = "", $content = null);
    }
}
