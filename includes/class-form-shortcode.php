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
                <div class="incpros-wizard-steps">
                    <div class="incpros-wizard-step" id="step-1">
                        <?php echo $this->render_step_1(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-2">
                        <?php echo $this->render_step_2(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-3">
                        <?php echo $this->render_step_3(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-4">
                        <?php echo $this->render_step_4(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-5">
                        <?php echo $this->render_step_5(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-6">
                        <?php echo $this->render_step_6(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-7">
                        <?php echo $this->render_step_7(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-8">
                        <?php echo $this->render_step_8(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-9">
                        <?php echo $this->render_step_9(); ?>
                    </div>
                    <div class="incpros-wizard-step" id="step-10">
                        <?php echo $this->render_step_10(); ?>
                    </div>
                </div>
                <div class="incpros-wizard-navigation">
                    <button type="button" id="prevBtn">Back</button>
                    <button type="button" id="nextBtn">Next</button>
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
        ob_start();
        ?>
        <h3>Choose Your Service</h3>
        <label><input type="radio" name="product[code]" value="EE" checked> Enterprise Establishment</label><br>
        <label><input type="radio" name="product[code]" value="PS"> Professional Structure</label><br>
        <label><input type="radio" name="product[code]" value="OC"> Organized Capital</label><br>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 2 of the form.
     *
     * @return string The step 2 HTML.
     */
    public function render_step_2() {
        ob_start();
        ?>
        <h3>Create Your Secure Account</h3>
        <label for="customer_email">Email:</label>
        <input type="email" id="customer_email" name="customer[email]" required>
        <label for="customer_password">Password:</label>
        <input type="password" id="customer_password" name="customer[password]" required>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 3 of the form.
     *
     * @return string The step 3 HTML.
     */
    public function render_step_3() {
        ob_start();
        ?>
        <h3>Tell Us About Your Business</h3>
        <label for="entity_name_1">Desired Company Name (1st choice):</label>
        <input type="text" id="entity_name_1" name="entity[name][0]" required>
        <label for="entity_name_2">Desired Company Name (2nd choice):</label>
        <input type="text" id="entity_name_2" name="entity[name][1]">
        <label for="entity_name_3">Desired Company Name (3rd choice):</label>
        <input type="text" id="entity_name_3" name="entity[name][2]">

        <label for="entity_type">Entity Type:</label>
        <select id="entity_type" name="entity[type]">
            <option value="llc">LLC</option>
            <option value="corp">Corporation</option>
        </select>

        <label for="entity_purpose">Business Purpose:</label>
        <textarea id="entity_purpose" name="entity[purpose]"></textarea>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 4 of the form.
     *
     * @return string The step 4 HTML.
     */
    public function render_step_4() {
        ob_start();
        ?>
        <h3>Set Your Location</h3>
        <label for="entity_location_formation">State of Formation:</label>
        <select id="entity_location_formation" name="entity[location][formation]" required>
            <option value="DE">Delaware</option>
            <option value="WY">Wyoming</option>
            <option value="NV">Nevada</option>
        </select>
        <label><input type="checkbox" id="foreign_qualification_checkbox"> Also file for Foreign Qualification</label>
        <div id="foreign_qualification_states" style="display: none;">
            <label for="entity_location_qualification">States for Qualification:</label>
            <select id="entity_location_qualification" name="entity[location][qualification][]" multiple>
                <option value="CA">California</option>
                <option value="TX">Texas</option>
                <option value="FL">Florida</option>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 5 of the form.
     *
     * @return string The step 5 HTML.
     */
    public function render_step_5() {
        ob_start();
        ?>
        <h3>Add Key People</h3>
        <div id="key_people_wrapper">
            <div class="key-person">
                <label>Full Name: <input type="text" name="entity[members][0][name]"></label>
                <label>Email Address: <input type="email" name="entity[members][0][email]"></label>
                <label>Role: <input type="text" name="entity[members][0][role]"></label>
                <label>Ownership Percentage: <input type="number" name="entity[members][0][ownership]"></label>
                <button type="button" class="remove-person">Remove</button>
            </div>
        </div>
        <button type="button" id="add_person">Add another person</button>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 6 of the form.
     *
     * @return string The step 6 HTML.
     */
    public function render_step_6() {
        ob_start();
        ?>
        <h3>Select Your Documents</h3>
        <h4>Governance</h4>
        <label>Operating Agreement:
            <input type="radio" name="paperos[docs][governance][operating_agreement]" value="have"> Have Now
            <input type="radio" name="paperos[docs][governance][operating_agreement]" value="template"> Need Template
            <input type="radio" name="paperos[docs][governance][operating_agreement]" value="custom"> Need Custom
        </label>
        <h4>Contracts</h4>
        <label>Independent Contractor Agreement:
            <input type="radio" name="paperos[docs][contracts][independent_contractor_agreement]" value="have"> Have Now
            <input type="radio" name="paperos[docs][contracts][independent_contractor_agreement]" value="template"> Need Template
            <input type="radio" name="paperos[docs][contracts][independent_contractor_agreement]" value="custom"> Need Custom
        </label>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 7 of the form.
     *
     * @return string The step 7 HTML.
     */
    public function render_step_7() {
        ob_start();
        ?>
        <h3>List Business Assets</h3>
        <label><input type="checkbox" name="paperos[assets][]" value="trademarks"> Trademarks</label><br>
        <label><input type="checkbox" name="paperos[assets][]" value="copyrights"> Copyrights</label><br>
        <label><input type="checkbox" name="paperos[assets][]" value="patents"> Patents</label><br>
        <label><input type="checkbox" name="paperos[assets][]" value="domain_names"> Domain Names</label><br>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 8 of the form.
     *
     * @return string The step 8 HTML.
     */
    public function render_step_8() {
        ob_start();
        ?>
        <h3>Outline Your Capital Plan</h3>
        <label>Amount to Raise: <input type="number" name="commerce[capital_plan][amount]"></label><br>
        <label>Security Type:
            <select name="commerce[capital_plan][security_type]">
                <option value="safe">SAFE</option>
                <option value="convertible_note">Convertible Note</option>
                <option value="equity">Equity</option>
            </select>
        </label><br>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 9 of the form.
     *
     * @return string The step 9 HTML.
     */
    public function render_step_9() {
        ob_start();
        ?>
        <h3>Confirm Compliance Needs</h3>
        <label><input type="checkbox" name="commerce[compliance][ein]" checked> Employer Identification Number (EIN)</label><br>
        <label><input type="checkbox" name="commerce[compliance][registered_agent]" checked> Registered Agent Service</label><br>
        <?php
        return ob_get_clean();
    }

    /**
     * Render step 10 of the form.
     *
     * @return string The step 10 HTML.
     */
    public function render_step_10() {
        ob_start();
        ?>
        <h3>Review Your Information</h3>
        
        <h4>Service Selection <button type="button" class="edit-step" data-step="1">Edit</button></h4>
        <p><strong>Service:</strong> <span id="review_product_code"></span></p>

        <h4>Account Information <button type="button" class="edit-step" data-step="2">Edit</button></h4>
        <p><strong>Email:</strong> <span id="review_customer_email"></span></p>

        <h4>Business Details <button type="button" class="edit-step" data-step="3">Edit</button></h4>
        <p><strong>Company Name (1st Choice):</strong> <span id="review_entity_name_0"></span></p>
        <p><strong>Company Name (2nd Choice):</strong> <span id="review_entity_name_1"></span></p>
        <p><strong>Company Name (3rd Choice):</strong> <span id="review_entity_name_2"></span></p>
        <p><strong>Entity Type:</strong> <span id="review_entity_type"></span></p>
        <p><strong>Business Purpose:</strong> <span id="review_entity_purpose"></span></p>

        <h4>Location <button type="button" class="edit-step" data-step="4">Edit</button></h4>
        <p><strong>State of Formation:</strong> <span id="review_entity_location_formation"></span></p>
        <p><strong>States for Qualification:</strong> <span id="review_entity_location_qualification"></span></p>

        <h4>Key People <button type="button" class="edit-step" data-step="5">Edit</button></h4>
        <div id="review_entity_members"></div>

        <h4>Documents <button type="button" class="edit-step" data-step="6">Edit</button></h4>
        <p><strong>Operating Agreement:</strong> <span id="review_paperos_docs_governance_operating_agreement"></span></p>
        <p><strong>Independent Contractor Agreement:</strong> <span id="review_paperos_docs_contracts_independent_contractor_agreement"></span></p>

        <h4>Assets <button type="button" class="edit-step" data-step="7">Edit</button></h4>
        <p><strong>Business Assets:</strong> <span id="review_paperos_assets"></span></p>

        <div id="review_capital_plan_section">
            <h4>Capital Plan <button type="button" class="edit-step" data-step="8">Edit</button></h4>
            <p><strong>Amount to Raise:</strong> <span id="review_commerce_capital_plan_amount"></span></p>
            <p><strong>Security Type:</strong> <span id="review_commerce_capital_plan_security_type"></span></p>
        </div>

        <h4>Compliance Needs <button type="button" class="edit-step" data-step="9">Edit</button></h4>
        <p><strong>EIN:</strong> <span id="review_commerce_compliance_ein"></span></p>
        <p><strong>Registered Agent:</strong> <span id="review_commerce_compliance_registered_agent"></span></p>

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