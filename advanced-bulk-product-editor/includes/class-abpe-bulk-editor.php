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
     * @param array $data Product data keyed by ID.
     * @return array List of proposed changes.
     */
    public static function preview_changes( $data ) {
        $preview = array();
        foreach ( $data as $id => $fields ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }
            $preview[ $id ] = array(
                'name'        => $product->get_name(),
                'title'       => $fields['title'] ? $fields['title'] : $product->get_name(),
                'description' => $fields['description'] ? $fields['description'] : $product->get_description(),
                'price'       => $fields['price'] ? $fields['price'] : $product->get_regular_price(),
                'sale_price'  => $fields['sale_price'] ? $fields['sale_price'] : $product->get_sale_price(),
                'stock'       => $fields['stock'] ? $fields['stock'] : $product->get_stock_quantity(),
            );
        }
        return $preview;
    }

    /**
     * Apply changes to products.
     *
     * @param array $data Product data keyed by ID.
     */
    public static function apply_changes( $data ) {
        $log = array();
        foreach ( $data as $id => $fields ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }

            // Store previous values for undo.
            $log[ $id ] = array(
                'title'        => $product->get_name(),
                'description'  => $product->get_description(),
                'price'        => $product->get_regular_price(),
                'sale_price'   => $product->get_sale_price(),
                'stock'        => $product->get_stock_quantity(),
                'stock_status' => $product->get_stock_status(),
                'categories'   => wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'ids' ) ),
            );

            if ( $fields['title'] !== '' ) {
                $product->set_name( $fields['title'] );
            }
            if ( $fields['description'] !== '' ) {
                $product->set_description( $fields['description'] );
            }
            if ( $fields['price'] !== '' ) {
                $product->set_regular_price( $fields['price'] );
            }
            if ( $fields['sale_price'] !== '' ) {
                $product->set_sale_price( $fields['sale_price'] );
            }
            if ( $fields['stock'] !== '' ) {
                $product->set_manage_stock( true );
                $product->set_stock_quantity( (int) $fields['stock'] );
            }
            if ( $fields['stock_status'] !== '' ) {
                $product->set_manage_stock( true );
                $product->set_stock_status( $fields['stock_status'] );
            }
            $product->save();
        }

        ABPE_Logger::log_edit( $log );
    }
}
