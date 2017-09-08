<?php
/*
  Plugin Name: Woocommerce Shipping Weight Calculator
  Plugin URI: http://github.com/egonzalezj
  Description: Simple plugin to set the shipping weight according to volumetric weight or real weight.
  Author: Elisabet González
  Version: 1.0
  Author URI: http://github.com/egonzalezj
*/


/* Weight types */
$weight = 0.0;
$volumetricWeight = 0.0;
$shippingWeight = 0.0;

/* Dimensions */
$length = 0.0;
$width = 0.0;
$height = 0.0;

$weight = floatval(WC_Product::get_weight());
calculateVolumetricWeight();

function setDimensions() {
  if(WC_Product::has_dimensions()) {
    global $length, $width, $height;
    $length = floatval(WC_Product::get_length());
    $width = floatval(WC_Product::get_width());
    $height = floatval(WC_Product::get_height());
    echo $length . " x " . $width . " x " . $height . " cm";
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
