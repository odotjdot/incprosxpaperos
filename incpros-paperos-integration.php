<?php
/**
 * Plugin Name: IncPros PaperOS Integration
 * Description: Integrates PaperOS with WordPress.
 * Version: 1.0.0
 * Author: Roo
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define the webhook secret.
define( 'PAPEROS_WEBHOOK_SECRET', 'a_very_secure_randomly_generated_string' );

// Include the API routes class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-api-routes.php';

// Instantiate the API routes class.
new IncPros_PaperOS_API_Routes();

// Include the form shortcode class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-form-shortcode.php';

// Instantiate the form shortcode class.
new IncPros_PaperOS_Form_Shortcode();