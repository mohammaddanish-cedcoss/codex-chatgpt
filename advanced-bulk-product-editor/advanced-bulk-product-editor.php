<?php
/**
 * Plugin Name: Advanced Bulk Product Editor
 * Description: Bulk edit WooCommerce products including price, stock, categories, tags and custom fields.
 * Version: 0.1.0
 * Author: ChatGPT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ABPE_Plugin' ) ) {
    class ABPE_Plugin {
        /**
         * Constructor
         */
        public function __construct() {
            define( 'ABPE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

            // Load dependencies.
            $this->includes();

            // Initialize components.
            new ABPE_Admin();
        }

        /**
         * Include required files.
         */
        private function includes() {
            require_once ABPE_PLUGIN_DIR . 'includes/class-abpe-admin.php';
            require_once ABPE_PLUGIN_DIR . 'includes/class-abpe-bulk-editor.php';
            require_once ABPE_PLUGIN_DIR . 'includes/class-abpe-logger.php';
            require_once ABPE_PLUGIN_DIR . 'includes/class-abpe-import-export.php';
        }
    }

    // Init plugin.
    add_action( 'plugins_loaded', function() {
        if ( class_exists( 'WooCommerce' ) ) {
            new ABPE_Plugin();
        }
    } );
}
