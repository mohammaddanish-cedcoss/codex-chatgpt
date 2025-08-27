<div class="wrap">
    <h1><?php esc_html_e( 'Bulk Product Editor', 'abpe' ); ?></h1>

    <?php if ( ! empty( $notice ) ) : ?>
        <div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
    <?php endif; ?>

    <style>
        .abpe-section { background:#fff; border:1px solid #ccd0d4; padding:20px; margin-top:20px; box-shadow:0 1px 1px rgba(0,0,0,0.04); }
        .abpe-section h2 { margin-top:0; }
    </style>

    <form method="post">
        <?php wp_nonce_field( 'abpe_bulk_edit', 'abpe_bulk_edit_nonce' ); ?>

        <div class="abpe-section">
        <h2><?php esc_html_e( 'Edit Products', 'abpe' ); ?></h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Select', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Product', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Title', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Description', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Sale Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Stock', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Stock Status', 'abpe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $products as $product ) : ?>
                    <tr>
                        <td><input type="checkbox" name="product_ids[]" value="<?php echo esc_attr( $product->get_id() ); ?>" /></td>
                        <td><?php echo esc_html( $product->get_name() ); ?></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->get_id() ); ?>][title]" value="<?php echo esc_attr( $product->get_name() ); ?>" /></td>
                        <td><textarea name="products[<?php echo esc_attr( $product->get_id() ); ?>][description]" rows="2" class="large-text"><?php echo esc_textarea( $product->get_description() ); ?></textarea></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->get_id() ); ?>][price]" value="<?php echo esc_attr( $product->get_regular_price() ); ?>" /></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->get_id() ); ?>][sale_price]" value="<?php echo esc_attr( $product->get_sale_price() ); ?>" /></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->get_id() ); ?>][stock]" value="<?php echo esc_attr( $product->get_stock_quantity() ); ?>" /></td>
                        <td>
                            <select name="products[<?php echo esc_attr( $product->get_id() ); ?>][stock_status]">
                                <option value=""><?php esc_html_e( 'No Change', 'abpe' ); ?></option>
                                <option value="instock" <?php selected( $product->get_stock_status(), 'instock' ); ?>><?php esc_html_e( 'In stock', 'abpe' ); ?></option>
                                <option value="outofstock" <?php selected( $product->get_stock_status(), 'outofstock' ); ?>><?php esc_html_e( 'Out of stock', 'abpe' ); ?></option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <p>
            <button type="submit" name="abpe_preview" class="button"><?php esc_html_e( 'Preview Changes', 'abpe' ); ?></button>
            <button type="submit" class="button button-primary"><?php esc_html_e( 'Apply Changes', 'abpe' ); ?></button>
            <button type="submit" name="abpe_export" class="button"><?php esc_html_e( 'Export Selected to CSV', 'abpe' ); ?></button>
        </p>
    </form>

    <form method="post" enctype="multipart/form-data" style="margin-top:2em;">
        <?php wp_nonce_field( 'abpe_import_export', 'abpe_import_export_nonce' ); ?>
        <h2><?php esc_html_e( 'Import / Export', 'abpe' ); ?></h2>
        <p>
            <input type="file" name="abpe_csv" />
            <button type="submit" name="abpe_import" class="button"><?php esc_html_e( 'Import CSV', 'abpe' ); ?></button>
        </p>
    </form>

    <form method="post" style="margin-top:2em;">
        <?php wp_nonce_field( 'abpe_undo', 'abpe_undo_nonce' ); ?>
        <p><button type="submit" name="abpe_undo" class="button"><?php esc_html_e( 'Undo Last Bulk Edit', 'abpe' ); ?></button></p>
    </form>
</div>
