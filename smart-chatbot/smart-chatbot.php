<?php
/**
 * Plugin Name: Smart Chatbot
 * Description: Provides a simple chatbot that scrapes multi-level content from a web address.
 * Version: 1.0.0
 * Author: ChatGPT
 * Text Domain: smart-chatbot
 * Domain Path: /languages
 *
 * Disclaimer: Scraping external websites may be subject to their terms of service. Use responsibly.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Load plugin textdomain for translations.
 */
function smart_chatbot_load_textdomain() {
    load_plugin_textdomain( 'smart-chatbot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'smart_chatbot_load_textdomain' );

/**
 * Register shortcode [smart_chatbot].
 */
function smart_chatbot_register_shortcode() {
    add_shortcode( 'smart_chatbot', 'smart_chatbot_render_shortcode' );
}
add_action( 'init', 'smart_chatbot_register_shortcode' );

/**
 * Render chatbot interface.
 *
 * @return string
 */
function smart_chatbot_render_shortcode() {
    ob_start();
    ?>
    <div id="smart-chatbot">
        <input type="text" id="smart-chatbot-url" placeholder="<?php echo esc_attr__( 'Enter URL', 'smart-chatbot' ); ?>" />
        <textarea id="smart-chatbot-question" placeholder="<?php echo esc_attr__( 'Ask a question', 'smart-chatbot' ); ?>"></textarea>
        <button id="smart-chatbot-send"><?php echo esc_html__( 'Ask', 'smart-chatbot' ); ?></button>
        <div id="smart-chatbot-response"></div>
    </div>
    <script>
    (function(){
        var btn = document.getElementById('smart-chatbot-send');
        btn.addEventListener('click', function(){
            var data = new FormData();
            data.append('action', 'smart_chatbot_query');
            data.append('url', document.getElementById('smart-chatbot-url').value);
            data.append('question', document.getElementById('smart-chatbot-question').value);
            data.append('depth', '2');
            fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            }).then(function(r){ return r.json(); }).then(function(res){
                if(res.success){
                    document.getElementById('smart-chatbot-response').innerText = res.data;
                } else {
                    document.getElementById('smart-chatbot-response').innerText = 'Error';
                }
            });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}

/**
 * AJAX handler for chatbot queries.
 */
function smart_chatbot_ajax_handler() {
    $url      = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
    $question = isset( $_POST['question'] ) ? sanitize_text_field( wp_unslash( $_POST['question'] ) ) : '';
    $depth    = isset( $_POST['depth'] ) ? intval( $_POST['depth'] ) : 1;

    if ( empty( $url ) || empty( $question ) ) {
        wp_send_json_error( __( 'URL and question required.', 'smart-chatbot' ) );
    }

    $content  = smart_chatbot_scrape( $url, $depth );
    $response = smart_chatbot_search_content( $content, $question );
    wp_send_json_success( $response );
}
add_action( 'wp_ajax_smart_chatbot_query', 'smart_chatbot_ajax_handler' );
add_action( 'wp_ajax_nopriv_smart_chatbot_query', 'smart_chatbot_ajax_handler' );

/**
 * Recursively scrape content up to a specific depth.
 *
 * @param string $url   The starting URL.
 * @param int    $depth Recursion depth.
 * @param array  $visited URLs already visited.
 * @return string Concatenated text content.
 */
function smart_chatbot_scrape( $url, $depth = 1, $visited = array() ) {
    if ( $depth < 1 || in_array( $url, $visited, true ) ) {
        return '';
    }

    $visited[] = $url;
    $response  = wp_remote_get( $url );
    if ( is_wp_error( $response ) ) {
        return '';
    }

    $html = wp_remote_retrieve_body( $response );
    if ( empty( $html ) ) {
        return '';
    }

    $dom = new DOMDocument();
    @$dom->loadHTML( $html );
    $text = '';

    foreach ( $dom->getElementsByTagName( 'p' ) as $p ) {
        $text .= ' ' . $p->textContent;
    }

    if ( $depth > 1 ) {
        foreach ( $dom->getElementsByTagName( 'a' ) as $link ) {
            $href = $link->getAttribute( 'href' );
            if ( $href && filter_var( $href, FILTER_VALIDATE_URL ) ) {
                $text .= smart_chatbot_scrape( $href, $depth - 1, $visited );
            }
        }
    }

    return $text;
}

/**
 * Search for the user's question within scraped content.
 *
 * @param string $content
 * @param string $question
 * @return string
 */
function smart_chatbot_search_content( $content, $question ) {
    if ( empty( $content ) ) {
        return __( 'No content found at the URL.', 'smart-chatbot' );
    }

    $pattern = '/.{0,100}' . preg_quote( $question, '/' ) . '.{0,100}/i';
    if ( preg_match( $pattern, $content, $matches ) ) {
        return $matches[0];
    }

    return __( 'No relevant information found.', 'smart-chatbot' );
}

