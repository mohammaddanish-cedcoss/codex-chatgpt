<?php
/**
 * Plugin Name:  WPS Boiler Plate
 * Description:  Admin + Public React boilerplate with separate bundles.
 * Version:      1.0.0
 * Author:       WP Swings
 * Text Domain:  wps-boiler-plate
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WPSBP_FILE', __FILE__ );
define( 'WPSBP_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSBP_URL', plugin_dir_url( __FILE__ ) );
define( 'WPSBP_VER', '1.0.0' ); // bump on release

// Autoload / includes.
require_once WPSBP_DIR . 'admin/class-wps-boiler-plate-admin.php';
require_once WPSBP_DIR . 'public/class-wps-boiler-plate-public.php';

class WPS_Boiler_Plate {
    /** @var WPS_Boiler_Plate_Admin */
    public $admin;

    /** @var WPS_Boiler_Plate_Public */
    public $public;

    /** @var string Hook suffix for admin page */
    public $menu_hook = '';

    public function __construct() {
        // i18n
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

        // Create top-level menu + page.
        add_action( 'admin_menu', [ $this, 'register_menu' ] );

        // Init layers.
        $this->admin  = new WPS_Boiler_Plate_Admin( $this );
        $this->public = new WPS_Boiler_Plate_Public();
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'wps-boiler-plate', false, dirname( plugin_basename( WPSBP_FILE ) ) . '/languages' );
    }

    public function register_menu() {
        $cap   = 'manage_options';
        $title = __( 'WPS Boiler Plate', 'wps-boiler-plate' );

        // Keep a stable slug for screen checks.
        $slug  = 'wps-boiler-plate';

        $this->menu_hook = add_menu_page(
            $title,                            // page title
            $title,                            // menu title
            $cap,
            $slug,
            [ $this->admin, 'render_admin_page' ], // content callback lives in Admin class
            'dashicons-admin-generic',
            58
        );

        // Pass the hook id to the Admin class so it can conditionally enqueue.
        $this->admin->set_screen_hook( $this->menu_hook );
    }
}

// Bootstrap
$GLOBALS['wps_boiler_plate'] = new WPS_Boiler_Plate();
