<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . '/class-wc-fyfypay-woocommerce.php';
require_once dirname( __FILE__ ) . '/class-wc-fyfypay-webhook-notifications.php';

/**
 * WC_FyfyPay_API class.
 *
 */
class WC_FyfyPay_API {

	// API endpoint
	const ENDPOINT = 'https://api.fyfypay.io/v1/{chain}/';

	/**
	 * API key
	 *
	 * @var string
	 */
	public static $api_key;

	/**
	 * Chain
	 *
	 * @var string
	 */
	public static $chain;

	/**
	 * Set api_key.
	 *
	 * @param string  $api_key
	 *
	 * @return void
	 */
	public static function set_api_key( $api_key ) {
		self::$api_key = $api_key;
	}

	/**
	 * Set chain.
	 *
	 * @param string  $chain
	 *
	 * @return void
	 */
	public static function set_chain( $chain ) {
		return self::$chain = $chain;
	}

	/**
	 * Get api endpoint.
	 *
	 * @return string
	 */
	public static function get_api_endpoint() {
		$endpoint = str_replace( '{chain}', self::$chain, self::ENDPOINT );

		return $endpoint;
	}

	/**
	 * Get headers containing authorization token.
	 *
	 * @return array
	 */
	public static function get_headers() {
		$headers = array(
			'Authorization' => 'Bearer ' . self::$api_key,
			'Content-Type'  => 'application/json'
		);

		return $headers;
	}
}