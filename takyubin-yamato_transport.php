<?php
/*
Plugin Name: TA-Q-BIN - Yamato Transport Shipping Table
Plugin URI: https://maisgeeks.com.br
Description: WooCommerce shipping support for Yamato Transport's TA-Q-BIN service (Japan only). It limits shipping by weight and calculates rate by size.
Version: 1.0.2
Author: +geeks
Author URI: https://maisgeeks.com.br
Text Domain: maisgeeks_takyubin
*/

/**
 * Preventing any direct access to plugin file
 */
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function maisgeeks_takyubin_shipping_method() {
		if ( ! class_exists( 'WC_MaisGeeks_Takyubin_Shipping_Method' ) ) {
			class WC_MaisGeeks_Takyubin_Shipping_Method extends WC_Shipping_Method {
				/**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
				public function __construct() {
					$this->id = 'maisgeeks_takyubin'; 
					$this->method_title = __( 'TA-Q-BIN - Yamato Transport Shipping', 'maisgeeks_takyubin' );  
					$this->method_description = __( 'Custom Shipping Method for Yamato Transport (TA-Q-BIN)', 'maisgeeks_takyubin' ); 

                    // Availability & Countries
					$this->availability = 'including';
					$this->countries = 	array(
						'JP',
					);

					$this->init();

					$this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
					$this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'TA-Q-BIN - Yamato Transport Shipping', 'maisgeeks_takyubin' );
				}

				/**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
				function init() {
                    // Load the settings API
					$this->init_form_fields(); 
					$this->init_settings(); 

                    // Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				/**
                 * Define settings field for this shipping
                 * @return void 
                 */
				function init_form_fields() {

					$this->form_fields = array(
						'enabled' => array(
							'title' 		=> __( 'Enable', 'maisgeeks_takyubin' ),
							'type' 			=> 'checkbox',
							'description' 	=> __( 'Enable this shipping.', 'maisgeeks_takyubin' ),
							'default' 		=> 'yes'
						),
						'title' => array(
							'title' 		=> __( 'Title', 'maisgeeks_takyubin' ),
							'type' 			=> 'text',
							'description' 	=> __( 'Title to be display on site', 'maisgeeks_takyubin' ),
							'default' 		=> __( 'TA-Q-BIN - Yamato Transport Shipping', 'maisgeeks_takyubin' )
						),
						'hokkaido_price_01' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokkaido_price_02' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokkaido_price_03' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokkaido_price_04' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokkaido_price_05' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokkaido_price_06' => array(
							'title'			=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Hokkaido', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_01' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_02' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_03' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_04' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_05' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'northern_tohoku_price_06' => array(
							'title'			=> __( 'Northern Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Aomori, Akita, Iwate', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_01' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_02' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_03' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_04' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_05' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'south_tohoku_price_06' => array(
							'title'			=> __( 'South Tohoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Miyagi, Yamagata, Fukushima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_01' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_02' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_03' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_04' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_05' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kanto_price_06' => array(
							'title'			=> __( 'Kanto', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Tokyo, Ibaraki, Tochigi, Gunma, Saitama, Chiba, Kanagawa, Yamanashi', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_01' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_02' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_03' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_04' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_05' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shinetsu_price_06' => array(
							'title'			=> __( 'Shinetsu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Nagano, Niigata', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_01' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_02' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_03' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_04' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_05' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'hokuriku_price_06' => array(
							'title'			=> __( 'Hokuriku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Toyama, Ishikawa, Fukui', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_01' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_02' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_03' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_04' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_05' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chubu_price_06' => array(
							'title'			=> __( 'Chubu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Shizuoka, Aichi, Gifu, Mie', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_01' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_02' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_03' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_04' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_05' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kansai_price_06' => array(
							'title'			=> __( 'Kansai', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kyoto, Shiga, Nara, Wakayama, Osaka, Hyogo', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_01' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_02' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_03' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_04' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_05' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_06' => array(
							'title'			=> __( 'Chugoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okayama, Hiroshima, Yamaguchi, Tottori, Shimane', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_01' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_02' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_03' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_04' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_05' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'shikoku_price_06' => array(
							'title'			=> __( 'Shikoku', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Kagawa, Tokushima, Kochi, Ehime', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_01' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_02' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_03' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_04' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_05' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'kyushu_price_06' => array(
							'title'			=> __( 'Kyushu', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Fukuoka, Saga, Nagasaki, Kumamoto, Oita, Miyazaki, Kagoshima', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'okinawa_price_01' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 60', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 60 cm & up to 2 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'okinawa_price_02' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 80', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 80 cm & up to 5 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'okinawa_price_03' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 100', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 100 cm & up to 10 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'okinawa_price_04' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 120', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 120 cm & up to 15 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'okinawa_price_05' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 140', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 140 cm & up to 20 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						),
						'chugoku_price_06' => array(
							'title'			=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( 'Rate', 'maisgeeks_takyubin' ) . ' - ' . __( 'Size 160', 'maisgeeks_takyubin' ),
							'type'			=> 'price',
							'description'	=> __( 'Okinawa', 'maisgeeks_takyubin' ) . ' ' . __( '(up to 160 cm & up to 25 kg)', 'maisgeeks_takyubin' ),
							'default'		=> '',
							'options'		=> array(
								'key'		=> 'value'
							)
						)
					);
				}

				/**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
				public function calculate_shipping( $package ) {
                    // Calculate total weight
					$weight = 0; 
					$weight_based_rate = 0;
					foreach ( $package['contents'] as $item_id => $values ) { 
						$_product = $values['data'];
						$subtotal_weight = $_product->get_weight() * $values['quantity'];
						$weight = $weight + $subtotal_weight; 
					} 
					$weight = wc_get_weight( $weight, 'kg' );
                    // Calculate rate based on weight
					switch (true) {
						case $weight <= 2:
							$weight_based_rate = 1;
							break;
						case $weight <= 5:
							$weight_based_rate = 2;
							break;
						case $weight <= 10:
							$weight_based_rate = 3;
							break;
						case $weight <= 15:
							$weight_based_rate = 4;
							break;
						case $weight <= 20:
							$weight_based_rate = 5;
							break;
						case $weight <= 25:
							$weight_based_rate = 6;
							break;
						default:
							$weight_based_rate = 0;
							break;
					}

                    // Calculate total size
					$size = 0; 
					$size_based_rate = 0;
					foreach ( $package['contents'] as $item_id => $values ) { 
						$_product = $values['data'];
						$each_size = $_product->get_height() + $_product->get_length() + $_product->get_width();
						$subtotal_size = $each_size * $values['quantity'];
						$size = $size + $subtotal_size; 
					} 
					$size = wc_get_dimension( $size, 'cm' );
                    // Calculate rate based on size
					switch (true) {
						case $size <= 60:
							$size_based_rate = 1;
							break;
						case $size <= 80:
							$size_based_rate = 2;
							break;
						case $size <= 100:
							$size_based_rate = 3;
							break;
						case $size <= 120:
							$size_based_rate = 4;
							break;
						case $size <= 140:
							$size_based_rate = 5;
							break;
						case $size <= 160:
							$size_based_rate = 6;
							break;
						default:
						$size_based_rate = 'disable';
						break;
					}

                    // Get base rate
					$based_rate = 0;
					switch (true) {
						case $weight_based_rate > $size_based_rate:
							$based_rate = $weight_based_rate;
							break;
						case $weight_based_rate < $size_based_rate:
							$based_rate = $size_based_rate;
							break;
						default:
							$based_rate = $weight_based_rate;
							break;
					}

		            // Get delivery province
					$province = WC()->customer->get_shipping_state();
					switch ( $province ){
						case "JP01":
							$province_label = __('Hokkaido', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'hokkaido_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'hokkaido_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'hokkaido_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'hokkaido_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'hokkaido_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'hokkaido_price_06' );
								break;
							}
							break;
						case "JP02": case "JP03": case "JP05":
							$province_label = __('Northern Tohoku', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'northern_tohoku_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'northern_tohoku_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'northern_tohoku_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'northern_tohoku_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'northern_tohoku_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'northern_tohoku_price_06' );
								break;
							}
							break;
						case "JP04": case "JP06": case "JP07":
							$province_label = __('South Tohoku', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'south_tohoku_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'south_tohoku_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'south_tohoku_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'south_tohoku_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'south_tohoku_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'south_tohoku_price_06' );
								break;
							}
							break;
						case "JP08": case "JP09": case "JP10": case "JP11": case "JP12": case "JP13": case "JP14": case "JP19":
							$province_label = __('Kanto', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'kanto_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'kanto_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'kanto_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'kanto_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'kanto_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'kanto_price_06' );
								break;
							}
							break;
						case "JP15": case "JP20":
							$province_label = __('Shinetsu', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'shinetsu_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'shinetsu_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'shinetsu_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'shinetsu_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'shinetsu_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'shinetsu_price_06' );
								break;
							}
							break;
						case "JP21": case "JP22": case "JP23": case "JP24":
							$province_label = __('Chubu', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'chubu_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'chubu_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'chubu_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'chubu_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'chubu_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'chubu_price_06' );
								break;
							}
							break;
						case "JP16": case "JP17": case "JP18":
							$province_label = __('Hokuriku', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'hokuriku_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'hokuriku_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'hokuriku_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'hokuriku_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'hokuriku_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'hokuriku_price_06' );
								break;
							}
							break;
						case "JP25": case "JP26": case "JP27": case "JP28": case "JP29": case "JP30":
							$province_label = __('Kansai', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'kansai_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'kansai_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'kansai_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'kansai_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'kansai_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'kansai_price_06' );
								break;
							}
							break;
						case "JP31": case "JP32": case "JP33": case "JP34": case "JP35":
							$province_label = __('Chugoku', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'chugoku_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'chugoku_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'chugoku_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'chugoku_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'chugoku_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'chugoku_price_06' );
								break;
							}
							break;
						case "JP36": case "JP37": case "JP38": case "JP39":
							$province_label = __('Shikoku', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'shikoku_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'shikoku_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'shikoku_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'shikoku_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'shikoku_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'shikoku_price_06' );
								break;
							}
							break;
						case "JP40": case "JP41": case "JP42": case "JP43": case "JP44": case "JP45": case "JP46":
							$province_label = __('Kyushu', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'kyushu_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'kyushu_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'kyushu_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'kyushu_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'kyushu_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'kyushu_price_06' );
								break;
							}
							break;
						case "JP47":
							$province_label = __('Okinawa', 'maisgeeks_takyubin');
							switch ($based_rate) {
								case 1:
								$cost=$this->get_option( 'okinawa_price_01' );
								break;

								case 2:
								$cost=$this->get_option( 'okinawa_price_02' );
								break;

								case 3:
								$cost=$this->get_option( 'okinawa_price_03' );
								break;

								case 4:
								$cost=$this->get_option( 'okinawa_price_04' );
								break;

								case 5:
								$cost=$this->get_option( 'okinawa_price_05' );
								break;

								case 6:
								$cost=$this->get_option( 'okinawa_price_06' );
								break;
							}
							break;
						default:
							$cost='4820';
							break;
					}

		            // Set label
					switch ($based_rate) {
						case 1:
							$label = $province_label . ' ' . __( '(Size 60)', 'maisgeeks_takyubin' );
							break;
						case 2:
							$label = $province_label . ' ' . __( '(Size 80)', 'maisgeeks_takyubin' );
							break;
						case 3:
							$label = $province_label . ' ' . __( '(Size 100)', 'maisgeeks_takyubin' );
							break;
						case 4:
							$label = $province_label . ' ' . __( '(Size 120)', 'maisgeeks_takyubin' );
							break;
						case 5:
							$label = $province_label . ' ' . __( '(Size 140)', 'maisgeeks_takyubin' );
							break;
						case 6:
							$label = $province_label . ' ' . __( '(Size 160)', 'maisgeeks_takyubin' );
							break;
						default:
							$label = $province_label;
							break;
					}

					$rate = array(
						'id' => $this->id,
						'label' => $label,
						'cost' => $cost,
						'calc_tax' => 'per_item'
					);

					$this->add_rate( $rate );                    
				}
			}
		}
	}
	add_action( 'woocommerce_shipping_init', 'maisgeeks_takyubin_shipping_method' );


	function add_maisgeeks_takyubin_shipping_method( $methods ) {
		$methods['maisgeeks_takyubin'] = 'WC_MaisGeeks_Takyubin_Shipping_Method';
		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'add_maisgeeks_takyubin_shipping_method' );


	function maisgeeks_takyubin_validate_order( $posted ) {
		$packages = WC()->shipping->get_packages();
		$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
		if( is_array( $chosen_methods ) && in_array( 'maisgeeks_takyubin', $chosen_methods ) ) {
			foreach ( $packages as $i => $package ) {
				if ( $chosen_methods[ $i ] != "maisgeeks_takyubin" ) {
					continue;
				}

				$weightLimit = 25;
				$sizeLimit = 160;

				$MaisGeeks_Validate_Takyubin_Shipping_Method = new WC_MaisGeeks_Takyubin_Shipping_Method();

                // Calculate total weight
                $weight = 0; 
                foreach ( $package['contents'] as $item_id => $values ) { 
                    $_product = $values['data'];
                    $subtotal_weight = $_product->get_weight() * $values['quantity'];
                    $weight = $weight + $subtotal_weight; 
                } 
                $weight = wc_get_weight( $weight, 'kg' );

                // Calculate total size
                $size = 0; 
                foreach ( $package['contents'] as $item_id => $values ) { 
                    $_product = $values['data'];
		            $each_size = $_product->get_height() + $_product->get_length() + $_product->get_width();
		            $subtotal_size = $each_size * $values['quantity'];
                    $size = $size + $subtotal_size; 
                } 
                $size = wc_get_dimension( $size, 'cm' );

				if( $weight > $weightLimit ) {
					$message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'maisgeeks_takyubin' ), $weight, $weightLimit, $MaisGeeks_Validate_Takyubin_Shipping_Method->title );
					$messageType = "error";
					if( ! wc_has_notice( $message, $messageType ) ) {
						wc_add_notice( $message, $messageType );
					}
				}
				if( $size > $sizeLimit ) {
					$message = sprintf( __( 'Sorry, %d kg exceeds the maximum size of %d cm for %s', 'maisgeeks_takyubin' ), $size, $sizeLimit, $MaisGeeks_Validate_Takyubin_Shipping_Method->title );
					$messageType = "error";
					if( ! wc_has_notice( $message, $messageType ) ) {
						wc_add_notice( $message, $messageType );
					}
				}
			}       
		} 
	}

	add_action( 'woocommerce_review_order_before_cart_contents', 'maisgeeks_takyubin_validate_order' , 10 );
	add_action( 'woocommerce_after_checkout_validation', 'maisgeeks_takyubin_validate_order' , 10 );
}