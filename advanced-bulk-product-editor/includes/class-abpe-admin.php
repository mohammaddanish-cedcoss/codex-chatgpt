<?php
/**
 * Admin UI for Advanced Bulk Product Editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ABPE_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_menu' ) );
    }

    /**
     * Register admin menu.
     */
    public function register_menu() {
        add_submenu_page(
            'woocommerce',
            __( 'Bulk Product Editor', 'abpe' ),
            __( 'Bulk Product Editor', 'abpe' ),
            'manage_woocommerce',
            'abpe-bulk-editor',
            array( $this, 'render_page' )
        );
    }

    /**
     * Render admin page.
     */
    public function render_page() {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        // Handle form submissions.
        $notice = '';
        if ( isset( $_POST['abpe_bulk_edit_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['abpe_bulk_edit_nonce'] ), 'abpe_bulk_edit' ) ) {
            $ids      = isset( $_POST['product_ids'] ) ? array_map( 'intval', (array) $_POST['product_ids'] ) : array();
            $data     = array(
                'price'       => isset( $_POST['price'] ) ? wc_clean( wp_unslash( $_POST['price'] ) ) : '',
                'stock'       => isset( $_POST['stock'] ) ? wc_clean( wp_unslash( $_POST['stock'] ) ) : '',
                'stock_status' => isset( $_POST['stock_status'] ) ? wc_clean( wp_unslash( $_POST['stock_status'] ) ) : '',
                'categories'  => isset( $_POST['categories'] ) ? array_map( 'intval', (array) $_POST['categories'] ) : array(),
            );

            if ( isset( $_POST['abpe_preview'] ) ) {
                $preview = ABPE_Bulk_Editor::preview_changes( $ids, $data );
                include ABPE_PLUGIN_DIR . 'views/preview.php';
                return;
            } elseif ( isset( $_POST['abpe_export'] ) ) {
                ABPE_Import_Export::export_csv( $ids );
            } else {
                ABPE_Bulk_Editor::apply_changes( $ids, $data );
                $notice = __( 'Products updated successfully.', 'abpe' );
            }
        } elseif ( isset( $_POST['abpe_import_export_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['abpe_import_export_nonce'] ), 'abpe_import_export' ) && isset( $_POST['abpe_import'] ) ) {
            ABPE_Import_Export::import_csv( $_FILES['abpe_csv'] );
            $notice = __( 'Import completed.', 'abpe' );
        } elseif ( isset( $_POST['abpe_undo_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['abpe_undo_nonce'] ), 'abpe_undo' ) && isset( $_POST['abpe_undo'] ) ) {
            ABPE_Logger::undo_last_edit();
            $notice = __( 'Last bulk edit undone.', 'abpe' );
        }

        $products = wc_get_products( array( 'limit' => -1 ) );
        $categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ) );
        include ABPE_PLUGIN_DIR . 'views/admin-page.php';
    }
}
