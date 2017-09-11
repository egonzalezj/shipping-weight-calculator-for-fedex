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

add_action('woocommerce_after_shop_loop_item', 'calculateShippingWeight', 10);

/* Weight types */
$weight = 0.0;
$volumetricWeight = 0.0;
$shippingWeight = 0.0;

/* Dimensions */
$length = 0.0;
$width = 0.0;
$height = 0.0;

function calculateShippingWeight() {
  global $product, $weight;
  $weight = floatval($product -> get_weight());
  echo "<script>console.log('$w');</script>";
  calculateVolumetricWeight();
}

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

function calculateVolumetricWeight() {
  global $volumetricWeight, $length, $width, $height;
  if(setDimensions()) {
    $volumetricWeight = $length*$width*$height/5000.0;
  }
  else {
    $volumetricWeight = 0.0;
  }
}

function compareWeights() {
  global $weight, $volumetricWeight, $shippingWeight;
  if(!$weight) {
    $weight = calculateVolumetricWeight();
  }
  if($weight > $volumetricWeight) {
    $shippingWeight = $weight;
  } else if($volumetricWeight > $weight) {
    $shippingWeight = $volumetricWeight;
  } else {
    $shippingWeight = $weight;
  }
}

?>
