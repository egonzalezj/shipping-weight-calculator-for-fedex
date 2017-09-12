<?php
  /*
  Plugin Name: Woocommerce Shipping Weight Calculator
  Plugin URI: http://github.com/egonzalezj
  Description: Simple plugin to set the shipping weight according to volumetric weight or real weight.
  Author: Elisabet González
  Version: 1.0
  Author URI: http://github.com/egonzalezj
  */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly

  if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return; // Check if WooCommerce is active

  //include_once( WC_ABSPATH . '/includes/abstracts/abstract-wc-product.php' );
  //add_action('init', calculateShippingWeight);
  add_action('woocommerce_after_shop_loop_item', 'calculateShippingWeight', 10);

  /**
   * Get products weight from Woocommerce API.
   * @return N/A.
   */
  function calculateShippingWeight() {
    /* Weight types */
    $weight = 0.0;
    $volumetricWeight = 0.0;
    $shippingWeight = 0.0;

    /* Dimensions */
    $length = 0.0;
    $width = 0.0;
    $height = 0.0;

    class WC_Shipping_Product extends WC_Product {
      function __construct() {
        global $data;
        parent::__construct();
        //$this -> data[] = "shippingWeight";
        //$this -> data[] = "volumetricWeight";
        array_push($data, "shippingWeight", "volumetricWeight");
      }

      function setWeight($shippingWeight) {
        $this -> data['shippingWeight'] = $shippingWeight;
        echo "<script>console.log('$shippingWeight');</script>";
      }

    }

    $shippingProduct = new WC_Shipping_Product();

    global $product, $weight;
    $weight = floatval($product -> get_weight());
    echo "<script>console.log('$weight');</script>";
    calculateVolumetricWeight();
  }

  /*class first_class {
    protected $i_am_protected = array(
      'a' => 'b',
      'options' => array()
    );
  }

  class second_class extends first_class {
    function setMyOptions($options) {
      $this->i_am_protected['options'] = $options;
    }
  }

  $second_class = new second_class();
  $second_class -> setMyOptions(
    array('foo' => 'bar')
  );
  */

  /**
   * Calculate and set volumetric weight of each product.
   * @return N/A.
   */
  function calculateVolumetricWeight() {
    global $volumetricWeight, $length, $width, $height;
    if(setDimensions()) {
      $volumetricWeight = $length*$width*$height/5000.0;
    }
    else {
      $volumetricWeight = 0.0;
    }
    compareWeights();
  }

  /**
   * Check which weight is heavier if volumetric or real weight.
   * @return N/A.
   */
  function compareWeights() {
    global $weight, $volumetricWeight, $shippingWeight;
    echo "<script>console.log('Volumetric Weight:".$volumetricWeight.", Real Weight:".$weight."');</script>";
    if(!$weight) {
      $weight = calculateVolumetricWeight();
    }
    if($weight > $volumetricWeight) {
      $shippingWeight = $weight;
    } else if($weight < $volumetricWeight) {
      $shippingWeight = $volumetricWeight;
    } else {
      $shippingWeight = $weight;
      echo "<script>console.log('Volumetric and Real Weight are equal');</script>";
    }
    $shippingProduct -> setWeight($shippingWeight);
  }

  /**
   * Check if the product has the dimensions set.
   * @return boolean value.
   */
  function setDimensions() {
    global $product;
    if($product -> has_dimensions()) {
      global $length, $width, $height;
      $length = floatval($product -> get_length());
      $width = floatval($product -> get_width());
      $height = floatval($product -> get_height());
      echo "<script>console.log('".$length." x ".$width." x ".$height."');</script>";
      return true;
    } else return false;
  }
?>
