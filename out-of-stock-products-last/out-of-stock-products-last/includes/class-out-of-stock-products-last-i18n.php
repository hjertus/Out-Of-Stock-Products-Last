<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://hjertus.online
 * @since      1.0.0
 *
 * @package    Out_Of_Stock_Products_Last
 * @subpackage Out_Of_Stock_Products_Last/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Out_Of_Stock_Products_Last
 * @subpackage Out_Of_Stock_Products_Last/includes
 * @author     Theon <theon@live.no>
 */
class Out_Of_Stock_Products_Last_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'out-of-stock-products-last',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
