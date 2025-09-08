<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Wps_Boiler_Plate
 * @subpackage Wps_Boiler_Plate/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wps_Boiler_Plate
 * @subpackage Wps_Boiler_Plate/includes
 * @author     WP Swings <support@wpswings.com>
 */
class Wps_Boiler_Plate_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wps-boiler-plate',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
