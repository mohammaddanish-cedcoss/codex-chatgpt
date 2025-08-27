<div class="wrap">
    <style>
        .abpe-section { background:#fff; border:1px solid #ccd0d4; padding:20px; margin-top:20px; box-shadow:0 1px 1px rgba(0,0,0,0.04); }
        .abpe-section h1 { margin-top:0; }
    </style>
    <div class="abpe-section">
    <h1><?php esc_html_e( 'Preview Changes', 'abpe' ); ?></h1>
    <form method="post">
        <?php wp_nonce_field( 'abpe_bulk_edit', 'abpe_bulk_edit_nonce' ); ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Product', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Title', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Description', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Sale Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Stock', 'abpe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $preview as $id => $row ) : ?>
                    <tr>
                        <td><?php echo esc_html( $row['name'] ); ?><input type="hidden" name="product_ids[]" value="<?php echo esc_attr( $id ); ?>" /></td>
                        <td><?php echo esc_html( $row['title'] ); ?></td>
                        <td><?php echo esc_html( $row['description'] ); ?></td>
                        <td><?php echo esc_html( $row['price'] ); ?></td>
                        <td><?php echo esc_html( $row['sale_price'] ); ?></td>
                        <td><?php echo esc_html( $row['stock'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php foreach ( $data as $id => $fields ) : ?>
            <?php foreach ( $fields as $key => $value ) : ?>
                <input type="hidden" name="products[<?php echo esc_attr( $id ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
            <?php endforeach; ?>
        <?php endforeach; ?>
        <p>
            <button type="submit" class="button button-primary"><?php esc_html_e( 'Apply Changes', 'abpe' ); ?></button>
        </p>
    </form>
    </div>
</div>
