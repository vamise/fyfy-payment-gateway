<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_API_WebhookNotifications class.
 *
 * @extends WC_Gateway_FyfyPay
 */
class WC_FyfyPay_API_WebhookNotifications extends WC_FyfyPay_API {

	/**
	 * Get a webhook notification.
	 *
	 * @param string  $notification_id
	 *
	 * @throws Exception If notification does not exist.
	 * @return object
	 */
	public static function get_notification ( $notification_id ) {
		$url = self::get_api_endpoint() . 'webhook-notifications/' . $notification_id;
		$headers = self::get_headers();

		$args = array(
			'headers' => $headers
		);

		$response = wp_remote_get( $url, $args );
		$body     = wp_remote_retrieve_body( $response );
		$notification = json_decode( $body );

		WC_8Pay_Logger::log( 'GET ' . $url . PHP_EOL . 'Request' . PHP_EOL . print_r( $args, true ) . PHP_EOL . 'Response' . PHP_EOL . print_r( $body, true ) );

		if ( $notification->error ) {
			throw new Exception( $notification->error->message );
		}

		return $notification;
	}
}
