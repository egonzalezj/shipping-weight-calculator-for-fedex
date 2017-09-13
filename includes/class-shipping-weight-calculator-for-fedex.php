<?php
  /**
  * Shipping Weight Calculator for FedEx Integration.
  *
  * @package  WC_Shipping_Product_Integration
  * @category Integration
  * @author   Elisabet Gonzalez
  **/

  /**
  * Check if WC_Integration exists
  **/
  if (!class_exists('WC_Integration')) return:
  class WC_Shipping_Product_Integration extends WC_Integration {
    /**
    * Init and hook in the integration
    **/
    public function __construct() {
      global $woocommerce;
      $this -> id                 = 'shipping-weight-calculator-for-fedex';
      $this -> method_title       = __('Shipping Weight Calcutator for FedEx')
      $this -> method_description = __('Integration to set the shipping weight according to volumetric weight or real weight.', 'shipping-weight-calculator-for-fedex');

      //Load settings
      $this -> init_form_fields();
      $this -> init_settings();

      //Define user set variables
      $this -> api_key  = $this -> get_option('api_key');
      $this -> debug    = $this -> get_option('debug');

      //Actions
      add_action('woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options'));

      //Filters
      add_filter('woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings'));
    }

    /**
    * Initialize integration settings form fields.
    **/
    public function init_form_fields() {
      $this -> form_fields = array(
        'api_key' => array(
                  'title'       => __('API Key', 'shipping-weight-calculator-for-fedex'),
                  'type'        => 'text',
                  'description' => __('Esta es una descripcion'),
                  'desc_tip'    => true,
                  'default'     => ''
        ),
        'debug' => array(
                'title'         => __('Debug Log', 'shipping-weight-calculator-for-fedex'),
                'type'          => 'checkbox',
                'label'         => __('Enable loggin', 'shipping-weight-calculator-for-fedex'),
                'default'       => 'no',
                'description'   => __('Log events such as API requests', 'shipping-weight-calculator-for-fedex')
        ),
      );
    }

    /**
    * Generate button HTMl.
    **/
    public function generate_button_html($key, $data) {
      $field    = $this -> plugin_id . $this -> id . '_' . $key;
      $defaults = array(
        'class' =>
      );
    }
  }
?>
