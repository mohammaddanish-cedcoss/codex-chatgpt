<?php
/**
 * CSV import/export functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ABPE_Import_Export {

    /**
     * Export products to CSV.
     *
     * @param array $ids Product IDs.
     */
    public static function export_csv( $ids ) {
        if ( empty( $ids ) ) {
            return;
        }
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=products.csv' );
        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'ID', 'Name', 'Price', 'Stock' ) );
        foreach ( $ids as $id ) {
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }
            fputcsv( $output, array( $id, $product->get_name(), $product->get_regular_price(), $product->get_stock_quantity() ) );
        }
        fclose( $output );
        exit;
    }

    /**
     * Import products from CSV and update.
     *
     * @param array $file Uploaded file array.
     */
    public static function import_csv( $file ) {
        if ( empty( $file['tmp_name'] ) ) {
            return;
        }
        $handle = fopen( $file['tmp_name'], 'r' );
        if ( ! $handle ) {
            return;
        }
        // Skip header.
        fgetcsv( $handle );
        while ( ( $data = fgetcsv( $handle ) ) !== false ) {
            list( $id, $name, $price, $stock ) = $data;
            $product = wc_get_product( $id );
            if ( ! $product ) {
                continue;
            }
            if ( $price !== '' ) {
                $product->set_regular_price( $price );
            }
            if ( $stock !== '' ) {
                $product->set_stock_quantity( (int) $stock );
            }
            $product->save();
        }
        fclose( $handle );
    }
}
