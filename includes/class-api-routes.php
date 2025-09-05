<?php
/**
 * Class to handle API routes for PaperOS integration.
 */
class IncPros_PaperOS_API_Routes {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register the REST API routes.
     */
    public function register_routes() {
        register_rest_route( 'incpros/v1', '/intake', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'handle_intake' ),
        ) );

        register_rest_route( 'incpros/v1', '/webhooks/paperos', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'handle_webhook' ),
        ) );
    }

    /**
     * Handle the /intake endpoint.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_intake( $request ) {
        $payload = $request->get_json_params();

        $required_keys = array( 'product', 'customer', 'entity', 'paperos', 'commerce' );
        foreach ( $required_keys as $key ) {
            if ( ! isset( $payload[ $key ] ) ) {
                return new WP_Error( 'missing_key', "Missing required key: {$key}", array( 'status' => 400 ) );
            }
        }

        error_log( print_r( $payload, true ) );

        return new WP_REST_Response( $payload, 200 );
    }

    /**
     * Handle the /webhooks/paperos endpoint.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_webhook( $request ) {
        $signature = $request->get_header( 'x_paperos_signature' );
        $body      = $request->get_body();
        $hash      = hash_hmac( 'sha256', $body, PAPEROS_WEBHOOK_SECRET );

        if ( ! hash_equals( $hash, $signature ) ) {
            return new WP_Error( 'invalid_signature', 'Invalid signature.', array( 'status' => 401 ) );
        }

        error_log( print_r( $request->get_json_params(), true ) );

        return new WP_REST_Response( 'Webhook received.', 200 );
    }
}