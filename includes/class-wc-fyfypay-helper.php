<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Helper class.
 *
 */
class WC_8Pay_Helper {

	/**
	 * Get webhook url.
	 *
	 * @return string
	 */
	public static function get_webhook_url() {
		return add_query_arg( 'wc-api', 'wc_fyfypay', trailingslashit( get_home_url() ) );
	}

	/**
	 * Get order meta value by meta key.
	 *
	 * @param string  $key
	 * @param object  $order
	 *
	 * @return string|null
	 */
	public static function get_meta_data_value ( $key, $order ) {
		$meta_data = array_map(
			function( $current_meta_data ) {
				return $current_meta_data->get_data();
			},
			$order->get_meta_data()
		);

		$filtered_meta_data = array_filter(
			$meta_data,
			function( $current_meta_data ) use ( $key ) {
				return $current_meta_data['key'] === $key;
			}
		);

		if ( count( $filtered_meta_data ) ) {
			$target_meta_data = array_shift( array_values( $filtered_meta_data ) );
			return $target_meta_data['value'];
		}

		return null;
	}
}