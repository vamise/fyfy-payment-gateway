<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Billing_Models class.
 *
 */
class WC_FyfyPay_Billing_Models {

	/**
	 * The list of FYFYPay billing models.
	 */
	const ONE_TIME           = 'one-time';
	const FIXED_RECURRING    = 'fixed-recurring';
	const VARIABLE_RECURRING = 'variable-recurring';
	const ON_DEMAND          = 'on-demand';
}