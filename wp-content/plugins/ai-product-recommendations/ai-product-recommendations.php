<?php
/**
 * Plugin Name: Advanced AI Product Recommendations
 * Description: Provides AI-driven product recommendations on WooCommerce pages.
 * Version: 1.1.0
 * Author: ChatGPT
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register settings page for configuration.
add_action( 'admin_menu', 'air_register_settings_page' );
function air_register_settings_page() {
    add_options_page(
        'AI Recommendations',
        'AI Recommendations',
        'manage_options',
        'air-settings',
        'air_render_settings_page'
    );
}

function air_render_settings_page() {
    if ( isset( $_POST['air_api_key'] ) && check_admin_referer( 'air_save_settings' ) ) {
        update_option( 'air_api_key', sanitize_text_field( $_POST['air_api_key'] ) );
        update_option( 'air_show_product', isset( $_POST['air_show_product'] ) ? 1 : 0 );
        update_option( 'air_show_cart', isset( $_POST['air_show_cart'] ) ? 1 : 0 );
        update_option( 'air_show_checkout', isset( $_POST['air_show_checkout'] ) ? 1 : 0 );
        echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'air' ) . '</p></div>';
    }
    $api_key       = get_option( 'air_api_key', '' );
    $show_product  = get_option( 'air_show_product', 1 );
    $show_cart     = get_option( 'air_show_cart', 1 );
    $show_checkout = get_option( 'air_show_checkout', 1 );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'AI Product Recommendations', 'air' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'air_save_settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'OpenAI API Key', 'air' ); ?></th>
                    <td><input type="text" name="air_api_key" value="<?php echo esc_attr( $api_key ); ?>" size="50" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Display on product pages', 'air' ); ?></th>
                    <td><input type="checkbox" name="air_show_product" value="1" <?php checked( $show_product ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Display on cart page', 'air' ); ?></th>
                    <td><input type="checkbox" name="air_show_cart" value="1" <?php checked( $show_cart ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Display on checkout page', 'air' ); ?></th>
                    <td><input type="checkbox" name="air_show_checkout" value="1" <?php checked( $show_checkout ); ?> /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Display recommendations on product, cart, and checkout pages.
add_action( 'woocommerce_after_single_product_summary', 'air_display_product_recommendations', 15 );
add_action( 'woocommerce_after_cart', 'air_display_cart_recommendations' );
add_action( 'woocommerce_after_checkout_form', 'air_display_checkout_recommendations' );

// Support WooCommerce Cart and Checkout blocks by appending markup after the block output.
add_filter( 'render_block', 'air_render_block_recommendations', 10, 2 );

function air_display_product_recommendations() {
    if ( ! get_option( 'air_show_product', 1 ) ) {
        return;
    }
    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }
    air_render_recommendations_section( $product->get_id() );
}

function air_display_cart_recommendations() {
    if ( ! get_option( 'air_show_cart', 1 ) ) {
        return;
    }
    $product_id = air_get_first_cart_product_id();
    if ( $product_id ) {
        air_render_recommendations_section( $product_id );
    }
}

function air_display_checkout_recommendations() {
    if ( ! get_option( 'air_show_checkout', 1 ) ) {
        return;
    }
    $product_id = air_get_first_cart_product_id();
    if ( $product_id ) {
        air_render_recommendations_section( $product_id );
    }
}

function air_get_first_cart_product_id() {
    if ( WC()->cart && ! WC()->cart->is_empty() ) {
        $items = WC()->cart->get_cart();
        $first = reset( $items );
        if ( isset( $first['product_id'] ) ) {
            return $first['product_id'];
        }
    }
    return 0;
}

function air_render_recommendations_section( $product_id, $echo = true ) {
    $ids = air_get_recommendations( $product_id );
    if ( empty( $ids ) ) {
        return '';
    }
    $html  = '<section class="air-recommendations">';
    $html .= '<h2>' . esc_html__( 'Recommended Products', 'air' ) . '</h2>';
    $html .= do_shortcode( '[products ids="' . implode( ',', array_map( 'intval', $ids ) ) . '" columns="3"]' );
    $html .= '</section>';
    if ( $echo ) {
        echo $html;
    }
    return $html;
}

function air_render_block_recommendations( $block_content, $block ) {
    if ( 'woocommerce/cart' === ( $block['blockName'] ?? '' ) && get_option( 'air_show_cart', 1 ) ) {
        $product_id = air_get_first_cart_product_id();
        if ( $product_id ) {
            $block_content .= air_render_recommendations_section( $product_id, false );
        }
    }

    if ( 'woocommerce/checkout' === ( $block['blockName'] ?? '' ) && get_option( 'air_show_checkout', 1 ) ) {
        $product_id = air_get_first_cart_product_id();
        if ( $product_id ) {
            $block_content .= air_render_recommendations_section( $product_id, false );
        }
    }

    return $block_content;
}

// Generate recommendations using AI.
function air_get_recommendations( $product_id ) {
    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        return array();
    }

    // Collect candidate products.
    $candidates = wc_get_products( array(
        'status'  => 'publish',
        'exclude' => array( $product_id ),
        'limit'   => 20,
        'return'  => 'ids',
    ) );

    if ( empty( $candidates ) ) {
        return array();
    }

    $names = array();
    foreach ( $candidates as $id ) {
        $names[ $id ] = get_the_title( $id );
    }

    $prompt = 'Given the product "' . $product->get_name() . '" described as "' . wp_strip_all_tags( $product->get_description() ) .
        '", choose 3 IDs from the following products that a shopper is most likely to buy next. '
        . 'Return the IDs as a JSON array. Available products:\n' . wp_json_encode( $names );

    $api_key = get_option( 'air_api_key' );
    if ( ! $api_key ) {
        shuffle( $candidates );
        return array_slice( $candidates, 0, 3 );
    }

    $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body'    => wp_json_encode( array(
            'model'     => 'gpt-3.5-turbo',
            'messages'  => array(
                array(
                    'role'    => 'user',
                    'content' => $prompt,
                ),
            ),
            'max_tokens'  => 50,
            'temperature' => 0.7,
        ) ),
        'timeout' => 20,
    ) );

    if ( is_wp_error( $response ) ) {
        shuffle( $candidates );
        return array_slice( $candidates, 0, 3 );
    }

    $body    = json_decode( wp_remote_retrieve_body( $response ), true );
    $content = $body['choices'][0]['message']['content'] ?? '';
    $ids     = json_decode( $content, true );

    if ( is_array( $ids ) ) {
        $ids = array_intersect( $ids, $candidates );
        if ( ! empty( $ids ) ) {
            return array_slice( $ids, 0, 3 );
        }
    }

    shuffle( $candidates );
    return array_slice( $candidates, 0, 3 );
}
