<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPS_Boiler_Plate_Public {

    public function __construct() {
        // Register shortcode.
        add_shortcode( 'wps_boiler_plate', [ $this, 'shortcode' ] );

        // (Optional) Pre-register script so we can safely enqueue with versioning later.
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    public function register_assets() {
        $rel  = 'public/dist/build.js';
        $path = WPSBP_DIR . $rel;
        $url  = WPSBP_URL . $rel;

        if ( file_exists( $path ) ) {
            // If bundling React:
            wp_register_script( 'wpsbp-public', $url, [], filemtime( $path ), true );

            // If externalizing React to front (less common), do:
            // wp_register_script( 'wpsbp-public', $url, [ 'wp-element' ], filemtime( $path ), true );
        }
    }

    /**
     * Shortcode handler: [wps_boiler_plate]
     * Outputs a mount point and enqueues the public bundle.
     */
    public function shortcode( $atts = [] ) {
        $rel  = 'public/dist/build.js';
        $path = WPSBP_DIR . $rel;

        if ( ! file_exists( $path ) ) {
            return '<div class="wpsbp-error">'
                . esc_html__( 'WPS Boiler Plate public build is missing. Please run npm run build.', 'wps-boiler-plate' )
                . '</div>';
        }

        // Enqueue now (in case register ran before).
        wp_enqueue_script( 'wpsbp-public' );

        // Localize data for the public app.
        wp_localize_script( 'wpsbp-public', 'WPSBP_PUBLIC', [
            'restUrl' => esc_url_raw( rest_url() ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
        ] );

        // Output container
        ob_start();
        ?>
        <div id="wpsbp-public-app"></div>
        <?php
        return ob_get_clean();
    }
}
