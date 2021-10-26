<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_FyfyPay class.
 *
 * @extends WC_Payment_Gateway
 */
class WC_Gateway_FyfyPay extends WC_Payment_Gateway {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = 'FyfyPay';
		$this->icon               = plugins_url( '/../public/images/icon.png', __FILE__ );
		$this->has_fields         = true;
		$this->method_title       = 'FYFYPay';
		$this->method_description = 'Redirects customers to FYFYPay checkout page to complete their payment.';
		$this->supports           = array(
			'products'
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values.
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
		$this->api_key     = $this->get_option( 'api_key' );
		$this->chain       = $this->get_option( 'chain' );
		$this->receiver    = $this->get_option( 'receiver' );
		$token_settings = [
			'FYFY'   => $this->get_option( 'FYFY' ),
			'BNB'    => $this->get_option( 'BNB' ),
			'WBNB'   => $this->get_option( 'WBNB' ),
			'BUSD'   => $this->get_option( 'BUSD' ),
			'USDT'   => $this->get_option( 'USDT' ),
			'USDC'   => $this->get_option( 'USDC' ),
			'ETH'    => $this->get_option( 'ETH' ),
			'BTCB'   => $this->get_option( 'BTCB' ),
			'CAKE'   => $this->get_option( 'CAKE' ),
			'BSCPAD' => $this->get_option( 'BSCPAD' ),
			'SOL' => $this->get_option( 'SOL' ),
			'NAFTY'   => $this->get_option( 'NAFTY' ),
		];

		$this->tokens = array_values(
			array_filter(
				array_keys($token_settings),
				function($symbol) use ($token_settings) {
					return 'yes' == $token_settings[$symbol];
				}
			)
		);

		WC_FyfyPay_API::set_api_key( $this->api_key );
		WC_FyfyPay_API::set_chain( $this->chain );

		add_action( 'wp_enqueue_scripts', [ $this, 'payment_scripts' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = require WC_FYFYPAY_PLUGIN_PATH . '/includes/admin/fyfypay-settings.php';
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		echo '<select name="fyfypay_payment_gateway_token" style="display: none">';
		foreach ($this->tokens as $token) {
			echo '  <option value="' . $token . '">' . $token . '</option>';
		}
		echo '</select>';

		echo '<p class="select-token-message">Select a token for the payment</p>';
		echo '<div class="tokens-container">';
		for ( $i = 0; $i < count( $this->tokens ); $i++ ) {
			$token = $this->tokens[$i];
			$selected = 0 === $i ? 'selected' : '';
			echo '  <div class="token-button ' . $selected . '" data-token="' . $token . '">';
			echo '    <img src="' . plugins_url( 'public/images/tokens/' . $token . '.svg', WC_FYFYPAY_MAIN_FILE ) . '">';
			echo '    <span class="token-symbol">' . $token . '</span>';
			echo '  </div>';
		}
		echo '</div>';
	}

	/**
	 * Outputs scripts and styles used for FYFYPay payment
	 */
	public function payment_scripts() {
		wp_register_script( 'woocommerce_fyfypay', plugins_url( 'public/js/fyfypay.js', WC_FYFYPAY_MAIN_FILE ), ['jquery'], WC_FYFYPAY_VERSION, true );
		wp_enqueue_script( 'woocommerce_fyfypay' );

		wp_register_style( 'fyfypay_styles', plugins_url( 'public/css/fyfypay-styles.css', WC_FYFYPAY_MAIN_FILE ), [], WC_FYFYPAY_VERSION );
		wp_enqueue_style( 'fyfypay_styles' );
	}

	/**
	 * Process the payment
	 *
	 * @param int  $order_id Reference.
	 *
	 * @throws Exception If payment will not be accepted.
	 * @return array|void
	 */
	public function process_payment( $order_id ) {
		global $woocommerce;

		$order = wc_get_order( $order_id );

		$order->add_meta_data( 'receiver', $this->receiver, true );
		$order->add_meta_data( 'token', $_POST['fyfypay_payment_gateway_token'], true );

		try {
			$short_url = WC_FyfyPay_Short_Urls::create_from_order( $order );

			$order->update_status('on-hold', 'Awaiting FYFYPay webhook');
			WC_FyfyPay_Logger::log( 'Order #' . $order_id . ': status updated to ' . $order->get_status() . PHP_EOL . 'Redirecting to FYFYPay...' );

			$woocommerce->cart->empty_cart();

			return [
				'result' => 'success',
				'redirect' => $short_url->link
			];
		} catch( Exception $e ) {
			wc_add_notice( 'Unable to process the order. Contact the site administrator if the problem persists. ', 'error' );
			WC_FyfyPay_Logger::log( 'Error in process payment: ' . $e->getMessage() );

			$order->update_status( 'failed' );
		}
	}

	public function validate_title_field( $key, $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			WC_Admin_Settings::add_error( 'Title is required.' );
			throw new Exception( 'Title is required.' );
		}

		return $value;
	}

	public function validate_api_key_field( $key, $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			WC_Admin_Settings::add_error( 'API key is required.' );
			throw new Exception( 'API key is required.' );
		}

		return $value;
	}

	public function validate_receiver_field( $key, $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			WC_Admin_Settings::add_error( 'Receiver is required.' );
			throw new Exception( 'Receiver is required.' );
		}

		return $value;
	}

	public function validate_checkbox_field( $key, $value ) {
		$selected_tokens = 0;
		foreach ( WC_FyfyPay_Tokens::TOKENS as $token ) {
			$post_field = 'woocommerce_fyfypay_' . $token;
			if ( isset( $_POST[ $post_field ] ) && '1' === $_POST[ $post_field ] ) {
				$selected_tokens++;
			}
		}

		if ( 0 === $selected_tokens ) {
			if ( 'FYFYPAY' === $key ) {
				WC_Admin_Settings::add_error( 'You must enable at least one of the supported tokens.' );
				throw new Exception( 'You must enable at least one of the supported tokens.' );
			}

			if ( in_array( $key, $this->tokens ) ) {
				return 'yes';
			}
		}

		if ( 'enabled' === $key && '1' === $value) {
			if (
				empty( $_POST['woocommerce_fyfypay_api_key'] )  ||
				empty( $_POST['woocommerce_fyfypay_receiver'] ) ||
				0 === $selected_tokens
			) {
				return 'no';
			}
		}

		return ! is_null( $value ) ? 'yes' : 'no';
	}
}
