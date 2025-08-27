<div class="wrap">
    <h1><?php esc_html_e( 'Bulk Product Editor', 'abpe' ); ?></h1>

    <?php if ( ! empty( $notice ) ) : ?>
        <div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div>
    <?php endif; ?>

    <form method="post">
        <?php wp_nonce_field( 'abpe_bulk_edit', 'abpe_bulk_edit_nonce' ); ?>

        <h2><?php esc_html_e( 'Select Products', 'abpe' ); ?></h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Select', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Name', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'Stock', 'abpe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $products as $product ) : ?>
                    <tr>
                        <td><input type="checkbox" name="product_ids[]" value="<?php echo esc_attr( $product->get_id() ); ?>" /></td>
                        <td><?php echo esc_html( $product->get_name() ); ?></td>
                        <td><?php echo esc_html( $product->get_regular_price() ); ?></td>
                        <td><?php echo esc_html( $product->get_stock_quantity() ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2><?php esc_html_e( 'Changes', 'abpe' ); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="price"><?php esc_html_e( 'Price', 'abpe' ); ?></label></th>
                <td><input type="text" name="price" id="price" /></td>
            </tr>
            <tr>
                <th><label for="stock"><?php esc_html_e( 'Stock Qty', 'abpe' ); ?></label></th>
                <td><input type="text" name="stock" id="stock" /></td>
            </tr>
            <tr>
                <th><label for="stock_status"><?php esc_html_e( 'Stock Status', 'abpe' ); ?></label></th>
                <td>
                    <select name="stock_status" id="stock_status">
                        <option value=""><?php esc_html_e( 'No Change', 'abpe' ); ?></option>
                        <option value="instock"><?php esc_html_e( 'In stock', 'abpe' ); ?></option>
                        <option value="outofstock"><?php esc_html_e( 'Out of stock', 'abpe' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="categories"><?php esc_html_e( 'Categories', 'abpe' ); ?></label></th>
                <td>
                    <select name="categories[]" id="categories" multiple>
                        <?php foreach ( $categories as $cat ) : ?>
                            <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

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
