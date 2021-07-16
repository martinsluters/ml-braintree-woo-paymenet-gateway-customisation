<?php
use WP_Mock\Tools\TestCase;
use WP_Mock;

class RoundingShippingAmountTest extends TestCase {

	const SHIPPING_AMOUNT_KEY = 'shippingAmount';

	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_must_return_args_without_shipping_amount_if_not_originally_there() {
		$this->assertFalse( array_key_exists( self::SHIPPING_AMOUNT_KEY, ml_btfwe_filter_transaction_args( [ 'testarg' => 'yes' ], 1, 1 ) ) );
	}

	public function test_must_return_args_with_shipping_amount_if_originally_there() {
		WP_Mock::userFunction( 'wc_format_decimal', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wc_get_price_decimals', array(
			'times' => 1,
		) );
		$this->assertTrue( array_key_exists( self::SHIPPING_AMOUNT_KEY, ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => 0.5 ], 1, 1 ) ) );
	}

	public function test_must_round_shipping_amount_two_decimals() {

		WP_Mock::userFunction( 'wc_format_decimal', array(
			'times' => 2,
			'return' => 4.13
		) );

		WP_Mock::userFunction( 'wc_get_price_decimals', array(
			'times' => 2,
		) );

		$this->assertSame( [ self::SHIPPING_AMOUNT_KEY => 4.13 ], ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => 4.125 ], 1, 1 ) );
		$this->assertSame( [ self::SHIPPING_AMOUNT_KEY => '4.13' ], ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => '4.125' ], 1, 1 ) );
	}

	public function test_must_not_round_if_shipping_amount_not_number() {
		$this->assertSame( [ self::SHIPPING_AMOUNT_KEY => false ], ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => false ], 1, 1 ) );
		$this->assertSame( [ self::SHIPPING_AMOUNT_KEY => '' ], ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => '' ], 1, 1 ) );
		$this->assertSame( [ self::SHIPPING_AMOUNT_KEY => 't' ], ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => 't' ], 1, 1 ) );
	}

	public function test_shipping_amount_must_be_string_if_originally_was_string() {

		WP_Mock::userFunction( 'wc_format_decimal', array(
			'times' => 1,
			'return' => 4.13
		) );

		WP_Mock::userFunction( 'wc_get_price_decimals', array(
			'times' => 1,
		) );
		$args = ml_btfwe_filter_transaction_args( [ self::SHIPPING_AMOUNT_KEY => '4.125' ], 1, 1 );
		$this->assertTrue( is_string( $args[ self::SHIPPING_AMOUNT_KEY ] ) );

	}

	public function test_must_call_wc_format_decimal_and_wc_get_price_decimals() {
		WP_Mock::userFunction( 'wc_format_decimal', array(
			'times' => 1,
		) );

		WP_Mock::userFunction( 'wc_get_price_decimals', array(
			'times' => 1,
		) );
		ml_btfwe_round_shipping_amount( 4.125 );
		$this->assertTrue( true );
	}
}
