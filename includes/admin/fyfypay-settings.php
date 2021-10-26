<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return apply_filters(
	'wc_FYFYPay_settings',
	array(
		'enabled'     => array(
			'title'       => 'Enable/Disable',
			'type'        => 'checkbox',
			'label'       => 'Enable FyfyPay',
			'default'     => 'no'
		),
		'title'       => array(
			'title'       => 'Title',
			'description' => 'Payment method description that the customer will see on your checkout.',
			'type'        => 'text',
			'default'     => 'FyfyPay',
			'desc_tip'    => true
		),
		'description' => array(
			'title'       => 'Description',
			'description' => 'Payment method description that the customer will see on your website.',
			'type'        => 'textarea',
			'default'     => 'You will be automatically redirect to FYFYPay checkout page to proceed with the payment.',
			'desc_tip'    => true
		),
		'api_key'     => array(
			'title'       => 'API Key',
			'description' => 'Get your API keys from your FYFYPay account.',
			'type'        => 'text',
			'desc_tip'    => true
		),
		'chain'       => array(
			'title'       => 'Chain',
			'description' => 'Chain used for payments.',
			'type'        => 'select',
			'options'     => array(
				'SOL' => 'Solana'
			),
			'desc_tip'    => true
		),
		'receiver'    => array(
			'title'       => 'Receiver',
			'description' => 'Address that will receive the payments.',
			'type'        => 'text',
			'desc_tip'    => true
		),
		'logging'     => array(
			'title'       => 'Logging',
			'description' => 'Save debug messages to the WooCommerce System Status Logs',
			'type'        => 'checkbox',
			'label'       => 'Enable log messages',
			'default'     => 'yes',
			'desc_tip'    => true
		),
		'tokens'      => array(
			'title'       => 'Supported Tokens',
			'type'        => 'title'
		),
		'FYFY'        => array(
			'title'       => 'FYFY',
			'type'        => 'checkbox',
			'label'       => 'Enable FYFY',
			'default'     => 'yes'
		),
		'BNB'        => array(
			'title'       => 'BNB',
			'type'        => 'checkbox',
			'label'       => 'Enable BNB',
			'default'     => 'false'
		),
		'WBNB'        => array(
			'title'       => 'WBNB',
			'type'        => 'checkbox',
			'label'       => 'Enable WBNB',
			'default'     => 'false'
		),
		'BUSD'        => array(
			'title'       => 'BUSD',
			'type'        => 'checkbox',
			'label'       => 'Enable BUSD',
			'default'     => 'false'
		),
		'USDT'        => array(
			'title'       => 'USDT',
			'type'        => 'checkbox',
			'label'       => 'Enable USDT',
			'default'     => 'false'
		),
		'USDC'        => array(
			'title'       => 'USDC',
			'type'        => 'checkbox',
			'label'       => 'Enable USDC',
			'default'     => 'false'
		),
		'ETH'        => array(
			'title'       => 'ETH',
			'type'        => 'checkbox',
			'label'       => 'Enable ETH',
			'default'     => 'false'
		),
		'BTCB'        => array(
			'title'       => 'BTCB',
			'type'        => 'checkbox',
			'label'       => 'Enable BTCB',
			'default'     => 'false'
		),
		'CAKE'        => array(
			'title'       => 'CAKE',
			'type'        => 'checkbox',
			'label'       => 'Enable CAKE',
			'default'     => 'false'
		),
		'BSCPAD'        => array(
			'title'       => 'BSCPAD',
			'type'        => 'checkbox',
			'label'       => 'Enable BSCPAD',
			'default'     => 'false'
		),
		'DAI'        => array(
			'title'       => 'DAI',
			'type'        => 'checkbox',
			'label'       => 'Enable DAI',
			'default'     => 'false'
		),
		'SOLANA'        => array(
			'title'       => 'SOLANA',
			'type'        => 'checkbox',
			'label'       => 'Enable SOLANA',
			'default'     => 'false'
		),
		'NAFTY'        => array(
			'title'       => 'NAFTY',
			'type'        => 'checkbox',
			'label'       => 'Enable NAFTY',
			'default'     => 'false'
		)
	)
);
