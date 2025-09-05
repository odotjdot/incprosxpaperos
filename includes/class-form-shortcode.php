<?php
/**
 * Class to handle the intake form shortcode.
 */
class IncPros_PaperOS_Form_Shortcode {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Register the shortcode.
     */
    public function register_shortcode() {
        add_shortcode( 'incpros_intake_form', array( $this, 'render_form' ) );
    }

    /**
     * Render the intake form.
     *
     * @return string The form HTML.
     */
    public function render_form() {
        ob_start();
        ?>
        <div id="incpros-intake-form-wrapper">
            <form id="incpros-intake-form">
                <h2>Enterprise Establishment (EE)</h2>

                <h3>Customer Information</h3>
                <label for="customer_name">Name:</label>
                <input type="text" id="customer_name" name="customer[name]" required>

                <label for="customer_email">Email:</label>
                <input type="email" id="customer_email" name="customer[email]" required>

                <h3>Entity Information</h3>
                <label for="entity_name">Company Name:</label>
                <input type="text" id="entity_name" name="entity[name]" required>

                <label for="entity_type">Entity Type:</label>
                <select id="entity_type" name="entity[type]">
                    <option value="llc">LLC</option>
                    <option value="corp">Corporation</option>
                </select>

                <br><br>
                <button type="submit">Submit</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'incpros_intake_form' ) ) {
            wp_enqueue_script(
                'incpros-form-handler',
                plugin_dir_url( __FILE__ ) . '../public/js/form-handler.js',
                array(),
                '1.0.0',
                true
            );
        }
    }
}