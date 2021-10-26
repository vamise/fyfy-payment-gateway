<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Webhook_Handler class.
 *
 */
class WC_FyfyPay_Webhook_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_api_wc_fyfypay', [ $this, 'check_for_webhook' ] );
	}

	/**
	 * Check incoming requests for FYFYPay Webhook data and process them.
	 */
	public function check_for_webhook() {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] )
			|| ( 'POST' !== $_SERVER['REQUEST_METHOD'] )
			|| ! isset( $_GET['wc-api'] )
			|| ( 'wc_fyfypay' !== $_GET['wc-api'] )
		) {
			return;
		}

		$request_body = file_get_contents( 'php://input' );
		$request_headers = $this->get_request_headers();

		// Validate it to make sure it is legit.
		$validation_result = $this->validate_request( $request_headers, $request_body );
		if ( $validation_result ) {
			try {
				$this->process_webhook( $request_body );
				status_header( 200 );
				exit;
			} catch ( Exception $e ) {
				WC_FyfyPay_Logger::log( 'Error processing webhook: ' . $e->getMessage() );
				status_header( 500 );
				exit;
			}
		} else {
			WC_FyfyPay_Logger::log( 'Incoming webhook failed validation' . PHP_EOL . print_r( $request_headers, true ) . print_r( $request_body, true ) );
			status_header( 400 );
			exit;
		}
	}

	/**
	 * Process webhook.
	 *
	 * @param object $request_body
	 */
	public function process_webhook( $request_body ) {
		$notification = json_decode( $request_body );

		switch ( $notification->type ) {
			case 'one-time':
				$this->process_webhook_one_time( $notification );
				break;
		}
	}

	/**
	 * Process one-time webhook.
	 *
	 * @param object $notification
	 */
	public function process_webhook_one_time( $notification ) {
		$order_id = $notification->extra->order_id;
		if ( ! $order_id ) {
			throw new Exception( 'Missing order_id' );
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			throw new Exception( 'Order #' . $order_id . ' not found' );
		}
		$order->payment_complete();
		WC_FyfyPay_Logger::log( 'Webhook processed' . PHP_EOL . print_r( $notification, true) . PHP_EOL . 'Order #' . $order_id . ': status updated to ' . $order->get_status() );
	}

	/**
	 * Verify the incoming webhook notification to make sure it is legit.
	 *
	 * @param array $request_headers
	 * @param array $request_body
	 *
	 * @return bool
	 */
	public function validate_request( $request_headers, $request_body ) {
		if ( empty( $request_headers ) ) {
			return false;
		}

		if ( empty( $request_body ) ) {
			return false;
		}

		$notification_webhook = json_decode( $request_body );

		try {
			$notification_8pay = WC_FyfyPay_API_WebhookNotifications::get_notification( $notification_webhook->id );

			if ( $notification_webhook == $notification_8pay ) {
				return true;
			}

			return false;
		} catch ( Exception $e ) {
			WC_FyfyPay_Logger::log( 'Error retrieving webhook notifications: ' . $e->getMessage() );
			return false;
		}
	}

	/**
	 * Gets the incoming request headers. Some servers are not using
	 * Apache and "getallheaders()" will not work so we may need to
	 * build our own headers.
	 */
	public function get_request_headers() {
		if ( ! function_exists( 'getallheaders' ) ) {
			$headers = [];

			foreach ( $_SERVER as $name => $value ) {
				if ( 'HTTP_' === substr( $name, 0, 5 ) ) {
					$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
				}
			}

			return $headers;
		} else {
			return getallheaders();
		}
	}
}

new WC_FyfyPay_Webhook_Handler();