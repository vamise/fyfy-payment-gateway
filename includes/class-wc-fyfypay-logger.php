<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Logger class.
 *
 */
class WC_FyfyPay_Logger {

	public static $logger;
	const LOG_FILENAME = 'fyfypay-payment-gateway';

	/**
	 * Utilize WC logger class
	 */
	public static function log( $message, $start_time = null, $end_time = null ) {
		if ( ! class_exists( 'WC_Logger' ) ) {
			return;
		}

		if ( apply_filters( 'wc_fyfypay_logging', true, $message ) ) {
			if ( empty( self::$logger ) ) {
				self::$logger = wc_get_logger();
			}

			$settings = get_option( 'woocommerce_fyfypay_settings', [] );

			if ( empty( $settings ) || isset( $settings['logging'] ) && 'yes' !== $settings['logging'] ) {
				return;
			}

			if ( ! is_null( $start_time ) ) {
				$formatted_start_time = date_i18n( get_option( 'date_format' ) . ' g:ia', $start_time );
				$end_time             = is_null( $end_time ) ? current_time( 'timestamp' ) : $end_time;
				$formatted_end_time   = date_i18n( get_option( 'date_format' ) . ' g:ia', $end_time );
				$elapsed_time         = round( abs( $end_time - $start_time ) / 60, 2 );

				$log_entry  = "\n" . '====FYFYPay Version: ' . WC_FYFYPAY_VERSION . '====' . "\n";
				$log_entry .= '====Start Log ' . $formatted_start_time . '====' . "\n" . $message . "\n";
				$log_entry .= '====End Log ' . $formatted_end_time . ' (' . $elapsed_time . ')====' . "\n\n";

			} else {
				$log_entry  = "\n" . '====FYFYPay Version: ' . WC_FYFYPAY_VERSION . '====' . "\n";
				$log_entry .= '====Start Log====' . "\n" . $message . "\n" . '====End Log====' . "\n\n";
			}

			self::$logger->debug( $log_entry, [ 'source' => self::LOG_FILENAME ] );
		}
	}
}
