<?php
/**
 * Plugin Name: Braintree For WooCommerce Extension
 * Plugin URI: https://github.com/awesem-agency/awesem-sm-braintree-woo-paymenet-gateway-customisation
 * Description: Extension to plugin "Braintree For WooCommerce" <= v3.2.23. Filters order transaction args before processing transaction. Rounds formats shipping amount to two decimal places.
 * Version: 1.0
 * Author: Martins Luters
 * Author URI: https://github.com/martinsluters
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'wc_braintree_order_transaction_args', 'ml_btfwe_filter_transaction_args', 10, 3 );
function ml_btfwe_filter_transaction_args( $args, $order, $wc_settings_api_class_id ) {
	if ( array_key_exists( 'shippingAmount', $args ) && is_numeric( $args['shippingAmount'] ) ) {
		$must_be_string = is_string( $args['shippingAmount'] );
		$args['shippingAmount'] = ml_btfwe_round_shipping_amount( $args['shippingAmount'] );
		$args['shippingAmount'] = ml_btfwe_maybe_stringify( $must_be_string, $args['shippingAmount'] );
	}
	return $args;
}

function ml_btfwe_round_shipping_amount( $amount ) {
	return wc_format_decimal( $amount, wc_get_price_decimals() );
}

function ml_btfwe_maybe_stringify( $must_be_string, $amount ) {
	if ( $must_be_string ) {
		return (string) $amount;
	}

	return $amount;
}
