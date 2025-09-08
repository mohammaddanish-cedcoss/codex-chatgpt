<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WPS_Boiler_Plate_Admin {

    /** @var string */
    private $screen_hook = '';

    /** @var WPS_Boiler_Plate */
    private $plugin;

    public function __construct( $plugin = null ) {
        $this->plugin = $plugin;

        // Only enqueue on our screen.
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function set_screen_hook( $hook ) {
        $this->screen_hook = $hook;
    }

    public function enqueue( $hook_suffix ) {
        if ( empty( $this->screen_hook ) || $hook_suffix !== $this->screen_hook ) {
            return;
        }

        $rel  = 'admin/dist/build.js';
        $path = WPSBP_DIR . $rel;
        $url  = WPSBP_URL . $rel;

        if ( ! file_exists( $path ) ) {
            add_action( 'admin_notices', function () use ( $rel ) {
                echo '<div class="notice notice-error"><p><strong>'
                    . esc_html__( 'WPS Boiler Plate:', 'wps-boiler-plate' )
                    . '</strong> '
                    . sprintf(
                        esc_html__( 'Build missing at %s. Run %s.', 'wps-boiler-plate' ),
                        '<code>' . esc_html( $rel ) . '</code>',
                        '<code>npm run build</code>'
                    )
                    . '</p></div>';
            } );
            return;
        }

        wp_enqueue_script(
            'wpsbp-admin',
            $url,
            [ 'wp-element' ],
            filemtime( $path ),
            true
        );

        wp_localize_script( 'wpsbp-admin', 'WPSBP_ADMIN', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'restUrl' => esc_url_raw( rest_url() ),
            'nonce'   => wp_create_nonce( 'wpsbp-admin' ),
        ] );
    }

    /**
     * Renders the admin React mount point using a PHP template.
     */
    public function render_admin_page() {
        $template = WPSBP_DIR . 'admin/src/index.php';
        if ( file_exists( $template ) ) {
            require $template;
        } else {
            echo '<div class="wrap"><h1>'
               . esc_html__( 'WPS Boiler Plate', 'wps-boiler-plate' )
               . '</h1><p>'
               . esc_html__( 'Admin template not found.', 'wps-boiler-plate' )
               . '</p></div>';
        }
    }
}
