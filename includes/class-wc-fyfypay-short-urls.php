<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Short_Urls class.
 *
 */
class WC_FyfyPay_Short_Urls {

	/**
	 * Create a short url for a order.
	 *
	 * @param object  $order
	 *
	 * @return object The short url
	 */
	public static function create_from_order ( $order ) {
		$order_id = $order->get_id();
		$currency = $order->get_currency();
		$total_amount = floatval( $order->get_total() );
		$receiver = WC_FyfyPay_Helper::get_meta_data_value( 'receiver', $order );
		$token = WC_FyfyPay_Helper::get_meta_data_value( 'token', $order );
		$token_amount = WC_FyfyPay_Rates::convert_currency_amount( $currency, $total_amount, $token );

		$params = array(
			'description' => 'Order #' . $order_id,
			'receivers' => array( $receiver ),
			'amounts' => array( $token_amount ),
			'token' => $token,
			'category' => 'Shop',
			'callbackSuccess' => WC_Gateway_FyfyPay::get_return_url( $order ),
			'webhook' => WC_FyfyPay_Helper::get_webhook_url(),
			'extra' => array( 'order_id' => $order_id )
		);

		$short_url = WC_FyfyPay_API_WooCommerce::create_short_url( $params );

		return $short_url;
	}
}
