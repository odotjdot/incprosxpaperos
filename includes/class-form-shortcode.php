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
                <h2 id="wizard-step-title">Step 1</h2>
                <div id="wizard-step-content">
                    <div class="wizard-step" id="wizard-step-1">
                        <?php echo $this->render_step_1(); ?>
                    </div>
                    <div class="wizard-step" id="wizard-step-2" style="display: none;">
                        <?php echo $this->render_step_2(); ?>
                    </div>
                    <div class="wizard-step" id="wizard-step-3" style="display: none;">
                        <?php echo $this->render_step_3(); ?>
                    </div>
                </div>
                <div id="wizard-navigation">
                    <button type="button" id="wizard-back-btn" style="display: none;">Back</button>
                    <button type="button" id="wizard-next-btn">Next</button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 1 of the form.
     *
     * @return string The step 1 HTML.
     */
    public function render_step_1() {
        return '<div>Step 1 Content</div>';
    }

    /**
     * Render step 2 of the form.
     *
     * @return string The step 2 HTML.
     */
    public function render_step_2() {
        return '<div>Step 2 Content</div>';
    }

    /**
     * Render step 3 of the form.
     *
     * @return string The step 3 HTML.
     */
    public function render_step_3() {
        return '<div>Step 3 Content</div>';
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