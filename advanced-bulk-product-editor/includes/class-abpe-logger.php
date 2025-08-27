<?php
/**
 * Logging and undo functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ABPE_Logger {
    const OPTION_KEY = 'abpe_last_bulk_edit';

    /**
     * Log last bulk edit.
     *
     * @param array $data Previous product values.
     */
    public static function log_edit( $data ) {
        update_option( self::OPTION_KEY, $data );
    }

    /**
     * Undo last bulk edit.
     */
    public static function undo_last_edit() {
        $log = get_option( self::OPTION_KEY, array() );
        foreach ( $log as $id => $values ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }
            $product->set_regular_price( $values['price'] );
            $product->set_stock_quantity( $values['stock'] );
            $product->set_stock_status( $values['stock_status'] );
            wp_set_post_terms( $id, $values['categories'], 'product_cat' );
            $product->save();
        }
        delete_option( self::OPTION_KEY );
    }
}
