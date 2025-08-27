<?php
/**
 * Bulk editing logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ABPE_Bulk_Editor {

    /**
     * Preview changes before applying.
     *
     * @param array $ids Product IDs.
     * @param array $data Data to update.
     * @return array List of proposed changes.
     */
    public static function preview_changes( $ids, $data ) {
        $preview = array();
        foreach ( $ids as $id ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }
            $preview[ $id ] = array(
                'name'  => $product->get_name(),
                'price' => $data['price'] ? $data['price'] : $product->get_regular_price(),
                'stock' => $data['stock'] ? $data['stock'] : $product->get_stock_quantity(),
            );
        }
        return $preview;
    }

    /**
     * Apply changes to products.
     *
     * @param array $ids Product IDs.
     * @param array $data Data to update.
     */
    public static function apply_changes( $ids, $data ) {
        $log = array();
        foreach ( $ids as $id ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }

            // Store previous values for undo.
            $log[ $id ] = array(
                'price'        => $product->get_regular_price(),
                'stock'        => $product->get_stock_quantity(),
                'stock_status' => $product->get_stock_status(),
                'categories'   => wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'ids' ) ),
            );

            if ( $data['price'] !== '' ) {
                $product->set_regular_price( $data['price'] );
            }
            if ( $data['stock'] !== '' ) {
                $product->set_stock_quantity( (int) $data['stock'] );
            }
            if ( $data['stock_status'] !== '' ) {
                $product->set_stock_status( $data['stock_status'] );
            }
            if ( ! empty( $data['categories'] ) ) {
                wp_set_post_terms( $id, $data['categories'], 'product_cat' );
            }
            $product->save();
        }

        ABPE_Logger::log_edit( $log );
    }
}
