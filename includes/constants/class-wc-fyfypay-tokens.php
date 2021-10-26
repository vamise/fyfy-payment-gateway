<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_FyfyPay_Tokens class.
 *
 */
class WC_FyfyPay_Tokens {

	/**
	 * The list of FYFYPay supported tokens.
	 */
	const TOKENS = array(
		'FYFY',
		'BNB',
		'WBNB',
		'BUSD',
		'USDT',
		'USDC',
		'ETH',
		'BTCB',
		'CAKE',
		'BSCPAD',
		'DAI',
		'SOLANA',
		'NAFTY'
	);
}