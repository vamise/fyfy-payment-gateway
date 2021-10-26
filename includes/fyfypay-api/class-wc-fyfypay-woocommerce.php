<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_API_WooCommerce class.
 *
 * @extends WC_Gateway_FyfyPay
 */
class WC_FyfyPay_API_WooCommerce extends WC_FyfyPay_API {

	/**
	 * Create a short url.
	 *
	 * @param object  $params
	 *
	 * @throws Exception If params are not valid.
	 * @return object
	 */
	public static function create_short_url ( $params ) {
		$url = self::get_api_endpoint() . 'woocommerce/short-urls';
		$headers = self::get_headers();
		$data = array(
			'name'   => 'WooCommerce',
			'params' => $params
		);

		$args = array(
			'headers' => $headers,
			'body'    => json_encode( $data )
		);

		$response = wp_remote_post( $url, $args );
		$body = wp_remote_retrieve_body( $response );
		$short_url = json_decode( $body );

		WC_fyfyPay_Logger::log( 'POST ' . $url . PHP_EOL . 'Request' . PHP_EOL . print_r( $args, true ) . PHP_EOL . 'Response' . PHP_EOL . print_r( $body, true ) );

		if ( $short_url->error ) {
			throw new Exception( $short_url->error->message );
		}

		return $short_url;
	}
}
