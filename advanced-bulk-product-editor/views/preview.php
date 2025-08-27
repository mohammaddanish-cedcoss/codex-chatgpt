<div class="wrap">
    <h1><?php esc_html_e( 'Preview Changes', 'abpe' ); ?></h1>
    <form method="post">
        <?php wp_nonce_field( 'abpe_bulk_edit', 'abpe_bulk_edit_nonce' ); ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Product', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Price', 'abpe' ); ?></th>
                    <th><?php esc_html_e( 'New Stock', 'abpe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $preview as $id => $row ) : ?>
                    <tr>
                        <td><?php echo esc_html( $row['name'] ); ?><input type="hidden" name="product_ids[]" value="<?php echo esc_attr( $id ); ?>" /></td>
                        <td><?php echo esc_html( $row['price'] ); ?></td>
                        <td><?php echo esc_html( $row['stock'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php foreach ( $data as $key => $value ) : ?>
            <?php if ( is_array( $value ) ) : ?>
                <?php foreach ( $value as $v ) : ?>
                    <input type="hidden" name="<?php echo esc_attr( $key ); ?>[]" value="<?php echo esc_attr( $v ); ?>" />
                <?php endforeach; ?>
            <?php else : ?>
                <input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" />
            <?php endif; ?>
        <?php endforeach; ?>
        <p>
            <button type="submit" class="button button-primary"><?php esc_html_e( 'Apply Changes', 'abpe' ); ?></button>
        </p>
    </form>
</div>
