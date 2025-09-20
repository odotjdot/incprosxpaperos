<?php
/**
 * Class to handle PaperOS API communication.
 */
class IncPros_PaperOS_API_Client {

    /**
     * Send the intake data to the PaperOS API.
     *
     * @param array $payload The validated form payload.
     */
    public static function send_intake_data( $payload ) {
        $url = 'https://demo.paperos.net/api/v1/accounts/init';

        $headers = array(
            'Content-Type'    => 'application/json',
            'Authorization'   => 'Bearer your-paperos-api-key',
            'Idempotency-Key' => $payload['idempotency_key'],
        );

        $response = wp_remote_post( $url, array(
            'headers' => $headers,
            'body'    => json_encode( $payload ),
        ) );

        if ( is_wp_error( $response ) ) {
            error_log( 'PaperOS API Error: ' . $response->get_error_message() );
        } else {
            error_log( 'PaperOS API Success: ' . wp_remote_retrieve_body( $response ) );
        }
    }
}