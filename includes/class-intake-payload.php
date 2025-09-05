<?php
/**
 * Data model for the intake payload.
 */
class IncPros_PaperOS_Intake_Payload {

    /**
     * @var array
     */
    public $product = array();

    /**
     * @var array
     */
    public $customer = array();

    /**
     * @var array
     */
    public $entity = array();

    /**
     * @var array
     */
    public $paperos = array();

    /**
     * @var array
     */
    public $commerce = array();

    /**
     * Assemble and return the data as a complete associative array.
     *
     * @return array
     */
    public function to_array() {
        return array(
            'product'  => $this->product,
            'customer' => $this->customer,
            'entity'   => $this->entity,
            'paperos'  => $this->paperos,
            'commerce' => $this->commerce,
        );
    }
}