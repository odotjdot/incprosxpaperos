<?php
/**
 * Class to handle WooCommerce integration.
 */
class IncPros_PaperOS_WooCommerce_Integration {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'woocommerce_payment_complete', array( $this, 'handle_payment_complete' ) );
        add_action( 'wp_footer', array( $this, 'add_checkout_script' ) );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_form_data_to_order_item' ), 10, 4 );
    }

    /**
     * Handle the woocommerce_payment_complete action.
     *
     * @param int $order_id The ID of the order.
     */
    public function handle_payment_complete( $order_id ) {
        $order = wc_get_order( $order_id );
        foreach ( $order->get_items() as $item_id => $item ) {
            $form_data = $item->get_meta( '_incpros_form_data' );
            if ( $form_data ) {
                // Call the PaperOS API with this data.
                require_once plugin_dir_path( __FILE__ ) . 'class-paperos-api-client.php';
                IncPros_PaperOS_API_Client::send_intake_data( $form_data );
            }
        }
    }

    public function add_checkout_script() {
        if ( is_checkout() ) {
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const getCookie = (name) => {
                        const value = `; ${document.cookie}`;
                        const parts = value.split(`; ${name}=`);
                        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
                    };

                    const formData = getCookie('incpros_form_data');
                    if (formData) {
                        jQuery(document.body).on('checkout_place_order', function(event, data) {
                            data.incpros_form_data = formData;
                        });
                    }
                });
            </script>
            <?php
        }
    }

    public function add_form_data_to_order_item( $item, $cart_item_key, $values, $order ) {
        if ( isset( $_POST['incpros_form_data'] ) ) {
            $item->add_meta_data( '_incpros_form_data', json_decode( stripslashes( $_POST['incpros_form_data'] ), true ) );
        }
    }
}