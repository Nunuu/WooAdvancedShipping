<?php

add_filter('woocommerce_package_rates', 'custom_shipping_costs', 100, 2);

function custom_shipping_costs($rates, $package) {

  global $woocommerce;
  
  $shipping_cost = get_field('kitchen_flat_rate_shipping_cost', 'option');
  $free_class_total = 0;

  $cart_items = $woocommerce->cart->get_cart();
  foreach ( $cart_items as $key => $item ) {
    $item_shipping_class = $item['data']->get_shipping_class();
    if ( $item_shipping_class == '' ) {
      $item_total = $item['data']->get_price() * $item['quantity'];
      $free_class_total += $item_total;
    }
  }

  if ($free_class_total >= get_field('kitchen_free_shipping_minimum_amount', 'option')) {
    foreach ($rates as $rate) {
      $rate->cost -= $shipping_cost;
    }
  }

  return $rates;
}